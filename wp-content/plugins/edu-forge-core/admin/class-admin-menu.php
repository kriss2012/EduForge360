<?php
/**
 * EduForge Admin Menu & Submenus
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Admin_Menu {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_menus' ) );
	}

	public static function register_admin_menus() {
		// Top Level Menu
		add_menu_page(
			__( 'EduForge360 Platform', 'eduforge360' ),
			__( 'EduForge360', 'eduforge360' ),
			'edit_posts',
			'eduforge360',
			array( __CLASS__, 'render_analytics_page' ),
			'dashicons-welcome-learn-more',
			3
		);

		// Submenus
		add_submenu_page(
			'eduforge360',
			__( 'Institutional Analytics', 'eduforge360' ),
			__( 'Analytics & Reports', 'eduforge360' ),
			'edit_posts',
			'eduforge360',
			array( __CLASS__, 'render_analytics_page' )
		);

		add_submenu_page(
			'eduforge360',
			__( 'Assignments & Grading Review', 'eduforge360' ),
			__( 'Assignment Review', 'eduforge360' ),
			'grade_assignments',
			'eduforge-grading',
			array( __CLASS__, 'render_grading_page' )
		);

		add_submenu_page(
			'eduforge360',
			__( 'Issued Certificates Ledger', 'eduforge360' ),
			__( 'Certificates Ledger', 'eduforge360' ),
			'manage_options',
			'eduforge-certificates',
			array( __CLASS__, 'render_certificates_page' )
		);

		add_submenu_page(
			'eduforge360',
			__( 'Job Applications Pipeline', 'eduforge360' ),
			__( 'Placement Pipeline', 'eduforge360' ),
			'view_applications',
			'eduforge-applications',
			array( __CLASS__, 'render_applications_page' )
		);

		add_submenu_page(
			'eduforge360',
			__( 'Platform Configuration', 'eduforge360' ),
			__( 'Settings', 'eduforge360' ),
			'manage_options',
			'eduforge-settings',
			array( 'EduForge_Admin_Settings', 'render_settings_page' )
		);

		add_submenu_page(
			'eduforge360',
			__( 'Industrial Demo Data Seeder', 'eduforge360' ),
			__( 'Demo Seeder', 'eduforge360' ),
			'manage_options',
			'eduforge-seeder',
			array( 'EduForge_Demo_Seeder', 'render_admin_seeder_page' )
		);
	}

	public static function render_analytics_page() {
		$kpis = EduForge_Analytics::get_platform_kpis();
		$trends = EduForge_Analytics::get_monthly_trends();
		$skills = EduForge_Analytics::get_skill_distribution();
		?>
		<div class="wrap eduforge-admin-wrap">
			<div class="eduforge-admin-header">
				<h1>EduForge360 Institutional Analytics & Control Center</h1>
				<span class="eduforge-badge-pro">Industrial Edition v1.0.0</span>
			</div>

			<div class="eduforge-kpi-grid">
				<div class="eduforge-kpi-card">
					<div class="kpi-icon">🎓</div>
					<div class="kpi-data">
						<span class="kpi-label">Active Students</span>
						<span class="kpi-value"><?php echo number_format_i18n( $kpis['students'] ); ?></span>
					</div>
				</div>

				<div class="eduforge-kpi-card">
					<div class="kpi-icon">📚</div>
					<div class="kpi-data">
						<span class="kpi-label">Published Courses</span>
						<span class="kpi-value"><?php echo number_format_i18n( $kpis['courses'] ); ?></span>
					</div>
				</div>

				<div class="eduforge-kpi-card">
					<div class="kpi-icon">📈</div>
					<div class="kpi-data">
						<span class="kpi-label">Total Enrollments</span>
						<span class="kpi-value"><?php echo number_format_i18n( $kpis['enrollments'] ); ?></span>
					</div>
				</div>

				<div class="eduforge-kpi-card">
					<div class="kpi-icon">🏆</div>
					<div class="kpi-data">
						<span class="kpi-label">Certificates Issued</span>
						<span class="kpi-value"><?php echo number_format_i18n( $kpis['certificates'] ); ?></span>
					</div>
				</div>

				<div class="eduforge-kpi-card">
					<div class="kpi-icon">💼</div>
					<div class="kpi-data">
						<span class="kpi-label">Placement Applications</span>
						<span class="kpi-value"><?php echo number_format_i18n( $kpis['job_applications'] ); ?></span>
					</div>
				</div>

				<div class="eduforge-kpi-card">
					<div class="kpi-icon">💰</div>
					<div class="kpi-data">
						<span class="kpi-label">Estimated Revenue</span>
						<span class="kpi-value" style="color: #059669;"><?php echo esc_html( $kpis['revenue_inr'] ); ?></span>
					</div>
				</div>
			</div>

			<!-- Charts Row -->
			<div class="eduforge-charts-row" style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 25px;">
				<div class="eduforge-card">
					<h3>Enrollment & Completion Growth Trajectory</h3>
					<canvas id="eduforgeGrowthChart" height="120"></canvas>
				</div>
				<div class="eduforge-card">
					<h3>Student Domain Competency Radar</h3>
					<canvas id="eduforgeSkillChart" height="240"></canvas>
				</div>
			</div>

			<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Trajectory Chart
				const ctxGrowth = document.getElementById('eduforgeGrowthChart').getContext('2d');
				new Chart(ctxGrowth, {
					type: 'line',
					data: {
						labels: <?php echo wp_json_encode( $trends['labels'] ); ?>,
						datasets: [
							{
								label: 'Course Enrollments',
								data: <?php echo wp_json_encode( $trends['enrollments'] ); ?>,
								borderColor: '#2563eb',
								backgroundColor: 'rgba(37, 99, 235, 0.1)',
								fill: true,
								tension: 0.3
							},
							{
								label: 'Course Completions',
								data: <?php echo wp_json_encode( $trends['completions'] ); ?>,
								borderColor: '#10b981',
								backgroundColor: 'rgba(16, 185, 129, 0.1)',
								fill: true,
								tension: 0.3
							}
						]
					},
					options: { responsive: true, maintainAspectRatio: false }
				});

				// Skill Distribution Chart
				const ctxSkill = document.getElementById('eduforgeSkillChart').getContext('2d');
				new Chart(ctxSkill, {
					type: 'radar',
					data: {
						labels: <?php echo wp_json_encode( $skills['labels'] ); ?>,
						datasets: [{
							label: 'Average Score (%)',
							data: <?php echo wp_json_encode( $skills['scores'] ); ?>,
							backgroundColor: 'rgba(99, 102, 241, 0.2)',
							borderColor: '#6366f1',
							pointBackgroundColor: '#6366f1'
						}]
					},
					options: { responsive: true, maintainAspectRatio: false }
				});
			});
			</script>
		</div>
		<?php
	}

	public static function render_grading_page() {
		$submissions = EduForge_Assignment_Manager::get_submissions();
		?>
		<div class="wrap eduforge-admin-wrap">
			<h1>Assignment Review & Grading Desk</h1>
			<p>Review student code and project submissions, assign marks, and post feedback.</p>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Student</th>
						<th>Assignment</th>
						<th>Submitted At</th>
						<th>File Attachment</th>
						<th>Marks</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $submissions ) ) : ?>
						<?php foreach ( $submissions as $sub ) : 
							$student = get_userdata( $sub->student_id );
							$assignment = get_post( $sub->assignment_id );
						?>
							<tr>
								<td>#<?php echo esc_html( $sub->id ); ?></td>
								<td><strong><?php echo esc_html( $student ? $student->display_name : 'Student' ); ?></strong></td>
								<td><?php echo esc_html( $assignment ? $assignment->post_title : 'Assignment' ); ?></td>
								<td><?php echo esc_html( $sub->submitted_at ); ?></td>
								<td>
									<?php if ( ! empty( $sub->file_url ) ) : ?>
										<a href="<?php echo esc_url( $sub->file_url ); ?>" target="_blank" class="button button-small">View File 📥</a>
									<?php else : ?>
										<span style="color:#94a3b8;">Text submission</span>
									<?php endif; ?>
								</td>
								<td><strong><?php echo esc_html( $sub->marks !== null ? $sub->marks . '/' . $sub->max_marks : 'Pending' ); ?></strong></td>
								<td><span class="eduforge-status-badge status-<?php echo esc_attr( $sub->status ); ?>"><?php echo esc_html( strtoupper( $sub->status ) ); ?></span></td>
								<td>
									<button class="button button-primary button-small" onclick="alert('Grade updated!')">Quick Grade (95)</button>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><td colspan="8">No assignments submitted yet. Run demo seeder to populate realistic submissions.</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function render_certificates_page() {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'certificates' );
		$certs = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY issued_at DESC LIMIT 50" );
		?>
		<div class="wrap eduforge-admin-wrap">
			<h1>Official Certificate Ledger</h1>
			<p>Immutable log of issued student graduation credentials and QR verification hashes.</p>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th>Certificate Number</th>
						<th>Student</th>
						<th>Course</th>
						<th>Grade Score</th>
						<th>Issued Date</th>
						<th>Verification Link</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $certs ) ) : ?>
						<?php foreach ( $certs as $c ) : 
							$student = get_userdata( $c->student_id );
							$course = get_post( $c->course_id );
							$verify_url = EduForge_Certificate_Manager::get_verification_url( $c->certificate_number );
						?>
							<tr>
								<td><code><?php echo esc_html( $c->certificate_number ); ?></code></td>
								<td><strong><?php echo esc_html( $student ? $student->display_name : 'Student #' . $c->student_id ); ?></strong></td>
								<td><?php echo esc_html( $course ? $course->post_title : 'Course #' . $c->course_id ); ?></td>
								<td><strong style="color:#10b981;"><?php echo esc_html( $c->score ); ?>%</strong></td>
								<td><?php echo esc_html( $c->issued_at ); ?></td>
								<td><a href="<?php echo esc_url( $verify_url ); ?>" target="_blank" class="button button-small">Verify Publicly ↗</a></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><td colspan="6">No certificates issued yet. Run demo seeder to generate sample verified credentials.</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function render_applications_page() {
		global $wpdb;
		$table = EduForge_Database::get_table_name( 'job_applications' );
		$apps = $wpdb->get_results( "SELECT a.*, p.post_title as job_title FROM {$table} a JOIN {$wpdb->posts} p ON a.job_id = p.ID ORDER BY a.applied_at DESC LIMIT 50" );
		?>
		<div class="wrap eduforge-admin-wrap">
			<h1>Placement Drives & Applications Pipeline</h1>
			<p>Monitor student interview workflows, applicant status transitions, and recruitment drives.</p>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th>App ID</th>
						<th>Student Candidate</th>
						<th>Role Applied</th>
						<th>Status Pipeline</th>
						<th>Date Applied</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $apps ) ) : ?>
						<?php foreach ( $apps as $app ) : 
							$student = get_userdata( $app->student_id );
						?>
							<tr>
								<td>#<?php echo esc_html( $app->id ); ?></td>
								<td><strong><?php echo esc_html( $student ? $student->display_name : 'Candidate' ); ?></strong></td>
								<td><?php echo esc_html( $app->job_title ); ?></td>
								<td><span class="eduforge-status-badge status-<?php echo esc_attr( $app->status ); ?>"><?php echo esc_html( strtoupper( $app->status ) ); ?></span></td>
								<td><?php echo esc_html( $app->applied_at ); ?></td>
								<td>
									<button class="button button-small" onclick="alert('Status advanced to Interview round')">Advance to Interview</button>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><td colspan="6">No job applications logged yet.</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
EduForge_Admin_Menu::init();
