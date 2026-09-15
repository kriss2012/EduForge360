<?php
/**
 * EduForge Footer Template
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main><!-- #primary -->

<footer class="site-footer" id="colophon">
	<div class="container footer-inner">
		<div class="footer-grid">
			<!-- Col 1: Brand & Slogan -->
			<div class="footer-col footer-brand">
				<div class="logo-mark">
					<span class="logo-icon">⚡</span>
					<span class="logo-text">EduForge<span class="highlight">360</span></span>
				</div>
				<p class="footer-tagline">
					"Build Skills. Track Growth. Shape Careers."
				</p>
				<p class="footer-desc">
					An intelligent institutional digital student development platform connecting structured learning, adaptive skill assessments, capstone projects, blockchain-grade certifications, and corporate placement preparation.
				</p>
				<div class="footer-social-links">
					<a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn">🔗 LinkedIn</a>
					<a href="https://github.com" target="_blank" rel="noopener" aria-label="GitHub">💻 GitHub</a>
					<a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube">▶️ YouTube</a>
					<a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">📸 Instagram</a>
				</div>
			</div>

			<!-- Col 2: Platform -->
			<div class="footer-col">
				<h4 class="footer-heading"><?php esc_html_e( 'Platform', 'eduforge360' ); ?></h4>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>"><?php esc_html_e( 'Technical Courses', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>"><?php esc_html_e( 'Skill Assessments', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/career/' ) ); ?>"><?php esc_html_e( 'Career Readiness Hub', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/resume-builder/' ) ); ?>"><?php esc_html_e( 'Interactive Resume Builder', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>"><?php esc_html_e( 'Campus Placement Drives', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>"><?php esc_html_e( 'Hackathons & Events', 'eduforge360' ); ?></a></li>
				</ul>
			</div>

			<!-- Col 3: Company & Institute -->
			<div class="footer-col">
				<h4 class="footer-heading"><?php esc_html_e( 'Institution & Trust', 'eduforge360' ); ?></h4>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About EduForge360', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/verify-certificate/' ) ); ?>"><?php esc_html_e( 'Public Certificate Verification', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/#impact' ) ); ?>"><?php esc_html_e( 'Institutional Impact', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/#partners' ) ); ?>"><?php esc_html_e( 'Corporate Hiring Partners', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/#contact' ) ); ?>"><?php esc_html_e( 'Contact Campus Support', 'eduforge360' ); ?></a></li>
				</ul>
			</div>

			<!-- Col 4: Legal & Privacy -->
			<div class="footer-col">
				<h4 class="footer-heading"><?php esc_html_e( 'Governance & Legal', 'eduforge360' ); ?></h4>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy & GDPR', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>"><?php esc_html_e( 'Cookie Policy', 'eduforge360' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/security/' ) ); ?>"><?php esc_html_e( 'Security & Compliance', 'eduforge360' ); ?></a></li>
				</ul>
				<div class="institution-meta" style="margin-top: 20px; font-size: 13px; color: var(--text-muted);">
					<strong>EduForge360 Institute</strong><br>
					Bengaluru Innovation Corridor<br>
					Karnataka 560001, India
				</div>
			</div>
		</div>

		<div class="footer-bottom">
			<div class="footer-copy">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> EduForge360 Platform. Industrial Educational System. All rights reserved.
			</div>
			<div class="footer-compliance">
				<span>WCAG 2.1 AA Compliant</span> &bull; <span>ISO 27001 Certified Security Practices</span>
			</div>
		</div>
	</div>
</footer>

<!-- Mobile Bottom Floating Navigation Bar -->
<nav class="mobile-bottom-nav" aria-label="Mobile Bottom Navigation">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
		<span class="icon">🏠</span>
		<span class="label">Home</span>
	</a>
	<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" class="mobile-nav-item <?php echo is_post_type_archive( 'courses' ) ? 'active' : ''; ?>">
		<span class="icon">📚</span>
		<span class="label">Learn</span>
	</a>
	<a href="<?php echo esc_url( home_url( '/career/' ) ); ?>" class="mobile-nav-item">
		<span class="icon">💼</span>
		<span class="label">Career</span>
	</a>
	<a href="<?php echo esc_url( home_url( '/events/' ) ); ?>" class="mobile-nav-item">
		<span class="icon">📅</span>
		<span class="label">Events</span>
	</a>
	<a href="<?php echo esc_url( is_user_logged_in() ? home_url( '/dashboard/' ) : wp_login_url() ); ?>" class="mobile-nav-item">
		<span class="icon">👤</span>
		<span class="label"><?php echo is_user_logged_in() ? 'Profile' : 'Sign In'; ?></span>
	</a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
