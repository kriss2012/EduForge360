<?php
/**
 * EduForge Roles & Capabilities Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Roles {

	public static function init() {
		// Verify roles on init if not present
		if ( ! get_role( 'student' ) ) {
			self::create_roles();
		}
	}

	public static function create_roles() {
		// 1. Student Role
		add_role(
			'student',
			__( 'Student', 'eduforge360' ),
			array(
				'read'                     => true,
				'upload_files'             => true,
				'take_assessments'         => true,
				'enroll_courses'           => true,
				'submit_assignments'       => true,
				'take_quizzes'             => true,
				'view_certificates'        => true,
				'apply_jobs'               => true,
				'register_events'          => true,
			)
		);

		// 2. Instructor / Faculty Role
		add_role(
			'instructor',
			__( 'Faculty / Instructor', 'eduforge360' ),
			array(
				'read'                     => true,
				'upload_files'             => true,
				'edit_posts'               => true,
				'publish_posts'            => true,
				'manage_eduforge_courses'  => true,
				'manage_eduforge_lessons'  => true,
				'manage_eduforge_quizzes'  => true,
				'grade_assignments'        => true,
				'view_course_analytics'    => true,
			)
		);

		// 3. Placement Officer Role
		add_role(
			'placement_officer',
			__( 'Placement Officer', 'eduforge360' ),
			array(
				'read'                     => true,
				'upload_files'             => true,
				'edit_posts'               => true,
				'publish_posts'            => true,
				'manage_companies'         => true,
				'manage_jobs'              => true,
				'view_student_readiness'   => true,
				'manage_placement_drives'  => true,
				'view_applications'        => true,
			)
		);

		// Grant custom capabilities to Administrator
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->add_cap( 'manage_eduforge_courses' );
			$admin->add_cap( 'manage_eduforge_lessons' );
			$admin->add_cap( 'manage_eduforge_quizzes' );
			$admin->add_cap( 'grade_assignments' );
			$admin->add_cap( 'view_course_analytics' );
			$admin->add_cap( 'manage_companies' );
			$admin->add_cap( 'manage_jobs' );
			$admin->add_cap( 'view_student_readiness' );
			$admin->add_cap( 'manage_placement_drives' );
			$admin->add_cap( 'view_applications' );
			$admin->add_cap( 'manage_eduforge_settings' );
		}
	}

	/**
	 * Check if a user is a student
	 */
	public static function is_student( $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$user = get_userdata( $user_id );
		return $user && in_array( 'student', (array) $user->roles, true );
	}

	/**
	 * Check if a user is faculty
	 */
	public static function is_instructor( $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$user = get_userdata( $user_id );
		return $user && ( in_array( 'instructor', (array) $user->roles, true ) || in_array( 'administrator', (array) $user->roles, true ) );
	}

	/**
	 * Check if a user is placement officer
	 */
	public static function is_placement_officer( $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$user = get_userdata( $user_id );
		return $user && ( in_array( 'placement_officer', (array) $user->roles, true ) || in_array( 'administrator', (array) $user->roles, true ) );
	}
}
