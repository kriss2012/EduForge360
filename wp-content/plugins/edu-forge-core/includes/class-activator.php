<?php
/**
 * Plugin Activator Handler
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Activator {

	public static function activate() {
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-database.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-roles.php';

		// Create database tables
		EduForge_Database::create_tables();

		// Register roles & capabilities
		EduForge_Roles::create_roles();

		// Set default settings if not exists
		if ( ! get_option( 'eduforge_settings' ) ) {
			$defaults = array(
				'institution_name'     => 'EduForge360 Institute of Technology',
				'passing_grade_quiz'   => 70,
				'auto_certificate'     => 1,
				'enable_job_board'     => 1,
				'currency_symbol'      => '₹',
				'currency_code'        => 'INR',
				'notification_email'   => get_option( 'admin_email' ),
				'enable_public_verify' => 1
			);
			update_option( 'eduforge_settings', $defaults );
		}

		// Schedule cron events
		if ( ! wp_next_scheduled( 'eduforge_daily_maintenance_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'eduforge_daily_maintenance_cron' );
		}

		// Flush rewrites on next load
		update_option( 'eduforge_flush_rewrite_rules', 1 );
	}
}
