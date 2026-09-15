<?php
/**
 * EduForge Shortcodes Manager
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Shortcodes {

	public static function init() {
		add_shortcode( 'eduforge_courses', array( __CLASS__, 'render_courses' ) );
		add_shortcode( 'eduforge_dashboard', array( __CLASS__, 'render_dashboard' ) );
		add_shortcode( 'eduforge_course', array( __CLASS__, 'render_single_course' ) );
		add_shortcode( 'eduforge_certificate', array( __CLASS__, 'render_certificate' ) );
		add_shortcode( 'eduforge_events', array( __CLASS__, 'render_events' ) );
		add_shortcode( 'eduforge_jobs', array( __CLASS__, 'render_jobs' ) );
		add_shortcode( 'eduforge_assessment', array( __CLASS__, 'render_assessment' ) );
		add_shortcode( 'eduforge_resume', array( __CLASS__, 'render_resume' ) );
	}

	public static function render_courses( $atts ) {
		$atts = shortcode_atts( array(
			'limit'    => 6,
			'category' => '',
		), $atts, 'eduforge_courses' );

		$args = array(
			'post_type'      => 'courses',
			'post_status'    => 'publish',
			'posts_per_page' => intval( $atts['limit'] ),
		);

		if ( ! empty( $atts['category'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'course_category',
					'field'    => 'slug',
					'terms'    => sanitize_text_field( $atts['category'] ),
				),
			);
		}

		$query = new WP_Query( $args );
		ob_start();

		if ( $query->have_posts() ) {
			echo '<div class="eduforge-courses-grid">';
			while ( $query->have_posts() ) {
				$query->the_post();
				if ( function_exists( 'get_template_part' ) ) {
					get_template_part( 'template-parts/course-card' );
				} else {
					echo '<div class="eduforge-course-card-simple"><h4>' . esc_html( get_the_title() ) . '</h4><a href="' . esc_url( get_permalink() ) . '">View Course</a></div>';
				}
			}
			echo '</div>';
			wp_reset_postdata();
		} else {
			echo '<p class="eduforge-empty">' . esc_html__( 'No courses found.', 'eduforge360' ) . '</p>';
		}

		return ob_get_clean();
	}

	public static function render_dashboard() {
		if ( ! is_user_logged_in() ) {
			return '<div class="eduforge-login-notice" style="padding: 24px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; text-align: center;">
				<h3>' . esc_html__( 'Please Sign In', 'eduforge360' ) . '</h3>
				<p>' . esc_html__( 'Access your learning dashboard, assignments, and placement tracker.', 'eduforge360' ) . '</p>
				<a href="' . esc_url( wp_login_url( get_permalink() ) ) . '" class="eduforge-btn-primary" style="display:inline-block; padding:10px 20px; background:#2563eb; color:#fff; border-radius:6px; text-decoration:none;">' . esc_html__( 'Sign In with Institutional ID', 'eduforge360' ) . '</a>
			</div>';
		}

		ob_start();
		$student_id = get_current_user_id();
		$enrolled = EduForge_Course_Manager::get_student_courses( $student_id );
		$readiness = EduForge_Career_Manager::calculate_career_readiness( $student_id );
		$certs = EduForge_Certificate_Manager::get_student_certificates( $student_id );
		?>
		<div class="eduforge-dashboard-shortcode" style="padding: 20px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
			<div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;">
				<div>
					<h2 style="margin: 0; font-size: 22px;"><?php printf( esc_html__( 'Welcome back, %s', 'eduforge360' ), esc_html( wp_get_current_user()->display_name ) ); ?></h2>
					<span style="font-size: 13px; color: #64748b;"><?php esc_html_e( 'EduForge360 Student Dashboard', 'eduforge360' ); ?></span>
				</div>
				<div style="text-align: right;">
					<div style="font-size: 12px; color: #64748b;"><?php esc_html_e( 'Career Readiness', 'eduforge360' ); ?></div>
					<div style="font-size: 20px; font-weight: 800; color: #2563eb;"><?php echo esc_html( $readiness ); ?>%</div>
				</div>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
				<div style="background: #eff6ff; padding: 16px; border-radius: 8px;">
					<div style="color: #1e40af; font-size: 13px; font-weight: 600;"><?php esc_html_e( 'Enrolled Courses', 'eduforge360' ); ?></div>
					<div style="font-size: 24px; font-weight: 800; color: #1e3a8a; margin-top: 4px;"><?php echo count( $enrolled ); ?></div>
				</div>
				<div style="background: #f0fdf4; padding: 16px; border-radius: 8px;">
					<div style="color: #166534; font-size: 13px; font-weight: 600;"><?php esc_html_e( 'Certificates Earned', 'eduforge360' ); ?></div>
					<div style="font-size: 24px; font-weight: 800; color: #14532d; margin-top: 4px;"><?php echo count( $certs ); ?></div>
				</div>
			</div>

			<a href="<?php echo esc_url( home_url( '/dashboard/' ) ); ?>" style="display: inline-block; background: #0f172a; color: #ffffff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
				<?php esc_html_e( 'Open Full SaaS Learning Dashboard →', 'eduforge360' ); ?>
			</a>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function render_single_course( $atts ) {
		$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'eduforge_course' );
		$course_id = intval( $atts['id'] );
		if ( ! $course_id ) {
			return '';
		}

		$course = get_post( $course_id );
		if ( ! $course || 'courses' !== $course->post_type ) {
			return '<p>' . esc_html__( 'Course not found.', 'eduforge360' ) . '</p>';
		}

		ob_start();
		$details = EduForge_Course_Manager::get_course_details( $course_id );
		?>
		<div class="eduforge-course-widget" style="border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; background: #fff;">
			<h3 style="margin-top: 0;"><?php echo esc_html( $course->post_title ); ?></h3>
			<p><?php echo wp_kses_post( wp_trim_words( $course->post_content, 25 ) ); ?></p>
			<div style="display: flex; gap: 15px; font-size: 13px; color: #64748b; margin-bottom: 15px;">
				<span>⏱ <?php echo esc_html( $details['duration'] ); ?></span>
				<span>📊 <?php echo esc_html( $details['level'] ); ?></span>
				<span>⭐ <?php echo esc_html( $details['rating'] ); ?>/5</span>
			</div>
			<a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" style="background: #2563eb; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-block;">
				<?php esc_html_e( 'View Curriculum & Enroll', 'eduforge360' ); ?>
			</a>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function render_certificate( $atts ) {
		$atts = shortcode_atts( array( 'id' => '' ), $atts, 'eduforge_certificate' );
		if ( empty( $atts['id'] ) ) {
			return '';
		}
		$cert = EduForge_Certificate_Manager::get_certificate_by_number( sanitize_text_field( $atts['id'] ) );
		if ( ! $cert ) {
			return '<p>' . esc_html__( 'Certificate not found.', 'eduforge360' ) . '</p>';
		}
		return '<div class="eduforge-cert-pill" style="display:inline-flex; align-items:center; gap:8px; background:#ecfdf5; border:1px solid #10b981; padding:6px 14px; border-radius:20px; font-size:13px; color:#065f46;">
			<span>🎓</span> <strong>' . esc_html( $cert->certificate_number ) . '</strong> — <a href="' . esc_url( EduForge_Certificate_Manager::get_verification_url( $cert->certificate_number ) ) . '" target="_blank" style="color:#047857; text-decoration:underline;">' . esc_html__( 'Verify Credential', 'eduforge360' ) . '</a>
		</div>';
	}

	public static function render_events( $atts ) {
		$atts = shortcode_atts( array( 'limit' => 3 ), $atts, 'eduforge_events' );
		$events = get_posts( array(
			'post_type'      => 'events',
			'post_status'    => 'publish',
			'posts_per_page' => intval( $atts['limit'] ),
		) );
		ob_start();
		echo '<div class="eduforge-events-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:20px;">';
		foreach ( $events as $ev ) {
			$meta = EduForge_Event_Manager::get_event_meta( $ev->ID );
			echo '<div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:20px;">';
			echo '<span style="font-size:12px; font-weight:700; color:#2563eb; text-transform:uppercase;">' . esc_html( $meta['date'] ) . '</span>';
			echo '<h4 style="margin:8px 0 10px 0;"><a href="' . esc_url( get_permalink( $ev->ID ) ) . '" style="color:#0f172a; text-decoration:none;">' . esc_html( $ev->post_title ) . '</a></h4>';
			echo '<p style="font-size:13px; color:#64748b; margin-bottom:15px;">' . esc_html( $meta['location'] ) . '</p>';
			echo '<a href="' . esc_url( get_permalink( $ev->ID ) ) . '" style="font-size:13px; font-weight:600; color:#2563eb; text-decoration:none;">Register Now →</a>';
			echo '</div>';
		}
		echo '</div>';
		return ob_get_clean();
	}

	public static function render_jobs( $atts ) {
		$atts = shortcode_atts( array( 'limit' => 4 ), $atts, 'eduforge_jobs' );
		$jobs = get_posts( array(
			'post_type'      => 'jobs',
			'post_status'    => 'publish',
			'posts_per_page' => intval( $atts['limit'] ),
		) );
		ob_start();
		echo '<div class="eduforge-jobs-list" style="display:flex; flex-direction:column; gap:12px;">';
		foreach ( $jobs as $j ) {
			$meta = EduForge_Placement_Manager::get_job_meta( $j->ID );
			echo '<div style="display:flex; justify-content:space-between; align-items:center; background:#fff; border:1px solid #e2e8f0; padding:16px 20px; border-radius:8px;">';
			echo '<div><h4 style="margin:0 0 4px 0;"><a href="' . esc_url( get_permalink( $j->ID ) ) . '" style="color:#0f172a; text-decoration:none;">' . esc_html( $j->post_title ) . '</a></h4>';
			echo '<div style="font-size:13px; color:#64748b;">' . esc_html( $meta['company'] ) . ' &bull; ' . esc_html( $meta['location'] ) . ' &bull; <strong style="color:#10b981;">' . esc_html( $meta['salary'] ) . '</strong></div></div>';
			echo '<a href="' . esc_url( get_permalink( $j->ID ) ) . '" style="background:#2563eb; color:#fff; padding:8px 16px; border-radius:6px; text-decoration:none; font-size:13px; font-weight:600;">Apply</a>';
			echo '</div>';
		}
		echo '</div>';
		return ob_get_clean();
	}

	public static function render_assessment() {
		return '<div class="eduforge-assessment-embed" style="padding:20px; text-align:center; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
			<h3>' . esc_html__( 'Interactive Skill Assessment Engine', 'eduforge360' ) . '</h3>
			<p>' . esc_html__( 'Evaluate your competencies across Programming, Database, Cloud, DevOps, and Communication.', 'eduforge360' ) . '</p>
			<a href="' . esc_url( home_url( '/assessment/' ) ) . '" class="eduforge-btn-primary" style="background:#2563eb; color:#fff; padding:10px 24px; border-radius:6px; text-decoration:none; font-weight:600; display:inline-block;">' . esc_html__( 'Launch Skill Assessment Test →', 'eduforge360' ) . '</a>
		</div>';
	}

	public static function render_resume() {
		return '<div class="eduforge-resume-embed" style="padding:20px; text-align:center; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
			<h3>' . esc_html__( 'EduForge Professional Resume Builder', 'eduforge360' ) . '</h3>
			<p>' . esc_html__( 'Generate an industry-standard, ATS-friendly resume formatted directly from your verified coursework and skill badges.', 'eduforge360' ) . '</p>
			<a href="' . esc_url( home_url( '/resume-builder/' ) ) . '" style="background:#0f172a; color:#fff; padding:10px 24px; border-radius:6px; text-decoration:none; font-weight:600; display:inline-block;">' . esc_html__( 'Open Resume Builder & PDF Export →', 'eduforge360' ) . '</a>
		</div>';
	}
}
