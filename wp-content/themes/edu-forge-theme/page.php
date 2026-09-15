<?php
/**
 * Default Page Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<h1 style="font-size: 32px; font-weight: 800; margin: 8px 0 0 0;"><?php the_title(); ?></h1>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px; max-width: 860px;">
	<article style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 40px; line-height: 1.8; color: #334155;">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</article>
</div>

<?php
get_footer();
