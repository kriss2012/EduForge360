<?php
/**
 * Search Results Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<h1 style="font-size: 32px; font-weight: 800; margin: 8px 0 0 0;">
			Search Results for: "<?php echo esc_html( get_search_query() ); ?>"
		</h1>
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
					<span style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700;"><?php echo esc_html( get_post_type() ); ?></span>
					<h3 style="margin: 6px 0 10px 0; font-size: 18px;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p style="font-size: 14px; color: #64748b;"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
					<a href="<?php the_permalink(); ?>" style="font-size: 13px; font-weight: 700; color: #2563eb;">Open Record →</a>
				</article>
				<?php
			endwhile;
		else :
			echo '<div style="grid-column: 1/-1; text-align:center; padding: 40px; background: #fff; border-radius: 8px;">';
			echo '<h3>No results matched your query</h3>';
			echo '<p style="color: #64748b;">Try searching for courses, skills, placement companies, or events.</p>';
			echo '</div>';
		endif;
		?>
	</div>
</div>

<?php
get_footer();
