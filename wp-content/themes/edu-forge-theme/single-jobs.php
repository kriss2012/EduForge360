<?php
/**
 * Single Job & Placement Drive Template
 *
 * @package EduForge360
 */

get_header();

$job_id = get_the_ID();
$meta = class_exists( 'EduForge_Placement_Manager' ) ? EduForge_Placement_Manager::get_job_meta( $job_id ) : array(
	'company'     => 'Tech Partner Global',
	'salary'      => '₹10.0 - ₹16.0 LPA',
	'location'    => 'Bengaluru / Hybrid',
	'experience'  => 'Freshers / 0-2 Yrs',
	'deadline'    => '2026-11-30',
	'eligibility' => 'MCA / B.Tech / BE (Min 65% aggregate)',
	'skills'      => 'Python, SQL, REST APIs, Git',
);
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 45px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<span style="font-size: 13px; font-weight: 700; color: #38bdf8; text-transform: uppercase;">Campus Drive Opportunity</span>
		<h1 style="font-size: 34px; font-weight: 800; margin: 8px 0 12px 0;"><?php the_title(); ?></h1>
		<div style="font-size: 15px; opacity: 0.9;">
			<span>🏢 <?php echo esc_html( $meta['company'] ); ?></span> &bull; 
			<span>📍 <?php echo esc_html( $meta['location'] ); ?></span> &bull; 
			<strong style="color: #4ade80;">💰 <?php echo esc_html( $meta['salary'] ); ?></strong>
		</div>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
	<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 35px; align-items: flex-start;">
		
		<!-- Left: Job Details -->
		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 35px;">
			<h2 style="margin-top: 0; font-size: 20px; color: #0f172a;">Role Specification & Responsibilities</h2>
			<div style="line-height: 1.8; color: #334155; margin-bottom: 30px;">
				<?php the_content(); ?>
			</div>

			<h3 style="font-size: 18px; color: #0f172a;">Selection & Interview Process</h3>
			<ol style="padding-left: 20px; line-height: 2; color: #475569; margin-bottom: 30px;">
				<li>Phase 1: Online Technical Assessment (Algorithms & Core Concepts)</li>
				<li>Phase 2: Live Hands-on Coding Challenge & Problem Solving</li>
				<li>Phase 3: Technical System Architecture Interview</li>
				<li>Phase 4: Behavioral & HR Evaluation</li>
			</ol>

			<!-- Application Form -->
			<div id="apply" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 25px; margin-top: 20px;">
				<h3 style="margin-top: 0; font-size: 18px; color: #0f172a;">Apply for this Campus Drive</h3>
				<?php if ( is_user_logged_in() ) : ?>
					<form id="jobApplyForm">
						<div style="margin-bottom: 15px;">
							<label style="display:block; font-size:13px; font-weight:700; margin-bottom:4px;">Cover Note / Candidate Highlights</label>
							<textarea id="coverNote" rows="3" placeholder="Highlight relevant coursework, capstone projects, and GitHub links..." style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px;"></textarea>
						</div>
						<button type="button" id="submitJobAppBtn" data-job-id="<?php echo esc_attr( $job_id ); ?>" class="btn btn-primary" style="padding: 12px 24px;">
							🚀 Submit Application with Resume Profile
						</button>
					</form>
				<?php else : ?>
					<p style="color: #64748b;">Please log in with your verified institutional student ID to apply.</p>
					<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="btn btn-primary btn-sm">Sign In to Apply</a>
				<?php endif; ?>
			</div>
		</div>

		<!-- Right: Quick Specs Box -->
		<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 25px;">
			<h3 style="margin-top: 0; font-size: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">Eligibility Overview</h3>
			
			<div style="display: flex; flex-direction: column; gap: 14px; font-size: 14px; margin-top: 15px;">
				<div>
					<span style="display: block; font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Academic Eligibility</span>
					<strong><?php echo esc_html( $meta['eligibility'] ); ?></strong>
				</div>
				<div>
					<span style="display: block; font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Experience</span>
					<strong><?php echo esc_html( $meta['experience'] ); ?></strong>
				</div>
				<div>
					<span style="display: block; font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Required Skill Badges</span>
					<strong><?php echo esc_html( $meta['skills'] ); ?></strong>
				</div>
				<div>
					<span style="display: block; font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Application Deadline</span>
					<strong style="color: #dc2626;"><?php echo esc_html( $meta['deadline'] ); ?></strong>
				</div>
			</div>
		</div>

	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const btn = document.getElementById('submitJobAppBtn');
	if (btn) {
		btn.addEventListener('click', function() {
			btn.disabled = true;
			btn.innerText = 'Submitting application...';
			
			jQuery.ajax({
				url: eduforgeThemeVars.ajaxurl,
				type: 'POST',
				data: {
					action: 'eduforge_apply_job',
					nonce: eduforgeThemeVars.nonce,
					job_id: btn.dataset.jobId,
					cover_note: document.getElementById('coverNote').value
				},
				success: function(res) {
					if (res.success) {
						alert(res.data.message);
						btn.innerText = 'Application Submitted ✓';
						btn.className = 'btn btn-success';
					} else {
						alert(res.data.message || 'Application failed.');
						btn.disabled = false;
						btn.innerText = 'Submit Application';
					}
				},
				error: function() {
					alert('Network error submitting job application.');
					btn.disabled = false;
					btn.innerText = 'Submit Application';
				}
			});
		});
	}
});
</script>

<?php
get_footer();
