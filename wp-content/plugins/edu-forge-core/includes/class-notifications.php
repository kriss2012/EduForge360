<?php
/**
 * EduForge In-App & Email Notification Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Notifications {

	/**
	 * Send in-app notification to user
	 */
	public static function add_notification( $user_id, $title, $message, $type = 'info', $link = '' ) {
		$notifications = get_user_meta( $user_id, '_eduforge_notifications', true );
		if ( ! is_array( $notifications ) ) {
			$notifications = array();
		}

		$notification = array(
			'id'        => uniqid( 'notif_' ),
			'title'     => sanitize_text_field( $title ),
			'message'   => sanitize_text_field( $message ),
			'type'      => sanitize_key( $type ),
			'link'      => esc_url_raw( $link ),
			'read'      => false,
			'timestamp' => current_time( 'mysql' ),
		);

		// Keep recent 20 notifications
		array_unshift( $notifications, $notification );
		$notifications = array_slice( $notifications, 0, 20 );

		update_user_meta( $user_id, '_eduforge_notifications', $notifications );

		return $notification;
	}

	/**
	 * Get user notifications
	 */
	public static function get_notifications( $user_id, $unread_only = false ) {
		$notifications = get_user_meta( $user_id, '_eduforge_notifications', true );
		if ( ! is_array( $notifications ) ) {
			return array();
		}

		if ( $unread_only ) {
			return array_values( array_filter( $notifications, function( $n ) {
				return empty( $n['read'] );
			} ) );
		}

		return $notifications;
	}

	/**
	 * Mark notifications as read
	 */
	public static function mark_as_read( $user_id, $notification_id = null ) {
		$notifications = get_user_meta( $user_id, '_eduforge_notifications', true );
		if ( ! is_array( $notifications ) ) {
			return;
		}

		foreach ( $notifications as &$n ) {
			if ( is_null( $notification_id ) || $n['id'] === $notification_id ) {
				$n['read'] = true;
			}
		}

		update_user_meta( $user_id, '_eduforge_notifications', $notifications );
	}

	/**
	 * Send HTML email with EduForge360 branding
	 */
	public static function send_email( $to, $subject, $heading, $content, $cta_text = '', $cta_url = '' ) {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$settings = get_option( 'eduforge_settings', array() );
		$institution = ! empty( $settings['institution_name'] ) ? $settings['institution_name'] : 'EduForge360 Platform';

		$body = '<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<style>
				body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
				.card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; }
				.header { background: #0f172a; color: #ffffff; padding: 24px; text-align: center; }
				.content { padding: 32px 24px; line-height: 1.6; }
				.button { display: inline-block; background: #2563eb; color: #ffffff !important; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 20px; }
				.footer { background: #f1f5f9; padding: 16px; text-align: center; font-size: 12px; color: #64748b; }
			</style>
		</head>
		<body>
			<div class="card">
				<div class="header">
					<h2 style="margin:0; font-size: 20px;">' . esc_html( $institution ) . '</h2>
				</div>
				<div class="content">
					<h3 style="margin-top:0; color: #0f172a;">' . esc_html( $heading ) . '</h3>
					<p>' . wp_kses_post( $content ) . '</p>';

		if ( ! empty( $cta_text ) && ! empty( $cta_url ) ) {
			$body .= '<div style="text-align: center;"><a href="' . esc_url( $cta_url ) . '" class="button">' . esc_html( $cta_text ) . '</a></div>';
		}

		$body .= '</div>
				<div class="footer">
					&copy; ' . gmdate( 'Y' ) . ' EduForge360. All rights reserved.<br>Empowering student development & institutional excellence.
				</div>
			</div>
		</body>
		</html>';

		return wp_mail( $to, $subject, $body, $headers );
	}
}
