<?php
/**
 * Template Name: SaaS Learning & Career Dashboard
 *
 * @package EduForge360
 */

if ( ! is_user_logged_in() ) {
	auth_redirect();
}

get_header();

$student_id = get_current_user_id();
$user = wp_get_current_user();

$enrolled_courses = class_exists( 'EduForge_Course_Manager' ) ? EduForge_Course_Manager::get_student_courses( $student_id ) : array();
$certificates = class_exists( 'EduForge_Certificate_Manager' ) ? EduForge_Certificate_Manager::get_student_certificates( $student_id ) : array();
$skills = class_exists( 'EduForge_Skill_Manager' ) ? EduForge_Skill_Manager::get_student_skills( $student_id ) : array();
$readiness = class_exists( 'EduForge_Career_Manager' ) ? EduForge_Career_Manager::calculate_career_readiness( $student_id ) : 76;
$applications = class_exists( 'EduForge_Placement_Manager' ) ? EduForge_Placement_Manager::get_student_applications( $student_id ) : array();

$completed_courses = array_filter( $enrolled_courses, function( $c ) { return $c->status === 'completed'; } );
$active_courses = array_filter( $enrolled_courses, function( $c ) { return $c->status === 'active'; } );
?>

<div class="saas-dashboard-wrapper" style="display: flex; min-height: calc(100vh - 75px); background: #f8fafc;">
	<!-- Dashboard Sidebar Navigation -->
	<aside class="dashboard-sidebar" style="width: 260px; background: #0f172a; color: #f8fafc; padding: 25px 20px; display: flex; flex-direction: column; justify-content: space-between;">
		<div>
			<div class="user-brief" style="display: flex; align-items: center; gap: 12px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #1e293b;">
				<div style="width: 44px; height: 44px; border-radius: 50%; background: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
					<?php echo strtoupper( substr( $user->display_name, 0, 2 ) ); ?>
				</div>
				<div style="overflow: hidden;">
					<strong style="display: block; font-size: 14px; white-space: nowrap; text-overflow: ellipsis;"><?php echo esc_html( $user->display_name ); ?></strong>
					<span style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">MCA Candidate</span>
				</div>
			</div>

			<nav class="dashboard-nav-links" style="display: flex; flex-direction: column; gap: 6px;">
				<a href="#overview" class="dash-tab-btn active" data-tab="tab-overview" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 600;">
					<span>📊</span> Overview
				</a>
				<a href="#my-courses" class="dash-tab-btn" data-tab="tab-courses" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 600;">
					<span>📚</span> My Courses
				</a>
				<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 600;">
					<span>🧠</span> Skill Assessments
				</a>
				<a href="<?php echo esc_url( home_url( '/career/' ) ); ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 600;">
					<span>🚀</span> Career Readiness
				</a>
				<a href="<?php echo esc_url( home_url( '/resume-builder/' ) ); ?>" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 600;">
					<span>📄</span> Resume Builder
				</a>
				<a href="#certificates" class="dash-tab-btn" data-tab="tab-certs" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 600;">
					<span>🏆</span> Certificates
				</a>
				<a href="#applications" class="dash-tab-btn" data-tab="tab-apps" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 600;">
					<span>💼</span> Job Applications
				</a>
			</nav>
		</div>

		<div>
			<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" style="display: flex; align-items: center; gap: 10px; color: #ef4444; text-decoration: none; font-size: 13px; font-weight: 600; padding: 10px 14px;">
				<span>🚪</span> Sign Out
			</a>
		</div>
	</aside>

	<!-- Main Content Area -->
	<main class="dashboard-canvas" style="flex: 1; padding: 35px 40px; overflow-y: auto;">
		
		<!-- TAB 1: OVERVIEW -->
		<section id="tab-overview" class="dash-tab-panel active">
			<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 25px;">
				<div>
					<h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">Welcome, <?php echo esc_html( $user->display_name ); ?></h1>
					<p style="color: #64748b; font-size: 14px; margin: 0;">Here is your institutional progress and placement preparation report.</p>
				</div>
				<span style="font-size: 13px; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-weight: 700;">Active Semester</span>
			</div>

			<!-- KPI Cards -->
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 30px;">
				<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
					<span style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Courses</span>
					<div style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?php echo count( $enrolled_courses ); ?></div>
				</div>
				<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
					<span style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase;">Active Programs</span>
					<div style="font-size: 26px; font-weight: 800; color: #2563eb; margin-top: 4px;"><?php echo count( $active_courses ); ?></div>
				</div>
				<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
					<span style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase;">Completed</span>
					<div style="font-size: 26px; font-weight: 800; color: #10b981; margin-top: 4px;"><?php echo count( $completed_courses ); ?></div>
				</div>
				<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
					<span style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase;">Certificates</span>
					<div style="font-size: 26px; font-weight: 800; color: #7c3aed; margin-top: 4px;"><?php echo count( $certificates ); ?></div>
				</div>
				<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
					<span style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase;">Career Readiness</span>
					<div style="font-size: 26px; font-weight: 800; color: #f59e0b; margin-top: 4px;"><?php echo esc_html( $readiness ); ?>%</div>
				</div>
			</div>

			<!-- Continue Learning Section -->
			<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px; margin-bottom: 30px;">
				<h3 style="margin-top: 0; font-size: 18px; color: #0f172a; margin-bottom: 20px;">Continue Learning</h3>
				<?php if ( ! empty( $active_courses ) ) : ?>
					<div style="display: flex; flex-direction: column; gap: 16px;">
						<?php foreach ( $active_courses as $c ) : ?>
							<div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border: 1px solid #f1f5f9; background: #f8fafc; border-radius: 8px;">
								<div style="flex: 1; min-width: 240px; margin-right: 20px;">
									<h4 style="margin: 0 0 8px 0; font-size: 16px;"><?php echo esc_html( $c->post_title ); ?></h4>
									<div style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; width: 100%; max-width: 320px;">
										<div style="background: #2563eb; height: 100%; width: <?php echo esc_attr( $c->progress ); ?>%;"></div>
									</div>
									<span style="font-size: 12px; color: #64748b; margin-top: 4px; display: inline-block;"><?php echo esc_html( $c->progress ); ?>% Complete</span>
								</div>
								<a href="<?php echo esc_url( get_permalink( $c->course_id ) ); ?>" class="btn btn-primary btn-sm">
									Continue Course →
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p style="color: #64748b;">No active courses in progress. Explore new tracks to advance your readiness.</p>
					<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" class="btn btn-outline btn-sm">Browse Catalog</a>
				<?php endif; ?>
			</div>

			<!-- Domain Skills Radar / Progress Bars -->
			<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
					<h3 style="margin: 0; font-size: 18px; color: #0f172a;">Technical Skill Proficiency Matrix</h3>
					<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="btn btn-outline btn-sm">Retake Assessment</a>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
					<?php foreach ( $skills as $key => $s ) : ?>
						<div style="border: 1px solid #f1f5f9; padding: 14px; border-radius: 6px; background: #fafafa;">
							<div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
								<span style="text-transform: capitalize;"><?php echo esc_html( str_replace( '_', ' ', $key ) ); ?></span>
								<span style="color: #2563eb;"><?php echo esc_html( $s['score'] ); ?>%</span>
							</div>
							<div style="background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden;">
								<div style="background: <?php echo $s['score'] >= 75 ? '#10b981' : ( $s['score'] >= 50 ? '#2563eb' : '#f59e0b' ); ?>; height: 100%; width: <?php echo esc_attr( $s['score'] ); ?>%;"></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- TAB 2: MY COURSES -->
		<section id="tab-courses" class="dash-tab-panel" style="display: none;">
			<h2 style="margin-top: 0;">My Enrolled Courses</h2>
			<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
				<?php foreach ( $enrolled_courses as $c ) : ?>
					<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
						<h3 style="margin-top: 0; font-size: 17px;"><?php echo esc_html( $c->post_title ); ?></h3>
						<div style="font-size: 13px; color: #64748b; margin-bottom: 12px;">Enrolled: <?php echo esc_html( gmdate( 'M j, Y', strtotime( $c->enrolled_at ) ) ); ?></div>
						<div style="background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden; margin-bottom: 12px;">
							<div style="background: #2563eb; height: 100%; width: <?php echo esc_attr( $c->progress ); ?>%;"></div>
						</div>
						<div style="display: flex; justify-content: space-between; align-items: center;">
							<span style="font-size: 13px; font-weight: 700; color: #0f172a;"><?php echo esc_html( $c->progress ); ?>%</span>
							<a href="<?php echo esc_url( get_permalink( $c->course_id ) ); ?>" class="btn btn-outline btn-sm">Launch Course</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<!-- TAB 3: CERTIFICATES -->
		<section id="tab-certs" class="dash-tab-panel" style="display: none;">
			<h2 style="margin-top: 0;">Earned Official Credentials</h2>
			<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 20px;">
				<?php if ( ! empty( $certificates ) ) : ?>
					<?php foreach ( $certificates as $cert ) : 
						$verify_url = class_exists( 'EduForge_Certificate_Manager' ) ? EduForge_Certificate_Manager::get_verification_url( $cert->certificate_number ) : '#';
					?>
						<div style="background: #fff; border: 2px solid #10b981; border-radius: 8px; padding: 24px; position: relative;">
							<div style="font-size: 12px; color: #059669; font-weight: 700; text-transform: uppercase;">Verified Credential</div>
							<h3 style="margin: 8px 0; font-size: 18px;"><?php echo esc_html( $cert->course_title ); ?></h3>
							<div style="font-family: monospace; font-size: 13px; color: #475569; margin-bottom: 15px;">ID: <?php echo esc_html( $cert->certificate_number ); ?></div>
							<a href="<?php echo esc_url( $verify_url ); ?>" target="_blank" class="btn btn-outline btn-sm">
								View Official Certificate & QR ↗
							</a>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p>No certificates earned yet. Complete all lessons and quizzes in a course to automatically issue your credential.</p>
				<?php endif; ?>
			</div>
		</section>

		<!-- TAB 4: APPLICATIONS -->
		<section id="tab-apps" class="dash-tab-panel" style="display: none;">
			<h2 style="margin-top: 0;">Placement Applications Status</h2>
			<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 20px; overflow-x: auto;">
				<table style="width: 100%; border-collapse: collapse; text-align: left;">
					<thead>
						<tr style="border-bottom: 1px solid #e2e8f0; background: #f8fafc; font-size: 13px; color: #64748b;">
							<th style="padding: 14px 18px;">Company</th>
							<th style="padding: 14px 18px;">Role</th>
							<th style="padding: 14px 18px;">Salary</th>
							<th style="padding: 14px 18px;">Date Applied</th>
							<th style="padding: 14px 18px;">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php if ( ! empty( $applications ) ) : ?>
							<?php foreach ( $applications as $app ) : ?>
								<tr style="border-bottom: 1px solid #f1f5f9; font-size: 14px;">
									<td style="padding: 14px 18px; font-weight: 700;"><?php echo esc_html( $app->company_name ?: 'Tech Partner' ); ?></td>
									<td style="padding: 14px 18px;"><?php echo esc_html( $app->job_title ); ?></td>
									<td style="padding: 14px 18px; color: #059669; font-weight: 600;"><?php echo esc_html( $app->salary_range ?: '₹10.0 LPA' ); ?></td>
									<td style="padding: 14px 18px; color: #64748b;"><?php echo esc_html( gmdate( 'M j, Y', strtotime( $app->applied_at ) ) ); ?></td>
									<td style="padding: 14px 18px;">
										<span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700;">
											<?php echo esc_html( strtoupper( $app->status ) ); ?>
										</span>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="5" style="padding: 24px; text-align: center; color: #64748b;">
									No active job applications. <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>">Explore campus placement drives →</a>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</section>

	</main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const tabBtns = document.querySelectorAll('.dash-tab-btn');
	const tabPanels = document.querySelectorAll('.dash-tab-panel');

	tabBtns.forEach(btn => {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			const targetId = this.dataset.tab;

			tabBtns.forEach(b => {
				b.style.background = 'transparent';
				b.style.color = '#cbd5e1';
			});
			this.style.background = '#1e293b';
			this.style.color = '#38bdf8';

			tabPanels.forEach(p => p.style.display = 'none');
			const targetPanel = document.getElementById(targetId);
			if (targetPanel) {
				targetPanel.style.display = 'block';
			}
		});
	});
});
</script>

<?php
get_footer();
