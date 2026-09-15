<?php
/**
 * Template Name: About EduForge360 Platform
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 50px 0;">
	<div class="container text-center">
		<span style="font-size: 13px; font-weight: 700; color: #38bdf8; text-transform: uppercase;">Institutional Vision</span>
		<h1 style="font-size: 36px; font-weight: 800; margin: 8px 0 14px 0;">About EduForge360</h1>
		<p style="font-size: 17px; opacity: 0.85; max-width: 720px; margin: 0 auto;">
			"Build Skills. Track Growth. Shape Careers."
		</p>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px; max-width: 960px;">
	
	<!-- Mission & Vision -->
	<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">
		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 30px;">
			<h3 style="color: #2563eb; margin-top: 0;">Our Mission</h3>
			<p style="color: #475569; line-height: 1.7; font-size: 15px;">
				To transform traditional higher education institutions into career acceleration engines by connecting curriculum coursework, rigorous code assessments, verifiable credentials, and placement operations into a unified platform.
			</p>
		</div>

		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 30px;">
			<h3 style="color: #10b981; margin-top: 0;">Our Vision</h3>
			<p style="color: #475569; line-height: 1.7; font-size: 15px;">
				An educational world where every student graduation credential is transparently verified, every technical skill is backed by real capstone projects, and every campus recruitment drive is powered by data-driven readiness metrics.
			</p>
		</div>
	</div>

	<!-- Architecture & Stakeholder Benefits -->
	<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 35px; margin-bottom: 40px;">
		<h2 style="margin-top: 0; font-size: 22px; color: #0f172a;">Stakeholder Benefits</h2>

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-top: 25px;">
			<div style="padding: 16px; background: #f8fafc; border-radius: 8px;">
				<h4 style="margin-top: 0; color: #0f172a;">🎓 Students</h4>
				<ul style="padding-left: 18px; font-size: 14px; color: #64748b; line-height: 1.8;">
					<li>Interactive learning player</li>
					<li>Objective skill gap diagnostics</li>
					<li>Automated verified certificates</li>
					<li>1-Click corporate applications</li>
				</ul>
			</div>

			<div style="padding: 16px; background: #f8fafc; border-radius: 8px;">
				<h4 style="margin-top: 0; color: #0f172a;">👨‍🏫 Faculty</h4>
				<ul style="padding-left: 18px; font-size: 14px; color: #64748b; line-height: 1.8;">
					<li>Dedicated assignment grading desk</li>
					<li>Automated quiz scoring engine</li>
					<li>Granular student progression tracking</li>
					<li>Curriculum management</li>
				</ul>
			</div>

			<div style="padding: 16px; background: #f8fafc; border-radius: 8px;">
				<h4 style="margin-top: 0; color: #0f172a;">💼 Placement Officers</h4>
				<ul style="padding-left: 18px; font-size: 14px; color: #64748b; line-height: 1.8;">
					<li>Filter eligible student rosters</li>
					<li>Create corporate recruitment drives</li>
					<li>Track application status pipeline</li>
					<li>Export institutional placement KPIs</li>
				</ul>
			</div>
		</div>
	</div>

	<!-- Contact & Inquiry Form -->
	<div id="contact" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 35px;">
		<h2 style="margin-top: 0; font-size: 22px; color: #0f172a;">Institutional Inquiries & Support</h2>
		<p style="color: #64748b; font-size: 14px; margin-bottom: 25px;">Connect with our academic technology council and campus coordinator.</p>

		<form id="contactForm">
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
				<div>
					<label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Full Name</label>
					<input type="text" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;" />
				</div>
				<div>
					<label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Email Address</label>
					<input type="email" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;" />
				</div>
			</div>
			<div style="margin-bottom: 15px;">
				<label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Message / Inquiry</label>
				<textarea rows="4" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;"></textarea>
			</div>
			<button type="button" onclick="alert('Thank you! Your institutional inquiry has been submitted.');" class="btn btn-primary">
				Submit Inquiry
			</button>
		</form>
	</div>

</div>

<?php
get_footer();
