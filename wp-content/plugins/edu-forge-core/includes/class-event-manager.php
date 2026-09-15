<?php
/**
 * EduForge Events & Hackathon Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Event_Manager {

	/**
	 * Register a student for an event
	 */
	public static function register_for_event( $student_id, $event_id ) {
		$registered_students = get_post_meta( $event_id, '_eduforge_registered_students', true );
		if ( ! is_array( $registered_students ) ) {
			$registered_students = array();
		}

		if ( in_array( intval( $student_id ), $registered_students, true ) ) {
			return new WP_Error( 'already_registered', __( 'You are already registered for this event.', 'eduforge360' ) );
		}

		$capacity = get_post_meta( $event_id, '_eduforge_max_capacity', true ) ?: 200;
		if ( count( $registered_students ) >= intval( $capacity ) ) {
			return new WP_Error( 'event_full', __( 'Registration closed: Event capacity reached.', 'eduforge360' ) );
		}

		$registered_students[] = intval( $student_id );
		update_post_meta( $event_id, '_eduforge_registered_students', $registered_students );

		// Also record on user meta for reverse lookups
		$user_events = get_user_meta( $student_id, '_eduforge_enrolled_events', true );
		if ( ! is_array( $user_events ) ) {
			$user_events = array();
		}
		$user_events[] = intval( $event_id );
		update_user_meta( $student_id, '_eduforge_enrolled_events', array_unique( $user_events ) );

		EduForge_Logger::info( "Student {$student_id} registered for event {$event_id}." );
		do_action( 'eduforge_event_registered', $student_id, $event_id );

		return array(
			'success' => true,
			'count'   => count( $registered_students ),
		);
	}

	/**
	 * Check if student is registered for an event
	 */
	public static function is_student_registered( $student_id, $event_id ) {
		$registered_students = get_post_meta( $event_id, '_eduforge_registered_students', true );
		return is_array( $registered_students ) && in_array( intval( $student_id ), $registered_students, true );
	}

	/**
	 * Get event metadata
	 */
	public static function get_event_meta( $event_id ) {
		$reg_students = get_post_meta( $event_id, '_eduforge_registered_students', true );
		$reg_count = is_array( $reg_students ) ? count( $reg_students ) : 0;

		return array(
			'date'          => get_post_meta( $event_id, '_eduforge_event_date', true ) ?: '2026-10-15',
			'time'          => get_post_meta( $event_id, '_eduforge_event_time', true ) ?: '10:00 AM - 04:00 PM IST',
			'location'      => get_post_meta( $event_id, '_eduforge_event_location', true ) ?: 'Tech Auditorium & Virtual Stream',
			'speaker'       => get_post_meta( $event_id, '_eduforge_event_speaker', true ) ?: 'Industry Technical Leaders',
			'capacity'      => get_post_meta( $event_id, '_eduforge_max_capacity', true ) ?: 200,
			'registered'    => $reg_count,
			'banner_url'    => get_the_post_thumbnail_url( $event_id, 'full' ) ?: '',
		);
	}
}
