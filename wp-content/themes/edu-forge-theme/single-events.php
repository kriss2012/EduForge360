<?php
/**
 * Single Event Template
 *
 * @package EduForge360
 */

get_header();

$event_id = get_the_ID();
$meta = class_exists( 'EduForge_Event_Manager' ) ? EduForge_Event_Manager::get_event_meta( $event_id ) : array(
	'date'       => '2026-10-15',
	'time'       => '10:00 AM - 04:00 PM IST',
	'location'   => 'Main Auditorium',
	'speaker'    => 'Keynote Leader',
	'capacity'   => 250,
	'registered' => 50,
);

$is_registered = ( is_user_logged_in() && class_exists( 'EduForge_Event_Manager' ) ) ? EduForge_Event_Manager::is_student_registered( get_current_user_id(), $event_id ) : false;
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<span style="font-size: 13px; font-weight: 700; color: #38bdf8; text-transform: uppercase;">Technical Symposium</span>
		<h1 style="font-size: 34px; font-weight: 800; margin: 8px 0 12px 0;"><?php the_title(); ?></h1>
		<div style="font-size: 15px; opacity: 0.9;">
			<span>📅 <?php echo esc_html( $meta['date'] ); ?></span> &bull; 
			<span>⏰ <?php echo esc_html( $meta['time'] ); ?></span> &bull; 
			<span>📍 <?php echo esc_html( $meta['location'] ); ?></span>
		</div>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
	<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 35px; align-items: flex-start;">
		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 35px;">
			<h2 style="margin-top: 0; font-size: 20px; color: #0f172a;">Event Description & Agenda</h2>
			<div style="line-height: 1.8; color: #334155;">
				<?php the_content(); ?>
			</div>
		</div>

		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 25px;">
			<h3 style="margin-top: 0; font-size: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">Registration Desk</h3>
			<div style="margin: 15px 0 20px 0; font-size: 14px; color: #64748b;">
				<div>Keynote Speaker: <strong><?php echo esc_html( $meta['speaker'] ); ?></strong></div>
				<div style="margin-top: 6px;">Available Seats: <strong><?php echo intval( $meta['capacity'] ) - intval( $meta['registered'] ); ?></strong> remaining</div>
			</div>

			<?php if ( $is_registered ) : ?>
				<button class="btn btn-success" style="width: 100%; padding: 12px 0;" disabled>✓ You are Registered</button>
			<?php else : ?>
				<button id="eventRegBtn" data-event-id="<?php echo esc_attr( $event_id ); ?>" class="btn btn-primary eduforge-register-event-btn" style="width: 100%; padding: 12px 0;">
					Register for this Event
				</button>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();
