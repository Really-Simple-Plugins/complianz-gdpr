<?php
defined( 'ABSPATH' ) or die();

function cmplz_elementor_initDomContentLoaded() {
	if ( cmplz_uses_thirdparty('youtube') ) {
		ob_start();
		?>
		<script>
			document.addEventListener("cmplz_enable_category", function(consentData) {
				var category = consentData.detail.category;
				if (category==='marketing') {
					var blockedContentContainers = [];
					document.querySelectorAll('[data-cmplz-elementor-settings]').forEach(obj => {
						if (obj.classList.contains('cmplz-activated')) return;
						obj.classList.add('cmplz-activated');
						obj.setAttribute('data-settings', obj.getAttribute('data-cmplz-elementor-settings'));
						blockedContentContainers.push(obj);
					});

					for (var key in blockedContentContainers) {
						if (blockedContentContainers.hasOwnProperty(key) && blockedContentContainers[key] !== undefined) {
							let blockedContentContainer = blockedContentContainers[key];
							if (elementorFrontend.elementsHandler) {
								elementorFrontend.elementsHandler.runReadyTrigger(blockedContentContainer)
							}
							var cssIndex = blockedContentContainer.getAttribute('data-placeholder_class_index');
							blockedContentContainer.classList.remove('cmplz-blocked-content-container');
							blockedContentContainer.classList.remove('cmplz-placeholder-' + cssIndex);
						}
					}
				}
			});
		</script>
		<?php
		$script = ob_get_clean();
		$script = str_replace(array('<script>', '</script>'), '', $script);
		wp_add_inline_script( 'cmplz-cookiebanner', $script);
	}
}
add_action( 'wp_enqueue_scripts', 'cmplz_elementor_initDomContentLoaded',PHP_INT_MAX );

/**
 * Filter cookie blocker output
 */
function cmplz_elementor_cookieblocker( $output ){
	if ( cmplz_uses_thirdparty('youtube') ) {
		$iframe_pattern = '/elementor-widget-video.*?data-settings=.*?youtube_url.*?&quot;:&quot;(.*?)&quot;/is';
		if ( preg_match_all( $iframe_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
			foreach ( $matches[0] as $key => $total_match ) {
				$placeholder = '';
				if ( cmplz_use_placeholder('youtube') && isset($matches[1][$key]) ) {
					$youtube_url = $matches[1][$key];
					$placeholder = 'data-placeholder-image="'.cmplz_placeholder( false, stripcslashes($youtube_url) ).'" ';
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
