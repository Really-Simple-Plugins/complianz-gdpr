<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( 'cmplz_cookie_blocker' ) ) {
	class cmplz_cookie_blocker {
		private static $_this;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
		}

		static function this() {
			return self::$_this;
		}

		/**
		 * Get array of scripts to block in correct format
		 * This is the base array, of which dependencies and placeholder lists also are derived
		 *
		 * @return array
		 */
		public function blocked_scripts()
		{
			$blocked_scripts = apply_filters( 'cmplz_known_script_tags', array() );
			$scripts = get_option("complianz_options_custom-scripts");
			if ( is_array($scripts) && isset($scripts['block_script']) && is_array($scripts['block_script']) ) {
				$custom_script_tags = array_filter( $scripts['block_script'], function($script) {
					return $script['enable'] == 1;
				});
				$blocked_scripts = array_merge($blocked_scripts, $custom_script_tags);
			}

			$blocked_scripts = apply_filters_deprecated( 'cmplz_known_iframe_tags', array($blocked_scripts), '6.0.0', 'cmplz_known_script_tags', 'The cmplz_known_iframe_tags filter is deprecated');

			//make sure every item has the default array structure
			foreach ($blocked_scripts as $key => $blocked_script ){
				$default = [
					'name'               => 'general',//default service name
					'enable_placeholder' => 1,
					'placeholder'        => '',
					'category'           => 'marketing',
					'urls'               => array( $blocked_script ),
					'enable'             => 1,
					'enable_dependency'  => 0,
					'dependency'         => '',
				];

				if ( !is_array($blocked_script) ){
					$service = cmplz_get_service_by_src( $blocked_script );
					$default['name'] = $service;
					$default['placeholder'] = $service;
					$blocked_scripts[$key] = $default;
				} else {
					$blocked_scripts[$key] = wp_parse_args( $blocked_script, $default);
				}
			}

			$formatted_custom_script_tags = [];
			foreach ( $blocked_scripts as $blocked_script ) {
				$blocked_script['name'] = sanitize_title($blocked_script['name']);
				if ( isset($blocked_script['urls']) ) {
					foreach ($blocked_script['urls'] as $url ) {
						$formatted_custom_script_tags[$url] = $blocked_script;
					}
				} else if (isset($blocked_script['editor'])) {
					$formatted_custom_script_tags[$blocked_script['editor']] = $blocked_script;
				}
			}
			return $formatted_custom_script_tags;
		}

		/**
		 * Get array of placeholder - placeholder_classes for non iframe blocked content
		 * @param array $blocked_scripts
		 * @return array
		 */
		public function placeholder_markers( $blocked_scripts )
		{
			$placeholder_markers = apply_filters( 'cmplz_placeholder_markers', array() );

			//current format: array('facebook' = array('class1','class2') )
			//force into new structure
			foreach ( $placeholder_markers as $name => $placeholders ) {
				foreach ( $placeholders as $class ) {
					$name = sanitize_title($name);
					$blocked_scripts[] = [
						'name' => $name,
						'placeholder' => $name,
						'placeholder_class' => $class,
						'category' => 'marketing',
						'enable_placeholder' => 1,
						'iframe' => 0,
					];
				}
			}

			//add script center data. add_script arrays aren't included in the "known_script_tags" function
			$scripts = get_option("complianz_options_custom-scripts");
			if ( is_array($scripts) && isset($scripts['add_script']) && is_array($scripts['add_script'] ) ) {
				$added_scripts = array_filter( $scripts['add_script'], function ( $script ) {
					return $script['enable'] == 1;
				} );
				if (!empty($added_scripts)) $blocked_scripts = array_merge($blocked_scripts, $added_scripts);
			}

			//filter out non-iframe and disabled placeholders.
			//add_script items do not have an iframe
			$blocked_scripts = array_filter( $blocked_scripts, function($script) {
				return $script['enable_placeholder'] == 1 && (!isset($script['iframe']) || $script['iframe'] == 0) && !empty($script['placeholder_class']);
			});
			return $blocked_scripts;
		}

		/**
		 * Get dependencies and merge with dependencies from the script center
		 * @param array $blocked_scripts
		 * @return array
		 */

		function dependencies( $blocked_scripts ) {
			//array['wait-for-this-script'] = 'script-that-should-wait';
			$dependencies = apply_filters( 'cmplz_dependencies', array() );
			$scripts = get_option( "complianz_options_custom-scripts" );
			if ( is_array( $scripts ) && isset( $scripts['block_script'] ) && is_array( $scripts['block_script'] ) ) {
				$added_scripts = array_filter( $scripts['block_script'], function ( $script ) {
					return $script['enable'] == 1;
				} );
				$blocked_scripts = array_merge($blocked_scripts, $added_scripts);
			}
			$blocked_scripts = array_filter( $blocked_scripts, function ( $script ) {
				return $script['enable_dependency'] == 1 && !empty($script['dependency']);
			} );

			$flat = array();
			foreach ( $blocked_scripts as $data ) {
				$flat = array_merge($flat, $data['dependency']);
			}
			return array_merge($dependencies, $flat);
		}

		/**
		 * Get array of whitelisted scripts, and add flattened scriptcenter whitelist
		 *
		 * @return array
		 */

		public function whitelisted_scripts(  ) {
			//whitelist our localized inline scripts
			$whitelisted_script_tags = apply_filters( 'cmplz_whitelisted_script_tags', array('user_banner_id') );
			$scripts = get_option("complianz_options_custom-scripts");
			if ( is_array($scripts) && isset($scripts['whitelist_script']) && is_array($scripts['whitelist_script']) ) {
				$custom_whitelisted_script_tags = array_filter( $scripts['whitelist_script'], function($script) {
					return $script['enable'] == 1;
				});

				//flatten array
				$flat = array();
				foreach ( $custom_whitelisted_script_tags as $data ) {
					$flat = array_merge($flat, $data['urls']);
				}

				$whitelisted_script_tags = array_merge($flat, $whitelisted_script_tags );
			}
			return $whitelisted_script_tags;
		}

		/**
		 * Apply the mixed content fixer.
		 *
		 * @since  1.0
		 *
		 * @access public
		 *
		 */

		public function filter_buffer( $buffer ) {
			if ( cmplz_is_amp() ) {
				$buffer = apply_filters( 'cmplz_cookieblocker_amp',  $buffer );
			} else {
				$buffer = $this->replace_tags( $buffer );
			}

			return $buffer;
		}

		/**
		 * Start buffering the output
		 *
		 * @since  1.0
		 *
		 * @access public
		 *
		 */

		public function start_buffer() {
			/**
			 * Don't activate the cookie blocker is AMP is active, but the AMP integration is not enabled
			 * This problem only occurs for manually included iframes, not for WP generated embeds
			 */

			if ( cmplz_is_amp_endpoint() && !cmplz_amp_integration_active() ) {
				return;
			}

			ob_start( array( $this, "filter_buffer" ) );
		}

		/**
		 * Flush the output buffer
		 *
		 * @since  1.0
		 *
		 * @access public
		 *
		 */

		public function end_buffer() {

			/**
			 * Don't activate the cookie blocker is AMP is active, but the AMP integration is not enabled
			 */

			if ( cmplz_is_amp_endpoint() && !cmplz_amp_integration_active() ) {
				return;
			}

			if ( ob_get_length() ) {
				ob_end_flush();
			}
		}

		/**
		 * Just before the page is sent to the visitor's browser, remove all tracked third party scripts
		 *
		 * @since  1.0
		 *
		 * @access public
		 *
		 */
		public function replace_tags( $output ) {
			/**
			 * Get style tags
			 *
			 * */
			$known_style_tags = apply_filters( 'cmplz_known_style_tags', array() );

            /**
             * Get script tags, including custom user scripts
             *
             * */
			$blocked_scripts = false;//get_transient('cmplz_blocked_scripts');
			if ( defined('WP_DEBUG') && WP_DEBUG ) {
				$blocked_scripts = false;
			}

			if ( !$blocked_scripts ) {
				$blocked_scripts = $this->blocked_scripts();
				set_transient('cmplz_blocked_scripts', $blocked_scripts, 5 * MINUTE_IN_SECONDS );
			}

			/**
			 * Get placeholder markers for non iframe blocked content
			 */

			$placeholder_markers = $this->placeholder_markers( $blocked_scripts );

            /**
             * Get whitelisted script tags
             *
             * */
            $whitelisted_script_tags = $this->whitelisted_scripts();

			/**
			 * Get dependencies between scripts
             *
			 * */
			$dependencies = $this->dependencies( $blocked_scripts );

			/**
			 * Get list of tags that require post scribe to be enabled on the page. Currently only for instawidget.js
			 *
			 * */

			$post_scribe_list = apply_filters( 'cmplz_post_scribe_tags', array() );


			//not meant as a "real" URL pattern, just a loose match for URL type strings.
			//edit: instagram uses ;width, so we need to allow ; as well.
			$url_pattern = '([\w.,;ß@?^=%&:()\/~+#!\-*]*?)';

			/**
			 * Handle images from third party services, e.g. google maps
			 *
			 * */
			$image_tags = apply_filters( 'cmplz_image_tags', array() );
			$image_pattern = '/<img.*?src=[\'|"](\X*?)[\'|"].*?>/s'; //matches multiline with s operater, for FB pixel
			if ( preg_match_all( $image_pattern, $output, $matches, PREG_PATTERN_ORDER )
			) {
				foreach ( $matches[1] as $key => $image_url ) {
					$total_match = $matches[0][ $key ];
					$found = cmplz_strpos_arr( $image_url, $image_tags );
					if ( $found !== false ) {
						$placeholder = cmplz_placeholder( false, $image_url );
						$service_name = cmplz_get_service_by_src( $image_url );
						$service_name = !$service_name ? 'general' :$service_name;
						$new = $total_match;
						$new = $this->add_data( $new, 'img', 'src-cmplz', $image_url );
						$new = $this->add_data( $new, 'img', 'service', $service_name );
						$new = $this->add_data( $new, 'img', 'category', 'marketing' );
						//remove lazy loading for images, as it is breaking on activation
						$new = str_replace('loading="lazy"', 'data-deferlazy="1"', $new );
						$new = $this->add_class( $new, 'img', apply_filters( 'cmplz_image_class', 'cmplz-image', $total_match, $found ) );
						$new = $this->replace_src( $new, apply_filters( 'cmplz_source_placeholder', $placeholder ) );
						$new = apply_filters('cmplz_image_html', $new, $image_url);

						if ( cmplz_use_placeholder( $image_url ) ) {
							$new = $this->add_class( $new, 'img', " cmplz-placeholder-element " );
							$new = '<div>' . $new . '</div>';
						}
						$output = str_replace( $total_match, $new, $output );
					}
				}
			}

			/**
			 * Handle styles (e.g. google fonts)
			 * fonts.google.com has currently been removed in favor of plugin recommendation
			 *
			 * */
			$style_pattern = '/<link rel=[\'|"]stylesheet[\'|"].*?href=[\'|"](\X*?)[\'|"][^>]*?>/i';
			if ( preg_match_all( $style_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
				foreach ( $matches[1] as $key => $style_url ) {
					$total_match = $matches[0][ $key ];
					if ( cmplz_strpos_arr( $style_url, $known_style_tags ) !== false ) {
						$new    = $this->replace_href( $total_match );
						$service_name = cmplz_get_service_by_src( $style_url );
						$new    = $this->add_data( $new, 'link', 'service', $service_name );
						$new    = $this->add_data( $new, 'link', 'category', 'marketing' );
						$output = str_replace( $total_match, $new, $output );
					}
				}
			}

			/**
			 * Handle iframes from third parties
			 *
			 * */
			//the iframes URL pattern allows for a space, which may be included in a Google Maps embed.
			$iframe_pattern = '/<(iframe)[^>].*?src=[\'"](.*?)[\'"].*?>.*?<\/iframe>/is';
			if ( preg_match_all( $iframe_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
				foreach ( $matches[0] as $key => $total_match ) {
					$iframe_src = $matches[2][ $key ];
					if ( ( $tag_key = cmplz_strpos_arr($iframe_src, array_keys($blocked_scripts)) ) !== false ) {
                        $tag = $blocked_scripts[$tag_key];
						if ($tag['category']==='functional') {
							continue;
						}

						$is_video = $this->is_video( $iframe_src );
						$service_name = sanitize_title($tag['name']);
						$new         = $total_match;
						$new         = preg_replace( '~<iframe\\s~i', '<iframe data-src-cmplz="' . $iframe_src . '" ', $new , 1 ); // make sure we replace it only once

						//remove lazy loading for iframes, as it is breaking on activation
						$new = str_replace('loading="lazy"', 'data-deferlazy="1"', $new );
                        //check if we can skip blocking this array if a specific string is included
                        if ( cmplz_strpos_arr($total_match, $whitelisted_script_tags) ) continue;
						//we insert video/no-video class for specific video styling
						$video_class = $is_video ? 'cmplz-video' : 'cmplz-no-video';
						$video_class = apply_filters( 'cmplz_video_class', $video_class );

						$new = $this->replace_src( $new, apply_filters( 'cmplz_source_placeholder', 'about:blank' ) );
						$new = $this->add_class( $new, 'iframe', "cmplz-iframe cmplz-iframe-styles $video_class " );
						$new = $this->add_data( $new, 'iframe', 'service', $service_name );
						$new = $this->add_data( $new, 'iframe', 'category', $tag['category'] );

						if ( cmplz_use_placeholder( $iframe_src ) ) {
							$placeholder = cmplz_placeholder($tag['placeholder'], $iframe_src );
							$new = $this->add_class( $new, 'iframe', "cmplz-placeholder-element" );
							$new = $this->add_data( $new, 'iframe', 'placeholder-image', $placeholder );
							//allow for integrations to override html
							$new = apply_filters( 'cmplz_iframe_html', $new );

							//make sure there is a parent element which contains this iframe only, to attach the placeholder to
							if ( ! $is_video
							     && ! $this->no_div( $iframe_src )
							) {
								$new = '<div>' . $new . '</div>';
							}
						}

						$output = str_replace( $total_match, $new, $output );
					}
				}
			}

			/**
			 * set non iframe placeholders
			 *
			 * */
			if ( cmplz_use_placeholder() ) {
				foreach ( $placeholder_markers as $placeholder ) {
					//placeholder class can be comma separated list e.g. facebook service integration
					$classes = array_map('trim', explode(',',$placeholder['placeholder_class']) );
					foreach ( $classes as $placeholder_class ) {
						$placeholder_pattern = '/<(a|section|div|blockquote|twitter-widget)*[^>]*class=[\'" ]*[^>]*(' . $placeholder_class . ')[\'" ].*?>/is';
						if ( preg_match_all( $placeholder_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
							foreach ( $matches[0] as $key => $html_match ) {
								$el = $matches[1][ $key ];
								if ( ! empty( $el ) ) {
									$type        = $placeholder['placeholder'];
									$new_html    = $this->add_data( $html_match, $el, 'placeholder-image', cmplz_placeholder( $type, $placeholder_class ) );
									$new_html    = $this->add_data( $new_html, $el, 'category', $placeholder['category'] );
									$new_html    = $this->add_data( $new_html, $el, 'service', $placeholder['name'] );
									$new_html    = $this->add_class( $new_html, $el, "cmplz-placeholder-element" );
									$output      = str_replace( $html_match, $new_html, $output );
								}
							}
						}
					}

				}
			}

			/**
			 * Handle scripts from third parties
			 *
			 * */
			$script_pattern = '/(<script.*?>)(\X*?)<\/script>/is';
			$index          = 0;
			if ( preg_match_all( $script_pattern, $output, $matches, PREG_PATTERN_ORDER ) ) {
				foreach ( $matches[1] as $key => $script_open ) {
					//we don't block scripts with the functional data attribute
					if ( strpos( $script_open, 'data-category="functional"' ) !== false ) {
						continue;
					}

					//exclude ld+json
					if ( strpos( $script_open, 'application/ld+json' ) !== false ) {
						continue;
					}

                    //check if we can skip blocking this array if a specific string is included
                    $total_match = $matches[0][ $key ];
                    $content     = $matches[2][ $key ];
					if ( cmplz_strpos_arr($total_match, $whitelisted_script_tags) ) {
						continue;
					}

                    //if there is inline script here, it has some content
					if ( ! empty( $content ) )
					{
						if ( strpos( $content, 'avia_preview' ) !== false ) {
							continue;
						}

						$found = cmplz_strpos_arr( $content, array_keys($blocked_scripts) );
						if ( $found !== false ) {

                            $match = $blocked_scripts[$found];
							$service_name = sanitize_title($match['name']);
							$new = $total_match;
							$category = apply_filters_deprecated( 'cmplz_script_class', array($match['category'], $total_match, $found), '6.0.0', 'cmplz_service_category', 'The cmplz_script_class filter has been deprecated since 6.0');
							$category = apply_filters('cmplz_service_category', $category, $total_match, $found);

							//skip if functional
							if ( $category === 'functional' ) {
								continue;
							}

							$new = $this->add_data( $new, 'script', 'category', $category );
							$new = $this->add_data( $new, 'script', 'service', $service_name );
							$new = $this->set_javascript_to_plain( $new );
							$waitfor = cmplz_strpos_arr( $content, $dependencies );
							if ( $waitfor !== false ) {
								$new = $this->add_data( $new, 'script', 'waitfor', $waitfor );
							}

							$output = str_replace( $total_match, $new, $output );
						}
					}

					//when script contains src
					$script_src_pattern = '/<script [^>]*?src=[\'"]' . $url_pattern . '[\'"].*?>/is';
					if ( preg_match_all( $script_src_pattern, $total_match, $src_matches, PREG_PATTERN_ORDER ) ) {
						foreach ( $src_matches[1] as $src_key => $script_src ) {
							$script_src = $src_matches[1][ $src_key ];
							$found = cmplz_strpos_arr( $script_src, array_keys($blocked_scripts) );
							if ( $found !== false ) {
                                $match = $blocked_scripts[$found];
                                $new = $total_match;
								$service_name = sanitize_title($match['name']);
								$category = apply_filters_deprecated( 'cmplz_script_class', array($match['category'], $total_match, $found), '6.0.0', 'cmplz_service_category', 'The cmplz_script_class filter has been deprecated since 6.0');
	                            $new = $this->add_data( $new, 'script', 'category', apply_filters('cmplz_service_category', $category, $total_match, $found) );
								$new = $this->add_data( $new, 'script', 'service', $service_name );
								//native scripts don't have to be blocked
								if ( strpos( $new, 'data-category="functional"' ) === false
								) {
									$new = $this->set_javascript_to_plain( $new );
									if ( cmplz_strpos_arr( $found, $post_scribe_list )
									) {
										//will be to late for the first page load, but will enable post scribe on next page load
										if (!get_option('cmplz_post_scribe_required')) {
											update_option('cmplz_post_scribe_required', true);
										}
										$index ++;
										$new = $this->add_data( $new, 'script', 'post_scribe_id', 'cmplz-ps-' . $index );
										$new .= '<div class="cmplz-blocked-content-container"><div class="cmplz-blocked-content-notice cmplz-accept-marketing">'
										        . apply_filters( 'cmplz_accept_cookies_blocked_content', cmplz_get_value( 'blocked_content_text' ) )
										        . '</div><div id="cmplz-ps-' . $index . '"><img src="' . cmplz_placeholder( 'div' ) . '"></div></div>';

									}

									//maybe add dependency
									$waitfor = cmplz_strpos_arr( $script_src, $dependencies );
									if ( $waitfor !== false ) {
										$new = $this->add_data( $new, 'script', 'waitfor', $waitfor );
									}
								}

								$output = str_replace( $total_match, $new, $output );
							}
						}
					}
				}
			}

			//add a marker so we can recognize if this function is active on the front-end
			$output = str_replace( "<body ", '<body data-cmplz=1 ', $output );


			return apply_filters('cmplz_cookie_blocker_output', $output);
		}

		/**
		 * Set the javascript attribute of a script element to plain
		 *
		 * @param string $script
		 *
		 * @return string
		 */

		private function set_javascript_to_plain( $script ) {
			//check if it's already set to plain
			if ( strpos( $script, 'text/plain')!== false ) return $script;

			$pattern = '/<script[^>].*?\K(type=[\'|\"]text\/javascript[\'|\"])(?=.*>)/i';
			preg_match( $pattern, $script, $matches );
			if ( $matches ) {
				$script = preg_replace( $pattern, 'type="text/plain"', $script,
					1 );
			} else {
				$pos = strpos( $script, "<script" );
				if ( $pos !== false ) {
					$script = substr_replace( $script,
						'<script type="text/plain"', $pos,
						strlen( "<script" ) );
				}
			}

			return $script;
		}

		private function remove_src( $script ) {
			$pattern
				    = '/src=[\'"](http:\/\/|https:\/\/)([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-]?)[\'"]/i';
			$script = preg_replace( $pattern, '', $script );

			return $script;
		}

		/**
		 * replace the src attribute with a placeholder of choice
		 *
		 * @param string $script
		 * @param string $new_src
		 *
		 * @return string
		 */

		private function replace_src( $script, $new_src ) {
			$pattern
				     = '/src=[\'"](http:\/\/|https:\/\/|\/\/)([\s\w.,@!?^=%&:\/~+#-;]*[\w@!?^=%&\/~+#-;]?)[\'"]/i';
			$new_src = ' src="' . $new_src . '" ';
			preg_match( $pattern, $script, $matches );
			$script = preg_replace( $pattern, $new_src, $script );

			return $script;
		}

		/**
		 * replace the href attribute with a data-href attribute
		 *
		 * @param string $link
		 *
		 * @return string
		 */

		private function replace_href( $link ) {
			return str_replace( 'href=', 'href="#" data-href=', $link );
		}

		/**
		 * Add a class to an HTML element
		 *
		 * @param string $html
		 * @param string $el
		 * @param string $class
		 *
		 * @return string
		 */

		public function add_class( $html, $el, $class ) {
			$classes = array_filter( explode(' ', $class) );
			preg_match( '/<' . $el . '[^\>]*[^\>\S]+\K(class=")(.*)"/i', $html, $matches );
			if ( $matches ) {
				foreach ($classes as $class){
					//check if class is already added
					if (strpos($matches[2], $class) === false && strlen(trim($class))>0) {
						$html = preg_replace( '/<' . $el . '[^\>]*[^\>\S]+\K(class=")/i', 'class="' . esc_attr($class) . ' ', $html, 1 );
					}
				}

			} else {
				$pos = strpos( $html, "<$el" );
				if ( $pos !== false ) {
					$html = substr_replace( $html,
						'<' . $el . ' class="' . esc_attr($class) . '"', $pos,
						strlen( "<$el" ) );
				}
			}

			return $html;
		}

		/**
		 * Add a data attribute to an html element
		 *
		 * @param string $html
		 * @param string $el
		 * @param string $id
		 * @param string $content
		 *
		 * @return string $html
		 */

		public function add_data( $html, $el, $id, $content ) {
			$content = esc_attr( $content );
			$id      = esc_attr( $id );

			//don't add if it's already included
			if ( strpos($html, 'data-'.$id) !== false ) {
				return $html;
			}

			$pos = strpos( $html, "<$el" );
			if ( $pos !== false ) {
				$html = substr_replace( $html,
					'<' . $el . ' data-' . $id . '="' . $content . '"', $pos,
					strlen( "<$el" ) );
			}

			return $html;
		}

		/**
		 * Check if this iframe source is a video
		 *
		 * @param $iframe_src
		 *
		 * @return bool
		 */

		private function is_video( $iframe_src ) {
			if ( strpos( $iframe_src, 'dailymotion' ) !== false
			     || strpos( $iframe_src, 'youtube' ) !== false
			     || strpos( $iframe_src, 'vimeo' ) !== false
			) {
				return true;
			}

			return false;
		}

		/**
		 * Check if this iframe source is soundcloud
		 *
		 * @param $iframe_src
		 *
		 * @return bool
		 */

		private function no_div( $iframe_src ) {
			if ( strpos( $iframe_src, 'soundcloud' ) !== false ) {
				return true;
			}

			return false;
		}

	}
}



