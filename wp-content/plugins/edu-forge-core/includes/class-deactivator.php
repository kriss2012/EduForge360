<?php
/**
 * Plugin Deactivator Handler
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Deactivator {

	public static function deactivate() {
		// Clear cron events
		$timestamp = wp_next_scheduled( 'eduforge_daily_maintenance_cron' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'eduforge_daily_maintenance_cron' );
		}

		// Flush rewrites
		flush_rewrite_rules();
	}
}
