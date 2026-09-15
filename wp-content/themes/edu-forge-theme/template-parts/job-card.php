<?php
/**
 * Template part for displaying a job card
 *
 * @package EduForge360
 */

$job_id = get_the_ID();
$meta = class_exists( 'EduForge_Placement_Manager' ) ? EduForge_Placement_Manager::get_job_meta( $job_id ) : array(
	'company'    => 'Enterprise Partner',
	'salary'     => '₹8.0 - ₹12.0 LPA',
	'location'   => 'Bengaluru / Hybrid',
	'experience' => '0 - 2 Years',
	'deadline'   => '2026-10-31',
);
?>

<article class="eduforge-job-card" id="post-<?php the_ID(); ?>">
	<div class="job-card-top">
		<div class="company-logo-placeholder">
			🏢
		</div>
		<div class="job-company-info">
			<span class="company-name"><?php echo esc_html( $meta['company'] ); ?></span>
			<h3 class="job-role-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
		</div>
		<div class="job-salary-pill">
			<?php echo esc_html( $meta['salary'] ); ?>
		</div>
	</div>

	<div class="job-card-tags">
		<span class="tag-pill">📍 <?php echo esc_html( $meta['location'] ); ?></span>
		<span class="tag-pill">💼 <?php echo esc_html( $meta['experience'] ); ?></span>
		<span class="tag-pill">⏳ Deadline: <?php echo esc_html( $meta['deadline'] ); ?></span>
	</div>

	<p class="job-summary">
		<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 18 ) ); ?>
	</p>

	<div class="job-card-footer">
		<a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">View Eligibility & Process</a>
		<a href="<?php the_permalink(); ?>#apply" class="btn btn-primary btn-sm">Apply via Campus Drive →</a>
	</div>
</article>
