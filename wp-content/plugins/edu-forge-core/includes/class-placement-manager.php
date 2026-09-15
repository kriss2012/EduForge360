<?php
/**
 * EduForge Placement & Job Board Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Placement_Manager {

	/**
	 * Application status definitions
	 */
	const STATUSES = array(
		'applied'     => 'Applied',
		'shortlisted' => 'Shortlisted',
		'assessment'  => 'Assessment',
		'interview'   => 'Interview',
		'selected'    => 'Selected',
		'rejected'    => 'Rejected',
	);

	/**
	 * Submit job application
	 */
	public static function apply_to_job( $student_id, $job_id, $cover_note = '', $resume_url = '' ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'job_applications' );

		// Check if already applied
		$existing = $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM {$table} WHERE student_id = %d AND job_id = %d",
			$student_id,
			$job_id
		) );

		if ( $existing ) {
			return new WP_Error( 'already_applied', __( 'You have already applied for this position.', 'eduforge360' ) );
		}

		$company_id = get_post_meta( $job_id, '_eduforge_company_id', true ) ?: 0;

		$inserted = $wpdb->insert(
			$table,
			array(
				'student_id'   => $student_id,
				'job_id'       => $job_id,
				'company_id'   => $company_id,
				'status'       => 'applied',
				'cover_note'   => sanitize_textarea_field( $cover_note ),
				'resume_url'   => esc_url_raw( $resume_url ),
				'applied_at'   => current_time( 'mysql' ),
				'updated_at'   => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $inserted ) {
			EduForge_Logger::info( "Student {$student_id} applied for job {$job_id}." );
			do_action( 'eduforge_job_applied', $wpdb->insert_id, $student_id, $job_id );
			return $wpdb->insert_id;
		}

		return new WP_Error( 'db_error', __( 'Failed to register application.', 'eduforge360' ) );
	}

	/**
	 * Update application status (Placement Officer / Admin)
	 */
	public static function update_application_status( $application_id, $new_status ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'job_applications' );

		if ( ! array_key_exists( $new_status, self::STATUSES ) ) {
			return new WP_Error( 'invalid_status', __( 'Invalid application status provided.', 'eduforge360' ) );
		}

		$updated = $wpdb->update(
			$table,
			array(
				'status'     => sanitize_key( $new_status ),
				'updated_at' => current_time( 'mysql' ),
			),
			array( 'id' => intval( $application_id ) ),
			array( '%s', '%s' ),
			array( '%d' )
		);

		if ( false !== $updated ) {
			EduForge_Logger::info( "Application {$application_id} status updated to {$new_status}." );
			do_action( 'eduforge_application_status_changed', $application_id, $new_status );
			return true;
		}

		return false;
	}

	/**
	 * Get applications for a student
	 */
	public static function get_student_applications( $student_id ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'job_applications' );

		return $wpdb->get_results( $wpdb->prepare(
			"SELECT a.*, p.post_title as job_title, 
			        m1.meta_value as company_name, 
			        m2.meta_value as salary_range,
			        m3.meta_value as job_location
			 FROM {$table} a 
			 JOIN {$wpdb->posts} p ON a.job_id = p.ID 
			 LEFT JOIN {$wpdb->postmeta} m1 ON p.ID = m1.post_id AND m1.meta_key = '_eduforge_company_name'
			 LEFT JOIN {$wpdb->postmeta} m2 ON p.ID = m2.post_id AND m2.meta_key = '_eduforge_salary_range'
			 LEFT JOIN {$wpdb->postmeta} m3 ON p.ID = m3.post_id AND m3.meta_key = '_eduforge_location'
			 WHERE a.student_id = %d 
			 ORDER BY a.applied_at DESC",
			$student_id
		) );
	}

	/**
	 * Get job meta details
	 */
	public static function get_job_meta( $job_id ) {
		return array(
			'company'     => get_post_meta( $job_id, '_eduforge_company_name', true ) ?: 'Tech Innovators Global',
			'salary'      => get_post_meta( $job_id, '_eduforge_salary_range', true ) ?: '₹8.0 - ₹14.0 LPA',
			'location'    => get_post_meta( $job_id, '_eduforge_location', true ) ?: 'Bengaluru / Hybrid',
			'experience'  => get_post_meta( $job_id, '_eduforge_experience', true ) ?: '0 - 2 Years (Freshers Welcome)',
			'deadline'    => get_post_meta( $job_id, '_eduforge_deadline', true ) ?: '2026-10-30',
			'eligibility' => get_post_meta( $job_id, '_eduforge_eligibility', true ) ?: 'B.Tech/BE/MCA/B.Sc (Min 65% aggregate)',
			'skills'      => get_post_meta( $job_id, '_eduforge_required_skills', true ) ?: 'Python, SQL, REST APIs, Git',
		);
	}
}
