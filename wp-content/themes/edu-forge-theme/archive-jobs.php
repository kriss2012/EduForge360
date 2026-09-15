<?php
/**
 * Jobs & Placement Drives Archive Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<h1 style="font-size: 32px; font-weight: 800; margin-bottom: 10px;">Campus Placement Drives & Corporate Openings</h1>
		<p style="font-size: 16px; opacity: 0.85; max-width: 720px;">
			Exclusive campus recruitment drives hosted by EduForge360 hiring partners for qualified engineering and postgraduate candidates.
		</p>
	</div>
</div>

<div class="container" style="margin-top: 35px; margin-bottom: 60px;">
	<div class="jobs-list-grid" style="display: flex; flex-direction: column; gap: 16px;">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/job-card' );
			endwhile;
		else :
			echo '<div style="background:#fff; border:1px dashed #cbd5e1; border-radius:8px; padding:40px; text-align:center;">';
			echo '<h3>No active placement drives found</h3>';
			echo '<p style="color:#64748b;">Run the Demo Data Seeder to populate realistic campus hiring drives.</p>';
			echo '</div>';
		endif;
		?>
	</div>
</div>

<?php
get_footer();
