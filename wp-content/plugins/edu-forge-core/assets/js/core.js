/**
 * EduForge Core JavaScript
 */
(function($) {
	'use strict';

	window.EduForge = {
		toast: function(message, isError) {
			$('.eduforge-toast').remove();
			const $toast = $('<div class="eduforge-toast"></div>').text(message);
			if (isError) {
				$toast.css('background', '#ef4444');
			}
			$('body').append($toast);
			setTimeout(function() {
				$toast.fadeOut(300, function() { $(this).remove(); });
			}, 4000);
		}
	};

	$(document).ready(function() {
		// Event registration AJAX
		$(document).on('click', '.eduforge-register-event-btn', function(e) {
			e.preventDefault();
			const $btn = $(this);
			const eventId = $btn.data('event-id');

			if (!eduforgeVars.isLoggedIn) {
				window.location.href = '/wp-login.php?redirect_to=' + encodeURIComponent(window.location.href);
				return;
			}

			$btn.prop('disabled', true).text(eduforgeVars.i18n.loading);

			$.ajax({
				url: eduforgeVars.ajaxurl,
				type: 'POST',
				data: {
					action: 'eduforge_register_event',
					nonce: eduforgeVars.nonce,
					event_id: eventId
				},
				success: function(res) {
					if (res.success) {
						EduForge.toast(res.data.message);
						$btn.text('Registered ✓').css('background', '#10b981');
					} else {
						EduForge.toast(res.data.message || eduforgeVars.i18n.errorOccurred, true);
						$btn.prop('disabled', false).text('Register Now');
					}
				},
				error: function() {
					EduForge.toast(eduforgeVars.i18n.errorOccurred, true);
					$btn.prop('disabled', false).text('Register Now');
				}
			});
		});
	});

})(jQuery);
