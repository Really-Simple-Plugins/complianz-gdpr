<?php
defined( 'ABSPATH' ) or die();

function cmplz_elementor_initDomContentLoaded() {
	if ( cmplz_uses_thirdparty('youtube') ) {
		?>
		<script>
			jQuery(document).ready(function ($) {
				$(document).on("cmplzRunAfterAllScripts", cmplz_elementor_fire_initOnReadyComponents);
				function cmplz_elementor_fire_initOnReadyComponents() {
					$('[data-cmplz-elementor-settings]').each(function (i, obj) {
						if ( $(this).hasClass('cmplz-activated') ) return;
						$(this).addClass('cmplz-activated' );
						$(this).data('settings', $(this).data('cmplz-elementor-settings'));

						var blockedContentContainer = $(this);
						blockedContentContainer.animate({"background-image": "url('')"}, 400, function () {
							//remove the added classes
							var cssIndex = blockedContentContainer.data('placeholderClassIndex');
							blockedContentContainer.removeClass('cmplz-blocked-content-container');
							blockedContentContainer.removeClass('cmplz-placeholder-' + cssIndex);
						});
					});
				}

				document.addEventListener('cmplzStatusChange', function (e) {
					if (e.detail.category === 'marketing') {
						//if ( cmplzElementor ) window.elementorFrontend.init();
						if ( $('[data-cmplz-elementor-settings]').length ) window.location.reload();					}
				});

			})
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'cmplz_elementor_initDomContentLoaded' );

/**
 * Filter cookie blocker output
 */
function cmplz_elementor_cookieblocker( $output ){
	if ( cmplz_uses_thirdparty('youtube') ) {
		$iframe_pattern = '/elementor-widget-video.*?data-settings=.*?youtube_url.*?&quot;:&quot;(.*?)&quot;/is';
		if ( preg_match_all( $iframe_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
			foreach ( $matches[0] as $key => $total_match ) {
				$placeholder = '';
				if ( cmplz_use_placeholder('youtube') && isset($matches[1][0]) ) {
					$youtube_url = $matches[1][0];
					$placeholder = 'data-placeholder-image="'.cmplz_placeholder( false, $youtube_url ).'" ';
				}

				$new_match = str_replace('data-settings', $placeholder.'data-cmplz-elementor-settings', $total_match);
				$new_match = str_replace('elementor-widget-video', 'elementor-widget-video cmplz-placeholder-element', $new_match);
				$output = str_replace($total_match, $new_match, $output);
			}

		}
	}

	return $output;
}
add_filter('cmplz_cookie_blocker_output', 'cmplz_elementor_cookieblocker');
