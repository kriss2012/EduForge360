<?php
/**
 * Template part for displaying an event card
 *
 * @package EduForge360
 */

$event_id = get_the_ID();
$meta = class_exists( 'EduForge_Event_Manager' ) ? EduForge_Event_Manager::get_event_meta( $event_id ) : array(
	'date'       => '2026-10-15',
	'location'   => 'Campus Auditorium',
	'speaker'    => 'Keynote Speaker',
	'registered' => 45,
	'capacity'   => 200,
);

$is_registered = ( is_user_logged_in() && class_exists( 'EduForge_Event_Manager' ) ) ? EduForge_Event_Manager::is_student_registered( get_current_user_id(), $event_id ) : false;
?>

<article class="eduforge-event-card" id="post-<?php the_ID(); ?>">
	<div class="event-card-header">
		<div class="event-date-badge">
			<span class="date-day"><?php echo esc_html( gmdate( 'd', strtotime( $meta['date'] ) ) ); ?></span>
			<span class="date-month"><?php echo esc_html( gmdate( 'M', strtotime( $meta['date'] ) ) ); ?></span>
		</div>
		<div class="event-type-badge">Official Event</div>
	</div>

	<div class="event-card-body">
		<h3 class="event-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		
		<p class="event-excerpt">
			<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
		</p>

		<div class="event-meta-list">
			<div class="event-meta-item">
				<span class="icon">📍</span>
				<span><?php echo esc_html( $meta['location'] ); ?></span>
			</div>
			<div class="event-meta-item">
				<span class="icon">🎤</span>
				<span><?php echo esc_html( $meta['speaker'] ); ?></span>
			</div>
		</div>
	</div>

	<div class="event-card-footer">
		<div class="event-seats">
			<span class="seats-count"><?php echo intval( $meta['registered'] ); ?> / <?php echo intval( $meta['capacity'] ); ?></span>
			<span class="seats-label">Registered</span>
		</div>
		<div class="event-action">
			<?php if ( $is_registered ) : ?>
				<button class="btn btn-success btn-sm" disabled>Registered ✓</button>
			<?php else : ?>
				<button class="btn btn-outline btn-sm eduforge-register-event-btn" data-event-id="<?php echo esc_attr( $event_id ); ?>">
					Register Now
				</button>
			<?php endif; ?>
		</div>
	</div>
</article>
