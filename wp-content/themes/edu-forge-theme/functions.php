<?php
/**
 * EduForge360 Custom Theme Functions
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDUFORGE_THEME_VERSION', '1.0.0' );
define( 'EDUFORGE_THEME_DIR', get_template_directory() );
define( 'EDUFORGE_THEME_URI', get_template_directory_uri() );

/**
 * Setup theme features
 */
function eduforge_theme_setup() {
	// Let WordPress manage document title
	add_theme_support( 'title-tag' );

	// Enable featured images
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 600, 360, true );
	add_image_size( 'eduforge-course-card', 480, 270, true );
	add_image_size( 'eduforge-banner', 1280, 500, true );

	// HTML5 markup support
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Custom Logo
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Register Navigation Menus
	register_nav_menus( array(
		'primary'   => __( 'Primary Navigation', 'eduforge360' ),
		'footer'    => __( 'Footer Navigation', 'eduforge360' ),
		'dashboard' => __( 'Student Dashboard Sidebar', 'eduforge360' ),
	) );
}
add_action( 'after_setup_theme', 'eduforge_theme_setup' );

/**
 * Enqueue scripts and styles conditionally
 */
function eduforge_enqueue_assets() {
	// Main Theme Stylesheet & Custom Tokens
	wp_enqueue_style( 'eduforge-style', get_stylesheet_uri(), array(), EDUFORGE_THEME_VERSION );
	wp_enqueue_style( 'eduforge-main', EDUFORGE_THEME_URI . '/assets/css/main.css', array( 'eduforge-style' ), EDUFORGE_THEME_VERSION );
	wp_enqueue_style( 'eduforge-responsive', EDUFORGE_THEME_URI . '/assets/css/responsive.css', array( 'eduforge-main' ), EDUFORGE_THEME_VERSION );

	// Main JavaScript
	wp_enqueue_script( 'eduforge-theme-js', EDUFORGE_THEME_URI . '/assets/js/main.js', array( 'jquery' ), EDUFORGE_THEME_VERSION, true );

	// Conditional Enqueueing for Maximum Performance (Lighthouse 90+)
	if ( is_page_template( 'page-dashboard.php' ) || is_page( 'dashboard' ) ) {
		wp_enqueue_style( 'eduforge-dashboard-css', EDUFORGE_THEME_URI . '/assets/css/dashboard.css', array(), EDUFORGE_THEME_VERSION );
		wp_enqueue_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js', array(), '4.4.0', true );
		wp_enqueue_script( 'eduforge-dashboard-js', EDUFORGE_THEME_URI . '/assets/js/dashboard.js', array( 'jquery', 'chartjs' ), EDUFORGE_THEME_VERSION, true );
	}

	if ( is_singular( 'lessons' ) || is_singular( 'courses' ) ) {
		wp_enqueue_style( 'eduforge-course-css', EDUFORGE_THEME_URI . '/assets/css/course.css', array(), EDUFORGE_THEME_VERSION );
		wp_enqueue_script( 'eduforge-course-player', EDUFORGE_THEME_URI . '/assets/js/course-player.js', array( 'jquery' ), EDUFORGE_THEME_VERSION, true );
	}

	if ( is_page_template( 'page-assessment.php' ) || is_page( 'assessment' ) ) {
		wp_enqueue_script( 'eduforge-assessment-js', EDUFORGE_THEME_URI . '/assets/js/assessment.js', array( 'jquery' ), EDUFORGE_THEME_VERSION, true );
	}

	if ( is_page_template( 'page-resume-builder.php' ) || is_page( 'resume-builder' ) ) {
		wp_enqueue_script( 'eduforge-resume-js', EDUFORGE_THEME_URI . '/assets/js/resume-builder.js', array( 'jquery' ), EDUFORGE_THEME_VERSION, true );
	}

	if ( is_post_type_archive( 'courses' ) || is_page( 'courses' ) ) {
		wp_enqueue_script( 'eduforge-ajax-filters', EDUFORGE_THEME_URI . '/assets/js/ajax-filters.js', array( 'jquery' ), EDUFORGE_THEME_VERSION, true );
	}

	// Localize Theme Script
	wp_localize_script( 'eduforge-theme-js', 'eduforgeThemeVars', array(
		'ajaxurl'   => admin_url( 'admin-ajax.php' ),
		'nonce'     => wp_create_nonce( 'eduforge_secure_nonce' ),
		'restUrl'   => esc_url_raw( rest_url( 'eduforge/v1/' ) ),
		'homeUrl'   => home_url( '/' ),
		'siteName'  => get_bloginfo( 'name' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'eduforge_enqueue_assets' );

/**
 * Register widget areas
 */
function eduforge_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Course Sidebar', 'eduforge360' ),
		'id'            => 'sidebar-course',
		'description'   => __( 'Widgets displayed on course catalog and detail views.', 'eduforge360' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'eduforge_widgets_init' );

/**
 * Clean Excerpt Length
 */
function eduforge_custom_excerpt_length( $length ) {
	return 22;
}
add_filter( 'excerpt_length', 'eduforge_custom_excerpt_length', 999 );

/**
 * Include helper tags and customizer settings
 */
require_once EDUFORGE_THEME_DIR . '/inc/template-tags.php';
require_once EDUFORGE_THEME_DIR . '/inc/customizer.php';
