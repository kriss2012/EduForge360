<?php
/**
 * EduForge Admin Settings Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Admin_Settings {

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	public static function register_settings() {
		register_setting( 'eduforge_settings_group', 'eduforge_settings', array(
			'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
		) );
	}

	public static function sanitize_settings( $input ) {
		$output = array();
		$output['institution_name']     = sanitize_text_field( $input['institution_name'] ?? '' );
		$output['passing_grade_quiz']   = intval( $input['passing_grade_quiz'] ?? 70 );
		$output['auto_certificate']     = ! empty( $input['auto_certificate'] ) ? 1 : 0;
		$output['enable_job_board']     = ! empty( $input['enable_job_board'] ) ? 1 : 0;
		$output['currency_symbol']      = sanitize_text_field( $input['currency_symbol'] ?? '₹' );
		$output['currency_code']        = sanitize_text_field( $input['currency_code'] ?? 'INR' );
		$output['notification_email']   = sanitize_email( $input['notification_email'] ?? '' );
		$output['enable_public_verify'] = ! empty( $input['enable_public_verify'] ) ? 1 : 0;
		$output['razorpay_key_id']      = sanitize_text_field( $input['razorpay_key_id'] ?? '' );

		EduForge_Logger::info( 'EduForge platform settings updated by administrator.' );
		return $output;
	}

	public static function render_settings_page() {
		$settings = get_option( 'eduforge_settings', array(
			'institution_name'     => 'EduForge360 Institute of Technology',
			'passing_grade_quiz'   => 70,
			'auto_certificate'     => 1,
			'enable_job_board'     => 1,
			'currency_symbol'      => '₹',
			'currency_code'        => 'INR',
			'notification_email'   => get_option( 'admin_email' ),
			'enable_public_verify' => 1,
			'razorpay_key_id'      => 'rzp_test_sample',
		) );
		?>
		<div class="wrap eduforge-admin-wrap">
			<h1>EduForge360 Platform Configuration</h1>
			<?php settings_errors(); ?>

			<form method="post" action="options.php" style="background:#fff; padding:30px; border-radius:8px; border:1px solid #cbd5e1; max-width:850px; margin-top:20px;">
				<?php
				settings_fields( 'eduforge_settings_group' );
				do_settings_sections( 'eduforge_settings_group' );
				?>

				<h2 style="border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">1. Institutional Identity</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="institution_name">Institution / University Name</label></th>
						<td>
							<input type="text" id="institution_name" name="eduforge_settings[institution_name]" value="<?php echo esc_attr( $settings['institution_name'] ?? '' ); ?>" class="regular-text" />
							<p class="description">Displayed on graduation certificates, student verification records, and reports.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="notification_email">System Notification Email</label></th>
						<td>
							<input type="email" id="notification_email" name="eduforge_settings[notification_email]" value="<?php echo esc_attr( $settings['notification_email'] ?? '' ); ?>" class="regular-text" />
						</td>
					</tr>
				</table>

				<h2 style="border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-top: 30px;">2. Academic & Evaluation Engine</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="passing_grade_quiz">Default Quiz Passing Grade (%)</label></th>
						<td>
							<input type="number" min="40" max="100" id="passing_grade_quiz" name="eduforge_settings[passing_grade_quiz]" value="<?php echo esc_attr( $settings['passing_grade_quiz'] ?? 70 ); ?>" class="small-text" /> %
						</td>
					</tr>
					<tr>
						<th scope="row">Automatic Certification</th>
						<td>
							<label>
								<input type="checkbox" name="eduforge_settings[auto_certificate]" value="1" <?php checked( 1, $settings['auto_certificate'] ?? 0 ); ?> />
								Automatically issue verified cryptographic certificate when student hits 100% course completion
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">Public Credential Verification</th>
						<td>
							<label>
								<input type="checkbox" name="eduforge_settings[enable_public_verify]" value="1" <?php checked( 1, $settings['enable_public_verify'] ?? 0 ); ?> />
								Enable public verification URLs and QR scan pages (<code>/verify-certificate/{id}</code>)
							</label>
						</td>
					</tr>
				</table>

				<h2 style="border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-top: 30px;">3. Commerce & Indian Payment Architecture</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="currency_code">Billing Currency</label></th>
						<td>
							<input type="text" id="currency_symbol" name="eduforge_settings[currency_symbol]" value="<?php echo esc_attr( $settings['currency_symbol'] ?? '₹' ); ?>" style="width:50px;" />
							<input type="text" id="currency_code" name="eduforge_settings[currency_code]" value="<?php echo esc_attr( $settings['currency_code'] ?? 'INR' ); ?>" style="width:80px;" />
							<p class="description">Standard INR formatting used across course catalog and student enrollment checkouts.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="razorpay_key_id">Razorpay / Payment Key ID</label></th>
						<td>
							<input type="text" id="razorpay_key_id" name="eduforge_settings[razorpay_key_id]" value="<?php echo esc_attr( $settings['razorpay_key_id'] ?? '' ); ?>" class="regular-text" />
							<p class="description">Secret keys should be stored in <code>.env</code> file (e.g. <code>RAZORPAY_KEY_SECRET</code>) and never committed into public version control.</p>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Save EduForge360 Settings', 'eduforge360' ) ); ?>
			</form>
		</div>
		<?php
	}
}
EduForge_Admin_Settings::init();
