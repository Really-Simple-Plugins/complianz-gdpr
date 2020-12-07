<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( ! class_exists( "cmplz_document" ) ) {
	class cmplz_document {
		private static $_this;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			$this->init();

		}

		static function this() {
			return self::$_this;
		}

		/**
		 * Get list of documents, based on selected regions
		 * @return array
		 */

		public function get_availabe_documents(){
			$documents = COMPLIANZ::$config->pages;
			$output = array();
			foreach( $documents as $region => $region_documents ){
				foreach( $region_documents as $type => $data ){
					if (!in_array( $type, $output )) {
						$output[] = $type;
					}
				}
			}

			return $output;
		}

		/**
		 * Get list of documents from the field list
		 * @return array
		 */

		public function get_document_types(){
			$fields = COMPLIANZ::$config->fields();
			$documents = array();
			foreach( $fields as $fieldname => $field ){
				if ( isset($field['type']) && $field['type'] === 'document') {
					$documents[] = $fieldname;
				}
			}

			return $documents;
		}

		/**
		 * If a document is loaded with the autoredirect parameter, we redirect automatically
		 */

		public function maybe_autoredirect() {
			//if the autoredirect parameter is used, we look for the region of the passed type, and if necessary redirect to the redirect region
			if ( isset( $_GET['cmplz_region_redirect'] )
			     && isset( $_GET['region'] )
			) {
				//get region from current page.
				global $post;
				$type = false;

				if ( $post ) {
					if ( preg_match( $this->get_shortcode_pattern( "gutenberg" ),
						$post->post_content, $matches )
					) {
						$type = $matches[1];
					} elseif ( preg_match( $this->get_shortcode_pattern( "classic" ),
						$post->post_content, $matches )
					) {
						$type = $matches[1];
					}
				}

				if ( !$type ){
					$slug = esc_url_raw($_SERVER['REQUEST_URI']);
					$documents = $this->get_availabe_documents();
					foreach($documents as $doc_type){
						if (strpos($slug, $doc_type)!==FALSE){
							$type = $doc_type;
						}
					}
				}

				$current_region = false;
				if ( substr( $type, - 3, 1 ) === '-' ) {
					$current_region = cmplz_get_region_from_legacy_type($type);
					$type = str_replace("-$current_region", '', $type);
				} elseif (isset($matches[2])) {
					$current_region = $matches[2];
				}

				if ($current_region) $type = str_replace("-$current_region", '', $type);
				$new_region = sanitize_title( $_GET['region'] );

				//if region is "other", get the default region
				if ( $new_region === 'other') {
					$new_region = COMPLIANZ::$company->get_default_region();
				}

				if ( ! isset( COMPLIANZ::$config->pages[ $new_region ][ $type ] ) ) {
					return;
				}

				if ( array_key_exists( $new_region, cmplz_get_regions() )
				     && $current_region !== $new_region
				) {
					//get the URL of the new document
					$new_url = COMPLIANZ::$document->get_permalink( $type, $new_region );
					wp_redirect( $new_url );
					exit;
				}
			}
		}

		/**
		 * Enqueue assets
		 */

		public function enqueue_assets() {
			if ( $this->is_complianz_page() ) {
				$min      = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? ''
					: '.min';
				$load_css = cmplz_get_value( 'use_document_css' );
				if ( $load_css ) {
					wp_register_style( 'cmplz-document',
						cmplz_url . "assets/css/document$min.css", false,
						cmplz_version );
					wp_enqueue_style( 'cmplz-document' );
				} else {
                    wp_register_style( 'cmplz-document-grid',
                        cmplz_url . "assets/css/document-grid$min.css", false,
                        cmplz_version );
                    wp_enqueue_style( 'cmplz-document-grid' );
                }

				add_action( 'wp_head', array( $this, 'inline_styles' ), 100 );
			}

		}

		/**
		 * Get custom CSS for documents
		 *
		 * */

		public function inline_styles() {

			//basic color style for revoke button
			$custom_css = '';
			if ( cmplz_get_value( 'use_custom_document_css' ) ) {
				$custom_css .= cmplz_get_value( 'custom_document_css' );
			}

			$custom_css = apply_filters( 'cmplz_custom_document_css',
				$custom_css );
			if ( empty( $custom_css ) ) {
				return;
			}

			echo '<style>' . $custom_css . '</style>';
		}

		/**
		 * Check if the page is public
		 *
		 * @param string $type
		 * @param string $region
		 *
		 * @return bool
		 */

		public function is_public_page( $type, $region ) {
			if ( ! isset( COMPLIANZ::$config->pages[ $region ][ $type ] ) ) {
				return false;
			}

			if ( isset( COMPLIANZ::$config->pages[ $region ][ $type ]['public'] )
			     && COMPLIANZ::$config->pages[ $region ][ $type ]['public']
			) {
				return true;
			}

			return false;
		}

		/**
		 * period in seconds the wizard wasn't updated
		 *
		 * @param int $period
		 *
		 * @return bool not_updated_in
		 * */

		public function not_updated_in( $period ) {
			//if the wizard is never completed, we don't want any update warnings.
			if ( ! get_option( 'cmplz_wizard_completed_once' ) ) {
				return false;
			}

			$date = get_option( 'cmplz_documents_update_date' );
			if ( ! $date ) {
				return false;
			}

			$time_passed = time() - $date;
			if ( $time_passed > $period ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if a page is required. If no condition is set, return true.
		 * condition is "AND", all conditions need to be met.
		 *
		 * @param array|string $page
		 * @param string       $region
		 *
		 * @return bool
		 */

		public function page_required( $page, $region ) {
			if ( ! is_array( $page ) ) {
				if ( ! isset( COMPLIANZ::$config->pages[ $region ][ $page ] ) ) {
					return false;
				}

				$page = COMPLIANZ::$config->pages[ $region ][ $page ];
			}

			//if it's not public, it's not required
			if ( isset( $page['public'] ) && $page['public'] == false ) {
				return false;
			}

			//if there's no condition, we set it as required
			if ( ! isset( $page['condition'] ) ) {
				return true;
			}

			if ( isset( $page['condition'] ) ) {
				$conditions    = $page['condition'];
				$condition_met = true;
				foreach (
					$conditions as $condition_question => $condition_answer
				) {

					$value  = cmplz_get_value( $condition_question, false,
						false, $use_default = false );
					$invert = false;
					if ( ! is_array( $condition_answer )
					     && strpos( $condition_answer, 'NOT ' ) !== false
					) {
						$condition_answer = str_replace( 'NOT ', '',
							$condition_answer );
						$invert           = true;
					}

					$condition_answer = is_array( $condition_answer )
						? $condition_answer : array( $condition_answer );
					foreach ( $condition_answer as $answer_item ) {
						if ( is_array( $value ) ) {
							if ( ! isset( $value[ $answer_item ] )
							     || ! $value[ $answer_item ]
							) {
								$condition_met = false;
							} else {
								$condition_met = true;
							}

						} else {
							$condition_met = ( $value == $answer_item );
						}

						//if one condition is met, we break with this condition, so it will return true.
						if ( $condition_met ) {
							break;
						}

					}

					//if one condition is not met, we break with this condition, so it will return false.
					if ( ! $condition_met ) {
						break;
					}

				}

				$condition_met = $invert ? ! $condition_met : $condition_met;
				return $condition_met;
			}

			return false;

		}

		/**
		 * Check if an element should be inserted. AND implementation s
		 *
		 *
		 * */

		public function insert_element( $element, $post_id ) {

			if ( $this->callback_condition_applies( $element )
			     && $this->condition_applies( $element, $post_id )
			) {
				return true;
			}

			return false;

		}

		/**
		 * @param $element
		 *
		 * @return bool
		 */

		public function callback_condition_applies( $element ) {

			if ( isset( $element['callback_condition'] ) ) {
				$conditions = is_array( $element['callback_condition'] )
					? $element['callback_condition']
					: array( $element['callback_condition'] );
				foreach ( $conditions as $func ) {
					$invert = false;
					if ( strpos( $func, 'NOT ' ) !== false ) {
						$invert = true;
						$func   = str_replace( 'NOT ', '', $func );
					}

					if ( ! function_exists( $func ) ) {
						break;
					}

					$show_field = $func();

					if ( $invert ) {
						$show_field = ! $show_field;
					}
					if ( ! $show_field ) {
						return false;
					}
				}
			}

			return true;
		}

		/**
		 * Check if the passed condition applies
		 *
		 * @param array $element
		 * @param int   $post_id
		 *
		 * @return bool
		 */

		public function condition_applies( $element, $post_id ) {
			if ( isset( $element['condition'] ) ) {
				$fields        = COMPLIANZ::$config->fields;
				$condition_met = true;
				$invert        = false;

				foreach (
					$element['condition'] as $question => $condition_answer
				) {
					if ( $condition_answer === 'loop' ) {
						continue;
					}
					if ( ! isset( $fields[ $question ]['type'] ) ) {
						return false;
					}
					$type  = $fields[ $question ]['type'];
					$value = cmplz_get_value( $question, $post_id );
					if ($condition_answer === 'NOT EMPTY') {
						if ( strlen( $value )===0 ) {
							return false;
						} else {
							return true;
						}
					}

					if ( strpos( $condition_answer, 'NOT ' ) !== false ) {
						$condition_answer = str_replace( 'NOT ', '', $condition_answer );
						$invert           = true;
					}

					if ( $type == 'multicheckbox' ) {
						if ( ! isset( $value[ $condition_answer ] ) || ! $value[ $condition_answer ] )
						{
							$current_condition_met = false;
						} else {
							$current_condition_met = true;
						}

					} else {
						$current_condition_met = $value == $condition_answer ;
					}
					$current_condition_met = $invert ? !$current_condition_met : $current_condition_met;
					$condition_met = $condition_met && $current_condition_met;
				}

				return $condition_met;

			}

			return true;
		}


		/**
		 * Check if this element should loop through dynamic multiple values
		 *
		 * @param array $element
		 *
		 * @return bool
		 * */

		public function is_loop_element( $element ) {
			if ( isset( $element['condition'] ) ) {
				foreach (
					$element['condition'] as $question => $condition_answer
				) {
					if ( $condition_answer === 'loop' ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Build a legal document by type
		 *
		 * @param string   $type
		 * @param          $region
		 * @param bool|int $post_id
		 *
		 * @return string
		 */

		public function get_document_html(
			$type, $region = false, $post_id = false
		) {
			//legacy, if region is not passed, we get it from the type string
			if ( ! $region ) {
				$region = cmplz_get_region_from_legacy_type( $type );
				$type   = str_replace( '-' . $region, '', $type );
			}

			if ( ! cmplz_has_region( $region )
			     || ! isset( COMPLIANZ::$config->pages[ $region ][ $type ] )
			) {
				return sprintf( __( 'Region %s not activated for %s.',
					'complianz-gdpr' ), strtoupper( $region ), $type );
			}
			$elements         = COMPLIANZ::$config->pages[ $region ][ $type ]["document_elements"];
			$html             = "";
			$paragraph        = 0;
			$sub_paragraph    = 0;
			$annex            = 0;
			$annex_arr        = array();
			$paragraph_id_arr = array();
			foreach ( $elements as $id => $element ) {
				//count paragraphs
				if ( $this->insert_element( $element, $post_id )
				     || $this->is_loop_element( $element )
				) {

					if ( isset( $element['title'] )
					     && ( ! isset( $element['numbering'] )
					          || $element['numbering'] )
					) {
						$sub_paragraph = 0;
						$paragraph ++;
						$paragraph_id_arr[ $id ]['main'] = $paragraph;
					}

					//count subparagraphs
					if ( isset( $element['subtitle'] ) && $paragraph > 0
					     && ( ! isset( $element['numbering'] )
					          || $element['numbering'] )
					) {
						$sub_paragraph ++;
						$paragraph_id_arr[ $id ]['main'] = $paragraph;
						$paragraph_id_arr[ $id ]['sub']  = $sub_paragraph;
					}

					//count annexes
					if ( isset( $element['annex'] ) ) {
						$annex ++;
						$annex_arr[ $id ] = $annex;
					}
				}
				if ( $this->is_loop_element( $element ) && $this->insert_element( $element, $post_id )
				) {
					$fieldname    = key( $element['condition'] );
					$values       = cmplz_get_value( $fieldname, $post_id );
					$loop_content = '';
					if ( ! empty( $values ) ) {
						foreach ( $values as $value ) {
							if ( ! is_array( $value ) ) {
								$value = array( $value );
							}
							$fieldnames = array_keys( $value );
							if ( count( $fieldnames ) == 1 && $fieldnames[0] == 'key'
							) {
								continue;
							}

							$loop_section = $element['content'];
							foreach ( $fieldnames as $c_fieldname ) {
								$field_value = ( isset( $value[ $c_fieldname ] ) ) ? $value[ $c_fieldname ] : '';
								if ( ! empty( $field_value ) && is_array( $field_value )
								) {
									$field_value = implode( ', ', $field_value );
								}

								$loop_section = str_replace( '[' . $c_fieldname . ']', $field_value, $loop_section );
							}

							$loop_content .= $loop_section;

						}
						$html .= $this->wrap_header( $element, $paragraph, $sub_paragraph, $annex );
						$html .= $this->wrap_content( $loop_content );
					}
				} elseif ( $this->insert_element( $element, $post_id ) ) {
					$html .= $this->wrap_header( $element, $paragraph, $sub_paragraph, $annex );
					if ( isset( $element['content'] ) ) {
						$html .= $this->wrap_content( $element['content'], $element );
					}
				}

				if ( isset( $element['callback'] ) && function_exists( $element['callback'] )
				) {
					$func = $element['callback'];
					$html .= $func();
				}
			}

			$html = $this->replace_fields( $html, $paragraph_id_arr, $annex_arr, $post_id, $type, $region );

			$comment = apply_filters( "cmplz_document_comment", "\n"
			                                                    . "<!-- This legal document was generated by Complianz | GDPR/CCPA Cookie Consent https://wordpress.org/plugins/complianz-gdpr -->"
			                                                    . "\n" );

			$html         = $comment . '<div id="cmplz-document" class="cmplz-document '.$type.'">' . $html . '</div>';
			$html         = wp_kses( $html, cmplz_allowed_html() );

			//in case we still have an unprocessed shortcode
			//this may happen when a shortcode is inserted in combination with gutenberg
			$html = do_shortcode($html);

			return apply_filters( 'cmplz_document_html', $html, $type, $post_id );
		}

		/**
		 * Wrap the header for a paragraph
		 *
		 * @param array $element
		 * @param int   $paragraph
		 * @param int   $sub_paragraph
		 * @param int   $annex
		 *
		 * @return string
		 */

		public function wrap_header(
			$element, $paragraph, $sub_paragraph, $annex
		) {
			$nr = "";
			if ( isset( $element['annex'] ) ) {
				$nr = __( "Annex", 'complianz-gdpr' ) . " " . $annex . ": ";
				if ( isset( $element['title'] ) ) {
					return '<h2 class="annex">' . cmplz_esc_html( $nr )
					       . cmplz_esc_html( $element['title'] ) . '</h2>';
				}
				if ( isset( $element['subtitle'] ) ) {
					return '<p class="subtitle annex">' . cmplz_esc_html( $nr )
					       . cmplz_esc_html( $element['subtitle'] ) . '</p>';
				}
			}

			if ( isset( $element['title'] ) ) {
				if ( empty( $element['title'] ) ) {
					return "";
				}
				$nr = '';
				if ( $paragraph > 0
				     && $this->is_numbered_element( $element )
				) {
					$nr         = $paragraph;
					$index_char = apply_filters( 'cmplz_index_char', '.' );
					$nr         = $nr . $index_char . ' ';
				}

				return '<h2>' . cmplz_esc_html( $nr )
				       . cmplz_esc_html( $element['title'] ) . '</h2>';
			}

			if ( isset( $element['subtitle'] ) ) {
				if ( $paragraph > 0 && $sub_paragraph > 0
				     && $this->is_numbered_element( $element )
				) {
					$nr = $paragraph . "." . $sub_paragraph . " ";
				}

				return '<p class="cmplz-subtitle">' . cmplz_esc_html( $nr )
				       . cmplz_esc_html( $element['subtitle'] ) . '</p>';
			}
		}

		/**
		 * Check if this element should be numbered
		 * if no key is set, default is true
		 *
		 * @param array $element
		 *
		 * @return bool
		 */

		public function is_numbered_element( $element ) {

			if ( ! isset( $element['numbering'] ) ) {
				return true;
			}

			return $element['numbering'];
		}

		/**
		 * Wrap subheader in html
		 *
		 * @param string $header
		 * @param int    $paragraph
		 * @param int    $subparagraph
		 *
		 * @return string $html
		 */

		public function wrap_sub_header( $header, $paragraph, $subparagraph ) {
			if ( empty( $header ) ) {
				return "";
			}

			return '<b>' . cmplz_esc_html( $header ) . '</b><br>';
		}

		/**
		 * Wrap content in html
		 *
		 * @param string $content
		 * @param bool   $element
		 *
		 * @return string
		 */
		public function wrap_content( $content, $element = false ) {
			if ( empty( $content ) ) {
				return "";
			}

			$class = isset( $element['class'] ) ? 'class="'
			                                      . esc_attr( $element['class'] )
			                                      . '"' : '';

			return "<p $class>" . $content . "</p>";
		}

		/**
		 * Replace all fields in the resulting output
		 *
		 * @param string $html
		 * @param array  $paragraph_id_arr
		 * @param array  $annex_arr
		 * @param int    $post_id
		 * @param string $type
		 * @param string $region
		 *
		 * @return string $html
		 */

		private function replace_fields(
			$html, $paragraph_id_arr, $annex_arr, $post_id, $type, $region
		) {
			//replace references
			foreach ( $paragraph_id_arr as $id => $paragraph ) {
				$html = str_replace( "[article-$id]",
					sprintf( __( '(See paragraph %s)', 'complianz-gdpr' ),
						cmplz_esc_html( $paragraph['main'] ) ), $html );
			}

			foreach ( $annex_arr as $id => $annex ) {
				$html = str_replace( "[annex-$id]",
					sprintf( __( '(See annex %s)', 'complianz-gdpr' ),
						cmplz_esc_html( $annex ) ), $html );
			}

			$active_cookiebanner_id = apply_filters( 'cmplz_user_banner_id', cmplz_get_default_banner_id() );
			$banner = new CMPLZ_COOKIEBANNER($active_cookiebanner_id);
			//some custom elements
			$html = str_replace( "[cookie_accept_text]",
				$banner->accept_x, $html );
			$html = str_replace( "[cookie_save_preferences_text]",
				$banner->save_preferences_x, $html );

			$html = str_replace( "[domain]",
				'<a href="' . cmplz_esc_url_raw( get_home_url() ) . '">'
				. cmplz_esc_url_raw( get_home_url() ) . '</a>', $html );
			$html = str_replace( "[cookie-statement-url]",
				cmplz_get_document_url( 'cookie-statement', $region ), $html );

			$html = str_replace( "[privacy-statement-url]",
				$this->get_page_url( 'privacy-statement', $region ), $html );
			$html = str_replace( "[privacy-statement-children-url]",
				$this->get_page_url( 'privacy-statement-children', $region ),
				$html );

			$html = str_replace( "[site_url]", site_url(), $html );

			//us can have two types of titles
			$cookie_policy_title
				  = esc_html( $this->get_document_title( 'cookie-statement',
				$region ) );
			$html = str_replace( '[cookie-statement-title]',
				$cookie_policy_title, $html );

			$date = $post_id
				? get_the_date( '', $post_id )
				: date( get_option( 'date_format' ),
					get_option( 'cmplz_publish_date' ) );
			$date = cmplz_localize_date( $date );
			$html = str_replace( "[publish_date]", cmplz_esc_html( $date ),
				$html );

			$html = str_replace( "[sync_date]",
				cmplz_esc_html( COMPLIANZ::$cookie_admin->get_last_cookie_sync_date() ),
				$html );

			$checked_date = date( get_option( 'date_format' ),
				get_option( 'cmplz_documents_update_date' ) );
			$checked_date = cmplz_localize_date( $checked_date );
			$html         = str_replace( "[checked_date]",
				cmplz_esc_html( $checked_date ), $html );

			//because the phonenumber is not required, we need to allow for an empty phonenr, making a dynamic string necessary.
			$contact_dpo = cmplz_get_value( 'email_dpo' );
			$phone_dpo   = cmplz_get_value( 'phone_dpo' );
			if ( strlen( $phone_dpo ) !== 0 ) {
				$contact_dpo .= " " . sprintf( _x( "or by telephone on %s",
						'if phonenumber is entered, this string is part of the sentence "you may contact %s, via %s or by telephone via %s"',
						"complianz-gdpr" ), $phone_dpo );
			}
			$html = str_replace( "[email_dpo]", $contact_dpo, $html );

			$contact_dpo_uk = cmplz_get_value( 'email_dpo_uk' );
			$phone_dpo_uk   = cmplz_get_value( 'phone_dpo_uk' );
			if ( strlen( $phone_dpo ) !== 0 ) {
				$contact_dpo_uk .= " " . sprintf( _x( "or by telephone on %s",
						'if phonenumber is entered, this string is part of the sentence "you may contact %s, via %s or by telephone via %s"',
						"complianz-gdpr" ), $phone_dpo_uk );
			}
			$html = str_replace( "[email_dpo_uk]", $contact_dpo_uk, $html );

			//replace all fields.
			foreach ( COMPLIANZ::$config->fields() as $fieldname => $field ) {

				if ( strpos( $html, "[$fieldname]" ) !== false ) {

					$html = str_replace( "[$fieldname]",
						$this->get_plain_text_value( $fieldname, $post_id , true,  $type ),
						$html );
					//when there's a closing shortcode it's always a link
					$html = str_replace( "[/$fieldname]", "</a>", $html );
				}

				if ( strpos( $html, "[comma_$fieldname]" ) !== false ) {
					$html = str_replace( "[comma_$fieldname]",
						$this->get_plain_text_value( $fieldname, $post_id,
							false , $type ), $html );
				}
			}

			return $html;

		}

		/**
		 *
		 * Get the plain text value of an option
		 *
		 * @param string $fieldname
		 * @param int    $post_id
		 * @param bool   $list_style
		 *
		 * @return string
		 */


		private function get_plain_text_value(
			$fieldname, $post_id, $list_style = true, $type
		) {
			$value = cmplz_get_value( $fieldname, $post_id );

			$front_end_label
				= $type !== 'impressum' && isset( COMPLIANZ::$config->fields[ $fieldname ]['document_label'] )
				? COMPLIANZ::$config->fields[ $fieldname ]['document_label']
				: false;

			if ( COMPLIANZ::$config->fields[ $fieldname ]['type'] == 'url' ) {
				$value = '<a href="' . $value . '" target="_blank">';
			} elseif ( COMPLIANZ::$config->fields[ $fieldname ]['type']
			           == 'email'
			) {
				$value = apply_filters( 'cmplz_document_email', $value );
			} elseif ( COMPLIANZ::$config->fields[ $fieldname ]['type']
			           == 'radio'
			) {
				$options = COMPLIANZ::$config->fields[ $fieldname ]['options'];
				$value   = isset( $options[ $value ] ) ? $options[ $value ]
					: '';
			} elseif ( COMPLIANZ::$config->fields[ $fieldname ]['type']
			           == 'textarea'
			) {
				//preserve linebreaks
				$value = nl2br( $value );
			} elseif ( is_array( $value ) ) {
				$options = COMPLIANZ::$config->fields[ $fieldname ]['options'];
				//array('3' => 1 );
				$value = array_filter( $value, function ( $item ) {
					return $item == 1;
				} );
				$value = array_keys( $value );
				//array (1, 4, 6)
				$labels = "";
				foreach ( $value as $index ) {
					//trying to fix strange issue where index is not set
					if ( ! isset( $options[ $index ] ) ) {
						continue;
					}

					if ( $list_style ) {
						$labels .= "<li>" . cmplz_esc_html( $options[ $index ] )
						           . '</li>';
					} else {
						$labels .= $options[ $index ] . ', ';
					}
				}
				//if (empty($labels)) $labels = __('None','complianz-gdpr');

				if ( $list_style ) {
					$labels = "<ul>" . $labels . "</ul>";
				} else {
					$labels = cmplz_esc_html( rtrim( $labels, ', ' ) );
					$labels = strrev( implode( strrev( ' ' . __( 'and',
							'complianz-gdpr' ) ),
						explode( strrev( ',' ), strrev( $labels ), 2 ) ) );
				}

				$value = $labels;
			} else {
				if ( isset( COMPLIANZ::$config->fields[ $fieldname ]['options'] ) ) {
					$options
						= COMPLIANZ::$config->fields[ $fieldname ]['options'];
					if ( isset( $options[ $value ] ) ) {
						$value = $options[ $value ];
					}
				}
			}

			if ( $front_end_label && ! empty( $value ) ) {
				$value = $front_end_label . $value . "<br>";
			}

			return $value;
		}


		/**
		 * Add some text to the privacy statement suggested texts in free.
		 */

		public function add_privacy_info() {
			if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
				return;
			}

			//only necessary for free, as premium will generate the privacy statement
			if ( ! defined( 'cmplz_free' ) ) {
				return;
			}

			$content = sprintf(
				__( "Complianz GDPR Cookie Consent does not process any personally identifiable information, which means there's no need to add text about this plugin to your Privacy Statement. The used cookies (all functional) will be added to your Cookie Policy automatically. You can find our Privacy Statement %shere%s.",
					'complianz-gdpr' ),
				'<a href="https://complianz.io/privacy-statement/" target="_blank">',
				'</a>'
			);

			wp_add_privacy_policy_content(
				'Complianz | GDPR/CCPA Cookie Consent',
				wp_kses_post( wpautop( $content, false ) )
			);
		}


		/**
		 * Get the region for a post id, based on the post type.
		 *
		 * @param int $post_id
		 *
		 * @return string|bool $region
		 * */

		public function get_region( $post_id ) {

			if ( ! is_numeric( $post_id ) && isset( $post_id['source'] ) ) {
				return substr( $post_id['source'], - 2 );
			}

			if ( $post_id ) {
				$term = wp_get_post_terms( $post_id, 'cmplz-region' );
				if ( is_wp_error( $term ) ) {
					return false;
				}

				if ( isset( $term[0] ) ) {
					return $term[0]->slug;
				}

				return false;
			}

			return false;
		}

		/**
		 * Set the region in a post
		 *
		 * @param      $post_id
		 * @param bool $region
		 */

		public function set_region( $post_id, $region = false ) {
			if ( ! $region ) {
				$region = $this->get_region( $post_id );
			}

			if ( ! $region ) {
				$regions = cmplz_get_regions();

				if ( isset( $_GET['page'] ) ) {
					$page = sanitize_title( $_GET['page'] );
					foreach ( $regions as $r => $label ) {
						if ( strpos( $page, '-' . $r ) !== false ) {
							$region = $r;
						}
					}
				}
			}

			$term = get_term_by( 'slug', $region, 'cmplz-region' );
			if ( ! $term ) {
				wp_insert_term( COMPLIANZ::$config->regions[ $region ]['label'],
					'cmplz-region', array(
						'slug' => $region,
					) );
				$term = get_term_by( 'slug', $region, 'cmplz-region' );
			}

			if ( empty( $term ) ) {
				return;
			}

			$term_id = $term->term_id;

			wp_set_object_terms( $post_id, array( $term_id ), 'cmplz-region' );
		}


		/**
		 * Check if legal documents should be updated
		 *
		 * @return bool
		 */

		public function documents_need_updating() {
			if ( cmplz_has_region( 'us' )
			     && $this->not_updated_in( MONTH_IN_SECONDS * 12 )
			) {
				return true;
			}

			return false;
		}

		/**
		 * Check if legal documents should be updated, and send mail to admin if so
		 */

		public function cron_check_last_updated_status() {

			if ( $this->documents_need_updating()
			     && ! get_option( 'cmplz_update_legal_documents_mail_sent' )
			) {
				update_option( 'cmplz_update_legal_documents_mail_sent', true );
				$to = get_option( 'admin_email' );

				$headers = array();
				if ( empty( $subject ) ) {
					$subject
						= sprintf( _x( 'Your legal documents on %s need to be updated.',
						'Subject in notification email', 'complianz-gdpr' ),
						home_url() );
				}

				$message
					= sprintf( _x( 'Your legal documents on %s have not been updated in 12 months. Please log in and run the wizard to check if everything is up to date.',
					'Email message in notification email', 'complianz-gdpr' ),
					home_url() );

				add_filter( 'wp_mail_content_type', function ( $content_type ) {
					return 'text/html';
				} );

				wp_mail( $to, $subject, $message, $headers );

				// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
				remove_filter( 'wp_mail_content_type',
					'set_html_content_type' );
			}
		}

		/**
		 * Render html for the manage consent shortcode
		 * @param array  $atts
		 * @param null   $content
		 * @param string $tag
		 *
		 * @return string
		 */

		public function manage_consent_html( $atts = array(), $content = null, $tag = ''
		) {
			return '<a id="manage-consent"></a><p id="cmplz-manage-consent-container" class="cmplz-manage-consent-container"></p>';
		}

		public function revoke_link( $atts = array(), $content = null, $tag = ''
		) {
			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );

			ob_start();

			// override default attributes with user attributes
			$atts = shortcode_atts( array( 'text' => false ), $atts, $tag );

			echo cmplz_revoke_link($atts['text']);

			return ob_get_clean();
		}

		/**
		 * Display an accept hyperlink
		 *
		 * @param array  $atts
		 * @param null   $content
		 * @param string $tag
		 *
		 * @return string
		 */

		public function accept_link( $atts = array(), $content = null, $tag = ''
		) {

			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );

			ob_start();

			// override default attributes with user attributes
			$atts = shortcode_atts( array( 'text' => false ), $atts, $tag );

			$accept_text = $atts['text']
				? $atts['text']
				: apply_filters( 'cmplz_accept_cookies_blocked_content',
					cmplz_get_value( 'blocked_content_text' ) );
			$html
			             = '<div class="cmplz-blocked-content-notice cmplz-accept-cookies"><a href="#">'
			               . $accept_text . '</a></div>';
			echo $html;

			return ob_get_clean();

		}

		/*
         * This class is extended with pro functions, so init is called also from the pro extension.
         * */

		public function init() {
			//this shortcode is also available as gutenberg block
			add_shortcode( 'cmplz-document', array( $this, 'load_document' ) );
			add_shortcode( 'cmplz-consent-area', array( $this, 'show_consent_area' ) );

			add_shortcode( 'cmplz-cookies', array( $this, 'cookies' ) );
			add_filter( 'display_post_states', array( $this, 'add_post_state') , 10, 2);

			/*
             * @todo add a gutenberg block for the revoke link and DNSMPD form
             *
             * These shortcodes are setup in a disconnected way, so this shortcode is not necessary: it's only used for user customizations
             * The gutenberg blocks will be calling the endpoint functions directly, in which case these shortcodes will become deprecated.
             *
             * */

			add_shortcode( 'cmplz-manage-consent', array( $this, 'manage_consent_html' ) );
			add_shortcode( 'cmplz-revoke-link', array( $this, 'revoke_link' ) );

			add_shortcode( 'cmplz-accept-link', array( $this, 'accept_link' ) );
			add_shortcode( 'cmplz-do-not-sell-personal-data-form',
				array( $this, 'do_not_sell_personal_data_form' ) );

			//clear shortcode transients after post update
			add_action( 'save_post',
				array( $this, 'clear_shortcode_transients' ), 10, 1 );
			add_action( 'cmplz_wizard_add_pages_to_menu',
				array( $this, 'wizard_add_pages_to_menu' ), 10, 1 );
			add_action( 'cmplz_wizard_add_pages',
				array( $this, 'callback_wizard_add_pages' ), 10, 1 );
			add_action( 'admin_init',
				array( $this, 'assign_documents_to_menu' ) );
			add_action( 'wp_enqueue_scripts',
				array( $this, 'enqueue_assets' ) );

			add_action( 'admin_init', array( $this, 'add_privacy_info' ) );


			add_filter( 'cmplz_document_email',
				array( $this, 'obfuscate_email' ) );

			add_filter( 'body_class',
				array( $this, 'add_body_class_for_complianz_documents' ) );

			//unlinking documents
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_metabox_data' ) );

			add_action( 'wp', array( $this, 'maybe_autoredirect' ) );
			add_action( 'wp_ajax_cmplz_create_pages',
				array( $this, 'ajax_create_pages' ) );

		}

		/**
		 * Add document post state
		 * @param array $post_states
		 * @param WP_Post $post
		 * @return array
		 */
		public function add_post_state($post_states, $post) {
			if ( $this->is_complianz_page( $post->ID ) ) {
				$post_states['page_for_privacy_policy'] = __("Legal Document", "complianz-gdpr");
			}
			return $post_states;
		}

		public function add_meta_box( $post_type ) {
			global $post;

			if ( ! $post ) {
				return;
			}

			if ( $this->is_complianz_page( $post->ID )
			     && ! cmplz_uses_gutenberg()
			) {
				add_meta_box( 'cmplz_edit_meta_box',
					__( 'Document status', 'complianz-gdpr' ),
					array( $this, 'metabox_unlink_from_complianz' ), null,
					'side', 'high', array() );
			}
		}

		/**
		 * Unlink a page from the shortcode, and use the html instead
		 *
		 */
		function metabox_unlink_from_complianz() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			wp_nonce_field( 'cmplz_unlink_nonce', 'cmplz_unlink_nonce' );

			global $post;
			$sync = $this->syncStatus( $post->ID );
			?>
			<select name="cmplz_document_status">
				<option value="sync" <?php echo $sync === 'sync'
					? 'selected="selected"'
					: '' ?>><?php _e( "Synchronize document with Complianz",
						"complianz-gdpr" ); ?></option>
				<option value="unlink" <?php echo $sync === 'unlink'
					? 'selected="selected"'
					: '' ?>><?php _e( "Edit document and stop synchronization",
						"complianz-gdpr" ); ?></option>
			</select>
			<?php

		}

		/**
		 * Get sync status of post
		 *
		 * @param $post_id
		 *
		 * @return string
		 */

		public function syncStatus( $post_id ) {
			$post = get_post( $post_id );
			$sync = 'unlink';

			if ( ! $post ) {
				return $sync;
			}

			$shortcode = 'cmplz-document';
			$block     = 'complianz/document';

			$html = $post->post_content;
			if ( cmplz_uses_gutenberg() && has_block( $block, $html ) ) {
				$elements = parse_blocks( $html );
				foreach ( $elements as $element ) {
					if ( $element['blockName'] === $block ) {
						if ( isset( $element['attrs']['documentSyncStatus'] )
						     && $element['attrs']['documentSyncStatus']
						        === 'unlink'
						) {
							$sync = 'unlink';
						} else {
							$sync = 'sync';
						}
					}
				}
			} elseif ( has_shortcode( $post->post_content, $shortcode ) ) {
				$sync = get_post_meta( $post_id, 'cmplz_document_status',
					true );
				if ( ! $sync ) {
					$sync = 'sync';
				}
			}

			//default
			return $sync;
		}

		/**
		 * Save data posted from the metabox
		 */
		public function save_metabox_data() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			// check if this isn't an auto save
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// security check
			if ( ! isset( $_POST['cmplz_unlink_nonce'] )
			     || ! wp_verify_nonce( $_POST['cmplz_unlink_nonce'],
					'cmplz_unlink_nonce' )
			) {
				return;
			}

			if ( ! isset( $_POST['cmplz_document_status'] ) ) {
				return;
			}

			global $post;

			if ( ! $post ) {
				return;
			}
			//prevent looping
			remove_action( 'save_post', array( $this, 'save_metabox_data' ) );

			$sync = sanitize_text_field( $_POST['cmplz_document_status'] )
			        == 'unlink' ? 'unlink' : 'sync';

			//save the document's shortcode in a meta field

			if ( $sync === 'unlink' ) {
				//get shortcode from page
				$shortcode = false;

				if ( preg_match( $this->get_shortcode_pattern( "gutenberg" ),
					$post->post_content, $matches )
				) {
					$shortcode = $matches[0];
					$type      = $matches[1];
					$region    = cmplz_get_region_from_legacy_type( $type );
					$type      = str_replace( '-' . $region, '', $type );
				} elseif ( preg_match( $this->get_shortcode_pattern( "classic" ),
					$post->post_content, $matches )
				) {
					$shortcode = $matches[0];
					$type      = $matches[1];
					$region    = $matches[2];
				} elseif ( preg_match( $this->get_shortcode_pattern( "classic",
					$legacy = true ), $post->post_content, $matches )
				) {
					$shortcode = $matches[0];
					$type      = $matches[1];
					$region    = cmplz_get_region_from_legacy_type( $type );
					$type      = str_replace( '-' . $region, '', $type );
				}

				if ( $shortcode ) {
					//store shortcode
					update_post_meta( $post->ID, 'cmplz_shortcode',
						$post->post_content );
					$document_html
						  = COMPLIANZ::$document->get_document_html( $type,
						$region );
					$args = array(
						'post_content' => $document_html,
						'ID'           => $post->ID,
					);
					wp_update_post( $args );
				}
			} else {
				$shortcode = get_post_meta( $post->ID, 'cmplz_shortcode',
					true );
				if ( $shortcode ) {
					$args = array(
						'post_content' => $shortcode,
						'ID'           => $post->ID,
					);
					wp_update_post( $args );
				}
				delete_post_meta( $post->ID, 'cmplz_shortcode' );
			}
			update_post_meta( $post->ID, 'cmplz_document_status', $sync );

			add_action( 'save_post', array( $this, 'save_metabox_data' ) );


		}

		/**
		 * add a class to the body telling the page it's a complianz doc. We use this for the soft cookie wall
		 *
		 * @param $classes
		 *
		 * @return array
		 */
		public function add_body_class_for_complianz_documents( $classes ) {
			global $post;
			if ( $post && $this->is_complianz_page( $post->ID ) ) {
				$classes[] = 'cmplz-document';
			}

			return $classes;
		}

		/**
		 * obfuscate the email address
		 *
		 * @param $email
		 *
		 * @return string
		 */

		public function obfuscate_email( $email ) {
			$alwaysEncode = array( '.', ':', '@' );

			$result = '';

			// Encode string using oct and hex character codes
			for ( $i = 0; $i < strlen( $email ); $i ++ ) {
				// Encode 25% of characters including several that always should be encoded
				if ( in_array( $email[ $i ], $alwaysEncode )
				     || mt_rand( 1, 100 ) < 25
				) {
					if ( mt_rand( 0, 1 ) ) {
						$result .= '&#' . ord( $email[ $i ] ) . ';';
					} else {
						$result .= '&#x' . dechex( ord( $email[ $i ] ) ) . ';';
					}
				} else {
					$result .= $email[ $i ];
				}
			}

			//make clickable

			$result = '<a href="mailto:'.$result.'">'.$result.'</a>';

			return $result;
		}


		/**
		 * Render shortcode for DNSMPI form
		 *
		 * @hooked shortcode hook
		 *
		 * @param array  $atts
		 * @param null   $content
		 * @param string $tag
		 *
		 * @return false|string
		 * @since  2.0
		 */

		public function do_not_sell_personal_data_form(
			$atts = array(), $content = null, $tag = ''
		) {

			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );

			ob_start();

			// override default attributes with user attributes
			$atts = shortcode_atts( array( 'text' => false ), $atts, $tag );

			echo cmplz_do_not_sell_personal_data_form();

			return ob_get_clean();

		}


		/**
		 * Render shortcode for cookie list
		 *
		 * @hooked shortcode hook
		 *
		 * @param array  $atts
		 * @param null   $content
		 * @param string $tag
		 *
		 * @return false|string
		 * @since  2.0
		 */

		public function cookies( $atts = array(), $content = null, $tag = '' ) {

			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );

			ob_start();

			// override default attributes with user attributes
			$atts = shortcode_atts( array( 'text' => false ), $atts, $tag );

			echo cmplz_used_cookies();

			return ob_get_clean();

		}

		/**
		 * Create legal document pages from the wizard using ajax
		 */
		public function ajax_create_pages(){

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$error   = false;
			if (!isset($_POST['pages'])){
				$error = true;
			}

			if (!$error){
				$pages = json_decode(stripslashes($_POST['pages']));
				foreach ($pages as $region => $pages ){
					foreach($pages as $type => $title) {
						$current_page_id = $this->get_shortcode_page_id($type, $region);
						if (!$current_page_id){
							$this->create_page( $type, $region );
						} else {
							//if the page already exists, just update it with the title
							$page = array(
								'ID'           => $current_page_id,
								'post_title'   => $title,
								'post_type'    => "page",
							);
							wp_update_post( $page );
						}
					}
				}
			}
			$data     = array(
				'success' => !$error,
			);
			$response = json_encode( $data );
			header( "Content-Type: application/json" );
			echo $response;
			exit;

		}

		/**
		 * Check if the site has missing pages for the auto generated documents
		 * @return bool
		 */

		public function has_missing_pages(){
			$pages = COMPLIANZ::$document->get_required_pages();
			$missing_pages = false;
			foreach ( $pages as $region => $region_pages ) {
				foreach ( $region_pages as $type => $page ) {
					$current_page_id = $this->get_shortcode_page_id( $type,
						$region );
					if ( ! $current_page_id ) {
						$missing_pages = true;
						break;
					}
				}
			}

			return $missing_pages;
		}

		/**
		 * Add pages from the wizard
		 */
		public function callback_wizard_add_pages(){
			//create a page foreach page that is needed.
			if ($this->has_missing_pages()){
				cmplz_notice(__("The pages marked with X should be added to your website. You can create these pages with a shortcode, a Gutenberg block or use the below \"Create missing pages\" button.","complianz-gdpr"));
			} else {
				cmplz_notice(__("All necessary pages have been created already. You can update the page titles here if you want, then click the \"Update pages\" button.","complianz-gdpr"));
			}

			$pages = COMPLIANZ::$document->get_required_pages();
			if (count($pages)==0){
				cmplz_notice(__("You haven't selected any legal documents to create. You can skip this step", "complianz-gdpr"), "warning");
			} else {
				$missing_pages = false;
				echo '<table style="width:100%">';
				foreach ( $pages as $region => $region_pages ) {
					foreach ( $region_pages as $type => $page ) {
						$class           = '';
						$current_page_id = $this->get_shortcode_page_id( $type,
							$region, false);
						if ( ! $current_page_id ) {
							$missing_pages = true;
							$title         = $page['title'];
							$icon
							               = '<i class="cmplz-page-created fa fa-times"></i>';
							$class         = 'cmplz-deleted-page';
						} else {
							$post  = get_post( $current_page_id );
							$icon
							       = '<i class="cmplz-page-created fa fa-check"></i>';
							$title = $post->post_title;
						}
						$shortcode = $this->get_shortcode( $type, $region,
							$force_classic = true );
						$shortcode = '<div  class="cmplz-selectable">'
						             . $shortcode . '</div>';


						$field = '<input name="' . $type . '" data-region="'
						         . $region . '" class="' . $class
						         . ' cmplz-create-page-title" required type="text" value="'
						         . $title . '">';

						?>

						<tr>
							<td><?php echo $icon ?></td>
							<td><?php echo $field ?></td>
							<td style="width:400px"><?php echo $shortcode; ?></td>
						</tr>
						<?php

					}
				}
				echo '</table>';
				if ($missing_pages){
					$btn = __("Create missing pages","complianz-gdpr");
				} else {
					$btn = __("Update pages","complianz-gdpr");
				}
				?>
				<p>
					<button type="button" class="button button-default"
					        id="cmplz-create_pages"><?php echo $btn ?></button>
				</p>
				<?php
			}
		}

		/**
		 *
		 * Show form to enable user to add pages to a menu
		 *
		 * @hooked field callback wizard_add_pages_to_menu
		 * @since  1.0
		 *
		 */

		public function wizard_add_pages_to_menu() {

			//this function is used as of 4.9.0
			if ( ! function_exists( 'wp_get_nav_menu_name' ) ) {
				cmplz_notice( __( 'Your WordPress version does not support the functions needed for this step. You can upgrade to the latest WordPress version, or add the pages manually to a menu.',
					'complianz-gdpr' ), 'warning' );

				return;
			}

			//get list of menus

			$menus = wp_list_pluck( wp_get_nav_menus(), 'name', 'term_id' );

			$link = '<a href="' . admin_url( 'nav-menus.php' ) . '">';
			if ( empty( $menus ) ) {
				cmplz_notice( sprintf( __( "No menus were found. Skip this step, or %screate a menu%s first." ),
					$link, '</a>' ) );

				return;
			}

			$created_pages = $this->get_created_pages(  );
			$required_pages = $this->get_required_pages();
			if (count($required_pages) > count($created_pages) ){
				cmplz_notice( __( 'You haven\'t created all required pages yet. You can add missing pages in the previous step, or create them manually with the shortcode. You can come back later to this step to add your pages to the desired menu, or do it manually via Appearance > Menu.',
					'complianz-gdpr' )
				);
			}

			$pages_not_in_menu = $this->pages_not_in_menu();
			if ( $pages_not_in_menu ) {
				if ( cmplz_ccpa_applies() ) {
					cmplz_notice( sprintf( __( 'You sell personal data from your customers. This means you are required to put the "%s" page clearly visible on your homepage.',
						'complianz-gdpr' ),
						cmplz_us_cookie_statement_title() ) );
				}

				$docs = array_map( 'get_the_title', $pages_not_in_menu );
				$docs = implode( ", ", $docs );
				cmplz_notice( sprintf( esc_html( _n( 'The generated document %s has not been assigned to a menu yet, you can do this now, or skip this step and do it later.',
					'The generated documents %s have not been assigned to a menu yet, you can do this now, or skip this step and do it later.',
					count( $pages_not_in_menu ), 'complianz-gdpr' ) ), $docs ),
					'warning' );
			} else {
				if (count($created_pages)>0 ) {
					cmplz_notice( __( "Great! All your generated documents have been assigned to a menu, so you can skip this step.",
						'complianz-gdpr' ), 'warning' );
				}
			}

			$regions = cmplz_get_regions( true );

			echo '<table>';
			foreach ( $regions as $region => $label ) {
				$pages = $this->get_created_pages( $region );
				if ( count( $pages ) > 0 ) {
					?>
					<tr>
						<td><h3><?php echo $label ?></h3></td>
					</tr>
					<?php

					foreach ( $pages as $page_id ) {
						echo "<tr><td>";
						echo get_the_title( $page_id );
						echo "</td><td>";
						?>

						<select
							name="cmplz_assigned_menu[<?php echo $page_id ?>]">
							<option value=""><?php _e( "Select a menu",
									'complianz-gdpr' ); ?></option>
							<?php foreach ( $menus as $menu_id => $menu ) {
								$selected
									= ( $this->is_assigned_this_menu( $page_id,
									$menu_id ) ) ? "selected" : "";
								echo '<option ' . $selected . ' value="'
								     . $menu_id
								     . '">' . $menu . '</option>';
							} ?>

						</select>
						<?php
						echo "</td></tr>";
					}
				}
			}
			echo "</table>";

		}

		/**
		 * Handle the submit of a form which assigns documents to a menu
		 *
		 * @hooked admin_init
		 *
		 */

		public function assign_documents_to_menu() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( isset( $_POST['cmplz_assigned_menu'] ) ) {
				foreach (
					$_POST['cmplz_assigned_menu'] as $page_id => $menu_id
				) {
					if ( empty( $menu_id ) ) {
						continue;
					}
					if ( $this->is_assigned_this_menu( $page_id, $menu_id ) ) {
						continue;
					}

					$page = get_post( $page_id );

					wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-title'     => get_the_title( $page ),
						'menu-item-object-id' => $page->ID,
						'menu-item-object'    => get_post_type( $page ),
						'menu-item-status'    => 'publish',
						'menu-item-type'      => 'post_type',
					) );
				}
			}
		}


		/**
		 * Get all pages that are not assigned to any menu
		 *
		 * @return array|bool
		 * @since 1.2
		 *
		 * */

		public function pages_not_in_menu() {
			//search in menus for the current post
			$menus         = wp_list_pluck( wp_get_nav_menus(), 'name',
				'term_id' );
			$pages         = $this->get_created_pages();
			$pages_in_menu = array();

			foreach ( $menus as $menu_id => $title ) {

				$menu_items = wp_get_nav_menu_items( $menu_id );
				foreach ( $menu_items as $post ) {
					if ( in_array( $post->object_id, $pages ) ) {
						$pages_in_menu[] = $post->object_id;
					}
				}

			}
			$pages_not_in_menu = array_diff( $pages, $pages_in_menu );
			if ( count( $pages_not_in_menu ) == 0 ) {
				return false;
			}

			return $pages_not_in_menu;
		}


		/**
		 *
		 * Check if a page is assigned to a menu
		 *
		 * @param int $page_id
		 * @param int $menu_id
		 *
		 * @return bool
		 *
		 * @since 1.2
		 */

		public function is_assigned_this_menu( $page_id, $menu_id ) {
			$menu_items = wp_list_pluck( wp_get_nav_menu_items( $menu_id ),
				'object_id' );

			return ( in_array( $page_id, $menu_items ) );

		}

		/**
		 * Create a page of certain type in wordpress
		 *
		 * @param string $type
		 * @param string $region
		 *
		 * @return int|bool page_id
		 * @since 1.0
		 */

		public function create_page( $type, $region ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}
			$pages = COMPLIANZ::$config->pages;

			if ( ! isset( $pages[ $region ][ $type ] ) ) {
				return false;
			}

			//only insert if there is no shortcode page of this type yet.
			$page_id = $this->get_shortcode_page_id( $type, $region );
			if ( ! $page_id ) {

				$page = $pages[ $region ][ $type ];


				$page = array(
					'post_title'   => $page['title'],
					'post_type'    => "page",
					'post_content' => $this->get_shortcode( $type, $region ),
					'post_status'  => 'publish',
				);

				// Insert the post into the database
				$page_id = wp_insert_post( $page );
			}

			do_action( 'cmplz_create_page', $page_id, $type, $region );

			return $page_id;

		}

		/**
		 * Delete a page of a type
		 *
		 * @param string $type
		 * @param string $region
		 *
		 */

		public function delete_page( $type, $region ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$page_id = $this->get_shortcode_page_id( $type, $region );
			if ( $page_id ) {
				wp_delete_post( $page_id, false );
			}
		}


		/**
		 *
		 * Check if page of certain type exists
		 *
		 * @param string $type
		 * @param string $region
		 *
		 * @return bool
		 *
		 */

		public function page_exists( $type, $region ) {
			if ( $this->get_shortcode_page_id( $type, $region ) ) {
				return true;
			}

			return false;
		}

		/**
		 * get the shortcode or block for a page type
		 *
		 * @param string $type
		 * @param string $region
		 * @param bool   $force_classic
		 *
		 * @return string $shortcode
		 *
		 */

		public function get_shortcode( $type, $region, $force_classic = false
		) {
			//even if on gutenberg, with elementor we have to use classic shortcodes.
			if ( ! $force_classic && cmplz_uses_gutenberg()
			     && ! $this->uses_elementor()
			) {
				$page = COMPLIANZ::$config->pages[ $region ][ $type ];
				$ext  = $region == 'eu' ? '' : '-' . $region;

				return '<!-- wp:complianz/document {"title":"' . $page['title']
				       . '","selectedDocument":"' . $type . $ext . '"} /-->';
			} else {
				return '[cmplz-document type="' . $type . '" region="' . $region
				       . '"]';
			}
		}

		/**
		 * Get shortcode pattern for this site, gutenberg or classic
		 *
		 * @param string $type
		 * @param bool   $legacy
		 *
		 * @return string
		 */
		public function get_shortcode_pattern(
			$type = "classic", $legacy = false
		) {
			if ( $type === 'classic' && $legacy ) {
				return '/\[cmplz\-document.*?type="(.*?)".*?]/i';
			}
			if ( $type === 'classic' && ! $legacy ) {
				return '/\[cmplz\-document.*?type="(.*?)".*?region="(.*?)".*?]/i';
			} else {
				return '/<!-- wp:complianz\/document {.*?"selectedDocument":"(.*?)"} \/-->/i';
			}
		}

		/**
		 * Check if this site uses Elementor
		 * When Elementor is used, the classic shortcode should be used, even when on Gutenberg
		 *
		 * @return bool $uses_elementor
		 */

		public function uses_elementor() {
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				return true;
			}

			return false;
		}


		/**
		 *
		 * Get type of document
		 *
		 * @param int $post_id
		 *
		 * @return array
		 *
		 *
		 */

		public function get_document_data( $post_id ) {

			$pattern = $this->get_shortcode_pattern('classic' );
			$pattern_legacy = $this->get_shortcode_pattern('classic' , true );
			$pattern_gutenberg = $this->get_shortcode_pattern('gutenberg' );
			$post    = get_post( $post_id );

			$content = $post->post_content;
			$output = array(
				'type' => '',
				'region' => false,
			);
			if ( preg_match_all( $pattern, $content, $matches, PREG_PATTERN_ORDER ) ) {
				if ( isset( $matches[1][0] ) ) {
					$output['type'] = $matches[1][0];
				}
				if ( isset( $matches[2][0] ) ) {
					$output['region'] = $matches[2][0];
				}
			} else if ( preg_match_all( $pattern_gutenberg, $content, $matches, PREG_PATTERN_ORDER ) ) {
				if ( isset( $matches[1][0] ) ) {
					$output['type'] = $matches[1][0];
				}
				if ( isset( $matches[2][0] ) ) {
					$output['region'] = $matches[2][0];
				}
			} else if ( preg_match_all( $pattern_legacy, $content, $matches, PREG_PATTERN_ORDER ) ) {
				if ( isset( $matches[1][0] ) ) {
					$output['type'] = $matches[1][0];
				}
				if ( isset( $matches[2][0] ) ) {
					$output['region'] = $matches[2][0];
				}
			}
			return $output;
		}

		/**
		 * Get list of all created pages with page id for current setup
		 *
		 * @return array $pages
		 *
		 *
		 */

		public function get_created_pages( $filter_region = false ) {
			$required_pages = COMPLIANZ::$document->get_required_pages();
			$pages          = array();
			if ( $filter_region ) {
				if ( isset( $required_pages[ $filter_region ] ) ) {
					foreach (
						$required_pages[ $filter_region ] as $type => $page
					) {
						$page_id = $this->get_shortcode_page_id( $type, $filter_region , false);
						if ($page_id) $pages[] = $page_id;
					}
				}
			} else {
				$regions = cmplz_get_regions();
				foreach ( $regions as $region => $label ) {
					if (!isset($required_pages[ $region ])) continue;
					foreach ( $required_pages[ $region ] as $type => $page ) {
						$page_id = $this->get_shortcode_page_id( $type, $region, false);
						if ($page_id) $pages[] = $page_id;
					}
				}
			}

			return $pages;
		}


		/**
		 * Get list of all required pages for current setup
		 *
		 * @return array $pages
		 *
		 *
		 */

		public function get_required_pages() {
			$regions  = cmplz_get_regions( $add_all_cat = true );
			$required = array();

			foreach ( $regions as $region => $label ) {
				if ( ! isset( COMPLIANZ::$config->pages[ $region ] ) ) {
					continue;
				}

				$pages = COMPLIANZ::$config->pages[ $region ];

				foreach ( $pages as $type => $page ) {
					if ( ! $page['public'] ) {
						continue;
					}
					if ( $this->page_required( $page, $region ) ) {
						$required[ $region ][ $type ] = $page;
					}
				}
			}


			return $required;
		}


		/**
		 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
		 *
		 * @param string $hex Colour as hexadecimal (with or without hash);
		 *
		 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
		 * @return string Lightened/Darkend colour as hexadecimal (with hash);
		 */

		function color_luminance( $hex, $percent ) {
			if ( empty( $hex ) ) {
				return $hex;
			}
			// validate hex string
			$hex     = preg_replace( '/[^0-9a-f]/i', '', $hex );
			$new_hex = '#';

			if ( strlen( $hex ) < 6 ) {
				$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2]
				       + $hex[2];
			}

			// convert to decimal and change luminosity
			for ( $i = 0; $i < 3; $i ++ ) {
				$dec     = hexdec( substr( $hex, $i * 2, 2 ) );
				$dec     = min( max( 0, $dec + $dec * $percent ), 255 );
				$new_hex .= str_pad( dechex( $dec ), 2, 0, STR_PAD_LEFT );
			}

			return $new_hex;
		}


		/**
		 * loads document content on shortcode call
		 *
		 * @param array  $atts
		 * @param null   $content
		 * @param string $tag
		 *
		 * @return string $html
		 *
		 *
		 */

		public function load_document(
			$atts = array(), $content = null, $tag = ''
		) {
			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );


			ob_start();

			// override default attributes with user attributes
			$atts   = shortcode_atts( array(
				'type'   => false,
				'region' => false
			),
				$atts, $tag );
			$type   = sanitize_title( $atts['type'] );
			$region = sanitize_title( $atts['region'] );

			if ( $type ) {
				$html         = $this->get_document_html( $type, $region );
				$allowed_html = cmplz_allowed_html();
				echo wp_kses( $html, $allowed_html );
			}

			return ob_get_clean();
		}

		/**
		 * Show content conditionally, based on consent
		 * @param array  $atts
		 * @param null   $content
		 * @param string $tag
		 *
		 * @return false|string
		 */

		public function show_consent_area(
			$atts = array(), $content = null, $tag = ''
		) {
			// normalize attribute keys, lowercase
			$atts = array_change_key_case( (array) $atts, CASE_LOWER );
			ob_start();

			//should always be wrapped in closing tag
			if (empty($content)) return '';
			$blocked_text = __('Click to accept marketing cookies and enable this content', 'complianz-gdpr');

			// override default attributes with user attributes
			$atts   = shortcode_atts( array(
				'cache_redirect'   => false,
				'category'   => 'marketing',
				'text'   => $blocked_text,
			),
				$atts, $tag );
			$category   = sanitize_text_field( $atts['category'] );
			if ($atts['cache_redirect']==="true" || $atts['cache_redirect']=== 1 || $atts['cache_redirect'] === true) {
				$cache_redirect = true;
			} else {
				$cache_redirect = false;
			}
			$cookie_name  = 'cmplz_'.sanitize_title( $category );
			$blocked_text = sanitize_text_field( $atts['text'] );
			$blocked_text = str_replace('marketing', $category, $blocked_text);

			//if has consent, show content. Otherwise, show placeholder.
			$has_consent = false;
			$consenttype = apply_filters( 'cmplz_user_consenttype', COMPLIANZ::$company->get_default_consenttype() );
			$cookiesettings = COMPLIANZ::$cookie_admin->get_cookiebanner_settings( apply_filters( 'cmplz_user_banner_id', cmplz_get_default_banner_id() ) );

			if ( $consenttype === 'optin' || $consenttype === 'optinstats' ) {
				if ( $cookiesettings['use_categories'] === 'no' ){
					if (isset($_COOKIE['complianz_consent_status']) && $_COOKIE['complianz_consent_status'] === 'allow' ) {
						$has_consent = true;
					}
				} else {
					if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] === 'allow' ) {
						$has_consent = true;
					}
				}
			} elseif ( $consenttype === 'optout' ) {
				if (isset($_COOKIE['complianz_consent_status']) && $_COOKIE['complianz_consent_status'] === 'allow') {
					$has_consent = true;
				} elseif (!isset($_COOKIE[$cookie_name]) || $_COOKIE[$cookie_name] === 'allow' ) {
					$has_consent = true;
				}
			} else {
				$has_consent = true;
			}

			if ( $has_consent ) {
				if ($cache_redirect) {
					//redirect if not on redirect url, to prevent caching issues
					?>
					<script>
						jQuery(document).ready(function ($) {
							var url = window.location.href;
							if (url.indexOf('cmplz_consent=1') === -1) {
								if (url.indexOf('?') !== -1) {url += '&';} else {url += '?';}
								url += 'cmplz_consent=1';
								window.location.replace(url);
							}
						});
					</script>
					<?php
				}
				echo '<a id="cmplz_consent_area_anchor"></a>'.do_shortcode($content);
			} else {
				//no consent
				$blocked_text = apply_filters( 'cmplz_accept_cookies_blocked_content', $blocked_text );
				if ($cache_redirect) {
				?>
				<script>
					jQuery(document).ready(function ($) {

						var url = window.location.href;
						var consented_area_visible = $('#cmplz_consent_area_anchor').length;
						if (url.indexOf('cmplz_consent=1') !== -1 && !consented_area_visible) {
							url = url.replace('cmplz_consent=1', '');
							url = url.replace('#cmplz_consent_area_anchor', '');
							url = url.replace('?&', '?');
							url = url.replace('&&', '?');
							//if last character is ? or &, drop it
							if (url.substring(url.length-1) == "&" || url.substring(url.length-1) == "?")
							{
								url = url.substring(0, url.length-1);
							}
							window.location.replace(url);
						}

						$(document).on("cmplzEnableScripts", cmplzEnableCustomBlockedContent);
						function cmplzEnableCustomBlockedContent(consentData) {
							if (consentData.consentLevel==='marketing' ){
								if (url.indexOf('cmplz_consent=1') === -1 ) {
									if (url.indexOf('?') !== -1) {url += '&';} else {url += '?';}
									url += 'cmplz_consent=1#cmplz_consent_area_anchor';
									window.location.replace(url);
								}
							}
						}
					});
				</script>
				<?php } else { ?>
				<script>
					jQuery(document).ready(function ($) {
						$(document).on("cmplzEnableScripts", cmplzEnableCustomBlockedContent);
						function cmplzEnableCustomBlockedContent(consentData) {
							if (consentData.consentLevel==='marketing' && !$("#cmplz_consent_area_anchor").length){
								location.reload();
							}
						}
					});
				</script>
				<?php } ?>
				<div class="cmplz-consent-area cmplz-accept-marketing <?php echo $cookie_name?>_consentarea" ><a href="#"><?php echo $blocked_text?></a></div>
				<?php
			}

			return ob_get_clean();
		}

		/**
		 * Check if we should use caching
		 * @param string $type
		 *
		 * @return bool
		 */
		private function use_cache( $type ) {

			//do not cache on multilanguage environments
			if ( function_exists( 'pll__' )
			     || function_exists( 'icl_translate' )
			) {
				return false;
			}

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				return false;
			}

			//do not cache for these types
			if ( ( $type === 'processing' ) || ( $type === 'dataleak' ) ) {
				return false;
			}

			return true;

		}


		/**
		 * checks if the current page contains the shortcode.
		 *
		 * @param int|bool $post_id
		 *
		 * @return boolean
		 * @since 1.0
		 */

		public function is_complianz_page( $post_id = false ) {
			$post_meta = get_post_meta( $post_id, 'cmplz_shortcode', false );
			if ( $post_meta ) {
				return true;
			}

			$shortcode = 'cmplz-document';
			$block     = 'complianz/document';
			$cookies_shortcode = 'cmplz-cookies';

			if ( $post_id ) {
				$post = get_post( $post_id );
			} else {
				global $post;
			}

			if ( $post ) {
				if ( cmplz_uses_gutenberg() && has_block( $block, $post ) ) {
					return true;
				}
				if ( has_shortcode( $post->post_content, $shortcode ) ) {
					return true;
				}
				if ( has_shortcode( $post->post_content, $cookies_shortcode ) ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * gets the  page that contains the shortcode or the gutenberg block
		 *
		 * @param string $type
		 * @param string $region
		 *
		 * @return int $page_id
		 * @since 1.0
		 */

		public function get_shortcode_page_id( $type, $region , $cache = true) {
			$shortcode = 'cmplz-document';
			$page_id   = $cache ? get_transient( 'cmplz_shortcode_' . $type . '-' . $region ) : false;

			if ( ! $page_id ) {
				$pages = get_pages();
				$type_region = ( $region == 'eu' ) ? $type : $type . '-' . $region;

				/**
				 * Gutenberg block check
				 *
				 * */
				foreach ( $pages as $page ) {
					$post_meta = get_post_meta( $page->ID, 'cmplz_shortcode',
						true );
					if ( $post_meta ) {
						$html = $post_meta;
					} else {
						$html = $page->post_content;
					}

					//check if block contains property
					if ( preg_match( '/"selectedDocument":"(.*?)"/i', $html,
						$matches )
					) {
						if ( $matches[1] === $type_region ) {

							set_transient( "cmplz_shortcode_$type-$region",
								$page->ID, HOUR_IN_SECONDS );

							return $page->ID;
						}
					}
				}

				/**
				 * If nothing found, or if not Gutenberg, check for shortcodes.
				 * Classic Editor, modern shortcode check
				 *
				 * */

				foreach ( $pages as $page ) {
					$post_meta = get_post_meta( $page->ID, 'cmplz_shortcode',
						true );
					if ( $post_meta ) {
						$html = $post_meta;
					} else {
						$html = $page->post_content;
					}

					if ( has_shortcode( $html, $shortcode )
					     && strpos( $html, 'type="' . $type . '"' ) !== false
					     && strpos( $html, 'region="' . $region . '"' )
					        !== false
					) {
						set_transient( "cmplz_shortcode_$type-$region",
							$page->ID, HOUR_IN_SECONDS );

						return $page->ID;
					}
				}

				/**
				 * 	legacy check
				 */

				foreach ( $pages as $page ) {
					$post_meta = get_post_meta( $page->ID, 'cmplz_shortcode',
						true );
					if ( $post_meta ) {
						$html = $post_meta;
					} else {
						$html = $page->post_content;
					}

					//if the region is eu, we should not match if there's a region defined.
					if ( $region==='eu' && strpos($html, ' region="') !== FALSE ) {
						continue;
					}

					if ( has_shortcode( $html, $shortcode )
					     && strpos( $html, 'type="' . $type_region . '"' )
					        !== false
					) {
						set_transient( "cmplz_shortcode_$type-$region",
							$page->ID, HOUR_IN_SECONDS );

						return $page->ID;
					}
				}
			} else {
				return $page_id;
			}


			return false;
		}


		/**
		 * clear shortcode transients after page update
		 *
		 * @param int|bool    $post_id
		 * @param object|bool $post
		 *
		 * @hooked save_post which is why the $post param is passed without being used.
		 *
		 * @return void
		 */


		public function clear_shortcode_transients(
			$post_id = false, $post = false
		) {
			$regions = cmplz_get_regions();
			foreach ( $regions as $region => $label ) {
				foreach (
					COMPLIANZ::$config->pages[ $region ] as $type => $page
				) {
					//if a post id is passed, this is from the save post hook. We only clear the transient for this specific post id.
					if ( $post_id ) {
						if ( get_transient( "cmplz_shortcode_$type-$region" )
						     == $post_id
						) {
							delete_transient( "cmplz_shortcode_$type-$region" );
						}

					} else {
						delete_transient( "cmplz_shortcode_$type-$region" );
					}
				}
			}
		}

		/**
		 *
		 * get the URl of a specific page type
		 *
		 * @param string $type cookie-policy, privacy-statement, etc
		 * @param string $region
		 * @return string
		 *
		 *
		 */

		public function get_page_url( $type, $region ) {
			if ( ! cmplz_has_region( $region ) ) {
				return '#';
			}

			if ( cmplz_get_value( $type ) === 'none' ) {
				return '#';
			} else if ( cmplz_get_value( $type ) === 'custom' ) {
				$id = get_option( "cmplz_" . $type . "_custom_page" );
				//get correct translated id
				$id = apply_filters( 'wpml_object_id', $id,
					'page', true, substr( get_locale(), 0, 2 ) );
				return intval( $id ) == 0
					? '#'
					: esc_url_raw( get_permalink( $id ) );
			} else if ( cmplz_get_value( $type ) === 'url' ) {
					$url = get_option("cmplz_".$type."_custom_page_url");
					return esc_url_raw( $url );
			} else {
				$policy_page_id = $this->get_shortcode_page_id( $type, $region );

				//get correct translated id
				$policy_page_id = apply_filters( 'wpml_object_id', $policy_page_id,
					'page', true, substr( get_locale(), 0, 2 ) );

				return get_permalink( $policy_page_id );
			}
		}

		/**
		 * Set default privacy page for WP, based on primary region
		 * @param $page_id
		 * @param $type
		 * @param $region
		 */

		public function set_wp_privacy_policy($page_id, $type, $region=false){
			$primary_region = COMPLIANZ::$company->get_default_region();

			if ($region && $region !== $primary_region) return;

			if ($type == 'privacy-statement') {
				update_option('wp_page_for_privacy_policy', intval($page_id));
			}

		}


		/**
		 *
		 * get the title of a specific page type. Only in use for generated docs from Complianz.
		 *
		 * @param string $type cookie-policy, privacy-statement, etc
		 * @param string $region
		 *
		 * @return string $title
		 */

		public function get_document_title( $type, $region ) {

			if ( cmplz_get_value( $type ) === 'custom' || cmplz_get_value( $type ) === 'generated' ) {
				if ( cmplz_get_value( $type ) === 'custom' ) {
					$policy_page_id = get_option( "cmplz_" . $type . "_custom_page" );
				} else if ( cmplz_get_value( $type ) === 'generated' ) {
					$policy_page_id = $this->get_shortcode_page_id( $type, $region );
				}

				//get correct translated id
				$policy_page_id = apply_filters( 'wpml_object_id',
					$policy_page_id,
					'page', true, substr( get_locale(), 0, 2 ) );

				$post = get_post( $policy_page_id );
				if ( $post ) {
					return $post->post_title;
				}
			}

			return str_replace('-', ' ', $type);
		}

		/**
		 * Generate the cookie policy snapshot
		 */

		public function generate_cookie_policy_snapshot( $force = false ) {
			if ( ! $force
			     && ! get_option( 'cmplz_generate_new_cookiepolicy_snapshot' )
			) {
				return;
			}

			$regions = cmplz_get_regions();
			foreach ( $regions as $region => $label ) {
				$banner_id = cmplz_get_default_banner_id();
				$banner    = new CMPLZ_COOKIEBANNER( $banner_id );
				$settings  = $banner->get_settings_array();
				$settings['privacy_link_us ']
					           = COMPLIANZ::$document->get_page_url( 'privacy-statement', 'us' );
				$settings_html = '';
				$skip          = array(
					'categorie',
					'use_custom_cookie_css',
					'custom_css_amp',
					'static',
					'set_cookies',
					'hide_revoke',
					'popup_background_color',
					'popup_text_color',
					'button_background_color',
					'button_text_color',
					'position',
					'theme',
					'version',
					'banner_version',
					'a_b_testing',
					'title',
					'privacy_link',
					'nonce',
					'url',
					'current_policy_id',
					'type',
					'layout',
					'use_custom_css',
					'custom_css',
					'border_color',
					'accept_all_background_color',
					'accept_all_text_color',
					'accept_all_border_color',
					'functional_background_color',
					'functional_text_color',
					'functional_border_color',
					'banner_width',
				);
				$cats_pattern = '/data-category="(.*?)"/i';
				if (isset($settings['categories'])) {
					if ( preg_match_all( $cats_pattern, $settings['categories'],
						$matches )
					) {
						$categories = $matches[1];
						foreach($categories as $index => $category ) {
							$category = str_replace('cmplz_', '', $category);
							if (is_numeric(intval($category))) {
								$category = 'Custom event ' . $category;
							}
							$categories[$index] = $category;
						}
						$settings['categories'] =  implode(', ', $categories);
					}
				}


				unset( $settings["readmore_url"] );
				$settings = apply_filters( 'cmplz_cookie_policy_snapshot_settings' ,$settings );

				foreach ( $settings as $key => $value ) {

					if ( in_array( $key, $skip ) ) {
						continue;
					}
//                    if (empty($value)) $value = __("no","complianz-gdpr");
					if (is_array($value)) $value = implode(',', $value);
					$settings_html .= '<li>' . $key . ' => ' . esc_html( $value ) . '</li>';
				}

				$settings_html = '<div><h1>' . __( 'Cookie consent settings', 'complianz-gdpr' ) . '</h1><ul>' . ( $settings_html ) . '</ul></div>';
				$intro         = '<h1>' . __( "Proof of Consent",
						"complianz-gdpr" ) . '</h1>
                     <p>' . sprintf( __( "This document was generated to show efforts made to comply with privacy legislation.
                            This document will contain the Cookie Policy and the cookie consent settings to proof consent
                            for the time and region specified below. For more information about this document, please go
                            to %shttps://complianz.io/consent%s.",
						"complianz-gdpr" ),
						'<a target="_blank" href="https://complianz.io/consent">',
						"</a>" ) . '</p>';
				$this->generate_pdf( 'cookie-statement', $region, false, true, $intro, $settings_html );
			}
			update_option( 'cmplz_generate_new_cookiepolicy_snapshot', false );
		}

		/**
		 * Function to generate a pdf file, either saving to file, or echo to browser
		 *
		 * @param $page
		 * @param $post_id
		 * @param $save_to_file
		 * @param $append //if we want to add addition html
		 *
		 * @throws \Mpdf\MpdfException
		 */

		public function generate_pdf(
			$page, $region, $post_id = false, $save_to_file = false,
			$intro = '', $append = ''
		) {
			if ( ! defined( 'DOING_CRON' ) ) {
				if ( ! is_user_logged_in() ) {
					die( "invalid command" );
				}

				if ( ! current_user_can( 'manage_options' ) ) {
					die( "invalid command" );
				}
			}

			$error      = false;
			$temp_dir = false;
			$uploads    = wp_upload_dir();
			$upload_dir = $uploads['basedir'];

			if ( ! isset( COMPLIANZ::$config->pages[ $region ] ) ) {
				return;
			}

			$pages = COMPLIANZ::$config->pages[ $region ];

			//double check if it exists
			if ( ! isset( $pages[ $page ] ) ) {
				return;
			}
			$title         = $pages[ $page ]['title'];
			$document_html = $intro . COMPLIANZ::$document->get_document_html( $page, $region, $post_id ) . $append;
			$document_html = apply_filters( 'cmplz_cookie_policy_snapshot_html', $document_html , $save_to_file );

			//prevent hidden fields
			$document_html = str_replace('cmplz-service-hidden', '', $document_html);

			$load_css      = cmplz_get_value( 'use_document_css' );
			$css           = '';

			if ( $load_css ) {
				$css = file_get_contents( cmplz_path . "assets/css/document.css" );
			}
			$title_html = $save_to_file ? ''
				: '<h4 class="center">' . $title . '</h4>';

			$html = '
                    <style>
                    ' . $css . '
                    body {
                      font-family: sans;
                      margin-top:100px;
                      color :#000;
                    }
                    h2 {
                        font-size:12pt;
                    }
                    h3 {
                        font-size:12pt;
                    }
                    h4 {
                        font-size:10pt;
                        font-weight: bold;
                    }
                    .center {
                      text-align:center;
                    }
					 #cmplz-tcf-buttons-template, #cmplz-tcf-vendor-template, #cmplz-tcf-type-template {
						display:none !important;
					}
                    </style>

                    <body >
                    ' . $title_html . '
                    ' . $document_html . '
                    </body>';

			//==============================================================
			//==============================================================
			//==============================================================

			require cmplz_path . '/assets/vendor/autoload.php';

			//generate a token when it's not there, otherwise use the existing one.
			if ( get_option( 'cmplz_pdf_dir_token' ) ) {
				$token = get_option( 'cmplz_pdf_dir_token' );
			} else {
				$token = time();
				update_option( 'cmplz_pdf_dir_token', $token );
			}

			if ( $save_to_file && ! is_writable( $upload_dir ) ) {
				$error = true;
			}

			if ( ! $error ) {
				if ( ! file_exists( $upload_dir . '/complianz' ) ) {
					mkdir( $upload_dir . '/complianz' );
				}
				if ( ! file_exists( $upload_dir . '/complianz/tmp' ) ) {
					mkdir( $upload_dir . '/complianz/tmp' );
				}
				if ( ! file_exists( $upload_dir . '/complianz/snapshots' ) ) {
					mkdir( $upload_dir . '/complianz/snapshots' );
				}
				$save_dir = $upload_dir . '/complianz/snapshots/';
				$temp_dir = $upload_dir . '/complianz/tmp/' . $token;
				if ( ! file_exists( $temp_dir ) ) {
					mkdir( $temp_dir );
				}
			}

			if ( ! $error && $temp_dir) {
				$mpdf = new Mpdf\Mpdf( array(
					'setAutoTopMargin'  => 'stretch',
					'autoMarginPadding' => 5,
					'tempDir'           => $temp_dir,
					'margin_left'       => 20,
					'margin_right'      => 20,
					'margin_top'        => 30,
					'margin_bottom'     => 30,
					'margin_header'     => 30,
					'margin_footer'     => 10,
				) );

				$mpdf->SetDisplayMode( 'fullpage' );
				$mpdf->SetTitle( $title );

				$img  = '';//'<img class="center" src="" width="150px">';
				$date = date_i18n( get_option( 'date_format' ), time() );

				$mpdf->SetHTMLHeader( $img );
				$footer_text = sprintf( "%s $title $date",
					get_bloginfo( 'name' ) );

				$mpdf->SetFooter( $footer_text );
				$mpdf->WriteHTML( $html );

				// Save the pages to a file
				if ( $save_to_file ) {
					$file_title = $save_dir . sanitize_file_name( get_bloginfo( 'name' )
					                                    . '-' . $region
					                                    . "-proof-of-consent-"
					                                    . $date );
				} else {
					$file_title = sanitize_file_name( get_bloginfo( 'name' )
					                                  . "-export-" . $date );
				}

				$output_mode = $save_to_file ? 'F' : 'I';
				$mpdf->Output( $file_title . ".pdf", $output_mode );
			} else {
				$_POST['cmplz_generate_snapshot_error'] = true;
				unset( $_POST['cmplz_generate_snapshot'] );
			}
		}
	}


} //class closure
