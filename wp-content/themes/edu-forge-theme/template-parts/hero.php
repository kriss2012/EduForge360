<?php
/**
 * Hero Section Template Part
 *
 * @package EduForge360
 */
?>

<section class="hero-section" id="hero">
	<div class="container hero-container">
		<div class="hero-content">
			<div class="hero-badge">
				<span class="badge-dot"></span>
				<span class="badge-text">Next-Gen Institutional Student Development Platform</span>
			</div>

			<h1 class="hero-title">
				Build Skills. Track Growth. <span class="gradient-text">Shape Careers.</span>
			</h1>

			<p class="hero-subtitle">
				An intelligent student development platform connecting learning, assessments, projects, certifications and career preparation in one place.
			</p>

			<div class="hero-cta-group">
				<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" class="btn btn-primary btn-lg">
					<span>Explore Courses</span>
					<span class="arrow">→</span>
				</a>
				<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="btn btn-secondary btn-lg">
					<span>Take Skill Assessment</span>
				</a>
			</div>

			<div class="hero-features-list">
				<div class="feature-item">✓ Verified Institutional Credentials</div>
				<div class="feature-item">✓ Live Corporate Placement Drives</div>
				<div class="feature-item">✓ Automated Resume Builder</div>
			</div>
		</div>

		<div class="hero-visual">
			<div class="hero-card-preview">
				<div class="preview-header">
					<div class="preview-avatar">
						<span>AS</span>
					</div>
					<div class="preview-user-meta">
						<strong>Aarav Sharma</strong>
						<span>MCA Post-Graduate Candidate</span>
					</div>
					<div class="preview-status-pill">
						Ready for Placement
					</div>
				</div>

				<div class="preview-metric-box">
					<div class="metric-info">
						<span>Career Readiness Score</span>
						<strong>81%</strong>
					</div>
					<div class="metric-progress-bar">
						<div class="progress-fill" style="width: 81%;"></div>
					</div>
				</div>

				<div class="preview-skills-tags">
					<span class="skill-tag">Python 82%</span>
					<span class="skill-tag">React 80%</span>
					<span class="skill-tag">Git 74%</span>
					<span class="skill-tag">AWS 70%</span>
				</div>

				<div class="preview-cert-badge">
					<span>🎓 2 Verified Industry Certificates</span>
					<span class="cert-code">EDU-2026-00125</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Metrics Strip -->
	<div class="hero-stats-strip">
		<div class="container stats-inner">
			<div class="stat-item">
				<strong class="stat-number">1,248+</strong>
				<span class="stat-label">Active Students</span>
			</div>
			<div class="stat-item">
				<strong class="stat-number">42+</strong>
				<span class="stat-label">Industry Courses</span>
			</div>
			<div class="stat-item">
				<strong class="stat-number">2,145+</strong>
				<span class="stat-label">Issued Certificates</span>
			</div>
			<div class="stat-item">
				<strong class="stat-number">2,760+</strong>
				<span class="stat-label">Placement Applications</span>
			</div>
		</div>
	</div>
</section>
