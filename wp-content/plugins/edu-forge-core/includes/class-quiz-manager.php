<?php
/**
 * EduForge Quiz Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Quiz_Manager {

	/**
	 * Get questions for a quiz
	 */
	public static function get_quiz_questions( $quiz_id ) {
		$questions = get_post_meta( $quiz_id, '_eduforge_quiz_questions', true );
		if ( ! is_array( $questions ) || empty( $questions ) ) {
			// Sample structured questions if none set
			$questions = array(
				array(
					'id'            => 1,
					'type'          => 'mcq',
					'question'      => 'What is the primary architectural purpose of a WordPress action hook (do_action)?',
					'options'       => array(
						'A' => 'To modify and return processed content',
						'B' => 'To allow external code to execute specific routines at specific lifecycle events',
						'C' => 'To compile Sass into minified CSS',
						'D' => 'To sanitize SQL database queries automatically'
					),
					'correct'       => array( 'B' ),
					'explanation'   => 'Action hooks allow developers to trigger external functions at specific execution points without returning values.'
				),
				array(
					'id'            => 2,
					'type'          => 'true_false',
					'question'      => 'In modern WordPress REST API endpoints, permission_callback is mandatory for secure operations.',
					'options'       => array(
						'T' => 'True',
						'F' => 'False'
					),
					'correct'       => array( 'T' ),
					'explanation'   => 'WordPress prints warnings if permission_callback is not defined, and it is critical for authentication and capability checks.'
				),
				array(
					'id'            => 3,
					'type'          => 'multiple',
					'question'      => 'Which functions provide safe SQL query execution against SQL injection? (Select all that apply)',
					'options'       => array(
						'A' => '$wpdb->prepare()',
						'B' => 'Direct string interpolation: $wpdb->query("SELECT * FROM " . $_POST["val"])',
						'C' => '$wpdb->insert() with format parameters',
						'D' => 'esc_sql() applied to trusted input only'
					),
					'correct'       => array( 'A', 'C' ),
					'explanation'   => '$wpdb->prepare() and $wpdb->insert() with format specifiers ensure parameters are safely prepared and escaped.'
				),
			);
		}
		return $questions;
	}

	/**
	 * Grade a submitted quiz attempt
	 */
	public static function grade_quiz( $student_id, $quiz_id, $submitted_answers ) {
		global $wpdb;
		$questions = self::get_quiz_questions( $quiz_id );
		$total_questions = count( $questions );
		if ( $total_questions === 0 ) {
			return new WP_Error( 'empty_quiz', __( 'Quiz contains no questions.', 'eduforge360' ) );
		}

		$correct_count = 0;
		$review = array();

		foreach ( $questions as $q ) {
			$qid = $q['id'];
			$student_answer = isset( $submitted_answers[ $qid ] ) ? (array) $submitted_answers[ $qid ] : array();
			$correct_answer = (array) $q['correct'];

			// Compare sorted answer arrays
			sort( $student_answer );
			sort( $correct_answer );

			$is_correct = ( $student_answer === $correct_answer );
			if ( $is_correct ) {
				$correct_count++;
			}

			$review[ $qid ] = array(
				'is_correct'     => $is_correct,
				'student_answer' => $student_answer,
				'correct_answer' => $correct_answer,
				'explanation'    => isset( $q['explanation'] ) ? $q['explanation'] : '',
			);
		}

		$score = round( ( $correct_count / $total_questions ) * 100, 2 );
		$passing_grade = get_post_meta( $quiz_id, '_eduforge_passing_score', true ) ?: 70;
		$status = ( $score >= $passing_grade ) ? 'passed' : 'failed';

		// Get attempt count
		$table = EduForge_Database::get_table_name( 'quiz_attempts' );
		$previous_attempts = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(id) FROM {$table} WHERE student_id = %d AND quiz_id = %d",
			$student_id,
			$quiz_id
		) );
		$attempt_number = intval( $previous_attempts ) + 1;

		$course_id = get_post_meta( $quiz_id, '_eduforge_course_id', true ) ?: 0;

		// Record attempt
		$wpdb->insert(
			$table,
			array(
				'student_id'     => $student_id,
				'quiz_id'        => $quiz_id,
				'course_id'      => $course_id,
				'score'          => $score,
				'status'         => $status,
				'attempt_number' => $attempt_number,
				'answers'        => wp_json_encode( $submitted_answers ),
				'started_at'     => current_time( 'mysql' ),
				'completed_at'   => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%d', '%f', '%s', '%d', '%s', '%s', '%s' )
		);

		EduForge_Logger::info( "Quiz {$quiz_id} graded for student {$student_id}. Score: {$score}% ({$status}). Attempt #{$attempt_number}." );

		return array(
			'score'           => $score,
			'status'          => $status,
			'correct_count'   => $correct_count,
			'total_questions' => $total_questions,
			'attempt_number'  => $attempt_number,
			'review'          => $review,
		);
	}

	/**
	 * Get attempt history for a student
	 */
	public static function get_student_attempts( $student_id, $quiz_id = 0 ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'quiz_attempts' );

		if ( $quiz_id > 0 ) {
			return $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$table} WHERE student_id = %d AND quiz_id = %d ORDER BY id DESC",
				$student_id,
				$quiz_id
			) );
		}

		return $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$table} WHERE student_id = %d ORDER BY id DESC",
			$student_id
		) );
	}
}
