<?php
/**
 * EduForge Career Development & Resume Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Career_Manager {

	/**
	 * Compute Career Readiness Score (0 - 100%)
	 * Weighted formula:
	 * - Skill Assessment Average: 30%
	 * - Course Progress & Completion: 25%
	 * - Projects Built: 20%
	 * - Certifications Earned: 15%
	 * - Resume Completeness: 10%
	 */
	public static function calculate_career_readiness( $student_id ) {
		global $wpdb;

		// 1. Skill Assessment Average
		$table_skills = EduForge_Database::get_table_name( 'skill_assessments' );
		$avg_skill = $wpdb->get_var( $wpdb->prepare(
			"SELECT AVG(score) FROM {$table_skills} WHERE student_id = %d",
			$student_id
		) );
		$skill_score = $avg_skill ? floatval( $avg_skill ) : 50.0;

		// 2. Course Completion & Progress
		$table_enrollments = EduForge_Database::get_table_name( 'enrollments' );
		$enrollment_progress = $wpdb->get_var( $wpdb->prepare(
			"SELECT AVG(progress) FROM {$table_enrollments} WHERE student_id = %d",
			$student_id
		) );
		$learning_score = $enrollment_progress ? floatval( $enrollment_progress ) : 40.0;

		// 3. Projects Count
		$project_count = count_user_posts( $student_id, 'projects' );
		$project_score = min( 100, $project_count * 35 );

		// 4. Certifications Count
		$table_certs = EduForge_Database::get_table_name( 'certificates' );
		$cert_count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(id) FROM {$table_certs} WHERE student_id = %d",
			$student_id
		) );
		$cert_score = min( 100, intval( $cert_count ) * 50 );

		// 5. Resume Completeness
		$resume_data = self::get_student_resume( $student_id );
		$resume_fields = array( 'full_name', 'summary', 'education', 'skills', 'projects', 'experience' );
		$filled = 0;
		foreach ( $resume_fields as $field ) {
			if ( ! empty( $resume_data[ $field ] ) ) {
				$filled++;
			}
		}
		$resume_score = round( ( $filled / count( $resume_fields ) ) * 100 );

		// Weighted aggregation
		$total_readiness = (
			( $skill_score * 0.30 ) +
			( $learning_score * 0.25 ) +
			( $project_score * 0.20 ) +
			( $cert_score * 0.15 ) +
			( $resume_score * 0.10 )
		);

		return round( $total_readiness );
	}

	/**
	 * Get student resume data
	 */
	public static function get_student_resume( $student_id ) {
		$resume = get_user_meta( $student_id, '_eduforge_student_resume', true );
		if ( ! is_array( $resume ) ) {
			$user = get_userdata( $student_id );
			$resume = array(
				'full_name'    => $user ? $user->display_name : 'Aarav Sharma',
				'email'        => $user ? $user->user_email : 'aarav.sharma@eduforge360.edu',
				'phone'        => '+91 98765 43210',
				'location'     => 'Bengaluru, India',
				'summary'      => 'Motivated MCA postgraduate student specialized in Python, scalable web architectures, and cloud backend solutions. Passionate about writing secure, clean, and test-driven code.',
				'education'    => "Master of Computer Applications (MCA) - 8.6 CGPA\nBachelor of Science in Computer Science - 8.4 CGPA",
				'skills'       => 'Python, PHP, React, MySQL, Docker, Git, AWS, REST APIs, Tailwind CSS',
				'projects'     => "EduForge360 Platform: Architected custom LMS & student development engine.\nCloud Microservice Tracker: Built real-time async monitoring pipeline with Redis.",
				'experience'   => "Software Development Intern at AlphaTech Solutions (6 Months)\nOpen Source Contributor to WordPress core documentation.",
				'achievements' => "Winner, Smart India Hackathon Regional Finals\nRanked Top 5% in National Coding Olympiad",
				'linkedin'     => 'https://linkedin.com/in/aarav-sharma-eduforge',
				'github'       => 'https://github.com/aarav-sharma',
			);
		}
		return $resume;
	}

	/**
	 * Save student resume data
	 */
	public static function save_student_resume( $student_id, $data ) {
		$sanitized = array(
			'full_name'    => sanitize_text_field( $data['full_name'] ?? '' ),
			'email'        => sanitize_email( $data['email'] ?? '' ),
			'phone'        => sanitize_text_field( $data['phone'] ?? '' ),
			'location'     => sanitize_text_field( $data['location'] ?? '' ),
			'summary'      => sanitize_textarea_field( $data['summary'] ?? '' ),
			'education'    => sanitize_textarea_field( $data['education'] ?? '' ),
			'skills'       => sanitize_textarea_field( $data['skills'] ?? '' ),
			'projects'     => sanitize_textarea_field( $data['projects'] ?? '' ),
			'experience'   => sanitize_textarea_field( $data['experience'] ?? '' ),
			'achievements' => sanitize_textarea_field( $data['achievements'] ?? '' ),
			'linkedin'     => esc_url_raw( $data['linkedin'] ?? '' ),
			'github'       => esc_url_raw( $data['github'] ?? '' ),
		);

		update_user_meta( $student_id, '_eduforge_student_resume', $sanitized );
		EduForge_Logger::info( "Student {$student_id} updated career resume profile." );

		return $sanitized;
	}
}
