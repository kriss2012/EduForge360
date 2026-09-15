<?php
/**
 * EduForge Custom Post Types
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Post_Types {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ) );
		add_filter( 'manage_courses_posts_columns', array( __CLASS__, 'set_courses_columns' ) );
		add_action( 'manage_courses_posts_custom_column', array( __CLASS__, 'render_courses_columns' ), 10, 2 );
		add_filter( 'manage_jobs_posts_columns', array( __CLASS__, 'set_jobs_columns' ) );
		add_action( 'manage_jobs_posts_custom_column', array( __CLASS__, 'render_jobs_columns' ), 10, 2 );
	}

	public static function register_post_types() {
		// 1. Courses
		register_post_type( 'courses', array(
			'labels' => array(
				'name'               => __( 'Courses', 'eduforge360' ),
				'singular_name'      => __( 'Course', 'eduforge360' ),
				'add_new'            => __( 'Add New Course', 'eduforge360' ),
				'add_new_item'       => __( 'Add New Course', 'eduforge360' ),
				'edit_item'          => __( 'Edit Course', 'eduforge360' ),
				'all_items'          => __( 'All Courses', 'eduforge360' ),
			),
			'public'             => true,
			'has_archive'        => 'courses',
			'rewrite'            => array( 'slug' => 'courses' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-welcome-learn-more',
			'capability_type'    => 'post',
		) );

		// 2. Lessons
		register_post_type( 'lessons', array(
			'labels' => array(
				'name'               => __( 'Lessons', 'eduforge360' ),
				'singular_name'      => __( 'Lesson', 'eduforge360' ),
				'add_new'            => __( 'Add New Lesson', 'eduforge360' ),
				'edit_item'          => __( 'Edit Lesson', 'eduforge360' ),
			),
			'public'             => true,
			'rewrite'            => array( 'slug' => 'lessons' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-media-document',
		) );

		// 3. Quizzes
		register_post_type( 'quizzes', array(
			'labels' => array(
				'name'               => __( 'Quizzes', 'eduforge360' ),
				'singular_name'      => __( 'Quiz', 'eduforge360' ),
				'add_new'            => __( 'Add New Quiz', 'eduforge360' ),
				'edit_item'          => __( 'Edit Quiz', 'eduforge360' ),
			),
			'public'             => true,
			'rewrite'            => array( 'slug' => 'quizzes' ),
			'supports'           => array( 'title', 'editor', 'excerpt' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-forms',
		) );

		// 4. Assignments
		register_post_type( 'assignments', array(
			'labels' => array(
				'name'               => __( 'Assignments', 'eduforge360' ),
				'singular_name'      => __( 'Assignment', 'eduforge360' ),
				'add_new'            => __( 'Add New Assignment', 'eduforge360' ),
				'edit_item'          => __( 'Edit Assignment', 'eduforge360' ),
			),
			'public'             => true,
			'rewrite'            => array( 'slug' => 'assignments' ),
			'supports'           => array( 'title', 'editor' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-clipboard',
		) );

		// 5. Events
		register_post_type( 'events', array(
			'labels' => array(
				'name'               => __( 'Events & Hackathons', 'eduforge360' ),
				'singular_name'      => __( 'Event', 'eduforge360' ),
				'add_new'            => __( 'Add New Event', 'eduforge360' ),
				'edit_item'          => __( 'Edit Event', 'eduforge360' ),
			),
			'public'             => true,
			'has_archive'        => 'events',
			'rewrite'            => array( 'slug' => 'events' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-calendar-alt',
		) );

		// 6. Companies
		register_post_type( 'companies', array(
			'labels' => array(
				'name'               => __( 'Companies', 'eduforge360' ),
				'singular_name'      => __( 'Company', 'eduforge360' ),
				'add_new'            => __( 'Add New Company', 'eduforge360' ),
				'edit_item'          => __( 'Edit Company', 'eduforge360' ),
			),
			'public'             => true,
			'rewrite'            => array( 'slug' => 'companies' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-building',
		) );

		// 7. Jobs / Placement Drives
		register_post_type( 'jobs', array(
			'labels' => array(
				'name'               => __( 'Jobs & Drives', 'eduforge360' ),
				'singular_name'      => __( 'Job Opportunity', 'eduforge360' ),
				'add_new'            => __( 'Add New Job', 'eduforge360' ),
				'edit_item'          => __( 'Edit Job', 'eduforge360' ),
			),
			'public'             => true,
			'has_archive'        => 'jobs',
			'rewrite'            => array( 'slug' => 'jobs' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-id-alt',
		) );

		// 8. Certificates (CPT for representation / admin tracking)
		register_post_type( 'certificates', array(
			'labels' => array(
				'name'               => __( 'Certificates', 'eduforge360' ),
				'singular_name'      => __( 'Certificate', 'eduforge360' ),
			),
			'public'             => true,
			'supports'           => array( 'title', 'editor' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-awards',
		) );

		// 9. Projects
		register_post_type( 'projects', array(
			'labels' => array(
				'name'               => __( 'Student Projects', 'eduforge360' ),
				'singular_name'      => __( 'Project', 'eduforge360' ),
			),
			'public'             => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-portfolio',
		) );

		// 10. Testimonials
		register_post_type( 'testimonials', array(
			'labels' => array(
				'name'               => __( 'Testimonials', 'eduforge360' ),
				'singular_name'      => __( 'Testimonial', 'eduforge360' ),
			),
			'public'             => true,
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-testimonial',
		) );

		// 11. Resources
		register_post_type( 'resources', array(
			'labels' => array(
				'name'               => __( 'Placement Resources', 'eduforge360' ),
				'singular_name'      => __( 'Resource', 'eduforge360' ),
			),
			'public'             => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-download',
		) );
	}

	public static function set_courses_columns( $columns ) {
		$new_columns = array();
		$new_columns['cb'] = $columns['cb'];
		$new_columns['title'] = __( 'Course Title', 'eduforge360' );
		$new_columns['instructor'] = __( 'Instructor', 'eduforge360' );
		$new_columns['category'] = __( 'Category', 'eduforge360' );
		$new_columns['students'] = __( 'Enrolled Students', 'eduforge360' );
		$new_columns['price'] = __( 'Price', 'eduforge360' );
		$new_columns['date'] = $columns['date'];
		return $new_columns;
	}

	public static function render_courses_columns( $column, $post_id ) {
		global $wpdb;
		switch ( $column ) {
			case 'instructor':
				$author_id = get_post_field( 'post_author', $post_id );
				echo esc_html( get_the_author_meta( 'display_name', $author_id ) );
				break;
			case 'category':
				$terms = get_the_term_list( $post_id, 'course_category', '', ', ' );
				echo $terms ? wp_kses_post( $terms ) : esc_html__( 'Uncategorized', 'eduforge360' );
				break;
			case 'students':
				$table = EduForge_Database::get_table_name( 'enrollments' );
				$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$table} WHERE course_id = %d", $post_id ) );
				echo '<strong>' . intval( $count ) . '</strong>';
				break;
			case 'price':
				$price = get_post_meta( $post_id, '_eduforge_price', true );
				echo ! empty( $price ) && $price > 0 ? '₹' . esc_html( $price ) : '<span class="eduforge-badge-free">Free</span>';
				break;
		}
	}

	public static function set_jobs_columns( $columns ) {
		$new_columns = array();
		$new_columns['cb'] = $columns['cb'];
		$new_columns['title'] = __( 'Role / Title', 'eduforge360' );
		$new_columns['company'] = __( 'Company', 'eduforge360' );
		$new_columns['applications'] = __( 'Applications', 'eduforge360' );
		$new_columns['deadline'] = __( 'Deadline', 'eduforge360' );
		$new_columns['date'] = $columns['date'];
		return $new_columns;
	}

	public static function render_jobs_columns( $column, $post_id ) {
		global $wpdb;
		switch ( $column ) {
			case 'company':
				$comp = get_post_meta( $post_id, '_eduforge_company_name', true );
				echo esc_html( $comp ? $comp : '—' );
				break;
			case 'applications':
				$table = EduForge_Database::get_table_name( 'job_applications' );
				$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM {$table} WHERE job_id = %d", $post_id ) );
				echo '<strong>' . intval( $count ) . '</strong>';
				break;
			case 'deadline':
				$dl = get_post_meta( $post_id, '_eduforge_job_deadline', true );
				echo esc_html( $dl ? $dl : 'Open' );
				break;
		}
	}
}
