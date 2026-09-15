<?php
/**
 * EduForge Custom Taxonomies
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Taxonomies {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ) );
	}

	public static function register_taxonomies() {
		// 1. Course Category
		register_taxonomy( 'course_category', array( 'courses' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'          => __( 'Course Categories', 'eduforge360' ),
				'singular_name' => __( 'Course Category', 'eduforge360' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'course-category' ),
		) );

		// 2. Course Level (Beginner, Intermediate, Advanced)
		register_taxonomy( 'course_level', array( 'courses' ), array(
			'hierarchical'      => false,
			'labels'            => array(
				'name'          => __( 'Course Levels', 'eduforge360' ),
				'singular_name' => __( 'Course Level', 'eduforge360' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'course-level' ),
		) );

		// 3. Skill Category
		register_taxonomy( 'skill_category', array( 'courses', 'quizzes', 'resources' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'          => __( 'Skill Categories', 'eduforge360' ),
				'singular_name' => __( 'Skill Category', 'eduforge360' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'skill-category' ),
		) );

		// 4. Event Category
		register_taxonomy( 'event_category', array( 'events' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'          => __( 'Event Categories', 'eduforge360' ),
				'singular_name' => __( 'Event Category', 'eduforge360' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'event-category' ),
		) );

		// 5. Job Category
		register_taxonomy( 'job_category', array( 'jobs' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'          => __( 'Job Categories', 'eduforge360' ),
				'singular_name' => __( 'Job Category', 'eduforge360' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'job-category' ),
		) );

		// 6. Industry
		register_taxonomy( 'industry', array( 'companies', 'jobs' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'          => __( 'Industries', 'eduforge360' ),
				'singular_name' => __( 'Industry', 'eduforge360' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'industry' ),
		) );
	}
}
