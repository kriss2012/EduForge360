<?php
/**
 * Public Certificate Verification Template
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cert_code = sanitize_text_field( get_query_var( 'eduforge_cert_verify' ) );
$certificate = EduForge_Certificate_Manager::get_certificate_by_number( $cert_code );

get_header();
?>

<div class="eduforge-verify-wrapper" style="max-width: 860px; margin: 40px auto; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
	<?php if ( $certificate ) : ?>
		<?php
		$student = get_userdata( $certificate->student_id );
		$course  = get_post( $certificate->course_id );
		$student_name = $student ? $student->display_name : 'Valued Student';
		$course_title = $course ? $course->post_title : 'Advanced Technical Program';
		$instructor_id = $course ? $course->post_author : 1;
		$instructor = get_userdata( $instructor_id );
		$instructor_name = $instructor ? $instructor->display_name : 'Dr. Radhakrishnan';
		$settings = get_option( 'eduforge_settings', array() );
		$institution = ! empty( $settings['institution_name'] ) ? $settings['institution_name'] : 'EduForge360 Institute of Technology';
		$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode( EduForge_Certificate_Manager::get_verification_url( $certificate->certificate_number ) );
		?>

		<div class="eduforge-cert-badge-success" style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 14px 20px; border-radius: 8px; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
			<span style="font-size: 24px;">✓</span>
			<div>
				<strong>Official Verified Credential</strong>
				<div style="font-size: 13px; color: #047857;">This certificate is authenticated and recorded in the EduForge360 immutable academic ledger.</div>
			</div>
		</div>

		<!-- Printable / Visual Certificate Container -->
		<div id="printableCertificate" class="eduforge-certificate-card" style="border: 8px double #1e293b; background: #ffffff; padding: 50px 40px; text-align: center; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: relative; background-image: radial-gradient(#f1f5f9 1px, transparent 1px); background-size: 24px 24px;">
			
			<div style="text-transform: uppercase; letter-spacing: 3px; font-size: 13px; color: #64748b; font-weight: 700; margin-bottom: 10px;">
				<?php echo esc_html( $institution ); ?>
			</div>

			<h1 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 0 0 15px 0; letter-spacing: -0.5px;">
				CERTIFICATE OF ACHIEVEMENT
			</h1>
			
			<p style="color: #64748b; font-size: 15px; margin: 0 0 25px 0;">This is proudly presented to</p>

			<div style="font-size: 30px; font-weight: 800; color: #2563eb; border-bottom: 2px solid #e2e8f0; display: inline-block; padding: 0 30px 10px 30px; margin-bottom: 25px;">
				<?php echo esc_html( $student_name ); ?>
			</div>

			<p style="color: #475569; font-size: 16px; line-height: 1.6; max-width: 620px; margin: 0 auto 30px auto;">
				for successfully completing all rigorous academic modules, capstone assessments, and practical exercises for the certified program:
			</p>

			<h2 style="font-size: 24px; color: #0f172a; margin: 0 0 35px 0; font-weight: 700;">
				<?php echo esc_html( $course_title ); ?>
			</h2>

			<div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid #e2e8f0; padding-top: 30px; margin-top: 20px;">
				<div style="text-align: left;">
					<div style="font-weight: 700; color: #0f172a; font-size: 15px;"><?php echo esc_html( $instructor_name ); ?></div>
					<div style="font-size: 12px; color: #64748b;">Lead Faculty & Instructor</div>
					<div style="font-size: 11px; color: #94a3b8; margin-top: 6px;">Issued: <?php echo esc_html( gmdate( 'F j, Y', strtotime( $certificate->issued_at ) ) ); ?></div>
				</div>

				<div style="text-align: center;">
					<img src="<?php echo esc_url( $qr_url ); ?>" alt="Verification QR" style="width: 80px; height: 80px; border: 1px solid #cbd5e1; padding: 4px; border-radius: 6px; background: #fff;" />
					<div style="font-size: 10px; color: #64748b; margin-top: 4px;">Scan to Verify</div>
				</div>

				<div style="text-align: right;">
					<div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;">Credential ID</div>
					<div style="font-family: monospace; font-size: 14px; font-weight: 700; color: #0f172a;"><?php echo esc_html( $certificate->certificate_number ); ?></div>
					<div style="font-size: 11px; color: #10b981; font-weight: 600; margin-top: 4px;">Grade Score: <?php echo esc_html( $certificate->score ); ?>%</div>
				</div>
			</div>
		</div>

		<div style="text-align: center; margin-top: 25px;">
			<button onclick="window.print();" class="eduforge-btn-primary" style="background: #2563eb; color: #fff; border: none; padding: 12px 28px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
				<span>🖨️</span> Print / Save Certificate as PDF
			</button>
			<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" style="margin-left: 15px; color: #64748b; text-decoration: none; font-size: 14px;">Browse More Courses →</a>
		</div>

	<?php else : ?>
		<div class="eduforge-cert-badge-error" style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 25px; border-radius: 8px; text-align: center;">
			<h2 style="margin: 0 0 10px 0;">Certificate Not Found</h2>
			<p style="margin: 0 0 20px 0;">No verified credential exists for the identifier: <strong><?php echo esc_html( $cert_code ); ?></strong></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: inline-block; background: #dc2626; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;">Return to Home</a>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
