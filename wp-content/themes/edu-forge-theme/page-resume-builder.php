<?php
/**
 * Template Name: Interactive Resume Builder & PDF Exporter
 *
 * @package EduForge360
 */

if ( ! is_user_logged_in() ) {
	auth_redirect();
}

get_header();

$student_id = get_current_user_id();
$resume = class_exists( 'EduForge_Career_Manager' ) ? EduForge_Career_Manager::get_student_resume( $student_id ) : array();
?>

<div class="page-header-banner" style="background: #0f172a; color: #fff; padding: 35px 0;">
	<div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
		<div>
			<?php eduforge_breadcrumbs(); ?>
			<h1 style="font-size: 28px; font-weight: 800; margin: 0 0 6px 0;">ATS-Compliant Resume Builder</h1>
			<p style="font-size: 14px; opacity: 0.85; margin: 0;">Tailored directly from your validated coursework, capstone projects, and verified certificates.</p>
		</div>
		<div style="display: flex; gap: 10px;">
			<button id="saveResumeBtn" class="btn btn-outline" style="color: #fff; border-color: #475569;">
				💾 Save Profile
			</button>
			<button onclick="window.print();" class="btn btn-primary">
				🖨️ Download / Print PDF
			</button>
		</div>
	</div>
</div>

<div class="container" style="margin-top: 35px; margin-bottom: 60px;">
	<div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 30px; align-items: flex-start;">
		
		<!-- Left: Resume Form Editor -->
		<div class="no-print" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 25px;">
			<h3 style="margin-top: 0; font-size: 18px; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">Candidate Profile Editor</h3>
			
			<form id="resumeForm">
				<div style="margin-bottom: 14px;">
					<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Full Legal Name</label>
					<input type="text" id="resName" value="<?php echo esc_attr( $resume['full_name'] ?? 'Aarav Sharma' ); ?>" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" />
				</div>

				<div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom: 14px;">
					<div>
						<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Email Address</label>
						<input type="email" id="resEmail" value="<?php echo esc_attr( $resume['email'] ?? 'aarav.sharma@eduforge360.edu' ); ?>" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" />
					</div>
					<div>
						<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Phone Number</label>
						<input type="text" id="resPhone" value="<?php echo esc_attr( $resume['phone'] ?? '+91 98765 43210' ); ?>" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" />
					</div>
				</div>

				<div style="margin-bottom: 14px;">
					<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Professional Summary</label>
					<textarea id="resSummary" rows="3" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $resume['summary'] ?? '' ); ?></textarea>
				</div>

				<div style="margin-bottom: 14px;">
					<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Education History</label>
					<textarea id="resEducation" rows="2" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $resume['education'] ?? '' ); ?></textarea>
				</div>

				<div style="margin-bottom: 14px;">
					<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Technical Skills (Comma-separated)</label>
					<textarea id="resSkills" rows="2" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $resume['skills'] ?? '' ); ?></textarea>
				</div>

				<div style="margin-bottom: 14px;">
					<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Capstone Projects</label>
					<textarea id="resProjects" rows="3" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $resume['projects'] ?? '' ); ?></textarea>
				</div>

				<div style="margin-bottom: 14px;">
					<label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">Experience / Internships</label>
					<textarea id="resExperience" rows="2" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;"><?php echo esc_textarea( $resume['experience'] ?? '' ); ?></textarea>
				</div>
			</form>
		</div>

		<!-- Right: Live ATS Resume Preview / Printable Sheet -->
		<div id="resumePrintArea" class="resume-sheet" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 45px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); font-family: 'Times New Roman', Times, serif; color: #111827; line-height: 1.5;">
			
			<div style="text-align: center; border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 20px;">
				<h1 id="pvName" style="font-size: 26px; text-transform: uppercase; margin: 0 0 6px 0; letter-spacing: 1px;">
					<?php echo esc_html( $resume['full_name'] ?? 'Aarav Sharma' ); ?>
				</h1>
				<div style="font-size: 13px; font-family: sans-serif; color: #374151;">
					<span id="pvEmail"><?php echo esc_html( $resume['email'] ?? 'aarav.sharma@eduforge360.edu' ); ?></span> | 
					<span id="pvPhone"><?php echo esc_html( $resume['phone'] ?? '+91 98765 43210' ); ?></span> | 
					<span>Bengaluru, India</span>
				</div>
			</div>

			<!-- Summary -->
			<div style="margin-bottom: 20px;">
				<h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #d1d5db; margin: 0 0 6px 0; padding-bottom: 2px; font-family: sans-serif;">
					Professional Summary
				</h3>
				<p id="pvSummary" style="font-size: 13px; margin: 0; text-align: justify;">
					<?php echo nl2br( esc_html( $resume['summary'] ?? '' ) ); ?>
				</p>
			</div>

			<!-- Education -->
			<div style="margin-bottom: 20px;">
				<h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #d1d5db; margin: 0 0 6px 0; padding-bottom: 2px; font-family: sans-serif;">
					Education
				</h3>
				<div id="pvEducation" style="font-size: 13px;">
					<?php echo nl2br( esc_html( $resume['education'] ?? '' ) ); ?>
				</div>
			</div>

			<!-- Technical Skills -->
			<div style="margin-bottom: 20px;">
				<h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #d1d5db; margin: 0 0 6px 0; padding-bottom: 2px; font-family: sans-serif;">
					Core Competencies & Technical Skills
				</h3>
				<p id="pvSkills" style="font-size: 13px; margin: 0;">
					<?php echo esc_html( $resume['skills'] ?? '' ); ?>
				</p>
			</div>

			<!-- Capstone Projects -->
			<div style="margin-bottom: 20px;">
				<h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #d1d5db; margin: 0 0 6px 0; padding-bottom: 2px; font-family: sans-serif;">
					Institutional Capstone Projects
				</h3>
				<div id="pvProjects" style="font-size: 13px;">
					<?php echo nl2br( esc_html( $resume['projects'] ?? '' ) ); ?>
				</div>
			</div>

			<!-- Experience -->
			<div style="margin-bottom: 20px;">
				<h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #d1d5db; margin: 0 0 6px 0; padding-bottom: 2px; font-family: sans-serif;">
					Experience & Internships
				</h3>
				<div id="pvExperience" style="font-size: 13px;">
					<?php echo nl2br( esc_html( $resume['experience'] ?? '' ) ); ?>
				</div>
			</div>

			<!-- Verified Institutional Badge -->
			<div style="margin-top: 25px; padding-top: 10px; border-top: 1px dashed #cbd5e1; font-family: sans-serif; font-size: 11px; color: #64748b; display: flex; justify-content: space-between;">
				<span>Validated by EduForge360 Institutional Verification Ledger</span>
				<span>QR-Certified Credential</span>
			</div>
		</div>

	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Real-time synchronization to preview
	function sync() {
		document.getElementById('pvName').innerText = document.getElementById('resName').value;
		document.getElementById('pvEmail').innerText = document.getElementById('resEmail').value;
		document.getElementById('pvPhone').innerText = document.getElementById('resPhone').value;
		document.getElementById('pvSummary').innerText = document.getElementById('resSummary').value;
		document.getElementById('pvEducation').innerText = document.getElementById('resEducation').value;
		document.getElementById('pvSkills').innerText = document.getElementById('resSkills').value;
		document.getElementById('pvProjects').innerText = document.getElementById('resProjects').value;
		document.getElementById('pvExperience').innerText = document.getElementById('resExperience').value;
	}

	const inputs = document.querySelectorAll('#resumeForm input, #resumeForm textarea');
	inputs.forEach(el => el.addEventListener('input', sync));

	// AJAX Save Resume
	document.getElementById('saveResumeBtn').addEventListener('click', function() {
		const btn = this;
		btn.innerText = 'Saving...';
		jQuery.ajax({
			url: eduforgeThemeVars.ajaxurl,
			type: 'POST',
			data: {
				action: 'eduforge_save_resume',
				nonce: eduforgeThemeVars.nonce,
				resume: {
					full_name: document.getElementById('resName').value,
					email: document.getElementById('resEmail').value,
					phone: document.getElementById('resPhone').value,
					summary: document.getElementById('resSummary').value,
					education: document.getElementById('resEducation').value,
					skills: document.getElementById('resSkills').value,
					projects: document.getElementById('resProjects').value,
					experience: document.getElementById('resExperience').value
				}
			},
			success: function(res) {
				btn.innerText = 'Saved ✓';
				setTimeout(() => { btn.innerText = '💾 Save Profile'; }, 2000);
				alert('Resume saved! Your overall career readiness score has been refreshed.');
			},
			error: function() {
				btn.innerText = 'Error';
			}
		});
	});
});
</script>

<style>
@media print {
	header, footer, .no-print, .page-header-banner, .mobile-bottom-nav {
		display: none !important;
	}
	body {
		background: #ffffff !important;
	}
	.container {
		max-width: 100% !important;
		padding: 0 !important;
		margin: 0 !important;
	}
	.resume-sheet {
		border: none !important;
		box-shadow: none !important;
		padding: 0 !important;
		width: 100% !important;
	}
}
</style>

<?php
get_footer();
