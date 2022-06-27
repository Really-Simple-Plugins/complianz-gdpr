<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

/**
 * Add notice for Burst Statistics
 *
 */

function cmplz_burst_statistics_integration_show_compile_statistics_notice() {
	cmplz_sidebar_notice( __("Burst Statistics will be configured automatically.", 'complianz-gdpr' ) );
}
add_action( 'cmplz_notice_compile_statistics', 'cmplz_burst_statistics_integration_show_compile_statistics_notice' );

function cmplz_burst_statistics_activate_burst() {
	ob_start();
	if ( burst_get_value('enable_cookieless_tracking') ) {
		// if cookieless tracking is enabled, we need to add a listener to the consent change
		// to turn off cookieless tracking and set a cookie
		?>
		<script>
			document.addEventListener("burst_before_track_hit", function(burstData) {
				if ( cmplz_has_consent('statistics') ) {
					window.burst_enable_cookieless_tracking = 0;
				}
			});
			document.addEventListener("cmplz_status_change", function (){
				if ( cmplz_has_consent('statistics') ) {
					window.burst_enable_cookieless_tracking = 0;
					let event = new CustomEvent('burst_enable_cookies');
					document.dispatchEvent( event );
				}
			});
		</script>
		<?php
	} else {
		?>
		<script>
			document.addEventListener("cmplz_cookie_warning_loaded", function(consentData) {
				let region = consentData.detail;
				if ( region !== 'uk' ) {
					let scriptElements = document.querySelectorAll('script[data-service="burst"]');
					scriptElements.forEach(obj => {
						if ( obj.classList.contains('cmplz-activated') || obj.getAttribute('type') === 'text/javascript' ) {
							return;
						}
						obj.classList.add( 'cmplz-activated' );
						let src = obj.getAttribute('src');
						if ( src ) {
							obj.setAttribute('type', 'text/javascript');
							cmplz_run_script(src, 'statistics', 'src');
							obj.parentNode.removeChild(obj);
						}
					});
				}

			});
			document.addEventListener("cmplz_run_after_all_scripts", cmplz_burst_fire_domContentLoadedEvent);
			function cmplz_burst_fire_domContentLoadedEvent() {
				let event = new CustomEvent('burst_fire_hit');
				document.dispatchEvent( event );
			}
		</script>
		<?php
	}


	$script = ob_get_clean();
	$script = str_replace(array('<script>', '</script>'), '', $script);
	wp_add_inline_script( 'cmplz-cookiebanner', $script);
}
add_action( 'wp_enqueue_scripts', 'cmplz_burst_statistics_activate_burst',PHP_INT_MAX );

/**
 * If checked for privacy friendly, and the user select "none of the above", return true, as it's burst.
 * @param $is_privacy_friendly
 *
 * @return bool
 */
function cmplz_burst_statistics_privacy_friendly($is_privacy_friendly){
	$statistics = cmplz_get_value( 'compile_statistics' );
	if ($statistics==='yes') {
		$is_privacy_friendly = true;
	}
	return $is_privacy_friendly;
}
add_filter('cmplz_cookie_warning_required_stats', 'cmplz_burst_statistics_privacy_friendly');
add_filter('cmplz_statistics_privacy_friendly', 'cmplz_burst_statistics_privacy_friendly');


/**
 * Add a script to the blocked list
 * @param array $tags
 *
 * @return array
 */
function cmplz_burst_script( $tags ) {
	//if cookieless tracking enabled, do not block.
	if ( burst_get_value('enable_cookieless_tracking') ) {
		return $tags;
	}

	$tags[] = array(
			'name' => 'burst',
			'category' => 'statistics',
			'urls' => array(
					'assets/js/burst.js',
					'assets/js/burst.min.js',
			),
			'enable_placeholder' => '0',
			'enable_dependency' => '0',
	);

	return $tags;
}
add_filter( 'cmplz_known_script_tags', 'cmplz_burst_script' );
