<?php
/**
 * EduForge Skill Assessment & Recommendation Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Skill_Manager {

	/**
	 * Core assessment domains
	 */
	public static function get_skill_domains() {
		return array(
			'programming'       => __( 'Programming & Algorithms', 'eduforge360' ),
			'web_dev'           => __( 'Web Development', 'eduforge360' ),
			'database'          => __( 'Database & SQL', 'eduforge360' ),
			'cloud'             => __( 'Cloud Computing', 'eduforge360' ),
			'devops'            => __( 'DevOps & Git', 'eduforge360' ),
			'aiml'              => __( 'AI & Machine Learning', 'eduforge360' ),
			'communication'     => __( 'Professional Communication', 'eduforge360' ),
			'aptitude'          => __( 'Quantitative Aptitude', 'eduforge360' ),
			'logical_reasoning' => __( 'Logical Reasoning', 'eduforge360' ),
		);
	}

	/**
	 * Record or update student skill assessment score
	 */
	public static function save_assessment( $student_id, $category, $score, $strengths = '', $weaknesses = '' ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'skill_assessments' );

		// Determine skill level
		$level = 'Beginner';
		if ( $score >= 80 ) {
			$level = 'Advanced';
		} elseif ( $score >= 60 ) {
			$level = 'Intermediate';
		}

		// Check if record exists for this domain
		$existing_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM {$table} WHERE student_id = %d AND category = %s",
			$student_id,
			$category
		) );

		if ( $existing_id ) {
			$wpdb->update(
				$table,
				array(
					'score'        => floatval( $score ),
					'skill_level'  => $level,
					'strengths'    => sanitize_text_field( $strengths ),
					'weaknesses'   => sanitize_text_field( $weaknesses ),
					'assessed_at'  => current_time( 'mysql' ),
				),
				array( 'id' => $existing_id ),
				array( '%f', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);
			$assessment_id = $existing_id;
		} else {
			$wpdb->insert(
				$table,
				array(
					'student_id'   => $student_id,
					'category'     => sanitize_key( $category ),
					'score'        => floatval( $score ),
					'skill_level'  => $level,
					'strengths'    => sanitize_text_field( $strengths ),
					'weaknesses'   => sanitize_text_field( $weaknesses ),
					'assessed_at'  => current_time( 'mysql' ),
				),
				array( '%d', '%s', '%f', '%s', '%s', '%s' )
			);
			$assessment_id = $wpdb->insert_id;
		}

		EduForge_Logger::info( "Skill assessment recorded for student {$student_id}, domain: {$category}, score: {$score}% ({$level})." );
		do_action( 'eduforge_skill_assessed', $student_id, $category, $score, $level );

		return $assessment_id;
	}

	/**
	 * Get all skill scores for a student
	 */
	public static function get_student_skills( $student_id ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'skill_assessments' );

		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$table} WHERE student_id = %d ORDER BY score DESC",
			$student_id
		) );

		$skills = array();
		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$skills[ $row->category ] = array(
					'score'      => floatval( $row->score ),
					'level'      => $row->skill_level,
					'strengths'  => $row->strengths,
					'weaknesses' => $row->weaknesses,
					'assessed_at'=> $row->assessed_at,
				);
			}
		}

		// Fill default values if not yet assessed
		$domains = self::get_skill_domains();
		foreach ( $domains as $key => $name ) {
			if ( ! isset( $skills[ $key ] ) ) {
				$skills[ $key ] = array(
					'score'      => 0,
					'level'      => 'Not Assessed',
					'strengths'  => '',
					'weaknesses' => '',
					'assessed_at'=> null,
				);
			}
		}

		return $skills;
	}

	/**
	 * Rule-Based Recommendation Engine
	 * Generates tailored course recommendations based on weakness thresholds
	 */
	public static function get_recommendations( $student_id ) {
		$skills = self::get_student_skills( $student_id );
		$recommendations = array();

		// Rule 1: Python / Programming
		if ( isset( $skills['programming']['score'] ) && $skills['programming']['score'] < 60 ) {
			$recommendations[] = array(
				'domain'      => 'programming',
				'score'       => $skills['programming']['score'],
				'title'       => 'Python Fundamentals & Data Structures',
				'slug'        => 'python-development',
				'reason'      => __( 'Your programming assessment score is under 60%. Mastering Python fundamentals will reinforce your core logic.', 'eduforge360' ),
			);
		}

		// Rule 2: Database / SQL
		if ( isset( $skills['database']['score'] ) && $skills['database']['score'] < 60 ) {
			$recommendations[] = array(
				'domain'      => 'database',
				'score'       => $skills['database']['score'],
				'title'       => 'SQL & Relational Database Architecture',
				'slug'        => 'database-development',
				'reason'      => __( 'Database query optimization and normalization skills are essential for industry placement drives.', 'eduforge360' ),
			);
		}

		// Rule 3: Git & DevOps
		if ( isset( $skills['devops']['score'] ) && $skills['devops']['score'] < 60 ) {
			$recommendations[] = array(
				'domain'      => 'devops',
				'score'       => $skills['devops']['score'],
				'title'       => 'Git, GitHub & Modern CI/CD Essentials',
				'slug'        => 'devops-engineering',
				'reason'      => __( 'Professional developers require mastery of version control workflows and branch collaboration.', 'eduforge360' ),
			);
		}

		// Rule 4: Communication
		if ( isset( $skills['communication']['score'] ) && $skills['communication']['score'] < 65 ) {
			$recommendations[] = array(
				'domain'      => 'communication',
				'score'       => $skills['communication']['score'],
				'title'       => 'Professional Tech Communication & Interview Mastery',
				'slug'        => 'tech-communication',
				'reason'      => __( 'Clear technical articulation directly enhances your technical screening and HR interview conversion.', 'eduforge360' ),
			);
		}

		// Rule 5: Cloud
		if ( isset( $skills['cloud']['score'] ) && $skills['cloud']['score'] < 60 ) {
			$recommendations[] = array(
				'domain'      => 'cloud',
				'score'       => $skills['cloud']['score'],
				'title'       => 'Cloud Architecture & AWS Solutions Foundation',
				'slug'        => 'cloud-computing',
				'reason'      => __( 'Enterprise placements heavily test cloud-native deployment patterns and serverless basics.', 'eduforge360' ),
			);
		}

		return $recommendations;
	}
}
