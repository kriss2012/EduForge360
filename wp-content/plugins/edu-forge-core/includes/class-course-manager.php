<?php
/**
 * EduForge Course Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Course_Manager {

	/**
	 * Check if a student is enrolled in a course
	 */
	public static function is_enrolled( $student_id, $course_id ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'enrollments' );

		$enrollment = $wpdb->get_row( $wpdb->prepare(
			"SELECT id, status, progress FROM {$table} WHERE student_id = %d AND course_id = %d",
			intval( $student_id ),
			intval( $course_id )
		) );

		return ! empty( $enrollment ) ? $enrollment : false;
	}

	/**
	 * Enroll a student in a course
	 */
	public static function enroll_student( $student_id, $course_id ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'enrollments' );

		if ( self::is_enrolled( $student_id, $course_id ) ) {
			return new WP_Error( 'already_enrolled', __( 'Student is already enrolled in this course.', 'eduforge360' ) );
		}

		$inserted = $wpdb->insert(
			$table,
			array(
				'student_id'   => intval( $student_id ),
				'course_id'    => intval( $course_id ),
				'progress'     => 0,
				'status'       => 'active',
				'enrolled_at'  => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%d', '%s', '%s' )
		);

		if ( $inserted ) {
			EduForge_Logger::info( "Student {$student_id} successfully enrolled in course {$course_id}." );
			do_action( 'eduforge_student_enrolled', $student_id, $course_id );
			return $wpdb->insert_id;
		}

		EduForge_Logger::error( "Enrollment failed for student {$student_id} into course {$course_id}." );
		return new WP_Error( 'enrollment_failed', __( 'Could not complete enrollment.', 'eduforge360' ) );
	}

	/**
	 * Get all lessons for a course
	 */
	public static function get_course_lessons( $course_id ) {
		$args = array(
			'post_type'      => 'lessons',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => '_eduforge_course_id',
			'meta_value'     => $course_id,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);
		return get_posts( $args );
	}

	/**
	 * Calculate course progress percentage
	 */
	public static function calculate_course_progress( $student_id, $course_id ) {
		global $wpdb;
		$lessons = self::get_course_lessons( $course_id );
		$total_lessons = count( $lessons );

		if ( $total_lessons === 0 ) {
			return 0;
		}

		// Count completed lessons stored in user meta
		$completed_lessons = get_user_meta( $student_id, '_eduforge_completed_lessons_' . $course_id, true );
		if ( ! is_array( $completed_lessons ) ) {
			$completed_lessons = array();
		}

		$completed_count = count( $completed_lessons );
		$progress = min( 100, round( ( $completed_count / $total_lessons ) * 100 ) );

		// Update database enrollment record
		$table = EduForge_Database::get_table_name( 'enrollments' );
		$data = array( 'progress' => $progress );
		$format = array( '%d' );

		if ( $progress >= 100 ) {
			$data['status'] = 'completed';
			$data['completed_at'] = current_time( 'mysql' );
			$format[] = '%s';
			$format[] = '%s';
			do_action( 'eduforge_course_completed', $student_id, $course_id );
		}

		$wpdb->update(
			$table,
			$data,
			array( 'student_id' => $student_id, 'course_id' => $course_id ),
			$format,
			array( '%d', '%d' )
		);

		return $progress;
	}

	/**
	 * Get student enrolled courses with filter
	 */
	public static function get_student_courses( $student_id, $filter = 'all' ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'enrollments' );

		$sql = "SELECT e.*, p.post_title, p.post_content, p.guid 
				FROM {$table} e 
				JOIN {$wpdb->posts} p ON e.course_id = p.ID 
				WHERE e.student_id = %d";

		if ( 'completed' === $filter ) {
			$sql .= " AND e.status = 'completed'";
		} elseif ( 'in_progress' === $filter ) {
			$sql .= " AND e.status = 'active' AND e.progress > 0 AND e.progress < 100";
		} elseif ( 'not_started' === $filter ) {
			$sql .= " AND e.status = 'active' AND e.progress = 0";
		}

		$sql .= " ORDER BY e.enrolled_at DESC";

		return $wpdb->get_results( $wpdb->prepare( $sql, intval( $student_id ) ) );
	}

	/**
	 * Get course syllabus summary
	 */
	public static function get_course_details( $course_id ) {
		$duration    = get_post_meta( $course_id, '_eduforge_duration', true );
		$level       = get_post_meta( $course_id, '_eduforge_level', true );
		$price       = get_post_meta( $course_id, '_eduforge_price', true );
		$rating      = get_post_meta( $course_id, '_eduforge_rating', true );
		$outcomes    = get_post_meta( $course_id, '_eduforge_outcomes', true );
		$projects    = get_post_meta( $course_id, '_eduforge_projects', true );

		return array(
			'duration'    => $duration ? $duration : '8 Weeks',
			'level'       => $level ? $level : 'Intermediate',
			'price'       => $price ? floatval( $price ) : 0,
			'rating'      => $rating ? floatval( $rating ) : 4.8,
			'outcomes'    => is_array( $outcomes ) ? $outcomes : ( $outcomes ? explode( "\n", $outcomes ) : array( 'Master core concepts', 'Build hands-on capstone projects', 'Industry-aligned preparation' ) ),
			'projects'    => is_array( $projects ) ? $projects : array( 'Production Deployment Project', 'Scalable Architecture Capstone' ),
		);
	}
}
