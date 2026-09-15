<?php
/**
 * EduForge Custom Database Handler
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Database {

	/**
	 * Table name accessors
	 */
	public static function get_table_name( $table ) {
		global $wpdb;
		return $wpdb->prefix . 'eduforge_' . $table;
	}

	/**
	 * Create or update custom database tables using dbDelta
	 */
	public static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table_enrollments = self::get_table_name( 'enrollments' );
		$table_quiz_attempts = self::get_table_name( 'quiz_attempts' );
		$table_certificates = self::get_table_name( 'certificates' );
		$table_job_applications = self::get_table_name( 'job_applications' );
		$table_skill_assessments = self::get_table_name( 'skill_assessments' );
		$table_assignments = self::get_table_name( 'assignments_submissions' );

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// 1. Enrollments Table
		$sql_enrollments = "CREATE TABLE {$table_enrollments} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			student_id bigint(20) unsigned NOT NULL,
			course_id bigint(20) unsigned NOT NULL,
			progress tinyint(3) unsigned NOT NULL DEFAULT 0,
			status varchar(30) NOT NULL DEFAULT 'active',
			enrolled_at datetime NOT NULL,
			completed_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY student_id (student_id),
			KEY course_id (course_id),
			KEY status (status)
		) {$charset_collate};";
		dbDelta( $sql_enrollments );

		// 2. Quiz Attempts Table
		$sql_quiz = "CREATE TABLE {$table_quiz_attempts} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			student_id bigint(20) unsigned NOT NULL,
			quiz_id bigint(20) unsigned NOT NULL,
			course_id bigint(20) unsigned NOT NULL DEFAULT 0,
			score decimal(5,2) NOT NULL DEFAULT 0.00,
			status varchar(20) NOT NULL DEFAULT 'failed',
			attempt_number int(11) NOT NULL DEFAULT 1,
			answers longtext DEFAULT NULL,
			started_at datetime NOT NULL,
			completed_at datetime DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY student_id (student_id),
			KEY quiz_id (quiz_id)
		) {$charset_collate};";
		dbDelta( $sql_quiz );

		// 3. Certificates Table
		$sql_certificates = "CREATE TABLE {$table_certificates} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			student_id bigint(20) unsigned NOT NULL,
			course_id bigint(20) unsigned NOT NULL,
			certificate_number varchar(60) NOT NULL,
			verification_hash varchar(64) NOT NULL,
			score decimal(5,2) NOT NULL DEFAULT 100.00,
			issued_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY certificate_number (certificate_number),
			UNIQUE KEY verification_hash (verification_hash),
			KEY student_id (student_id),
			KEY course_id (course_id)
		) {$charset_collate};";
		dbDelta( $sql_certificates );

		// 4. Job Applications Table
		$sql_jobs = "CREATE TABLE {$table_job_applications} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			student_id bigint(20) unsigned NOT NULL,
			job_id bigint(20) unsigned NOT NULL,
			company_id bigint(20) unsigned NOT NULL DEFAULT 0,
			status varchar(30) NOT NULL DEFAULT 'applied',
			cover_note text DEFAULT NULL,
			resume_url varchar(255) DEFAULT NULL,
			applied_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY student_id (student_id),
			KEY job_id (job_id),
			KEY status (status)
		) {$charset_collate};";
		dbDelta( $sql_jobs );

		// 5. Skill Assessments Table
		$sql_skills = "CREATE TABLE {$table_skill_assessments} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			student_id bigint(20) unsigned NOT NULL,
			category varchar(50) NOT NULL,
			score decimal(5,2) NOT NULL DEFAULT 0.00,
			skill_level varchar(30) NOT NULL DEFAULT 'Beginner',
			strengths text DEFAULT NULL,
			weaknesses text DEFAULT NULL,
			assessed_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY student_id (student_id),
			KEY category (category)
		) {$charset_collate};";
		dbDelta( $sql_skills );

		// 6. Assignment Submissions Table
		$sql_assignments = "CREATE TABLE {$table_assignments} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			student_id bigint(20) unsigned NOT NULL,
			assignment_id bigint(20) unsigned NOT NULL,
			course_id bigint(20) unsigned NOT NULL DEFAULT 0,
			submission_text text DEFAULT NULL,
			file_url varchar(255) DEFAULT NULL,
			marks decimal(5,2) DEFAULT NULL,
			max_marks decimal(5,2) NOT NULL DEFAULT 100.00,
			feedback text DEFAULT NULL,
			status varchar(30) NOT NULL DEFAULT 'submitted',
			submitted_at datetime NOT NULL,
			reviewed_at datetime DEFAULT NULL,
			reviewed_by bigint(20) unsigned DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY student_id (student_id),
			KEY assignment_id (assignment_id),
			KEY status (status)
		) {$charset_collate};";
		dbDelta( $sql_assignments );

		EduForge_Logger::info( 'EduForge custom tables verified/created successfully via dbDelta.' );
	}
}
