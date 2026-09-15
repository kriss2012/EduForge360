<?php
/**
 * EduForge Secure Logger
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Logger {

	/**
	 * Log levels
	 */
	const INFO    = 'INFO';
	const WARNING = 'WARNING';
	const ERROR   = 'ERROR';
	const SECURE  = 'SECURITY';

	/**
	 * Path to log directory
	 */
	private static function get_log_dir() {
		$upload_dir = wp_upload_dir();
		$log_dir = trailingslashit( $upload_dir['basedir'] ) . 'eduforge-logs/';
		if ( ! file_exists( $log_dir ) ) {
			wp_mkdir_p( $log_dir );
			// Add .htaccess to deny direct HTTP access
			file_put_contents( $log_dir . '.htaccess', "Order Deny,Allow\nDeny from all\n" );
			file_put_contents( $log_dir . 'index.php', "<?php // Silence is golden\n" );
		}
		return $log_dir;
	}

	/**
	 * Sanitize sensitive information from logs
	 */
	private static function scrub_sensitive_data( $message ) {
		if ( is_array( $message ) || is_object( $message ) ) {
			$message = wp_json_encode( $message );
		}

		// Pattern scrubbing for passwords, secret keys, tokens, auth cookies
		$patterns = array(
			'/("?(?:password|pass|secret|token|api_key|authorization)"?\s*[:=]\s*)"[^"]+"/i' => '$1"***SCRUBBED***"',
			'/("?(?:password|pass|secret|token|api_key|authorization)"?\s*[:=]\s*)[^\s,]+/i' => '$1***SCRUBBED***',
		);

		return preg_replace( array_keys( $patterns ), array_values( $patterns ), $message );
	}

	/**
	 * Main write log method
	 */
	public static function log( $level, $message, $context = array() ) {
		$log_dir = self::get_log_dir();
		$file_name = 'eduforge-' . gmdate( 'Y-m-d' ) . '.log';
		$file_path = $log_dir . $file_name;

		$scrubbed_msg = self::scrub_sensitive_data( $message );
		$context_str = ! empty( $context ) ? ' | Context: ' . self::scrub_sensitive_data( $context ) : '';

		$timestamp = gmdate( 'Y-m-d H:i:s' );
		$user_id = get_current_user_id();
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'CLI';

		$entry = sprintf(
			"[%s] [%s] [User:%d] [IP:%s] %s%s\n",
			$timestamp,
			strtoupper( $level ),
			$user_id,
			$ip,
			$scrubbed_msg,
			$context_str
		);

		// Append securely
		error_log( $entry, 3, $file_path );
	}

	public static function info( $message, $context = array() ) {
		self::log( self::INFO, $message, $context );
	}

	public static function warning( $message, $context = array() ) {
		self::log( self::WARNING, $message, $context );
	}

	public static function error( $message, $context = array() ) {
		self::log( self::ERROR, $message, $context );
	}

	public static function security( $message, $context = array() ) {
		self::log( self::SECURE, $message, $context );
	}
}
