<?php
/**
 * Events Archive Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<h1 style="font-size: 32px; font-weight: 800; margin-bottom: 10px;">Campus Hackathons, Seminars & Bootcamps</h1>
		<p style="font-size: 16px; opacity: 0.85; max-width: 700px;">
			Participate in collaborative programming challenges, technical guest lectures, and placement workshops.
		</p>
	</div>
</div>

<div class="container" style="margin-top: 35px; margin-bottom: 60px;">
	<div class="events-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/event-card' );
			endwhile;
		else :
			echo '<p style="grid-column: 1/-1; text-align:center;">No upcoming events found.</p>';
		endif;
		?>
	</div>
</div>

<?php
get_footer();
