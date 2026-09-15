<?php
/**
 * Template part for displaying a course card
 *
 * @package EduForge360
 */

$course_id = get_the_ID();
$details = class_exists( 'EduForge_Course_Manager' ) ? EduForge_Course_Manager::get_course_details( $course_id ) : array(
	'duration' => '8 Weeks',
	'level'    => 'Intermediate',
	'price'    => 0,
	'rating'   => 4.8
);

$author_id = get_post_field( 'post_author', $course_id );
$author_name = get_the_author_meta( 'display_name', $author_id ) ?: 'Lead Faculty';
$is_enrolled = ( is_user_logged_in() && class_exists( 'EduForge_Course_Manager' ) ) ? EduForge_Course_Manager::is_enrolled( get_current_user_id(), $course_id ) : false;
?>

<article class="eduforge-course-card" id="post-<?php the_ID(); ?>">
	<div class="course-thumbnail-wrap">
		<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'eduforge-course-card', array( 'class' => 'course-card-img', 'alt' => get_the_title() ) ); ?>
			<?php else : ?>
				<div class="course-placeholder-thumbnail">
					<span class="icon">💻</span>
				</div>
			<?php endif; ?>
		</a>
		<span class="course-badge-level"><?php echo esc_html( $details['level'] ); ?></span>
	</div>

	<div class="course-card-body">
		<div class="course-meta-top">
			<span class="course-rating">⭐ <?php echo esc_html( $details['rating'] ); ?></span>
			<span class="course-duration">⏱ <?php echo esc_html( $details['duration'] ); ?></span>
		</div>

		<h3 class="course-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="course-card-excerpt">
			<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 14 ) ); ?>
		</p>

		<div class="course-instructor-info">
			<span class="instructor-avatar">👨‍🏫</span>
			<span class="instructor-name"><?php echo esc_html( $author_name ); ?></span>
		</div>

		<div class="course-card-footer">
			<div class="course-pricing">
				<?php if ( ! empty( $details['price'] ) && $details['price'] > 0 ) : ?>
					<span class="price-value">₹<?php echo esc_html( number_format( $details['price'] ) ); ?></span>
				<?php else : ?>
					<span class="badge-free">Free Enrollment</span>
				<?php endif; ?>
			</div>

			<div class="course-action-wrap">
				<?php if ( $is_enrolled ) : ?>
					<a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">Continue Learning →</a>
				<?php else : ?>
					<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">Explore Syllabus</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>
