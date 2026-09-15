<?php
/**
 * 404 Error Page Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="container" style="padding: 100px 20px; text-align: center; max-width: 650px;">
	<div style="font-size: 80px; font-weight: 900; color: #2563eb; line-height: 1;">404</div>
	<h1 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 15px 0;">Page Not Found</h1>
	<p style="color: #64748b; font-size: 16px; line-height: 1.6; margin-bottom: 30px;">
		The academic resource, course, or assessment page you requested could not be located. It may have been archived or moved.
	</p>
	<div style="display: flex; justify-content: center; gap: 15px;">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Return to Homepage</a>
		<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" class="btn btn-outline">Browse Course Catalog</a>
	</div>
</div>

<?php
get_footer();
