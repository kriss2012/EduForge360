<?php
/**
 * EduForge Lesson Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Lesson_Manager {

	/**
	 * Mark a lesson complete for a student
	 */
	public static function mark_lesson_complete( $student_id, $lesson_id ) {
		$course_id = get_post_meta( $lesson_id, '_eduforge_course_id', true );
		if ( ! $course_id ) {
			return new WP_Error( 'invalid_course', __( 'Course ID not found for this lesson.', 'eduforge360' ) );
		}

		$completed = get_user_meta( $student_id, '_eduforge_completed_lessons_' . $course_id, true );
		if ( ! is_array( $completed ) ) {
			$completed = array();
		}

		if ( ! in_array( $lesson_id, $completed ) ) {
			$completed[] = intval( $lesson_id );
			update_user_meta( $student_id, '_eduforge_completed_lessons_' . $course_id, $completed );
		}

		// Recompute course progress
		$progress = EduForge_Course_Manager::calculate_course_progress( $student_id, $course_id );

		EduForge_Logger::info( "Student {$student_id} completed lesson {$lesson_id}. Course {$course_id} progress is now {$progress}%." );

		return array(
			'success'        => true,
			'progress'       => $progress,
			'completed_count'=> count( $completed ),
		);
	}

	/**
	 * Check if a student has completed a lesson
	 */
	public static function is_lesson_completed( $student_id, $lesson_id ) {
		$course_id = get_post_meta( $lesson_id, '_eduforge_course_id', true );
		if ( ! $course_id ) {
			return false;
		}
		$completed = get_user_meta( $student_id, '_eduforge_completed_lessons_' . $course_id, true );
		return is_array( $completed ) && in_array( intval( $lesson_id ), $completed, true );
	}

	/**
	 * Get next and previous lesson IDs
	 */
	public static function get_adjacent_lessons( $course_id, $current_lesson_id ) {
		$lessons = EduForge_Course_Manager::get_course_lessons( $course_id );
		$prev_id = null;
		$next_id = null;

		$lesson_ids = wp_list_pluck( $lessons, 'ID' );
		$current_index = array_search( intval( $current_lesson_id ), $lesson_ids, true );

		if ( false !== $current_index ) {
			if ( $current_index > 0 ) {
				$prev_id = $lesson_ids[ $current_index - 1 ];
			}
			if ( $current_index < count( $lesson_ids ) - 1 ) {
				$next_id = $lesson_ids[ $current_index + 1 ];
			}
		}

		return array(
			'prev' => $prev_id,
			'next' => $next_id,
		);
	}

	/**
	 * Get lesson metadata (video URL, PDF link, resources, quiz id, assignment id)
	 */
	public static function get_lesson_meta( $lesson_id ) {
		return array(
			'video_url'       => get_post_meta( $lesson_id, '_eduforge_video_url', true ),
			'pdf_url'         => get_post_meta( $lesson_id, '_eduforge_pdf_url', true ),
			'code_example'    => get_post_meta( $lesson_id, '_eduforge_code_snippet', true ),
			'duration'        => get_post_meta( $lesson_id, '_eduforge_duration', true ) ?: '15 mins',
			'quiz_id'         => get_post_meta( $lesson_id, '_eduforge_quiz_id', true ),
			'assignment_id'   => get_post_meta( $lesson_id, '_eduforge_assignment_id', true ),
		);
	}
}
