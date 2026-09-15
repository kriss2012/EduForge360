<?php
/**
 * Template Name: Career Development Hub
 *
 * @package EduForge360
 */

get_header();

$student_id = get_current_user_id();
$readiness = ( $student_id && class_exists( 'EduForge_Career_Manager' ) ) ? EduForge_Career_Manager::calculate_career_readiness( $student_id ) : 81;
?>

<div class="page-header-banner" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: #fff; padding: 50px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<h1 style="font-size: 34px; font-weight: 800; margin-bottom: 12px;">Career Development & Placement Readiness</h1>
		<p style="font-size: 16px; opacity: 0.9; max-width: 720px;">
			Track your quantitative interview readiness, build an ATS resume, and connect directly with campus hiring partners.
		</p>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
	<!-- Readiness Overview Hero Box -->
	<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 35px; margin-bottom: 35px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
		<div>
			<span style="font-size: 12px; font-weight: 700; color: #2563eb; text-transform: uppercase;">Composite Placement Algorithm</span>
			<h2 style="margin: 6px 0 10px 0; font-size: 28px; color: #0f172a;">
				Your Career Readiness: <span style="color: #2563eb;"><?php echo esc_html( $readiness ); ?>%</span>
			</h2>
			<p style="color: #64748b; font-size: 15px; max-width: 580px; margin: 0;">
				<?php if ( $readiness >= 80 ) : ?>
					🎉 <strong>High Placement Readiness:</strong> Your portfolio, capstone projects, and skill assessments meet criteria for Tier-1 corporate drives.
				<?php else : ?>
					⚡ Improve your SQL and DSA skills to reach 85%+ readiness and unlock premium placement drives.
				<?php endif; ?>
			</p>
		</div>

		<div style="display: flex; gap: 12px;">
			<a href="<?php echo esc_url( home_url( '/resume-builder/' ) ); ?>" class="btn btn-primary btn-lg">
				📄 Edit / Export Resume
			</a>
			<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="btn btn-outline btn-lg">
				🏢 View Campus Drives
			</a>
		</div>
	</div>

	<!-- 4 Pillar Roadmap -->
	<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">
			<span style="font-size: 28px;">🧠</span>
			<h3 style="font-size: 18px; margin: 12px 0 8px 0;">1. Diagnostic Assessments</h3>
			<p style="font-size: 14px; color: #64748b; line-height: 1.6;">Continuous evaluation across algorithms, systems design, and database queries.</p>
			<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" style="font-size: 13px; font-weight: 700; color: #2563eb;">Retake Assessment →</a>
		</div>

		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">
			<span style="font-size: 28px;">💻</span>
			<h3 style="font-size: 18px; margin: 12px 0 8px 0;">2. Capstone Repositories</h3>
			<p style="font-size: 14px; color: #64748b; line-height: 1.6;">Build full stack production applications with code reviews and documentation.</p>
			<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" style="font-size: 13px; font-weight: 700; color: #2563eb;">Explore Capstones →</a>
		</div>

		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">
			<span style="font-size: 28px;">🎓</span>
			<h3 style="font-size: 18px; margin: 12px 0 8px 0;">3. Verifiable Credentials</h3>
			<p style="font-size: 14px; color: #64748b; line-height: 1.6;">Certificates verified through an anti-tamper public QR validation page.</p>
			<a href="<?php echo esc_url( home_url( '/verify-certificate/' ) ); ?>" style="font-size: 13px; font-weight: 700; color: #2563eb;">Verify Sample ID →</a>
		</div>

		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">
			<span style="font-size: 28px;">🏢</span>
			<h3 style="font-size: 18px; margin: 12px 0 8px 0;">4. Institutional Drives</h3>
			<p style="font-size: 14px; color: #64748b; line-height: 1.6;">Apply directly to hiring drives managed by campus placement officers.</p>
			<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" style="font-size: 13px; font-weight: 700; color: #2563eb;">Browse Drives →</a>
		</div>
	</div>
</div>

<?php
get_footer();
