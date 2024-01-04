<?php
defined( 'ABSPATH' ) or die();

function cmplz_yotuwp_cookieblocker( $output ){
	if ( cmplz_uses_thirdparty('youtube') ) {
		$iframe_pattern = '/div class="yotu-playlist.*?<a.*data-videoid="(.*?)"/is';
		if ( preg_match_all( $iframe_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
			foreach ( $matches[0] as $key => $total_match ) {
				$placeholder = '';

				if ( cmplz_use_placeholder('youtube') && isset($matches[1][0]) ) {
					$youtube_id = $matches[1][0];
					$youtube_url = "https://www.youtube.com/watch?v=$youtube_id";
					$placeholder = 'data-placeholder-image="'.cmplz_placeholder( false, $youtube_url).'" ';
				}

				$new_match = str_replace('yotu-playlist', 'cmplz-yotu-playlist cmplz-placeholder-element ', $total_match);
				$new_match = str_replace('data-page=', $placeholder.' data-service="youtube" data-page=', $new_match);
				$output = str_replace($total_match, $new_match, $output);
			}
		}
	}
	return $output;
}
add_filter('cmplz_cookie_blocker_output', 'cmplz_yotuwp_cookieblocker');

/**
 * Add some css
 * @return void
 */

function cmplz_yotu_css() {
	if ( cmplz_uses_thirdparty('youtube') ) { ?>
		.cmplz-yotu-playlist  {
		max-height:400px;
		}
	<?php }
}
add_action( 'cmplz_banner_css', 'cmplz_yotu_css' );

/**
 * Add script to handle placeholder and reload on consent
 * @return void
 */
function cmplz_yotuwp_handle_youtube() {
	if ( cmplz_uses_thirdparty('youtube') || 1==1) {
		ob_start();
		?>
		<script>
			function cmplz_maybe_trigger_yotuwp(){
				//if not defined, wait a bit
				if (typeof yotuwp === 'undefined') {
					setTimeout(cmplz_maybe_trigger_yotuwp, 500);
				} else {
					yotuwp.init();
				}
			}
			document.addEventListener("cmplz_enable_category", function(consentData) {
				var category = consentData.detail.category;
				var service = consentData.detail.service;
				let selectorVideo = '.cmplz-yotu-playlist';
				if (category!=='marketing' && service !== 'youtube' ) {
					return;
				}
				dispatchEvent(new Event('load'));
				document.querySelectorAll(selectorVideo).forEach(obj => {
					obj.classList.remove('cmplz-yotu-playlist');
					obj.classList.remove('cmplz-blocked-content-container');
					obj.classList.add('yotu-playlist');
					let index = obj.getAttribute('data-placeholder_class_index');
					obj.classList.remove('cmplz-placeholder-'+index);
					cmplz_maybe_trigger_yotuwp();
				});
			});
		</script>
		<?php
		$script = ob_get_clean();
		$script = str_replace(array('<script>', '</script>'), '', $script);
		wp_add_inline_script( 'cmplz-cookiebanner', $script);
	}
}
add_action( 'wp_enqueue_scripts', 'cmplz_yotuwp_handle_youtube',PHP_INT_MAX );
function cmplz_yotuwp_whitelist($tags){
	$tags[] = 'cmplz_maybe_trigger_yotuwp';

	return $tags;
}
add_filter( 'cmplz_whitelisted_script_tags', 'cmplz_yotuwp_whitelist');
add_filter( 'cmplz_known_script_tags', 'cmplz_yotuwp_iframetags' );
function cmplz_yotuwp_iframetags( $tags ) {
	$tags[] = array(
		'name' => 'youtube',
		'placeholder' => 'youtube',
		'category' => 'marketing',
		'urls' => array(
			'yotuwp-easy-youtube-embed',
			'yotuwp-pro',
			'yotuwp.data',
			'var yotujs',
		),
	);
	return $tags;
}

/**
 * Add services to the list of detected items, so it will get set as default, and will be added to the notice about it
 *
 * @param $services
 *
 * @return array
 */

function cmplz_yotuwp_detected_services( $services ) {
	if ( ! in_array( 'youtube', $services ) ) {
		$services[] = 'youtube';
	}
	return $services;
}
add_filter( 'cmplz_detected_services', 'cmplz_yotuwp_detected_services' );
