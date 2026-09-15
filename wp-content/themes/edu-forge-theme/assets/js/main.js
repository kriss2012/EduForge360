/**
 * EduForge360 Main JavaScript
 */
(function($) {
	'use strict';

	$(document).ready(function() {
		// Mobile Menu Toggle
		const $toggle = $('#primary-menu-toggle');
		const $nav = $('#site-navigation');

		$toggle.on('click', function() {
			$nav.toggleClass('is-active');
			const expanded = $nav.hasClass('is-active');
			$toggle.attr('aria-expanded', expanded);
		});

		// Close mobile nav on outside click
		$(document).on('click', function(e) {
			if (!$(e.target).closest('#site-navigation').length && !$(e.target).closest('#primary-menu-toggle').length) {
				$nav.removeClass('is-active');
				$toggle.attr('aria-expanded', 'false');
			}
		});

		// Smooth Anchor Scrolling
		$('a[href^="#"]').on('click', function(e) {
			const target = $(this.getAttribute('href'));
			if (target.length) {
				e.preventDefault();
				$('html, body').stop().animate({
					scrollTop: target.offset().top - 85
				}, 500);
			}
		});
	});

})(jQuery);
