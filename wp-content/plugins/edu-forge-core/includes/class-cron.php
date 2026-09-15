<?php
/**
 * EduForge WP-Cron Scheduler
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Cron {

	public static function init() {
		add_action( 'eduforge_daily_maintenance_cron', array( __CLASS__, 'execute_daily_tasks' ) );
	}

	public static function execute_daily_tasks() {
		EduForge_Logger::info( 'WP-Cron: Executing EduForge daily maintenance routine...' );

		// 1. Clean up old log files older than 30 days
		self::cleanup_old_logs();

		// 2. Check assignment deadlines and notify students with pending submissions
		self::check_assignment_deadlines();

		EduForge_Logger::info( 'WP-Cron: Daily maintenance routine completed.' );
	}

	private static function cleanup_old_logs() {
		$upload_dir = wp_upload_dir();
		$log_dir = trailingslashit( $upload_dir['basedir'] ) . 'eduforge-logs/';
		if ( ! is_dir( $log_dir ) ) {
			return;
		}

		$files = glob( $log_dir . '*.log' );
		$now = time();
		foreach ( $files as $file ) {
			if ( is_file( $file ) && ( $now - filemtime( $file ) >= 30 * DAY_IN_SECONDS ) ) {
				unlink( $file );
			}
		}
	}

	private static function check_assignment_deadlines() {
		// Scans published assignments with impending deadlines within 24 hours
		$assignments = get_posts( array(
			'post_type'      => 'assignments',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
		) );

		foreach ( $assignments as $assignment ) {
			$deadline = get_post_meta( $assignment->ID, '_eduforge_deadline', true );
			if ( $deadline && strtotime( $deadline ) > time() && ( strtotime( $deadline ) - time() ) < DAY_IN_SECONDS ) {
				EduForge_Logger::info( "Assignment deadline imminent for: {$assignment->post_title}" );
			}
		}
	}
}
