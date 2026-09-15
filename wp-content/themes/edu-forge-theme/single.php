<?php
/**
 * Single Blog Post Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container" style="max-width: 860px;">
		<?php eduforge_breadcrumbs(); ?>
		<span style="font-size: 13px; color: #38bdf8; font-weight: 700; text-transform: uppercase;">
			<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?> &bull; By <?php the_author(); ?>
		</span>
		<h1 style="font-size: 34px; font-weight: 800; margin: 10px 0 0 0;"><?php the_title(); ?></h1>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px; max-width: 860px;">
	<article style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 40px; line-height: 1.8; color: #334155;">
		<?php if ( has_post_thumbnail() ) : ?>
			<div style="border-radius: 8px; overflow: hidden; margin-bottom: 25px;">
				<?php the_post_thumbnail( 'full' ); ?>
			</div>
		<?php endif; ?>

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
