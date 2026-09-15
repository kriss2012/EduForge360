<?php
/**
 * Front Page Template
 *
 * @package EduForge360
 */

get_header();

// Section 1: Hero
get_template_part( 'template-parts/hero' );
?>

<!-- Section 2: Platform Introduction -->
<section class="section section-intro" id="platform-intro">
	<div class="container">
		<div class="section-header text-center">
			<span class="section-badge">Institutional LMS & Career Ecosystem</span>
			<h2 class="section-title">The Complete Lifecycle of Student Engineering Excellence</h2>
			<p class="section-description">
				EduForge360 bridges the gap between academic theory and high-growth industry demands through measurable skill analytics, real-world project portfolios, and transparent placement pipelines.
			</p>
		</div>

		<div class="intro-grid">
			<div class="intro-feature-card">
				<div class="feature-icon">🧠</div>
				<h3>Adaptive Skill Diagnostics</h3>
				<p>Pre-evaluation testing across 9 technical and aptitude domains to map student baseline strengths and weaknesses.</p>
			</div>
			<div class="intro-feature-card">
				<div class="feature-icon">💻</div>
				<h3>Production-Grade LMS</h3>
				<p>Hands-on lessons with interactive code snippets, timed quizzes, and faculty-graded code repositories.</p>
			</div>
			<div class="intro-feature-card">
				<div class="feature-icon">📜</div>
				<h3>Verifiable Credentials</h3>
				<p>Cryptographically hashed certificates with live public QR code authentication endpoints.</p>
			</div>
			<div class="intro-feature-card">
				<div class="feature-icon">🎯</div>
				<h3>Corporate Placement Engine</h3>
				<p>Direct campus recruitment drives, ATS resume generator, and transparent application tracking.</p>
			</div>
		</div>
	</div>
</section>

<!-- Section 3: Why EduForge360 -->
<section class="section section-why" id="why-eduforge" style="background: #f1f5f9;">
	<div class="container">
		<div class="split-layout">
			<div class="split-content">
				<span class="section-badge">Industrial Grade Standard</span>
				<h2 class="section-title">Why Top Colleges & Universities Trust EduForge360</h2>
				<p class="section-text">
					Traditional learning management systems operate as basic file repositories. EduForge360 functions as a comprehensive career intelligence operating system.
				</p>

				<div class="value-bullets">
					<div class="value-item">
						<div class="check-icon">✓</div>
						<div>
							<strong>Zero Guesswork in Placement Readiness</strong>
							<p>Quantitative readiness scoring calculated from quiz attempts, project submissions, and skill diagnostics.</p>
						</div>
					</div>
					<div class="value-item">
						<div class="check-icon">✓</div>
						<div>
							<strong>Faculty Empowerment & Real-Time Analytics</strong>
							<p>Instructors evaluate assignments with dedicated grading consoles and granular submission timelines.</p>
						</div>
					</div>
					<div class="value-item">
						<div class="check-icon">✓</div>
						<div>
							<strong>Placement Officer Command Center</strong>
							<p>Track student eligibility, publish corporate recruitment drives, and short-list top performers in seconds.</p>
						</div>
					</div>
				</div>
			</div>

			<div class="split-visual">
				<div class="eduforge-glass-box">
					<div class="glass-header">
						<div class="dots"><span></span><span></span><span></span></div>
						<span>EduForge360 Architecture Engine</span>
					</div>
					<div class="glass-content">
						<div class="arch-flow-node">1. Student Registration & Profile</div>
						<div class="arch-arrow">↓</div>
						<div class="arch-flow-node">2. 9-Domain Skill Assessment & Score Mapping</div>
						<div class="arch-arrow">↓</div>
						<div class="arch-flow-node">3. Rule-Based Course & Project Recommendation</div>
						<div class="arch-arrow">↓</div>
						<div class="arch-flow-node">4. Capstones, Quizzes & Faculty Grading</div>
						<div class="arch-arrow">↓</div>
						<div class="arch-flow-node">5. Verifiable Certificate & Corporate Placement</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Section 4: Learning Categories -->
<section class="section section-categories" id="categories">
	<div class="container">
		<div class="section-header text-center">
			<span class="section-badge">Curated Domains</span>
			<h2 class="section-title">Explore High-Demand Engineering Tracks</h2>
			<p class="section-description">Specialized learning roadmaps mapped directly to industry job roles.</p>
		</div>

		<div class="categories-grid">
			<a href="<?php echo esc_url( home_url( '/courses/?cat=programming' ) ); ?>" class="category-pill-card">
				<span class="cat-icon">🐍</span>
				<h4>Programming & DSA</h4>
				<span>8 Courses &bull; Python, Java, C++</span>
			</a>
			<a href="<?php echo esc_url( home_url( '/courses/?cat=web_dev' ) ); ?>" class="category-pill-card">
				<span class="cat-icon">🌐</span>
				<h4>Full Stack Web</h4>
				<span>6 Courses &bull; React, Node, WP REST</span>
			</a>
			<a href="<?php echo esc_url( home_url( '/courses/?cat=cloud' ) ); ?>" class="category-pill-card">
				<span class="cat-icon">☁️</span>
				<h4>Cloud Architecture</h4>
				<span>5 Courses &bull; AWS, Azure, GCP</span>
			</a>
			<a href="<?php echo esc_url( home_url( '/courses/?cat=devops' ) ); ?>" class="category-pill-card">
				<span class="cat-icon">🚀</span>
				<h4>DevOps & CI/CD</h4>
				<span>4 Courses &bull; Docker, Git, CI/CD</span>
			</a>
			<a href="<?php echo esc_url( home_url( '/courses/?cat=aiml' ) ); ?>" class="category-pill-card">
				<span class="cat-icon">🤖</span>
				<h4>AI & Machine Learning</h4>
				<span>6 Courses &bull; Neural Nets, LLMs</span>
			</a>
			<a href="<?php echo esc_url( home_url( '/courses/?cat=database' ) ); ?>" class="category-pill-card">
				<span class="cat-icon">🗄️</span>
				<h4>Database Systems</h4>
				<span>4 Courses &bull; MySQL, Redis, ACID</span>
			</a>
		</div>
	</div>
</section>

<!-- Section 5: Featured Courses -->
<section class="section section-featured-courses" id="featured-courses">
	<div class="container">
		<div class="section-header-split">
			<div>
				<span class="section-badge">Hands-On Programs</span>
				<h2 class="section-title">Featured Industry Courses</h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" class="btn btn-outline">Browse All 42+ Courses →</a>
		</div>

		<div class="eduforge-courses-grid">
			<?php
			$featured_courses = new WP_Query( array(
				'post_type'      => 'courses',
				'post_status'    => 'publish',
				'posts_per_page' => 4,
			) );

			if ( $featured_courses->have_posts() ) :
				while ( $featured_courses->have_posts() ) :
					$featured_courses->the_post();
					get_template_part( 'template-parts/course-card' );
				endwhile;
				wp_reset_postdata();
			else :
				echo '<p class="text-center" style="grid-column: 1/-1;">Courses are currently compiling. Please run Demo Data Seeder in admin menu.</p>';
			endif;
			?>
		</div>
	</div>
</section>

<!-- Section 6: Student Success Metrics -->
<section class="section section-metrics-counter" id="impact-metrics" style="background: #0f172a; color: #ffffff;">
	<div class="container">
		<div class="section-header text-center" style="color: #fff;">
			<span class="section-badge" style="background: rgba(255,255,255,0.1); color: #38bdf8;">Measurable Institutional Impact</span>
			<h2 class="section-title" style="color: #fff;">Proven Track Record Across Top Campuses</h2>
		</div>

		<div class="metrics-grid">
			<div class="metric-card-dark">
				<div class="num">94.8%</div>
				<div class="label">Course Completion Rate</div>
				<p>Driven by interactive checkpoint quizzes and faculty code reviews.</p>
			</div>
			<div class="metric-card-dark">
				<div class="num">82.4%</div>
				<div class="label">Placement Conversion</div>
				<p>Students hitting 80%+ career readiness secure tier-1 technical roles.</p>
			</div>
			<div class="metric-card-dark">
				<div class="num">150+</div>
				<div class="label">Corporate Hiring Partners</div>
				<p>Top companies actively recruit from the EduForge360 placement ledger.</p>
			</div>
			<div class="metric-card-dark">
				<div class="num">100%</div>
				<div class="label">Verifiable Certificates</div>
				<p>Instant verification prevents credential fabrication in resumes.</p>
			</div>
		</div>
	</div>
</section>

<!-- Section 7: Skill Development Process -->
<section class="section section-process" id="how-it-works">
	<div class="container">
		<div class="section-header text-center">
			<span class="section-badge">Student Journey</span>
			<h2 class="section-title">The 7-Step Career Shape Engine</h2>
		</div>

		<div class="process-steps-track">
			<div class="step-card">
				<div class="step-num">01</div>
				<h4>Registration</h4>
				<p>Create verified student profile with academic major & technical interests.</p>
			</div>
			<div class="step-card">
				<div class="step-num">02</div>
				<h4>Skill Assessment</h4>
				<p>Complete adaptive diagnostic tests across 9 core competencies.</p>
			</div>
			<div class="step-card">
				<div class="step-num">03</div>
				<h4>Recommendations</h4>
				<p>System automatically recommends courses based on assessment weak points.</p>
			</div>
			<div class="step-card">
				<div class="step-num">04</div>
				<h4>Learning & Labs</h4>
				<p>Engage with modular video lectures, downloadable guides, and code examples.</p>
			</div>
			<div class="step-card">
				<div class="step-num">05</div>
				<h4>Quizzes & Projects</h4>
				<p>Complete timed evaluations and submit production-ready assignment code.</p>
			</div>
			<div class="step-card">
				<div class="step-num">06</div>
				<h4>Certification</h4>
				<p>Earn verified credentials with unique ID and anti-tamper QR code.</p>
			</div>
			<div class="step-card">
				<div class="step-num">07</div>
				<h4>Placement Drives</h4>
				<p>Build an ATS-optimized resume and apply directly to recruitment drives.</p>
			</div>
		</div>
	</div>
</section>

<!-- Section 8: Upcoming Events -->
<section class="section section-events" id="upcoming-events" style="background: #f8fafc;">
	<div class="container">
		<div class="section-header-split">
			<div>
				<span class="section-badge">Campus & Virtual Symposiums</span>
				<h2 class="section-title">Upcoming Hackathons & Placement Bootcamps</h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/events/' ) ); ?>" class="btn btn-outline">All Events →</a>
		</div>

		<div class="events-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-top: 30px;">
			<?php
			$events_query = new WP_Query( array(
				'post_type'      => 'events',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
			) );

			if ( $events_query->have_posts() ) :
				while ( $events_query->have_posts() ) :
					$events_query->the_post();
					get_template_part( 'template-parts/event-card' );
				endwhile;
				wp_reset_postdata();
			else :
				echo '<p>No events currently scheduled. Seed demo data to populate events.</p>';
			endif;
			?>
		</div>
	</div>
</section>

<!-- Section 9: Placement Preparation & Active Drives -->
<section class="section section-placement-drives" id="placement-drives">
	<div class="container">
		<div class="section-header-split">
			<div>
				<span class="section-badge">Corporate Hiring</span>
				<h2 class="section-title">Active Placement Drives & Job Opportunities</h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="btn btn-primary">Visit Placement Board →</a>
		</div>

		<div class="jobs-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-top: 30px;">
			<?php
			$jobs_query = new WP_Query( array(
				'post_type'      => 'jobs',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
			) );

			if ( $jobs_query->have_posts() ) :
				while ( $jobs_query->have_posts() ) :
					$jobs_query->the_post();
					get_template_part( 'template-parts/job-card' );
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>
</section>

<!-- Section 10: Testimonials -->
<section class="section section-testimonials" id="testimonials" style="background: #f1f5f9;">
	<div class="container">
		<div class="section-header text-center">
			<span class="section-badge">Student Voices</span>
			<h2 class="section-title">What Our Students & Faculty Say</h2>
		</div>

		<div class="testimonials-grid">
			<div class="testimonial-card">
				<div class="stars">⭐⭐⭐⭐⭐</div>
				<p class="quote">
					"EduForge360 took me from understanding basic Python syntax to designing asynchronous microservices. The career readiness tracker showed me exactly which database queries to optimize before my campus interviews."
				</p>
				<div class="author">
					<strong>Aarav Sharma</strong>
					<span>MCA Graduate &bull; Placed at Razorpay</span>
				</div>
			</div>

			<div class="testimonial-card">
				<div class="stars">⭐⭐⭐⭐⭐</div>
				<p class="quote">
					"As an instructor managing 300+ students, reviewing code submissions with standard emails was impossible. EduForge's assignment grading desk and automated quiz grading streamlined our entire academic department."
				</p>
				<div class="author">
					<strong>Dr. K. Radhakrishnan</strong>
					<span>Head of Computer Applications</span>
				</div>
			</div>

			<div class="testimonial-card">
				<div class="stars">⭐⭐⭐⭐⭐</div>
				<p class="quote">
					"Verifiable certificate QR codes gave hiring managers immediate trust in our candidates. Our placement drives have seen an increase of 40% in candidate shortlisting thanks to the transparent skill scores."
				</p>
				<div class="author">
					<strong>Priya Nair</strong>
					<span>Chief Placement & Training Officer</span>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Section 11: Corporate Partners -->
<section class="section section-partners text-center">
	<div class="container">
		<span class="section-badge">Trusted Network</span>
		<h3 style="color: #64748b; font-size: 15px; margin: 12px 0 25px 0;">Graduates Hired by Industry Leaders</h3>
		<div class="partners-logos" style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; opacity: 0.8; font-weight: 700; color: #475569; font-size: 18px;">
			<span>💼 Razorpay Software</span>
			<span>🏢 Tata Consultancy Services</span>
			<span>☁️ Google Cloud Partner Tech</span>
			<span>🚀 Infosys Technologies</span>
			<span>⚡ Wipro Digital</span>
		</div>
	</div>
</section>

<!-- Section 12: Blog & Insights -->
<section class="section section-blog" id="insights">
	<div class="container">
		<div class="section-header text-center">
			<span class="section-badge">Engineering Articles</span>
			<h2 class="section-title">Latest Technical Guides & Career Insights</h2>
		</div>

		<div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
			<div class="blog-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:20px;">
				<span class="date" style="font-size:12px; color:#2563eb; font-weight:700;">SEPTEMBER 2026</span>
				<h4 style="margin:10px 0;"><a href="#" style="color:#0f172a;">How to Master Technical Whiteboard Interviews for Campus Placements</a></h4>
				<p style="font-size:14px; color:#64748b;">Key patterns, edge-case testing methodologies, and time complexity communication techniques.</p>
			</div>
			<div class="blog-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:20px;">
				<span class="date" style="font-size:12px; color:#2563eb; font-weight:700;">AUGUST 2026</span>
				<h4 style="margin:10px 0;"><a href="#" style="color:#0f172a;">Why WordPress Developers Must Master Custom Plugins Over Theme Snippets</a></h4>
				<p style="font-size:14px; color:#64748b;">De-coupling business logic, database migrations, and REST APIs from presentation themes.</p>
			</div>
			<div class="blog-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:20px;">
				<span class="date" style="font-size:12px; color:#2563eb; font-weight:700;">AUGUST 2026</span>
				<h4 style="margin:10px 0;"><a href="#" style="color:#0f172a;">Building Scalable REST Endpoints in WordPress with Proper Sanitization</a></h4>
				<p style="font-size:14px; color:#64748b;">A deep dive into permission callbacks, prepared statements, and nonces in high-traffic platforms.</p>
			</div>
		</div>
	</div>
</section>

<!-- Section 13: FAQ -->
<section class="section section-faq" id="faq" style="background: #f8fafc;">
	<div class="container" style="max-width: 800px;">
		<div class="section-header text-center">
			<span class="section-badge">Frequently Asked Questions</span>
			<h2 class="section-title">Everything You Need to Know About EduForge360</h2>
		</div>

		<div class="faq-list">
			<details class="faq-item" style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:16px 20px; margin-bottom:12px;">
				<summary style="font-weight:700; cursor:pointer; color:#0f172a;">How are the skill assessment scores and recommendations calculated?</summary>
				<p style="margin-top:10px; color:#64748b; font-size:14px; line-height:1.6;">
					The platform executes a 9-domain evaluation matrix. If your proficiency in any category (e.g., Python, SQL, Git) falls below the 60% threshold, the rule engine automatically generates tailored course recommendations to address your specific weaknesses.
				</p>
			</details>
			<details class="faq-item" style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:16px 20px; margin-bottom:12px;">
				<summary style="font-weight:700; cursor:pointer; color:#0f172a;">How do employers verify EduForge360 certificates?</summary>
				<p style="margin-top:10px; color:#64748b; font-size:14px; line-height:1.6;">
					Every certificate issued by EduForge360 includes a unique serial identifier (e.g. <code>EDU-2026-00125</code>) and a dynamic QR code leading to the public verification endpoint <code>/verify-certificate/{id}</code>.
				</p>
			</details>
			<details class="faq-item" style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:16px 20px; margin-bottom:12px;">
				<summary style="font-weight:700; cursor:pointer; color:#0f172a;">Can faculty create their own quizzes and assignments?</summary>
				<p style="margin-top:10px; color:#64748b; font-size:14px; line-height:1.6;">
					Yes. Instructors assigned the custom Faculty role can author courses, upload video/PDF lessons, create timed MCQ and multiple-answer quizzes, and review code submissions directly in the assignment grading desk.
				</p>
			</details>
			<details class="faq-item" style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:16px 20px; margin-bottom:12px;">
				<summary style="font-weight:700; cursor:pointer; color:#0f172a;">Is WooCommerce integrated for paid certificate programs?</summary>
				<p style="margin-top:10px; color:#64748b; font-size:14px; line-height:1.6;">
					Yes. When WooCommerce is activated, courses can be linked directly to WooCommerce products. As soon as a student completes payment, the system hooks into <code>woocommerce_order_status_completed</code> and unlocks full course access automatically.
				</p>
			</details>
		</div>
	</div>
</section>

<!-- Section 14: Final CTA -->
<section class="section section-cta" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%); color: #ffffff; text-align: center; padding: 70px 20px;">
	<div class="container" style="max-width: 750px;">
		<h2 style="font-size: 36px; font-weight: 800; margin-bottom: 16px;">Ready to Accelerate Your Engineering Career?</h2>
		<p style="font-size: 18px; opacity: 0.9; line-height: 1.6; margin-bottom: 30px;">
			Join 1,200+ students mastering real-world software development, earning verifiable certifications, and preparing for top-tier placement drives.
		</p>
		<div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
			<a href="<?php echo esc_url( home_url( '/assessment/' ) ); ?>" class="btn btn-primary btn-lg" style="background: #2563eb;">
				Take Free Skill Assessment →
			</a>
			<a href="<?php echo esc_url( home_url( '/courses/' ) ); ?>" class="btn btn-outline btn-lg" style="color:#fff; border-color:#fff;">
				Explore All Courses
			</a>
		</div>
	</div>
</section>

<?php
get_footer();
