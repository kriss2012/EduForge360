<?php
/**
 * Course Archive & Catalog Template
 *
 * @package EduForge360
 */

get_header();
?>

<div class="page-header-banner">
	<div class="container">
		<?php eduforge_breadcrumbs(); ?>
		<h1 class="page-title">Explore Industry Courses & Curriculums</h1>
		<p class="page-subtitle">Learn from lead faculty, build production-grade projects, and earn verified credentials.</p>
	</div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 60px;">
	<!-- Search & Filter Controls -->
	<div class="course-filter-bar" style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:30px; display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
		<div style="flex: 1; min-width: 240px;">
			<input type="text" id="courseSearchInput" placeholder="🔍 Search courses by keyword or tech..." style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px;" />
		</div>

		<div>
			<select id="courseCategorySelect" style="padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; background:#fff;">
				<option value="all">All Categories</option>
				<option value="programming">Programming & DSA</option>
				<option value="web_dev">Web Development</option>
				<option value="cloud">Cloud Computing</option>
				<option value="devops">DevOps & Git</option>
				<option value="aiml">AI & Machine Learning</option>
				<option value="database">Database Systems</option>
			</select>
		</div>

		<div>
			<select id="courseDifficultySelect" style="padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; background:#fff;">
				<option value="all">All Levels</option>
				<option value="Beginner">Beginner</option>
				<option value="Intermediate">Intermediate</option>
				<option value="Advanced">Advanced</option>
			</select>
		</div>

		<div>
			<select id="coursePricingSelect" style="padding:10px 14px; border:1px solid #cbd5e1; border-radius:6px; background:#fff;">
				<option value="all">All Pricing</option>
				<option value="free">Free Courses Only</option>
				<option value="paid">Paid Programs</option>
			</select>
		</div>

		<button id="resetFiltersBtn" class="btn btn-outline btn-sm">Reset</button>
	</div>

	<!-- Results Indicator -->
	<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
		<h2 style="font-size: 20px; font-weight: 700; color: #0f172a;" id="resultsCount">Available Courses</h2>
		<span style="font-size: 13px; color: #64748b;">Live AJAX Filtered</span>
	</div>

	<!-- Course Grid Container -->
	<div class="eduforge-courses-grid" id="courseCatalogGrid">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/course-card' );
			endwhile;
		else :
			echo '<div class="eduforge-empty" style="grid-column: 1/-1; text-align:center; padding:50px; background:#fff; border-radius:8px;">';
			echo '<h3>No Courses Found</h3>';
			echo '<p>Try adjusting your search criteria or resetting filters.</p>';
			echo '</div>';
		endif;
		?>
	</div>
</div>

<?php
get_footer();
