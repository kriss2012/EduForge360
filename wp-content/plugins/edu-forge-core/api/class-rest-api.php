<?php
/**
 * EduForge Custom REST API Endpoints
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_REST_API {

	const NAMESPACE = 'eduforge/v1';

	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	public static function register_routes() {
		// 1. GET /courses
		register_rest_route( self::NAMESPACE, '/courses', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'get_courses' ),
			'permission_callback' => '__return_true',
		) );

		// 2. GET /courses/{id}
		register_rest_route( self::NAMESPACE, '/courses/(?P<id>\d+)', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'get_single_course' ),
			'permission_callback' => '__return_true',
			'args'                => array(
				'id' => array( 'validate_callback' => function( $param ) { return is_numeric( $param ); } ),
			),
		) );

		// 3. GET /student/profile
		register_rest_route( self::NAMESPACE, '/student/profile', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'get_student_profile' ),
			'permission_callback' => array( __CLASS__, 'check_auth_permission' ),
		) );

		// 4. GET /student/progress
		register_rest_route( self::NAMESPACE, '/student/progress', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'get_student_progress' ),
			'permission_callback' => array( __CLASS__, 'check_auth_permission' ),
		) );

		// 5. POST /enrollment
		register_rest_route( self::NAMESPACE, '/enrollment', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'enroll_student' ),
			'permission_callback' => array( __CLASS__, 'check_auth_permission' ),
			'args'                => array(
				'course_id' => array(
					'required'          => true,
					'validate_callback' => function( $param ) { return is_numeric( $param ); },
					'sanitize_callback' => 'absint',
				),
			),
		) );

		// 6. POST /quiz/submit
		register_rest_route( self::NAMESPACE, '/quiz/submit', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'submit_quiz' ),
			'permission_callback' => array( __CLASS__, 'check_auth_permission' ),
			'args'                => array(
				'quiz_id' => array(
					'required'          => true,
					'validate_callback' => function( $param ) { return is_numeric( $param ); },
					'sanitize_callback' => 'absint',
				),
				'answers' => array(
					'required' => true,
				),
			),
		) );

		// 7. GET /certificates
		register_rest_route( self::NAMESPACE, '/certificates', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'get_certificates' ),
			'permission_callback' => array( __CLASS__, 'check_auth_permission' ),
		) );

		// 8. GET /certificates/verify/{hash}
		register_rest_route( self::NAMESPACE, '/certificates/verify/(?P<hash>[a-zA-Z0-9_-]+)', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'verify_certificate' ),
			'permission_callback' => '__return_true',
		) );

		// 9. POST /job/apply
		register_rest_route( self::NAMESPACE, '/job/apply', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => array( __CLASS__, 'apply_job' ),
			'permission_callback' => array( __CLASS__, 'check_auth_permission' ),
			'args'                => array(
				'job_id' => array(
					'required'          => true,
					'validate_callback' => function( $param ) { return is_numeric( $param ); },
					'sanitize_callback' => 'absint',
				),
			),
		) );

		// 10. GET /analytics/summary
		register_rest_route( self::NAMESPACE, '/analytics/summary', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( __CLASS__, 'get_analytics_summary' ),
			'permission_callback' => function() {
				return current_user_can( 'manage_options' ) || current_user_can( 'view_institutional_analytics' );
			},
		) );
	}

	public static function check_auth_permission() {
		return is_user_logged_in();
	}

	/**
	 * Handlers
	 */
	public static function get_courses( $request ) {
		$posts = get_posts( array(
			'post_type'      => 'courses',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
		) );

		$data = array();
		foreach ( $posts as $p ) {
			$details = EduForge_Course_Manager::get_course_details( $p->ID );
			$data[] = array(
				'id'          => $p->ID,
				'title'       => $p->post_title,
				'excerpt'     => wp_trim_words( $p->post_content, 20 ),
				'link'        => get_permalink( $p->ID ),
				'thumbnail'   => get_the_post_thumbnail_url( $p->ID, 'medium' ) ?: '',
				'details'     => $details,
			);
		}

		return rest_ensure_response( array( 'success' => true, 'courses' => $data ) );
	}

	public static function get_single_course( $request ) {
		$course_id = intval( $request['id'] );
		$post = get_post( $course_id );

		if ( ! $post || 'courses' !== $post->post_type ) {
			return new WP_Error( 'not_found', __( 'Course not found.', 'eduforge360' ), array( 'status' => 404 ) );
		}

		$lessons = EduForge_Course_Manager::get_course_lessons( $course_id );
		$lesson_list = array();
		foreach ( $lessons as $l ) {
			$lesson_list[] = array(
				'id'       => $l->ID,
				'title'    => $l->post_title,
				'duration' => get_post_meta( $l->ID, '_eduforge_duration', true ) ?: '15m',
			);
		}

		return rest_ensure_response( array(
			'success' => true,
			'course'  => array(
				'id'       => $post->ID,
				'title'    => $post->post_title,
				'content'  => apply_filters( 'the_content', $post->post_content ),
				'details'  => EduForge_Course_Manager::get_course_details( $course_id ),
				'lessons'  => $lesson_list,
			),
		) );
	}

	public static function get_student_profile( $request ) {
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		$resume = EduForge_Career_Manager::get_student_resume( $user_id );
		$skills = EduForge_Skill_Manager::get_student_skills( $user_id );
		$readiness = EduForge_Career_Manager::calculate_career_readiness( $user_id );

		return rest_ensure_response( array(
			'success' => true,
			'student' => array(
				'id'               => $user_id,
				'name'             => $user->display_name,
				'email'            => $user->user_email,
				'career_readiness' => $readiness,
				'skills'           => $skills,
				'resume'           => $resume,
			),
		) );
	}

	public static function get_student_progress( $request ) {
		$student_id = get_current_user_id();
		$courses = EduForge_Course_Manager::get_student_courses( $student_id );
		$certs = EduForge_Certificate_Manager::get_student_certificates( $student_id );

		return rest_ensure_response( array(
			'success'      => true,
			'courses'      => $courses,
			'certificates' => $certs,
		) );
	}

	public static function enroll_student( $request ) {
		$student_id = get_current_user_id();
		$course_id = intval( $request['course_id'] );

		$result = EduForge_Course_Manager::enroll_student( $student_id, $course_id );
		if ( is_wp_error( $result ) ) {
			return new WP_Error( $result->get_error_code(), $result->get_error_message(), array( 'status' => 400 ) );
		}

		return rest_ensure_response( array(
			'success'       => true,
			'message'       => __( 'Enrolled successfully!', 'eduforge360' ),
			'enrollment_id' => $result,
		) );
	}

	public static function submit_quiz( $request ) {
		$student_id = get_current_user_id();
		$quiz_id = intval( $request['quiz_id'] );
		$answers = (array) $request['answers'];

		$result = EduForge_Quiz_Manager::grade_quiz( $student_id, $quiz_id, $answers );
		if ( is_wp_error( $result ) ) {
			return new WP_Error( $result->get_error_code(), $result->get_error_message(), array( 'status' => 400 ) );
		}

		return rest_ensure_response( array( 'success' => true, 'result' => $result ) );
	}

	public static function get_certificates( $request ) {
		$student_id = get_current_user_id();
		$certs = EduForge_Certificate_Manager::get_student_certificates( $student_id );
		return rest_ensure_response( array( 'success' => true, 'certificates' => $certs ) );
	}

	public static function verify_certificate( $request ) {
		$hash = sanitize_text_field( $request['hash'] );
		$cert = EduForge_Certificate_Manager::get_certificate_by_number( $hash );

		if ( ! $cert ) {
			return new WP_Error( 'not_found', __( 'Certificate not verified.', 'eduforge360' ), array( 'status' => 404 ) );
		}

		$student = get_userdata( $cert->student_id );
		$course = get_post( $cert->course_id );

		return rest_ensure_response( array(
			'verified'       => true,
			'certificate_no' => $cert->certificate_number,
			'student_name'   => $student ? $student->display_name : 'Student',
			'course'         => $course ? $course->post_title : '',
			'score'          => $cert->score,
			'issued_at'      => $cert->issued_at,
		) );
	}

	public static function apply_job( $request ) {
		$student_id = get_current_user_id();
		$job_id = intval( $request['job_id'] );
		$cover = isset( $request['cover_note'] ) ? sanitize_textarea_field( $request['cover_note'] ) : '';

		$result = EduForge_Placement_Manager::apply_to_job( $student_id, $job_id, $cover );
		if ( is_wp_error( $result ) ) {
			return new WP_Error( $result->get_error_code(), $result->get_error_message(), array( 'status' => 400 ) );
		}

		return rest_ensure_response( array(
			'success'        => true,
			'application_id' => $result,
			'message'        => __( 'Application registered successfully.', 'eduforge360' ),
		) );
	}

	public static function get_analytics_summary( $request ) {
		$kpis = EduForge_Analytics::get_platform_kpis();
		$trends = EduForge_Analytics::get_monthly_trends();

		return rest_ensure_response( array(
			'success' => true,
			'kpis'    => $kpis,
			'trends'  => $trends,
		) );
	}
}
