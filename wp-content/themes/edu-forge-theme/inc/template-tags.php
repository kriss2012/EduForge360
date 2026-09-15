<?php
/**
 * Custom Template Tags and Helpers
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display EduForge Breadcrumbs
 */
function eduforge_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="eduforge-breadcrumbs" aria-label="Breadcrumb">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">Home</a>';

	if ( is_post_type_archive( 'courses' ) || is_singular( 'courses' ) ) {
		echo ' <span class="sep">/</span> <a href="' . esc_url( get_post_type_archive_link( 'courses' ) ) . '">Courses</a>';
	} elseif ( is_post_type_archive( 'jobs' ) || is_singular( 'jobs' ) ) {
		echo ' <span class="sep">/</span> <a href="' . esc_url( get_post_type_archive_link( 'jobs' ) ) . '">Placement Drives</a>';
	} elseif ( is_post_type_archive( 'events' ) || is_singular( 'events' ) ) {
		echo ' <span class="sep">/</span> <a href="' . esc_url( get_post_type_archive_link( 'events' ) ) . '">Events</a>';
	}

	if ( is_single() ) {
		echo ' <span class="sep">/</span> <span class="current">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_page() ) {
		echo ' <span class="sep">/</span> <span class="current">' . esc_html( get_the_title() ) . '</span>';
	}

	echo '</nav>';
}

/**
 * Output JSON-LD Schema.org Structured Data
 */
function eduforge_schema_markup() {
	$schema = array(
		'@context' => 'https://schema.org',
	);

	if ( is_singular( 'courses' ) ) {
		$course_id = get_the_ID();
		$details = class_exists( 'EduForge_Course_Manager' ) ? EduForge_Course_Manager::get_course_details( $course_id ) : array();
		$schema['@type'] = 'Course';
		$schema['name'] = get_the_title();
		$schema['description'] = wp_strip_all_tags( get_the_excerpt() );
		$schema['provider'] = array(
			'@type'  => 'EducationalOrganization',
			'name'   => 'EduForge360 Institute',
			'sameAs' => home_url( '/' ),
		);
		$schema['offers'] = array(
			'@type'         => 'Offer',
			'price'         => ! empty( $details['price'] ) ? $details['price'] : '0',
			'priceCurrency' => 'INR',
			'category'      => ! empty( $details['price'] ) ? 'Paid' : 'Free',
		);
	} elseif ( is_singular( 'events' ) ) {
		$event_id = get_the_ID();
		$meta = class_exists( 'EduForge_Event_Manager' ) ? EduForge_Event_Manager::get_event_meta( $event_id ) : array();
		$schema['@type'] = 'Event';
		$schema['name'] = get_the_title();
		$schema['startDate'] = $meta['date'] ?? '2026-10-20';
		$schema['location'] = array(
			'@type'   => 'Place',
			'name'    => $meta['location'] ?? 'EduForge Auditorium',
			'address' => 'Bengaluru, India',
		);
	} elseif ( is_singular( 'jobs' ) ) {
		$job_id = get_the_ID();
		$meta = class_exists( 'EduForge_Placement_Manager' ) ? EduForge_Placement_Manager::get_job_meta( $job_id ) : array();
		$schema['@type'] = 'JobPosting';
		$schema['title'] = get_the_title();
		$schema['description'] = wp_strip_all_tags( get_the_content() );
		$schema['hiringOrganization'] = array(
			'@type' => 'Organization',
			'name'  => $meta['company'] ?? 'Tech Partner',
		);
	} else {
		$schema['@type'] = 'EducationalOrganization';
		$schema['name'] = 'EduForge360';
		$schema['url'] = home_url( '/' );
		$schema['description'] = 'Industrial Student Development, Learning Management, Assessment, and Career Preparation Platform.';
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'eduforge_schema_markup' );
