<?php
/**
 * EduForge Certificate Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Certificate_Manager {

	public static function init() {
		add_action( 'eduforge_course_completed', array( __CLASS__, 'maybe_generate_certificate' ), 10, 2 );
		add_action( 'init', array( __CLASS__, 'register_rewrite_rules' ) );
		add_filter( 'query_vars', array( __CLASS__, 'register_query_vars' ) );
		add_action( 'template_redirect', array( __CLASS__, 'handle_public_verification' ) );
	}

	public static function register_rewrite_rules() {
		add_rewrite_rule(
			'^verify-certificate/([^/]+)/?',
			'index.php?eduforge_cert_verify=$matches[1]',
			'top'
		);
	}

	public static function register_query_vars( $vars ) {
		$vars[] = 'eduforge_cert_verify';
		return $vars;
	}

	/**
	 * Render verification page if query var is present
	 */
	public static function handle_public_verification() {
		$cert_code = get_query_var( 'eduforge_cert_verify' );
		if ( ! empty( $cert_code ) ) {
			include EDUFORGE_PLUGIN_DIR . 'templates/certificate-verify.php';
			exit;
		}
	}

	/**
	 * Trigger automatic certificate issuance
	 */
	public static function maybe_generate_certificate( $student_id, $course_id ) {
		$settings = get_option( 'eduforge_settings', array() );
		if ( empty( $settings['auto_certificate'] ) ) {
			return;
		}
		self::issue_certificate( $student_id, $course_id );
	}

	/**
	 * Generate and issue certificate
	 */
	public static function issue_certificate( $student_id, $course_id, $score = 100.00 ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'certificates' );

		// Check if already issued
		$existing = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM {$table} WHERE student_id = %d AND course_id = %d",
			$student_id,
			$course_id
		) );

		if ( $existing ) {
			return $existing;
		}

		// Generate unique serial and cryptographic verification hash
		$random_number = wp_rand( 10000, 99999 );
		$cert_number = sprintf( 'EDU-%s-%05d', gmdate( 'Y' ), $random_number );
		$hash_payload = $student_id . '|' . $course_id . '|' . $cert_number . '|' . wp_salt();
		$verification_hash = hash( 'sha256', $hash_payload );

		$inserted = $wpdb->insert(
			$table,
			array(
				'student_id'         => $student_id,
				'course_id'          => $course_id,
				'certificate_number' => $cert_number,
				'verification_hash'  => $verification_hash,
				'score'              => floatval( $score ),
				'issued_at'          => current_time( 'mysql' ),
			),
			array( '%d', '%d', '%s', '%s', '%f', '%s' )
		);

		if ( $inserted ) {
			EduForge_Logger::info( "Certificate {$cert_number} generated for student {$student_id} for course {$course_id}." );
			do_action( 'eduforge_certificate_issued', $wpdb->insert_id, $student_id, $course_id, $cert_number );
			return self::get_certificate_by_number( $cert_number );
		}

		return false;
	}

	/**
	 * Get certificate by ID or code
	 */
	public static function get_certificate_by_number( $cert_number ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'certificates' );
		return $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM {$table} WHERE certificate_number = %s OR verification_hash = %s",
			$cert_number,
			$cert_number
		) );
	}

	/**
	 * Get student certificates
	 */
	public static function get_student_certificates( $student_id ) {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'certificates' );
		return $wpdb->get_results( $wpdb->prepare(
			"SELECT c.*, p.post_title as course_title 
			 FROM {$table} c 
			 JOIN {$wpdb->posts} p ON c.course_id = p.ID 
			 WHERE c.student_id = %d 
			 ORDER BY c.issued_at DESC",
			$student_id
		) );
	}

	/**
	 * Get verification URL
	 */
	public static function get_verification_url( $cert_number ) {
		return home_url( '/verify-certificate/' . urlencode( $cert_number ) . '/' );
	}
}
EduForge_Certificate_Manager::init();
