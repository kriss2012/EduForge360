<?php
/**
 * EduForge WordPress Dashboard Widget
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Admin_Dashboard_Widgets {

	public static function init() {
		add_action( 'wp_dashboard_setup', array( __CLASS__, 'add_dashboard_widgets' ) );
	}

	public static function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'eduforge_admin_overview_widget',
			__( 'EduForge360 — Institutional Performance at a Glance', 'eduforge360' ),
			array( __CLASS__, 'render_overview_widget' )
		);
	}

	public static function render_overview_widget() {
		$kpis = EduForge_Analytics::get_platform_kpis();
		?>
		<div class="eduforge-widget-content" style="padding: 10px 0;">
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 15px;">
				<div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
					<div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;">Students</div>
					<div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?php echo number_format_i18n( $kpis['students'] ); ?></div>
				</div>
				<div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
					<div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;">Active Enrollments</div>
					<div style="font-size: 20px; font-weight: 800; color: #2563eb; margin-top: 4px;"><?php echo number_format_i18n( $kpis['enrollments'] ); ?></div>
				</div>
				<div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
					<div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;">Completion Rate</div>
					<div style="font-size: 20px; font-weight: 800; color: #10b981; margin-top: 4px;"><?php echo esc_html( $kpis['completion_rate'] ); ?>%</div>
				</div>
				<div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
					<div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;">Certificates Issued</div>
					<div style="font-size: 20px; font-weight: 800; color: #7c3aed; margin-top: 4px;"><?php echo number_format_i18n( $kpis['certificates'] ); ?></div>
				</div>
			</div>
			<p style="margin-bottom: 0;">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=eduforge360' ) ); ?>" class="button button-primary">Open Full Analytics Center &rarr;</a>
			</p>
		</div>
		<?php
	}
}
EduForge_Admin_Dashboard_Widgets::init();
