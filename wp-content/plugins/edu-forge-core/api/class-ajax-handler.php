<?php
/**
 * EduForge AJAX Handlers
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_AJAX_Handler {

	public static function init() {
		// Public / Student AJAX actions
		$actions = array(
			'filter_courses'        => true, // nopriv allowed
			'mark_lesson_complete'  => false,
			'submit_quiz'           => false,
			'submit_assignment'     => false,
			'save_resume'           => false,
			'apply_job'             => false,
			'register_event'        => false,
			'save_assessment'       => false,
			'grade_assignment'      => false, // faculty only
		);

		foreach ( $actions as $action => $allow_nopriv ) {
			add_action( 'wp_ajax_eduforge_' . $action, array( __CLASS__, 'handle_' . $action ) );
			if ( $allow_nopriv ) {
				add_action( 'wp_ajax_nopriv_eduforge_' . $action, array( __CLASS__, 'handle_' . $action ) );
			}
		}
	}

	/**
	 * Verify Nonce and User
	 */
	private static function verify_request() {
		check_ajax_referer( 'eduforge_secure_nonce', 'nonce' );
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Please log in to perform this action.', 'eduforge360' ) ), 401 );
		}
	}

	/**
	 * 1. Filter Courses AJAX
	 */
	public static function handle_filter_courses() {
		check_ajax_referer( 'eduforge_secure_nonce', 'nonce' );

		$category   = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';
		$difficulty = isset( $_POST['difficulty'] ) ? sanitize_text_field( wp_unslash( $_POST['difficulty'] ) ) : '';
		$pricing    = isset( $_POST['pricing'] ) ? sanitize_text_field( wp_unslash( $_POST['pricing'] ) ) : '';
		$search     = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

		$args = array(
			'post_type'      => 'courses',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
		);

		if ( ! empty( $search ) ) {
			$args['s'] = $search;
		}

		$tax_query = array();
		if ( ! empty( $category ) && 'all' !== $category ) {
			$tax_query[] = array(
				'taxonomy' => 'course_category',
				'field'    => 'slug',
				'terms'    => $category,
			);
		}
		if ( ! empty( $difficulty ) && 'all' !== $difficulty ) {
			$tax_query[] = array(
				'taxonomy' => 'course_level',
				'field'    => 'slug',
				'terms'    => $difficulty,
			);
		}
		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query;
		}

		if ( ! empty( $pricing ) && 'all' !== $pricing ) {
			if ( 'free' === $pricing ) {
				$args['meta_query'] = array(
					'relation' => 'OR',
					array( 'key' => '_eduforge_price', 'compare' => 'NOT EXISTS' ),
					array( 'key' => '_eduforge_price', 'value' => 0, 'compare' => '=' ),
					array( 'key' => '_eduforge_price', 'value' => '', 'compare' => '=' ),
				);
			} elseif ( 'paid' === $pricing ) {
				$args['meta_query'] = array(
					array( 'key' => '_eduforge_price', 'value' => 0, 'compare' => '>' ),
				);
			}
		}

		$query = new WP_Query( $args );
		ob_start();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/course-card' );
			}
			wp_reset_postdata();
		} else {
			echo '<div class="eduforge-no-results" style="grid-column: 1/-1; text-align: center; padding: 40px; background: #fff; border-radius: 8px; border: 1px dashed #cbd5e1;">';
			echo '<h3>' . esc_html__( 'No courses match your criteria', 'eduforge360' ) . '</h3>';
			echo '<p style="color: #64748b;">' . esc_html__( 'Try clearing filters or search for another keyword.', 'eduforge360' ) . '</p>';
			echo '</div>';
		}

		$html = ob_get_clean();
		wp_send_json_success( array( 'html' => $html, 'found_posts' => $query->found_posts ) );
	}

	/**
	 * 2. Mark Lesson Complete AJAX
	 */
	public static function handle_mark_lesson_complete() {
		self::verify_request();

		$lesson_id = isset( $_POST['lesson_id'] ) ? intval( $_POST['lesson_id'] ) : 0;
		if ( ! $lesson_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid lesson ID.', 'eduforge360' ) ) );
		}

		$student_id = get_current_user_id();
		$result = EduForge_Lesson_Manager::mark_lesson_complete( $student_id, $lesson_id );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array(
			'message'  => __( 'Lesson completed!', 'eduforge360' ),
			'progress' => $result['progress'],
		) );
	}

	/**
	 * 3. Submit Quiz AJAX
	 */
	public static function handle_submit_quiz() {
		self::verify_request();

		$quiz_id = isset( $_POST['quiz_id'] ) ? intval( $_POST['quiz_id'] ) : 0;
		$answers = isset( $_POST['answers'] ) ? (array) $_POST['answers'] : array();

		if ( ! $quiz_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid quiz ID.', 'eduforge360' ) ) );
		}

		$student_id = get_current_user_id();
		$result = EduForge_Quiz_Manager::grade_quiz( $student_id, $quiz_id, $answers );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( $result );
	}

	/**
	 * 4. Submit Assignment AJAX
	 */
	public static function handle_submit_assignment() {
		self::verify_request();

		$assignment_id = isset( $_POST['assignment_id'] ) ? intval( $_POST['assignment_id'] ) : 0;
		$submission_text = isset( $_POST['submission_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['submission_text'] ) ) : '';
		$file = isset( $_FILES['assignment_file'] ) ? $_FILES['assignment_file'] : null;

		$student_id = get_current_user_id();
		$result = EduForge_Assignment_Manager::submit_assignment( $student_id, $assignment_id, $submission_text, $file );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array(
			'message'       => __( 'Assignment submitted successfully for instructor review!', 'eduforge360' ),
			'submission_id' => $result,
		) );
	}

	/**
	 * 5. Save Resume AJAX
	 */
	public static function handle_save_resume() {
		self::verify_request();

		$student_id = get_current_user_id();
		$data = isset( $_POST['resume'] ) ? (array) $_POST['resume'] : array();

		$saved = EduForge_Career_Manager::save_student_resume( $student_id, $data );
		$readiness = EduForge_Career_Manager::calculate_career_readiness( $student_id );

		wp_send_json_success( array(
			'message'   => __( 'Resume successfully updated!', 'eduforge360' ),
			'resume'    => $saved,
			'readiness' => $readiness,
		) );
	}

	/**
	 * 6. Apply Job AJAX
	 */
	public static function handle_apply_job() {
		self::verify_request();

		$student_id = get_current_user_id();
		$job_id = isset( $_POST['job_id'] ) ? intval( $_POST['job_id'] ) : 0;
		$cover_note = isset( $_POST['cover_note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cover_note'] ) ) : '';

		$result = EduForge_Placement_Manager::apply_to_job( $student_id, $job_id, $cover_note );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array(
			'message'        => __( 'Your application has been forwarded to the placement cell and hiring company!', 'eduforge360' ),
			'application_id' => $result,
		) );
	}

	/**
	 * 7. Register Event AJAX
	 */
	public static function handle_register_event() {
		self::verify_request();

		$student_id = get_current_user_id();
		$event_id = isset( $_POST['event_id'] ) ? intval( $_POST['event_id'] ) : 0;

		$result = EduForge_Event_Manager::register_for_event( $student_id, $event_id );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Confirmed! You are registered for this event.', 'eduforge360' ),
			'count'   => $result['count'],
		) );
	}

	/**
	 * 8. Save Assessment AJAX
	 */
	public static function handle_save_assessment() {
		self::verify_request();

		$student_id = get_current_user_id();
		$category   = isset( $_POST['category'] ) ? sanitize_key( $_POST['category'] ) : '';
		$score      = isset( $_POST['score'] ) ? floatval( $_POST['score'] ) : 0;
		$strengths  = isset( $_POST['strengths'] ) ? sanitize_text_field( wp_unslash( $_POST['strengths'] ) ) : '';
		$weaknesses = isset( $_POST['weaknesses'] ) ? sanitize_text_field( wp_unslash( $_POST['weaknesses'] ) ) : '';

		$id = EduForge_Skill_Manager::save_assessment( $student_id, $category, $score, $strengths, $weaknesses );
		$recommendations = EduForge_Skill_Manager::get_recommendations( $student_id );
		$readiness = EduForge_Career_Manager::calculate_career_readiness( $student_id );

		wp_send_json_success( array(
			'message'         => __( 'Assessment score recorded.', 'eduforge360' ),
			'recommendations' => $recommendations,
			'readiness'       => $readiness,
		) );
	}

	/**
	 * 9. Faculty Grade Assignment AJAX
	 */
	public static function handle_grade_assignment() {
		check_ajax_referer( 'eduforge_secure_nonce', 'nonce' );

		if ( ! EduForge_Roles::is_instructor() ) {
			wp_send_json_error( array( 'message' => __( 'Access denied. Faculty permissions required.', 'eduforge360' ) ), 403 );
		}

		$submission_id = isset( $_POST['submission_id'] ) ? intval( $_POST['submission_id'] ) : 0;
		$marks         = isset( $_POST['marks'] ) ? floatval( $_POST['marks'] ) : 0;
		$feedback      = isset( $_POST['feedback'] ) ? sanitize_textarea_field( wp_unslash( $_POST['feedback'] ) ) : '';
		$status        = isset( $_POST['status'] ) ? sanitize_key( $_POST['status'] ) : 'approved';

		$success = EduForge_Assignment_Manager::grade_submission( $submission_id, $marks, $feedback, $status );
		if ( $success ) {
			wp_send_json_success( array( 'message' => __( 'Grade and feedback recorded successfully.', 'eduforge360' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Could not save grade.', 'eduforge360' ) ) );
		}
	}
}
