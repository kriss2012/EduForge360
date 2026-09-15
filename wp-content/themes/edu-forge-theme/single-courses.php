<?php
/**
 * Single Course Detail Template
 *
 * @package EduForge360
 */

get_header();

$course_id = get_the_ID();
$details = class_exists( 'EduForge_Course_Manager' ) ? EduForge_Course_Manager::get_course_details( $course_id ) : array(
	'duration' => '8 Weeks',
	'level'    => 'Intermediate',
	'price'    => 0,
	'rating'   => 4.8,
	'outcomes' => array( 'Build production-ready applications', 'Understand architectural design patterns' ),
	'projects' => array( 'Enterprise Architecture Capstone' ),
);

$author_id = get_post_field( 'post_author', $course_id );
$author = get_userdata( $author_id );
$author_name = $author ? $author->display_name : 'Lead Faculty';
$lessons = class_exists( 'EduForge_Course_Manager' ) ? EduForge_Course_Manager::get_course_lessons( $course_id ) : array();

$is_student = is_user_logged_in();
$enrollment = ( $is_student && class_exists( 'EduForge_Course_Manager' ) ) ? EduForge_Course_Manager::is_enrolled( get_current_user_id(), $course_id ) : false;
?>

<div class="course-detail-header" style="background: #0f172a; color: #ffffff; padding: 50px 0;">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		
		<div style="display: flex; gap: 40px; align-items: flex-start; justify-content: space-between; flex-wrap: wrap;">
			<div style="flex: 2; min-width: 320px;">
				<div style="display: flex; gap: 10px; margin-bottom: 15px;">
					<span style="background: rgba(37,99,235,0.2); color: #60a5fa; padding: 4px 10px; border-radius: 4px; font-size: 13px; font-weight: 600;">
						<?php echo esc_html( $details['level'] ); ?>
					</span>
					<span style="background: rgba(255,255,255,0.1); color: #cbd5e1; padding: 4px 10px; border-radius: 4px; font-size: 13px;">
						⏱ <?php echo esc_html( $details['duration'] ); ?>
					</span>
					<span style="background: rgba(255,255,255,0.1); color: #fbbf24; padding: 4px 10px; border-radius: 4px; font-size: 13px;">
						⭐ <?php echo esc_html( $details['rating'] ); ?>/5 Rating
					</span>
				</div>

				<h1 style="font-size: 36px; font-weight: 800; line-height: 1.2; margin-bottom: 16px;">
					<?php the_title(); ?>
				</h1>

				<p style="font-size: 17px; opacity: 0.85; line-height: 1.6; max-width: 750px;">
					<?php echo wp_kses_post( get_the_excerpt() ); ?>
				</p>

				<div style="display: flex; align-items: center; gap: 12px; margin-top: 25px;">
					<span style="font-size: 32px;">👨‍🏫</span>
					<div>
						<div style="font-size: 12px; opacity: 0.7; text-transform: uppercase;">Lead Instructor</div>
						<strong><?php echo esc_html( $author_name ); ?></strong>
					</div>
				</div>
			</div>

			<!-- Course Action Box / Sticky Card -->
			<div style="flex: 1; min-width: 280px; max-width: 380px; background: #ffffff; color: #0f172a; border-radius: 12px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
				<?php if ( has_post_thumbnail() ) : ?>
					<div style="border-radius: 8px; overflow: hidden; margin-bottom: 18px;">
						<?php the_post_thumbnail( 'eduforge-course-card' ); ?>
					</div>
				<?php endif; ?>

				<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 20px;">
					<?php if ( ! empty( $details['price'] ) && $details['price'] > 0 ) : ?>
						<span style="font-size: 30px; font-weight: 800; color: #0f172a;">₹<?php echo esc_html( number_format( $details['price'] ) ); ?></span>
						<span style="font-size: 13px; color: #64748b; text-decoration: line-through;">₹<?php echo esc_html( number_format( $details['price'] * 1.5 ) ); ?></span>
					<?php else : ?>
						<span style="font-size: 26px; font-weight: 800; color: #059669;">Free Enrollment</span>
						<span style="font-size: 13px; color: #64748b;">Sponsored Curriculum</span>
					<?php endif; ?>
				</div>

				<div class="course-enrollment-action" style="margin-bottom: 20px;">
					<?php if ( $enrollment ) : ?>
						<div style="background: #ecfdf5; border: 1px solid #10b981; padding: 12px; border-radius: 6px; text-align: center; margin-bottom: 12px;">
							<strong style="color: #065f46; font-size: 14px;">Enrolled (<?php echo esc_html( $enrollment->progress ); ?>% Complete)</strong>
						</div>
						<?php if ( ! empty( $lessons ) ) : ?>
							<a href="<?php echo esc_url( get_permalink( $lessons[0]->ID ) ); ?>" class="btn btn-primary" style="width: 100%; text-align: center; display: block; padding: 12px 0;">
								Continue Learning in Player →
							</a>
						<?php endif; ?>
					<?php else : ?>
						<?php if ( ! is_user_logged_in() ) : ?>
							<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="btn btn-primary" style="width: 100%; text-align: center; display: block; padding: 12px 0;">
								Log In to Enroll
							</a>
						<?php elseif ( empty( $details['price'] ) || $details['price'] == 0 ) : ?>
							<button id="instantEnrollBtn" data-course-id="<?php echo esc_attr( $course_id ); ?>" class="btn btn-primary" style="width: 100%; padding: 12px 0; font-size: 16px;">
								⚡ Enroll Now (Instant Access)
							</button>
						<?php else : ?>
							<button onclick="alert('Redirecting to secure Razorpay payment gateway...');" class="btn btn-primary" style="width: 100%; padding: 12px 0; font-size: 16px; background: #059669;">
								💳 Buy Course (₹<?php echo esc_html( number_format( $details['price'] ) ); ?>)
							</button>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; color: #475569; line-height: 2;">
					<li>✓ Full Lifetime Syllabus Access</li>
					<li>✓ Practical Capstone Code Repositories</li>
					<li>✓ Shareable Verifiable Certificate</li>
					<li>✓ Direct Placement Drive Eligibility</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
	<div style="max-width: 800px;">
		<!-- Course Overview -->
		<section style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 30px; margin-bottom: 30px;">
			<h2 style="margin-top: 0; font-size: 22px; color: #0f172a;">Program Overview</h2>
			<div style="line-height: 1.8; color: #334155;">
				<?php the_content(); ?>
			</div>
		</section>

		<!-- Learning Outcomes -->
		<section style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 30px; margin-bottom: 30px;">
			<h2 style="margin-top: 0; font-size: 22px; color: #0f172a;">What You Will Master</h2>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; margin-top: 15px;">
				<?php foreach ( (array) $details['outcomes'] as $outcome ) : ?>
					<div style="display: flex; gap: 10px; align-items: baseline;">
						<span style="color: #10b981; font-weight: 800;">✓</span>
						<span style="color: #334155; font-size: 15px;"><?php echo esc_html( $outcome ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<!-- Curriculum & Lessons Breakdown -->
		<section style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 30px; margin-bottom: 30px;">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
				<h2 style="margin: 0; font-size: 22px; color: #0f172a;">Curriculum Structure</h2>
				<span style="font-size: 13px; color: #64748b; font-weight: 600;"><?php echo count( $lessons ); ?> Modules Included</span>
			</div>

			<?php if ( ! empty( $lessons ) ) : ?>
				<div class="curriculum-list" style="display: flex; flex-direction: column; gap: 10px;">
					<?php foreach ( $lessons as $index => $lesson ) : 
						$l_duration = get_post_meta( $lesson->ID, '_eduforge_duration', true ) ?: '20 mins';
					?>
						<div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border: 1px solid #f1f5f9; background: #f8fafc; border-radius: 6px;">
							<div style="display: flex; align-items: center; gap: 12px;">
								<span style="background: #e2e8f0; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 12px; font-weight: 700; color: #475569;">
									<?php echo $index + 1; ?>
								</span>
								<a href="<?php echo esc_url( get_permalink( $lesson->ID ) ); ?>" style="font-weight: 600; color: #0f172a;">
									<?php echo esc_html( $lesson->post_title ); ?>
								</a>
							</div>
							<span style="font-size: 13px; color: #64748b;">⏱ <?php echo esc_html( $l_duration ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p style="color: #64748b;">Curriculum is being compiled by faculty. Check back shortly.</p>
			<?php endif; ?>
		</section>

		<!-- Capstone Projects -->
		<section style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 30px;">
			<h2 style="margin-top: 0; font-size: 22px; color: #0f172a;">Hands-on Capstones & Portfolios</h2>
			<p style="color: #64748b; margin-bottom: 20px;">Every student completes end-to-end projects reviewed directly by faculty before graduation certification.</p>
			<?php foreach ( (array) $details['projects'] as $proj ) : ?>
				<div style="padding: 14px; background: #f8fafc; border-left: 4px solid #2563eb; border-radius: 4px; margin-bottom: 10px;">
					<strong>🚀 <?php echo esc_html( $proj ); ?></strong>
				</div>
			<?php endforeach; ?>
		</section>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const btn = document.getElementById('instantEnrollBtn');
	if (btn) {
		btn.addEventListener('click', function() {
			btn.disabled = true;
			btn.innerText = 'Enrolling...';
			fetch(eduforgeThemeVars.restUrl + 'enrollment', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': eduforgeThemeVars.nonce
				},
				body: JSON.stringify({ course_id: btn.dataset.courseId })
			})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					alert('Enrollment confirmed! Redirecting to course...');
					window.location.reload();
				} else {
					alert(data.message || 'Enrollment failed.');
					btn.disabled = false;
					btn.innerText = '⚡ Enroll Now (Instant Access)';
				}
			})
			.catch(() => {
				alert('Network error while processing enrollment.');
				btn.disabled = false;
				btn.innerText = '⚡ Enroll Now (Instant Access)';
			});
		});
	}
});
</script>

<?php
get_footer();
