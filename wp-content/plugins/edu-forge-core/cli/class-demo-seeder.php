<?php
/**
 * EduForge Industrial Demo Data Seeder
 *
 * @package EduForge360
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EduForge_Demo_Seeder {

	public static function init() {
		// Register WP-CLI command if WP-CLI is present
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'eduforge seed', array( __CLASS__, 'cli_seed' ) );
		}
	}

	public static function render_admin_seeder_page() {
		if ( isset( $_POST['eduforge_run_seed'] ) && check_admin_referer( 'eduforge_seed_action', 'eduforge_seed_nonce' ) ) {
			$report = self::run_seed();
			echo '<div class="notice notice-success is-dismissible"><p><strong>Industrial Demo Data Seeded Successfully!</strong> ' . esc_html( $report ) . '</p></div>';
		}
		?>
		<div class="wrap eduforge-admin-wrap">
			<h1>EduForge360 Industrial Demo Seeder</h1>
			<p>Populate your platform with realistic institutional data for presentation, testing, and internship interview evaluation.</p>

			<div style="background:#fff; border:1px solid #cbd5e1; border-radius:8px; padding:24px; max-width:700px; margin-top:20px;">
				<h3>Dataset Summary:</h3>
				<ul style="list-style: disc; padding-left: 20px; line-height: 1.8; color: #475569;">
					<li><strong>Demo Users:</strong> Aarav Sharma (MCA Student), Dr. Radhakrishnan (Faculty), Priya Nair (Placement Officer)</li>
					<li><strong>8 Core Courses:</strong> Python Development, Full Stack Web, Cloud, DevOps, AI/ML, Data Analytics, Cyber Security, Database</li>
					<li><strong>Modules:</strong> Lessons, Quizzes, Assignments with realistic criteria</li>
					<li><strong>4 Industry Events:</strong> Hackathon 2026, AI Research League, Code Carnival, Placement Bootcamp</li>
					<li><strong>Partner Companies & Jobs:</strong> TCS, Infosys, Razorpay, Google Cloud Partner</li>
					<li><strong>Student Metrics:</strong> Pre-calculated Aarav Sharma dashboard (76% progress, 81% career readiness, 2 verified certificates)</li>
				</ul>

				<form method="post" style="margin-top: 25px;">
					<?php wp_nonce_field( 'eduforge_seed_action', 'eduforge_seed_nonce' ); ?>
					<button type="submit" name="eduforge_run_seed" class="button button-primary button-hero">
						🚀 Seed Complete Demo Ecosystem
					</button>
				</form>
			</div>
		</div>
		<?php
	}

	public static function cli_seed() {
		WP_CLI::line( 'Seeding EduForge360 Industrial Demo Data...' );
		$report = self::run_seed();
		WP_CLI::success( $report );
	}

	public static function run_seed() {
		global $wpdb;

		// 1. Create Users
		$student_id = self::seed_user( 'aarav', 'aarav.sharma@eduforge360.edu', 'Aarav Sharma', 'student' );
		$instructor_id = self::seed_user( 'radhakrishnan', 'dr.radhakrishnan@eduforge360.edu', 'Dr. Radhakrishnan', 'instructor' );
		$placement_id = self::seed_user( 'priya', 'priya.nair@eduforge360.edu', 'Priya Nair', 'placement_officer' );

		// 2. Seed Courses
		$courses_data = array(
			array(
				'title'    => 'Python Backend & Scalable Architecture',
				'category' => 'programming',
				'level'    => 'Intermediate',
				'duration' => '10 Weeks',
				'price'    => 0, // Free
				'desc'     => 'Comprehensive backend engineering in Python covering data structures, OOP, asynchronous programming, microservices, and high-throughput REST APIs.',
			),
			array(
				'title'    => 'Full Stack Modern Web Development',
				'category' => 'web_dev',
				'level'    => 'Intermediate',
				'duration' => '12 Weeks',
				'price'    => 4999,
				'desc'     => 'End-to-end full stack development using modern JavaScript, React, Node.js, and WordPress Headless REST APIs.',
			),
			array(
				'title'    => 'Cloud Computing & AWS Solutions Architect',
				'category' => 'cloud',
				'level'    => 'Advanced',
				'duration' => '8 Weeks',
				'price'    => 5999,
				'desc'     => 'Architecting highly available, fault-tolerant enterprise infrastructure on AWS using EC2, S3, RDS, Lambda, and VPC.',
			),
			array(
				'title'    => 'DevOps, Docker & CI/CD Pipelines',
				'category' => 'devops',
				'level'    => 'Intermediate',
				'duration' => '6 Weeks',
				'price'    => 0,
				'desc'     => 'Master containerization with Docker, orchestration, GitHub Actions CI/CD automation, and Linux server hardening.',
			),
			array(
				'title'    => 'AI & Applied Machine Learning',
				'category' => 'aiml',
				'level'    => 'Advanced',
				'duration' => '12 Weeks',
				'price'    => 6999,
				'desc'     => 'Hands-on machine learning engineering with NumPy, Pandas, Scikit-Learn, neural networks, and LLM orchestration.',
			),
			array(
				'title'    => 'Enterprise Data Analytics & BI',
				'category' => 'data_analytics',
				'level'    => 'Beginner',
				'duration' => '8 Weeks',
				'price'    => 3499,
				'desc'     => 'Statistical modeling, Tableau / PowerBI visualizations, exploratory data analysis, and predictive dashboards.',
			),
			array(
				'title'    => 'Industrial Cyber Security & Penetration Testing',
				'category' => 'cyber_security',
				'level'    => 'Advanced',
				'duration' => '10 Weeks',
				'price'    => 5499,
				'desc'     => 'Defensive and offensive cybersecurity, OWASP Top 10 vulnerabilities, network traffic analysis, and ethical hacking.',
			),
			array(
				'title'    => 'SQL & Relational Database Engineering',
				'category' => 'database',
				'level'    => 'Beginner',
				'duration' => '6 Weeks',
				'price'    => 0,
				'desc'     => 'Relational schema design, normalization, complex joins, index tuning, and ACID transaction architecture in MySQL/PostgreSQL.',
			),
		);

		$seeded_course_ids = array();

		foreach ( $courses_data as $c ) {
			$post_id = wp_insert_post( array(
				'post_title'   => $c['title'],
				'post_content' => $c['desc'],
				'post_excerpt' => wp_trim_words( $c['desc'], 20 ),
				'post_status'  => 'publish',
				'post_type'    => 'courses',
				'post_author'  => $instructor_id,
			) );

			if ( $post_id ) {
				update_post_meta( $post_id, '_eduforge_duration', $c['duration'] );
				update_post_meta( $post_id, '_eduforge_level', $c['level'] );
				update_post_meta( $post_id, '_eduforge_price', $c['price'] );
				update_post_meta( $post_id, '_eduforge_rating', 4.9 );

				// Seed 3 lessons per course
				self::seed_lessons_for_course( $post_id, $c['title'], $instructor_id );

				$seeded_course_ids[] = $post_id;
			}
		}

		// 3. Seed Events
		$events = array(
			array( 'title' => 'National Hackathon 2026', 'date' => '2026-10-20', 'loc' => 'Main Innovation Center', 'speaker' => 'Dr. A. P. Roy (Lead Architect)' ),
			array( 'title' => 'AI Research League & Showcase', 'date' => '2026-11-05', 'loc' => 'Virtual Stream', 'speaker' => 'Sundar V. (AI Director)' ),
			array( 'title' => 'Code Carnival: 48h Algorithm Challenge', 'date' => '2026-11-18', 'loc' => 'Turing Labs', 'speaker' => 'Coding Advisory Board' ),
			array( 'title' => 'Placement Bootcamp & Mock Technical Interviews', 'date' => '2026-12-02', 'loc' => 'Auditorium 2', 'speaker' => 'Priya Nair & Tech HR Leads' ),
		);
		foreach ( $events as $ev ) {
			$ev_id = wp_insert_post( array(
				'post_title'   => $ev['title'],
				'post_content' => 'Annual flagship technical symposium and recruitment preparation drive organized by EduForge360.',
				'post_status'  => 'publish',
				'post_type'    => 'events',
			) );
			if ( $ev_id ) {
				update_post_meta( $ev_id, '_eduforge_event_date', $ev['date'] );
				update_post_meta( $ev_id, '_eduforge_event_location', $ev['loc'] );
				update_post_meta( $ev_id, '_eduforge_event_speaker', $ev['speaker'] );
				update_post_meta( $ev_id, '_eduforge_max_capacity', 250 );
			}
		}

		// 4. Seed Jobs
		$jobs = array(
			array( 'role' => 'Python Backend Software Engineer', 'company' => 'Razorpay Software', 'salary' => '₹12.0 - ₹18.0 LPA', 'loc' => 'Bengaluru' ),
			array( 'role' => 'Junior Cloud Solutions Associate', 'company' => 'Google Cloud Partner Tech', 'salary' => '₹8.5 - ₹12.0 LPA', 'loc' => 'Hyderabad / Remote' ),
			array( 'role' => 'Full Stack Web Developer (React / PHP)', 'company' => 'TCS Digital Innovation', 'salary' => '₹7.5 - ₹11.0 LPA', 'loc' => 'Pune' ),
			array( 'role' => 'DevOps Automation Engineer', 'company' => 'Infosys Special Initiative', 'salary' => '₹9.0 - ₹14.0 LPA', 'loc' => 'Bengaluru / Hybrid' ),
		);
		foreach ( $jobs as $j ) {
			$j_id = wp_insert_post( array(
				'post_title'   => $j['role'],
				'post_content' => 'Seeking talented graduate engineers with strong fundamentals in algorithms, clean code, Git versioning, and cloud platforms.',
				'post_status'  => 'publish',
				'post_type'    => 'jobs',
			) );
			if ( $j_id ) {
				update_post_meta( $j_id, '_eduforge_company_name', $j['company'] );
				update_post_meta( $j_id, '_eduforge_salary_range', $j['salary'] );
				update_post_meta( $j_id, '_eduforge_location', $j['loc'] );
				update_post_meta( $j_id, '_eduforge_deadline', '2026-11-30' );
			}
		}

		// 5. Seed Aarav Sharma's Metrics
		if ( ! empty( $seeded_course_ids ) ) {
			// Enroll in first 4 courses
			$table_enrollments = EduForge_Database::get_table_name( 'enrollments' );
			$wpdb->query( "DELETE FROM {$table_enrollments} WHERE student_id = {$student_id}" );

			// 2 completed courses with certificates
			$c1 = $seeded_course_ids[0];
			$c2 = $seeded_course_ids[1];
			$c3 = $seeded_course_ids[2];
			$c4 = $seeded_course_ids[3];

			$wpdb->insert( $table_enrollments, array( 'student_id' => $student_id, 'course_id' => $c1, 'progress' => 100, 'status' => 'completed', 'enrolled_at' => '2026-08-01 10:00:00', 'completed_at' => '2026-09-01 15:30:00' ) );
			$wpdb->insert( $table_enrollments, array( 'student_id' => $student_id, 'course_id' => $c2, 'progress' => 100, 'status' => 'completed', 'enrolled_at' => '2026-08-05 11:00:00', 'completed_at' => '2026-09-10 18:00:00' ) );
			$wpdb->insert( $table_enrollments, array( 'student_id' => $student_id, 'course_id' => $c3, 'progress' => 72, 'status' => 'active', 'enrolled_at' => '2026-09-01 09:00:00' ) );
			$wpdb->insert( $table_enrollments, array( 'student_id' => $student_id, 'course_id' => $c4, 'progress' => 35, 'status' => 'active', 'enrolled_at' => '2026-09-05 14:00:00' ) );

			// Generate 2 certificates for Aarav
			EduForge_Certificate_Manager::issue_certificate( $student_id, $c1, 94.5 );
			EduForge_Certificate_Manager::issue_certificate( $student_id, $c2, 91.0 );

			// Seed skill assessments for Aarav
			EduForge_Skill_Manager::save_assessment( $student_id, 'programming', 82, 'Algorithmic Thinking, Python Syntax', 'Dynamic Programming edge cases' );
			EduForge_Skill_Manager::save_assessment( $student_id, 'database', 61, 'CRUD, Joins', 'Subqueries, Indexing performance' );
			EduForge_Skill_Manager::save_assessment( $student_id, 'devops', 74, 'Git branching, Dockerfiles', 'Kubernetes Helm charts' );
			EduForge_Skill_Manager::save_assessment( $student_id, 'communication', 84, 'Technical articulation, active listening', 'Cross-cultural negotiation' );
			EduForge_Skill_Manager::save_assessment( $student_id, 'cloud', 70, 'AWS EC2, S3', 'VPC Peering, IAM Policies' );
			EduForge_Skill_Manager::save_assessment( $student_id, 'web_dev', 80, 'REST APIs, React components', 'Server-Side Rendering hydration' );
		}

		return 'Seeded 3 users, 8 courses with lessons, 4 events, 4 placement job drives, 2 verified student certificates, and Aarav Sharma baseline profile!';
	}

	private static function seed_user( $username, $email, $display_name, $role ) {
		$user_id = username_exists( $username );
		if ( ! $user_id && ! email_exists( $email ) ) {
			$user_id = wp_create_user( $username, 'EduForge@2026', $email );
			if ( ! is_wp_error( $user_id ) ) {
				wp_update_user( array(
					'ID'           => $user_id,
					'display_name' => $display_name,
					'role'         => $role,
				) );
			}
		}
		return $user_id ?: 1;
	}

	private static function seed_lessons_for_course( $course_id, $course_title, $instructor_id ) {
		$modules = array(
			'01: Architecture & Environment Setup',
			'02: Deep Dive into Core Principles',
			'03: Practical Capstone Implementation',
		);

		foreach ( $modules as $idx => $m_title ) {
			$l_id = wp_insert_post( array(
				'post_title'   => $course_title . ' — ' . $m_title,
				'post_content' => 'In this rigorous module, students examine production patterns, performance best practices, and code examples.',
				'post_status'  => 'publish',
				'post_type'    => 'lessons',
				'post_author'  => $instructor_id,
				'menu_order'   => $idx + 1,
			) );

			if ( $l_id ) {
				update_post_meta( $l_id, '_eduforge_course_id', $course_id );
				update_post_meta( $l_id, '_eduforge_duration', '25 mins' );
				update_post_meta( $l_id, '_eduforge_video_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' );
			}
		}
	}
}
EduForge_Demo_Seeder::init();
