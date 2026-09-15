/**
 * EduForge Course Catalog AJAX Filters
 */
(function($) {
	'use strict';

	$(document).ready(function() {
		const $searchInput = $('#courseSearchInput');
		const $catSelect = $('#courseCategorySelect');
		const $diffSelect = $('#courseDifficultySelect');
		const $priceSelect = $('#coursePricingSelect');
		const $grid = $('#courseCatalogGrid');
		const $resetBtn = $('#resetFiltersBtn');
		let timer = null;

		function triggerFilter() {
			$grid.css('opacity', '0.5');

			$.ajax({
				url: eduforgeThemeVars.ajaxurl,
				type: 'POST',
				data: {
					action: 'eduforge_filter_courses',
					nonce: eduforgeThemeVars.nonce,
					search: $searchInput.val(),
					category: $catSelect.val(),
					difficulty: $diffSelect.val(),
					pricing: $priceSelect.val()
				},
				success: function(res) {
					$grid.css('opacity', '1');
					if (res.success) {
						$grid.html(res.data.html);
						$('#resultsCount').text(res.data.found_posts + ' Courses Found');
					}
				},
				error: function() {
					$grid.css('opacity', '1');
				}
			});
		}

		$searchInput.on('input', function() {
			clearTimeout(timer);
			timer = setTimeout(triggerFilter, 300);
		});

		$catSelect.on('change', triggerFilter);
		$diffSelect.on('change', triggerFilter);
		$priceSelect.on('change', triggerFilter);

		$resetBtn.on('click', function() {
			$searchInput.val('');
			$catSelect.val('all');
			$diffSelect.val('all');
			$priceSelect.val('all');
			triggerFilter();
		});
	});

})(jQuery);
