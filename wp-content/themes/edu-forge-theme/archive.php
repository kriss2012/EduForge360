<?php
/**
 * Generic Archive Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<h1 style="font-size: 32px; font-weight: 800; margin: 8px 0 0 0;"><?php the_archive_title(); ?></h1>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
	<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
					<h3 style="margin-top: 0; font-size: 18px;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p style="font-size: 14px; color: #64748b;"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
					<a href="<?php the_permalink(); ?>" style="font-size: 13px; font-weight: 700; color: #2563eb;">Read Article →</a>
				</article>
				<?php
			endwhile;
		else :
			echo '<p>No records found.</p>';
		endif;
		?>
	</div>
</div>

<?php
get_footer();
