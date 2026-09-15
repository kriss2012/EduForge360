<?php
/**
 * EduForge Assignment Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Assignment_Manager {

	/**
	 * Allowed mime types and extensions for assignment uploads
	 */
	const ALLOWED_MIME_TYPES = array(
		'pdf'  => 'application/pdf',
		'doc'  => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'ppt'  => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png'  => 'image/png',
		'webp' => 'image/webp',
		'zip'  => 'application/zip',
	);

	/**
	 * Handle assignment file upload securely
	 */
	public static function handle_file_upload( $file_array ) {
		if ( empty( $file_array['name'] ) || empty( $file_array['tmp_name'] ) ) {
			return new WP_Error( 'no_file', __( 'No file was uploaded.', 'eduforge360' ) );
		}

		// Check size (20MB limit)
		if ( $file_array['size'] > 20 * 1024 * 1024 ) {
			return new WP_Error( 'file_too_large', __( 'File size exceeds maximum 20MB limit.', 'eduforge360' ) );
		}

		$file_info = wp_check_filetype( $file_array['name'], self::ALLOWED_MIME_TYPES );
		if ( ! $file_info['ext'] || ! $file_info['type'] ) {
			EduForge_Logger::security( 'Blocked dangerous assignment file upload: ' . $file_array['name'] );
			return new WP_Error( 'invalid_file_type', __( 'Invalid file type. Only PDF, DOC, DOCX, PPT, PPTX, JPG, PNG, WEBP, and ZIP files are allowed.', 'eduforge360' ) );
		}

		// Double-check real MIME type with finfo if available
		if ( function_exists( 'finfo_open' ) ) {
			$finfo = finfo_open( FILEINFO_MIME_TYPE );
			$real_mime = finfo_file( $finfo, $file_array['tmp_name'] );
			finfo_close( $finfo );

			if ( ! in_array( $real_mime, self::ALLOWED_MIME_TYPES, true ) && 'application/octet-stream' !== $real_mime ) {
				EduForge_Logger::security( "MIME spoofing detected. Reported: {$file_array['type']}, Real: {$real_mime}" );
				return new WP_Error( 'mime_mismatch', __( 'File content does not match its declared extension.', 'eduforge360' ) );
			}
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$upload_overrides = array(
			'test_form' => false,
			'mimes'     => self::ALLOWED_MIME_TYPES,
		);

		$uploaded_file = wp_handle_upload( $file_array, $upload_overrides );

		if ( isset( $uploaded_file['error'] ) ) {
			return new WP_Error( 'upload_failed', $uploaded_file['error'] );
		}

		return $uploaded_file['url'];
	}

	/**
	 * Submit student assignment
	 */
	public static function submit_assignment( $student_id, $assignment_id, $submission_text, $file_array = null ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'assignments_submissions' );

		$file_url = '';
		if ( ! empty( $file_array['name'] ) ) {
			$upload_result = self::handle_file_upload( $file_array );
			if ( is_wp_error( $upload_result ) ) {
				return $upload_result;
			}
			$file_url = $upload_result;
		}

		$course_id = get_post_meta( $assignment_id, '_eduforge_course_id', true ) ?: 0;
		$max_marks = get_post_meta( $assignment_id, '_eduforge_max_marks', true ) ?: 100;

		$inserted = $wpdb->insert(
			$table,
			array(
				'student_id'      => $student_id,
				'assignment_id'   => $assignment_id,
				'course_id'       => $course_id,
				'submission_text' => sanitize_textarea_field( $submission_text ),
				'file_url'        => esc_url_raw( $file_url ),
				'max_marks'       => floatval( $max_marks ),
				'status'          => 'submitted',
				'submitted_at'    => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%d', '%s', '%s', '%f', '%s', '%s' )
		);

		if ( $inserted ) {
			EduForge_Logger::info( "Student {$student_id} submitted assignment {$assignment_id}." );
			do_action( 'eduforge_assignment_submitted', $wpdb->insert_id, $student_id, $assignment_id );
			return $wpdb->insert_id;
		}

		return new WP_Error( 'db_error', __( 'Failed to save submission.', 'eduforge360' ) );
	}

	/**
	 * Faculty reviews and grades assignment
	 */
	public static function grade_submission( $submission_id, $marks, $feedback, $status = 'approved', $reviewer_id = 0 ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'assignments_submissions' );

		if ( ! $reviewer_id ) {
			$reviewer_id = get_current_user_id();
		}

		$updated = $wpdb->update(
			$table,
			array(
				'marks'       => floatval( $marks ),
				'feedback'    => sanitize_textarea_field( $feedback ),
				'status'      => sanitize_key( $status ),
				'reviewed_at' => current_time( 'mysql' ),
				'reviewed_by' => intval( $reviewer_id ),
			),
			array( 'id' => intval( $submission_id ) ),
			array( '%f', '%s', '%s', '%s', '%d' ),
			array( '%d' )
		);

		if ( false !== $updated ) {
			EduForge_Logger::info( "Submission {$submission_id} reviewed by {$reviewer_id}. Status: {$status}, Marks: {$marks}." );
			return true;
		}

		return false;
	}

	/**
	 * Get submissions for an assignment
	 */
	public static function get_submissions( $assignment_id = 0, $student_id = 0 ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'assignments_submissions' );

		if ( $student_id > 0 && $assignment_id > 0 ) {
			return $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$table} WHERE assignment_id = %d AND student_id = %d ORDER BY id DESC",
				$assignment_id,
				$student_id
			) );
		} elseif ( $student_id > 0 ) {
			return $wpdb->get_results( $wpdb->prepare(
				"SELECT s.*, p.post_title as assignment_title FROM {$table} s JOIN {$wpdb->posts} p ON s.assignment_id = p.ID WHERE s.student_id = %d ORDER BY s.id DESC",
				$student_id
			) );
		} elseif ( $assignment_id > 0 ) {
			return $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$table} WHERE assignment_id = %d ORDER BY id DESC",
				$assignment_id
			) );
		}

		return $wpdb->get_results( "SELECT * FROM {$table} ORDER BY id DESC LIMIT 50" );
	}
}
