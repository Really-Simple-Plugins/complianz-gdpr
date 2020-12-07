<?php
/*100% match*/

defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

if ( ! class_exists( "cmplz_field" ) ) {
	class cmplz_field {
		private static $_this;
		public $position;
		public $fields;
		public $default_args;
		public $form_errors = array();

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			//safe before the fields are loaded in config, in init
			add_action( 'plugins_loaded', array( $this, 'process_save' ), 14 );
			add_action( 'cmplz_register_translation',
				array( $this, 'register_translation' ), 10, 2 );

			add_action( 'complianz_before_label',
				array( $this, 'before_label' ), 10, 1 );
			add_action( 'complianz_before_label', array( $this, 'show_errors' ),
				10, 1 );
			add_action( 'complianz_after_label', array( $this, 'after_label' ),
				10, 1 );
			add_action( 'complianz_after_field', array( $this, 'after_field' ),
				10, 1 );

			$this->load();
		}

		static function this() {
			return self::$_this;
		}


		/**
		 * Register each string in supported string translation tools
		 *
		 */

		public function register_translation( $fieldname, $string ) {
			//polylang
			if ( function_exists( "pll_register_string" ) ) {
				pll_register_string( $fieldname, $string, 'complianz' );
			}

			//wpml
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'complianz', $fieldname, $string );
			}

			do_action( 'wpml_register_single_string', 'complianz', $fieldname,
				$string );
		}

		public function load() {
			$this->default_args = array(
				"fieldname"          => '',
				"type"               => 'text',
				"required"           => false,
				'default'            => '',
				'label'              => '',
				'table'              => false,
				'callback_condition' => false,
				'condition'          => false,
				'callback'           => false,
				'placeholder'        => '',
				'optional'           => false,
				'disabled'           => false,
				'hidden'             => false,
				'region'             => false,
				'media'              => true,
				'first'              => false,
				'warn'               => false,
				'cols'               => false,
			);


		}

		public function process_save() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( isset( $_POST['complianz_nonce'] ) ) {
				//check nonce
				if ( ! isset( $_POST['complianz_nonce'] )
				     || ! wp_verify_nonce( $_POST['complianz_nonce'],
						'complianz_save' )
				) {
					return;
				}

				$fields = COMPLIANZ::$config->fields();

				//remove multiple field
				if ( isset( $_POST['cmplz_remove_multiple'] ) ) {
					$fieldnames = array_map( function ( $el ) {
						return sanitize_title( $el );
					}, $_POST['cmplz_remove_multiple'] );

					foreach ( $fieldnames as $fieldname => $key ) {

						$page    = $fields[ $fieldname ]['source'];
						$options = get_option( 'complianz_options_' . $page );

						$multiple_field = $this->get_value( $fieldname,
							array() );

						unset( $multiple_field[ $key ] );

						$options[ $fieldname ] = $multiple_field;
						if ( ! empty( $options ) ) {
							update_option( 'complianz_options_' . $page,
								$options );
						}
					}
				}

				//add multiple field
				if ( isset( $_POST['cmplz_add_multiple'] ) ) {
					$fieldname
						= $this->sanitize_fieldname( $_POST['cmplz_add_multiple'] );
					$this->add_multiple_field( $fieldname );
				}

				//save multiple field
				if ( ( isset( $_POST['cmplz-save'] )
				       || isset( $_POST['cmplz-next'] ) )
				     && isset( $_POST['cmplz_multiple'] )
				) {
					$fieldnames
						= $this->sanitize_array( $_POST['cmplz_multiple'] );
					$this->save_multiple( $fieldnames );
				}

				//Save the custom URL's for not Complianz generated pages.
				$docs = COMPLIANZ::$document->get_document_types();
				foreach ($docs as $document){
					if (isset($_POST["cmplz_".$document."_custom_page"])){
						$doc_id = intval($_POST["cmplz_".$document."_custom_page"]);
						update_option("cmplz_".$document."_custom_page", $doc_id );
						//if we have an actual privacy statement, custom, set it as privacy url for WP
						if ($document==='privacy-statement' && $doc_id > 0){
							COMPLIANZ::$document->set_wp_privacy_policy($doc_id, 'privacy-statement');
						}
					}
					if (isset($_POST["cmplz_".$document."_custom_page_url"])){
						$url = esc_url_raw($_POST["cmplz_".$document."_custom_page_url"]);
						update_option("cmplz_".$document."_custom_page_url", $url );
					}
				}

				//save data
				$posted_fields = array_filter( $_POST, array( $this, 'filter_complianz_fields' ), ARRAY_FILTER_USE_KEY );
				foreach ( $posted_fields as $fieldname => $fieldvalue ) {
					$this->save_field( $fieldname, $fieldvalue );
				}
				do_action('cmplz_after_saved_all_fields', $posted_fields );
			}
		}



		/**
		 * santize an array for save storage
		 *
		 * @param $array
		 *
		 * @return mixed
		 */

		public function sanitize_array( $array ) {
			foreach ( $array as &$value ) {
				if ( ! is_array( $value ) ) {
					$value = sanitize_text_field( $value );
				} //if ($value === 'on') $value = true;
				else {
					$this->sanitize_array( $value );
				}
			}

			return $array;

		}



		/**
		 * Check if this is a conditional field
		 *
		 * @param $fieldname
		 *
		 * @return bool
		 */

		public function is_conditional( $fieldname ) {
			$fields = COMPLIANZ::$config->fields();
			if ( isset( $fields[ $fieldname ]['condition'] )
			     && $fields[ $fieldname ]['condition']
			) {
				return true;
			}

			return false;
		}

		/**
		 * Check if this is a multiple field
		 *
		 * @param $fieldname
		 *
		 * @return bool
		 */

		public function is_multiple_field( $fieldname ) {
			$fields = COMPLIANZ::$config->fields();
			if ( isset( $fields[ $fieldname ]['type'] )
			     && ( $fields[ $fieldname ]['type'] == 'thirdparties' )
			) {
				return true;
			}
			if ( isset( $fields[ $fieldname ]['type'] )
			     && ( $fields[ $fieldname ]['type'] == 'processors' )
			) {
				return true;
			}

			return false;
		}


		public function save_multiple( $fieldnames ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$fields = COMPLIANZ::$config->fields();
			foreach ( $fieldnames as $fieldname => $saved_fields ) {

				if ( ! isset( $fields[ $fieldname ] ) ) {
					return;
				}

				$page           = $fields[ $fieldname ]['source'];
				$type           = $fields[ $fieldname ]['type'];
				$options        = get_option( 'complianz_options_' . $page );
				$multiple_field = $this->get_value( $fieldname, array() );


				foreach ( $saved_fields as $key => $value ) {
					$value = is_array( $value )
						? array_map( 'sanitize_text_field', $value )
						: sanitize_text_field( $value );
					//store the fact that this value was saved from the back-end, so should not get overwritten.
					$value['saved_by_user'] = true;
					$multiple_field[ $key ] = $value;

					//make cookies and thirdparties translatable
					if ( $type === 'cookies' || $type === 'thirdparties'
					     || $type === 'processors'
					     || $type === 'editor'
					) {
						if ( isset( $fields[ $fieldname ]['translatable'] )
						     && $fields[ $fieldname ]['translatable']
						) {
							foreach ( $value as $value_key => $field_value ) {
								do_action( 'cmplz_register_translation',
									$key . '_' . $fieldname . "_" . $value_key,
									$field_value );
							}
						}
					}
				}

				$options[ $fieldname ] = $multiple_field;
				if ( ! empty( $options ) ) {
					update_option( 'complianz_options_' . $page, $options );
				}
			}
		}

		/**
		 * Save the field
		 * @param string $fieldname
		 * @param mixed $fieldvalue
		 */

		public function save_field( $fieldname, $fieldvalue ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$fieldvalue = apply_filters("cmplz_fieldvalue", $fieldvalue, $fieldname);
			$fields    = COMPLIANZ::$config->fields();
			$fieldname = str_replace( "cmplz_", '', $fieldname );

			//do not save callback fields
			if ( isset( $fields[ $fieldname ]['callback'] ) ) {
				return;
			}

			$type     = $fields[ $fieldname ]['type'];
			$page     = $fields[ $fieldname ]['source'];
			$required = isset( $fields[ $fieldname ]['required'] ) ? $fields[ $fieldname ]['required'] : false;
			$fieldvalue = $this->sanitize( $fieldvalue, $type );

			if ( ! $this->is_conditional( $fieldname ) && $required
			     && empty( $fieldvalue )
			) {
				$this->form_errors[] = $fieldname;
			}

			//make translatable
			if ( $type == 'text' || $type == 'textarea' || $type == 'editor' ) {
				if ( isset( $fields[ $fieldname ]['translatable'] )
				     && $fields[ $fieldname ]['translatable']
				) {
					do_action( 'cmplz_register_translation', $fieldname, $fieldvalue );
				}
			}

			$options = get_option( 'complianz_options_' . $page );
			if ( ! is_array( $options ) ) {
				$options = array();
			}
			$prev_value = isset( $options[ $fieldname ] ) ? $options[ $fieldname ] : false;
			do_action( "complianz_before_save_" . $page . "_option", $fieldname, $fieldvalue, $prev_value, $type );
			$options[ $fieldname ] = $fieldvalue;

			if ( ! empty( $options ) ) {
				update_option( 'complianz_options_' . $page, $options );
			}

			do_action( "complianz_after_save_" . $page . "_option", $fieldname, $fieldvalue, $prev_value, $type );
		}


		public function add_multiple_field( $fieldname, $cookie_type = false ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$fields = COMPLIANZ::$config->fields();

			$page    = $fields[ $fieldname ]['source'];
			$options = get_option( 'complianz_options_' . $page );

			$multiple_field = $this->get_value( $fieldname, array() );
			if ( $fieldname === 'used_cookies' && ! $cookie_type ) {
				$cookie_type = 'custom_' . time();
			}
			if ( ! is_array( $multiple_field ) ) {
				$multiple_field = array( $multiple_field );
			}

			if ( $cookie_type ) {
				//prevent key from being added twice
				foreach ( $multiple_field as $index => $cookie ) {
					if ( $cookie['key'] === $cookie_type ) {
						return;
					}
				}

				//don't add field if it was deleted previously
				$deleted_cookies = get_option( 'cmplz_deleted_cookies' );
				if ( ( $deleted_cookies
				       && in_array( $cookie_type, $deleted_cookies ) )
				) {
					return;
				}

				//don't add default wordpress cookies
				if ( strpos( $cookie_type, 'wordpress_' ) !== false ) {
					return;
				}

				$multiple_field[] = array( 'key' => $cookie_type );
			} else {
				$multiple_field[] = array();
			}

			$options[ $fieldname ] = $multiple_field;

			if ( ! empty( $options ) ) {
				update_option( 'complianz_options_' . $page, $options );
			}
		}

		/**
		 * Sanitize a field
		 * @param $value
		 * @param $type
		 *
		 * @return array|bool|int|string|void
		 */
		public function sanitize( $value, $type ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}
			switch ( $type ) {
				case 'colorpicker':
					return sanitize_hex_color( $value );
				case 'text':
					return sanitize_text_field( $value );
				case 'multicheckbox':
					if ( ! is_array( $value ) ) {
						$value = array( $value );
					}

					return array_map( 'sanitize_text_field', $value );
				case 'phone':
					$value = sanitize_text_field( $value );

					return $value;
				case 'email':
					return sanitize_email( $value );
				case 'url':
					return esc_url_raw( $value );
				case 'number':
					return intval( $value );
				case 'css':
				case 'javascript':
					return  $value ;
				case 'editor':
				case 'textarea':
					return wp_kses_post( $value );
			}

			return sanitize_text_field( $value );
		}

		/**/

		private
		function filter_complianz_fields(
			$fieldname
		) {
			if ( strpos( $fieldname, 'cmplz_' ) !== false
			     && isset( COMPLIANZ::$config->fields[ str_replace( 'cmplz_',
						'', $fieldname ) ] )
			) {
				return true;
			}

			return false;
		}

		public
		function before_label(
			$args
		) {

			$condition          = false;
			$condition_question = '';
			$condition_answer   = '';

			if ( ! empty( $args['condition'] ) ) {
				$condition          = true;
				$condition_answer   = reset( $args['condition'] );
				$condition_question = key( $args['condition'] );
			}
			$condition_class = $condition ? 'condition-check' : '';
			$hidden_class    = ( $args['hidden'] ) ? 'hidden' : '';
			$first_class     = ( $args['first'] ) ? 'first' : '';
			$type            = $args['type'] === 'notice' ? '' : $args['type'];
			$cols            = $args['cols'];
			$cols_class = $cols ? "cmplz-cols-$cols" : '';

			$this->get_master_label( $args );
			if ( $args['table'] ) {
				echo '<tr class="cmplz-settings field-group '
				     . esc_attr( 'cmplz-' . $args['fieldname'] . ' cmplz-'
				                 . $args['type'] . ' ' . $hidden_class . ' '
				                 . $condition_class ) . ' "';
				echo $condition ? 'data-condition-question="'
				                  . esc_attr( $condition_question )
				                  . '" data-condition-answer="'
				                  . esc_attr( $condition_answer ) . '"' : '';
				echo '><th scope="row">';
			} else {
				echo '<div class="field-group ' . esc_attr( $args['fieldname'] . ' '
	                                            . esc_attr( $cols_class ) . ' '
				                                            .'cmplz-'. $type . ' '
				                                            . $hidden_class . ' '
				                                            . $first_class . ' '
				                                            . $condition_class )
				     . '" ';
				echo $condition ? 'data-condition-question="'
				                  . esc_attr( $condition_question )
				                  . '" data-condition-answer="'
				                  . esc_attr( $condition_answer ) . '"' : '';
				echo '><div class="cmplz-label">';
			}
		}

		public function get_master_label( $args ) {
			if ( ! isset( $args['master_label'] ) ) {
				return;
			}
			?>
			<div
				class="cmplz-master-label"><?php echo esc_html( $args['master_label'] ) ?></div>
			<hr>
			<?php

		}

		public
		function show_errors(
			$args
		) {
			if ( in_array( $args['fieldname'], $this->form_errors ) ) {
				?>
				<div class="cmplz-form-errors">
					<?php _e( "This field is required. Please complete the question before continuing",
						'complianz-gdpr' ) ?>
				</div>
				<?php
			}
		}

		public
		function after_label(
			$args
		) {
			if ( $args['table'] ) {
				echo '</th><td>';
			} else {
				echo '</div><div class="cmplz-field">';
			}

			do_action( 'cmplz_notice_' . $args['fieldname'], $args );

		}

		public
		function after_field(
			$args
		) {
			$this->get_comment( $args );

			if ( $args['table'] ) {
				echo '</td><td>' . $this->get_help_tip( $args ) . '</td></tr>';
			} else {
				echo '</div>' . $this->get_help_tip( $args ) . '</div>';
			}
		}


		public
		function text(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];

			$value = $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<input <?php if ( $args['required'] ) {
				echo 'required';
			} ?>
				class="validation <?php if ( $args['required'] ) {
					echo 'is-required';
				} ?>"
				placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
				type="text"
				value="<?php echo esc_html( $value ) ?>"
				name="<?php echo esc_html( $fieldname ) ?>">
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function url(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'],
				$args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<input <?php if ( $args['required'] ) {
				echo 'required';
			} ?>
				class="validation <?php if ( $args['required'] ) {
					echo 'is-required';
				} ?>"
				placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
				type="text"
				pattern="^(http(s)?(:\/\/))?(www\.)?[#a-zA-Z0-9-_\.\/\:]+"
				value="<?php echo esc_html( $value ) ?>"
				name="<?php echo esc_html( $fieldname ) ?>">
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function email(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'],
				$args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<input <?php if ( $args['required'] ) {
				echo 'required';
			} ?>
				class="validation <?php if ( $args['required'] ) {
					echo 'is-required';
				} ?>"
				placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
				type="email"
				value="<?php echo esc_html( $value ) ?>"
				name="<?php echo esc_html( $fieldname ) ?>">
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function phone(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'],
				$args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<input autocomplete="tel" <?php if ( $args['required'] ) {
				echo 'required';
			} ?>
			       class="validation <?php if ( $args['required'] ) {
				       echo 'is-required';
			       } ?>"
			       placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
			       type="text"
			       value="<?php echo esc_html( $value ) ?>"
			       name="<?php echo esc_html( $fieldname ) ?>">
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function number(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'],
				$args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<input <?php if ( $args['required'] ) {
				echo 'required';
			} ?>
				class="validation <?php if ( $args['required'] ) {
					echo 'is-required';
				} ?>"
				placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
				type="number"
				value="<?php echo esc_html( $value ) ?>"
				name="<?php echo esc_html( $fieldname ) ?>"
				min="0" step="<?php echo isset($args["validation_step"]) ? intval($args["validation_step"]) : 1?>"
				>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}


		public
		function checkbox(
			$args, $force_value = false
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];

			$value             = $force_value ? $force_value
				: $this->get_value( $args['fieldname'], $args['default'] );
			$placeholder_value = ( $args['disabled'] && $value ) ? $value : 0;
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>

			<label class="<?php if ( $args['disabled'] ) {
				echo 'cmplz-disabled';
			} ?>"
			       for="<?php echo esc_html( $fieldname ) ?>-label"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>

			<?php do_action( 'complianz_after_label', $args ); ?>

			<label class="cmplz-switch">
				<input name="<?php echo esc_html( $fieldname ) ?>" type="hidden"
				       value="<?php echo $placeholder_value ?>"/>

				<input name="<?php echo esc_html( $fieldname ) ?>" size="40"
				       type="checkbox"
					<?php if ( $args['disabled'] ) {
						echo 'disabled';
					} ?>
					   class="<?php if ( $args['required'] ) {
						   echo 'is-required';
					   } ?>"
					   value="1" <?php checked( 1, $value, true ) ?> />
				<span class="cmplz-slider cmplz-round"></span>
			</label>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function multicheckbox(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'] );
			if ( ! is_array( $value ) ) {
				$value = array();
			}

			//if no value at all has been set, assign a default value
			$has_selection = false;
			foreach ( $value as $key => $index ) {
				if ( $index == 1 ) {
					$has_selection = true;
					break;
				}
			}

			$default_index = $args['default'];

			if ( ! $this->show_field( $args ) ) {
				return;
			}

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>

			<label
				for="<?php echo esc_html( $fieldname ) ?>"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>

			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php if ( ! empty( $args['options'] ) ) { ?>
				<div class="<?php if ( $args['required'] )
					echo 'cmplz-validate-multicheckbox' ?>">
					<?php foreach (
						$args['options'] as $option_key => $option_label
					) {
						$sel_key = false;
						if ( ! $has_selection ) {
							if (is_array($default_index)) {
								if ($default_index[$option_key] == 1) {
									$sel_key = $option_key;
								}
							} else {
								$sel_key = $default_index;

							}

						} elseif ( isset( $value[ $option_key ] )
						           && $value[ $option_key ]
						) {
							$sel_key = $option_key;
						}
						$disabled = '';
						if (is_array($args['disabled']) && in_array($option_key, $args['disabled'])) {
							$disabled = 'disabled';
						}
						?>
						<div>
							<input
								name="<?php echo esc_html( $fieldname ) ?>[<?php echo $option_key ?>]"
								type="hidden" value="<?php echo $disabled && ( (string) ( $sel_key == (string) $option_key ) ) ? 1 : ''?>"/>
							<input <?php echo $disabled?> class="<?php if ( $args['required'] ) {
								echo 'is-required';
							} ?>"
							       name="<?php echo esc_html( $fieldname ) ?>[<?php echo $option_key ?>]"
							       size="40" type="checkbox"
							       value="1" <?php echo ( (string) ( $sel_key == (string) $option_key ) )
								? "checked" : "" ?> >
							<label>
								<?php echo esc_html( $option_label ) ?>
							</label>
						</div>
					<?php } ?>
				</div>
			<?php } else {
				cmplz_notice( __( 'No options found', 'complianz-gdpr' ) );
			} ?>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function radio(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'], $args['default'] );
			$options   = $args['options'];

			if ( ! $this->show_field( $args ) ) {
				return;
			}

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>

			<label for="<?php echo $args['fieldname'] ?>"><?php echo $args['label'] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>

			<?php do_action( 'complianz_after_label', $args ); ?>
			<div class="cmplz-validate-radio">
				<?php
				if ( ! empty( $options ) ) {
					if ( $args['disabled'] ) {
						echo '<input type="hidden" value="' . $args['default'] . '" name="' . $fieldname . '">';
					}
					foreach ( $options as $option_value => $option_label ) {
						?>
						<input <?php
							if ( !is_array($args['disabled']) ) {
								if ($args['disabled']) echo "disabled";
							} else {
								if ( in_array($option_value, $args['disabled'] ) ) {
									echo "disabled";
								}
							}
								?>
							<?php if ( $args['required'] ) {
								echo "required";
							} ?>
							type="radio"
							id="<?php echo esc_html( $fieldname ) ?>"
							name="<?php echo esc_html( $fieldname ) ?>"
							value="<?php echo esc_html( $option_value ); ?>" <?php if ( $value
							                                                            == $option_value
						)
							echo "checked" ?>>
						<label class="">
							<?php echo esc_html( $option_label ); ?>
						</label>
						<div class="clear"></div>
					<?php }
				}
				?>
			</div>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function document(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'], $args['default'] );
			$doc_args = array(
				'post_type' => 'page',
				'posts_per_page' => -1,
			);
			$pages = get_posts($doc_args);
			$pages = wp_list_pluck($pages, 'post_title','ID' );
			$custom_page_id = get_option('cmplz_'.$args['fieldname'].'_custom_page');
			if ( ! $this->show_field( $args ) ) {
				return;
			}

			/**
			 * If there's no active privacy statement, use the wp privacy statement, if available
			 */
			if ( $args['fieldname'] === 'privacy-statement' && !$custom_page_id ){
				$wp_privacy_policy = get_option('wp_page_for_privacy_policy');
				if ($wp_privacy_policy){
					$custom_page_id = $wp_privacy_policy;
				}
			}

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label for="<?php echo $args['fieldname'] ?>"><?php printf(__("Select how you want to add your %s.",'complianz-gdpr'), $args['label']) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<div class="cmplz-validate-radio cmplz-document-field" data-fieldname="<?php echo esc_html( $fieldname ) ?>">
				<input <?php if ( $args['disabled'] )
					echo "disabled" ?>
					<?php if ( $args['required'] ) {
						echo "required";
					} ?>
					type="radio"
					name="<?php echo esc_html( $fieldname ) ?>"
					value="generated" <?php if ( $value == 'generated'
				)
					echo "checked" ?> class="cmplz-document-input">
				<label class="">
					<?php
						if ($fieldname === 'cmplz_cookie-statement'){
							echo apply_filters("cmplz_generate_document_label", __("Generated by Complianz", "complianz-gdpr"), $args['fieldname']);
						} else {
							echo apply_filters("cmplz_generate_document_label", sprintf(__("Generate a comprehensive and legally validated %s with %spremium%s", "complianz-gdpr"), $args['label'],'<a href="https://complianz.io/l/pricing/" target="_blank">', '</a>' ), $args['fieldname']);
						}
					 ?>
				</label>
				<div class="clear"></div>

				<input
					<?php if ( $args['required'] ) {
						echo "required";
					} ?>
					type="radio"
					name="<?php echo esc_html( $fieldname ) ?>"
					value="custom"
					<?php if ( $value == 'custom' )
					echo "checked" ?> class="cmplz-document-input">
				<label class="">
					<?php _e("Link to custom page", "complianz-gdpr"); ?>
				</label>

				<div class="clear"></div>
				<input
					<?php if ( $args['required'] ) {
						echo "required";
					} ?>
					type="radio"
					name="<?php echo esc_html( $fieldname ) ?>"
					value="url"
					<?php if ( $value == 'url' )
						echo "checked" ?> class="cmplz-document-input">
				<label class="">
					<?php _e("Custom URL", "complianz-gdpr"); ?>
				</label>
				<div class="clear"></div>

				<?php if ( $args['fieldname'] !== 'cookie-statement' ){?>
					<input
						<?php if ( $args['required'] ) {
							echo "required";
						} ?>
						type="radio"
						name="<?php echo esc_html( $fieldname ) ?>"
						value="none"
						<?php if ( $value == 'none' )
							echo "checked" ?> class="cmplz-document-input">
					<label class="">
						<?php printf( __("No document", "complianz-gdpr"), $args['label'] ); ?>
					</label>
					<div class="clear"></div>
				<?php } ?>
				<input type="text" class="cmplz-document-custom-url" value="<?php echo get_option($fieldname."_custom_page_url")?>" placeholder = "https://domain.com/your-policy" name="<?php echo esc_html( $fieldname."_custom_page_url" ) ?>">

				<select class="cmplz-document-custom-page" name="<?php echo esc_html( $fieldname."_custom_page" ) ?>">
					<option value=""><?php _e("None selected", "complianz-gdpr")?></option>

					<?php foreach ($pages as $ID => $page){
						$selected = $ID==$custom_page_id ? "selected" : "";
						?>
						<option value="<?php echo $ID?>" <?=$selected?>><?php echo $page?></option>
					<?php } ?>
				</select>
			</div>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}


		public
		function show_field(
			$args
		) {
			$show = ( $this->condition_applies( $args, 'callback_condition' ) );

			return $show;
		}


		public function function_callback_applies( $func ) {
			$invert = false;

			if ( strpos( $func, 'NOT ' ) !== false ) {
				$invert = true;
				$func   = str_replace( 'NOT ', '', $func );
			}
			$show_field = $func();
			if ( $invert ) {
				$show_field = ! $show_field;
			}
			if ( $show_field ) {
				return true;
			} else {
				return false;
			}
		}

		public
		function condition_applies(
			$args, $type = false
		) {
			$default_args = $this->default_args;
			$args         = wp_parse_args( $args, $default_args );

			if ( ! $type ) {
				if ( $args['condition'] ) {
					$type = 'condition';
				} elseif ( $args['callback_condition'] ) {
					$type = 'callback_condition';
				}
			}

			if ( ! $type || ! $args[ $type ] ) {
				return true;
			}

			//function callbacks
			$maybe_is_function = str_replace( 'NOT ', '', $args[ $type ] );
			if ( ! is_array( $args[ $type ] ) && ! empty( $args[ $type ] )
			     && function_exists( $maybe_is_function )
			) {
				return $this->function_callback_applies( $args[ $type ] );
			}

			$condition = $args[ $type ];

			//if we're checking the condition, but there's also a callback condition, check that one as well.
			//but only if it's an array. Otherwise it's a func.
			if ( $type === 'condition' && isset( $args['callback_condition'] )
			     && is_array( $args['callback_condition'] )
			) {
				$condition += $args['callback_condition'];
			}

			foreach ( $condition as $c_fieldname => $c_value_content ) {
				$c_values = $c_value_content;
				//the possible multiple values are separated with comma instead of an array, so we can add NOT.
				if ( ! is_array( $c_value_content )
				     && strpos( $c_value_content, ',' ) !== false
				) {
					$c_values = explode( ',', $c_value_content );
				}
				$c_values = is_array( $c_values ) ? $c_values
					: array( $c_values );

				foreach ( $c_values as $c_value ) {
					$maybe_is_function = str_replace( 'NOT ', '', $c_value );
					if ( function_exists( $maybe_is_function ) ) {
						$match = $this->function_callback_applies( $c_value );
						if ( ! $match ) {
							return false;
						}
					} else {
						$actual_value = cmplz_get_value( $c_fieldname );

						$fieldtype = $this->get_field_type( $c_fieldname );

						if ( strpos( $c_value, 'NOT ' ) === false ) {
							$invert = false;
						} else {
							$invert  = true;
							$c_value = str_replace( "NOT ", "", $c_value );
						}

						if ( $fieldtype == 'multicheckbox' ) {
							if ( ! is_array( $actual_value ) ) {
								$actual_value = array( $actual_value );
							}
							//get all items that are set to true
							$actual_value = array_filter( $actual_value,
								function ( $item ) {
									return $item == 1;
								} );
							$actual_value = array_keys( $actual_value );

							if ( ! is_array( $actual_value ) ) {
								$actual_value = array( $actual_value );
							}
							$match = false;
							foreach ( $c_values as $check_each_value ) {
								if ( in_array( $check_each_value,
									$actual_value )
								) {
									$match = true;
								}
							}

						} else {
							//when the actual value is an array, it is enough when just one matches.
							//to be able to return false, for no match at all, we check all items, then return false if none matched
							//this way we can preserve the AND property of this function
							$match = ( $c_value === $actual_value
							           || in_array( $actual_value,
									$c_values ) );

						}
						if ( $invert ) {
							$match = ! $match;
						}
						if ( ! $match ) {
							return false;
						}
					}

				}
			}

			return true;
		}

		public function get_field_type( $fieldname ) {
			if ( ! isset( COMPLIANZ::$config->fields[ $fieldname ] ) ) {
				return false;
			}

			return COMPLIANZ::$config->fields[ $fieldname ]['type'];
		}

		public
		function textarea(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];

			$value = $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<textarea name="<?php echo esc_html( $fieldname ) ?>"
                      <?php if ( $args['required'] ) {
	                      echo 'required';
                      } ?>
                        class="validation <?php if ( $args['required'] ) {
	                        echo 'is-required';
                        } ?>"
                      placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"><?php echo esc_html( $value ) ?></textarea>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		/*
         * Show field with editor
         *
         *
         * */

		public
		function editor(
			$args, $step = ''
		) {
			$fieldname     = 'cmplz_' . $args['fieldname'];
			$args['first'] = true;
			$media         = $args['media'] ? true : false;

			$value = $this->get_value( $args['fieldname'], $args['default'] );

			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php
			$settings = array(
				'media_buttons' => $media,
				'editor_height' => 300,
				// In pixels, takes precedence and has no default value
				'textarea_rows' => 15,
			);
			wp_editor( $value, $fieldname, $settings ); ?>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function javascript(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'],
				$args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<div id="<?php echo esc_html( $fieldname ) ?>editor"
			     style="height: 200px; width: 100%"><?php echo $value ?></div>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<script>
				var <?php echo esc_html( $fieldname )?> =
				ace.edit("<?php echo esc_html( $fieldname )?>editor");
				<?php echo esc_html( $fieldname )?>.setTheme("ace/theme/monokai");
				<?php echo esc_html( $fieldname )?>.session.setMode("ace/mode/javascript");
				jQuery(document).ready(function ($) {
					var textarea = $('textarea[name="<?php echo esc_html( $fieldname )?>"]');
					<?php echo esc_html( $fieldname )?>.
					getSession().on("change", function () {
						textarea.val(<?php echo esc_html( $fieldname )?>.getSession().getValue()
					)
					});
				});
			</script>
			<textarea style="display:none"
			          name="<?php echo esc_html( $fieldname ) ?>"><?php echo $value ?></textarea>
			<?php
		}

		public
		function css(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];

			$value = $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo $args['fieldname'] ?>"><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<div id="<?php echo esc_html( $fieldname ) ?>editor"
			     style="height: 200px; width: 100%"><?php echo $value ?></div>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<script>
				var <?php echo esc_html( $fieldname )?> =
				ace.edit("<?php echo esc_html( $fieldname )?>editor");
				<?php echo esc_html( $fieldname )?>.setTheme("ace/theme/monokai");
				<?php echo esc_html( $fieldname )?>.session.setMode("ace/mode/css");
				jQuery(document).ready(function ($) {
					var textarea = $('textarea[name="<?php echo esc_html( $fieldname )?>"]');
					<?php echo esc_html( $fieldname )?>.
					getSession().on("change", function () {
						textarea.val(<?php echo esc_html( $fieldname )?>.getSession().getValue()
					)
					});
				});
			</script>
			<textarea style="display:none"
			          name="<?php echo esc_html( $fieldname ) ?>"><?php echo $value ?></textarea>
			<?php
		}


		public
		function colorpicker(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];

			$value = $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}


			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo esc_html( $fieldname ) ?>"><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<input type="hidden" name="<?php echo esc_html( $fieldname ) ?>"
			       id="<?php echo esc_html( $fieldname ) ?>"
			       value="<?php echo esc_html( $value ) ?>"
			       class="cmplz-color-picker-hidden">
			<input type="text" name="color_picker_container"
			       data-hidden-input='<?php echo esc_html( $fieldname ) ?>'
			       value="<?php echo esc_html( $value ) ?>"
			       class="cmplz-color-picker"
			       data-default-color="<?php echo esc_html( $args['default'] ) ?>">
			<?php do_action( 'complianz_after_field', $args ); ?>

			<?php
		}

		/**
		 * Check if a step has any fields
		 * @param string $page
		 * @param bool $step
		 * @param bool $section
		 *
		 * @return bool
		 */
		public function step_has_fields( $page, $step = false, $section = false ) {

			$fields = COMPLIANZ::$config->fields( $page, $step, $section );
			foreach ( $fields as $fieldname => $args ) {
				$default_args = $this->default_args;
				$args         = wp_parse_args( $args, $default_args );

				$type              = ( $args['callback'] ) ? 'callback'
					: $args['type'];
				$args['fieldname'] = $fieldname;

				if ( $type == 'callback' ) {
					return true;
				} else {
					if ( $this->show_field( $args ) ) {
						return true;
					}
				}
			}

			return false;
		}

		public
		function get_fields(
			$source, $step = false, $section = false, $get_by_fieldname = false
		) {

			$fields = COMPLIANZ::$config->fields( $source, $step, $section,
				$get_by_fieldname );


			$i = 0;
			foreach ( $fields as $fieldname => $args ) {
				if ( $i === 0 ) {
					$args['first'] = true;
				}
				$i ++;
				$default_args = $this->default_args;
				$args         = wp_parse_args( $args, $default_args );


				$type              = ( $args['callback'] ) ? 'callback'
					: $args['type'];
				$args['fieldname'] = $fieldname;
				switch ( $type ) {
					case 'callback':
						$this->callback( $args );
						break;
					case 'text':
						$this->text( $args );
						break;
					case 'document':
						$this->document( $args );
						break;
					case 'button':
						$this->button( $args );
						break;
					case 'upload':
						$this->upload( $args );
						break;
					case 'url':
						$this->url( $args );
						break;
					case 'select':
						$this->select( $args );
						break;
					case 'colorpicker':
						$this->colorpicker( $args );
						break;
					case 'checkbox':
						$this->checkbox( $args );
						break;
					case 'textarea':
						$this->textarea( $args );
						break;
					case 'cookies':
						$this->cookies( $args );
						break;
					case 'services':
						$this->services( $args );
						break;
					case 'multiple':
						$this->multiple( $args );
						break;
					case 'radio':
						$this->radio( $args );
						break;
					case 'multicheckbox':
						$this->multicheckbox( $args );
						break;
					case 'javascript':
						$this->javascript( $args );
						break;
					case 'css':
						$this->css( $args );
						break;
					case 'email':
						$this->email( $args );
						break;
					case 'phone':
						$this->phone( $args );
						break;
					case 'thirdparties':
						$this->thirdparties( $args );
						break;
					case 'processors':
						$this->processors( $args );
						break;
					case 'number':
						$this->number( $args );
						break;
					case 'notice':
						$this->notice( $args );
						break;
					case 'editor':
						$this->editor( $args, $step );
						break;
					case 'label':
						$this->label( $args );
						break;
				}
			}

		}

		public
		function callback(
			$args
		) {
			$callback = $args['callback'];
			do_action( "cmplz_$callback", $args );
		}

		public
		function notice(
			$args
		) {
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			do_action( 'complianz_before_label', $args );
			cmplz_notice( $args['label'], 'warning' );
			do_action( 'complianz_after_label', $args );
			do_action( 'complianz_after_field', $args );
		}

		public
		function select(
			$args
		) {

			$fieldname = 'cmplz_' . $args['fieldname'];

			$value = $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo esc_html( $fieldname ) ?>"><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<select <?php if ( $args['required'] ) {
				echo 'required';
			} ?> name="<?php echo esc_html( $fieldname ) ?>">
				<option value=""><?php _e( "Choose an option",
						'complianz-gdpr' ) ?></option>
				<?php foreach (
					$args['options'] as $option_key => $option_label
				) { ?>
					<option
						value="<?php echo esc_html( $option_key ) ?>" <?php echo ( $option_key
						                                                           == $value )
						? "selected"
						: "" ?>><?php echo esc_html( $option_label ) ?></option>
				<?php } ?>
			</select>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public
		function label(
			$args
		) {

			$fieldname = 'cmplz_' . $args['fieldname'];
			if ( ! $this->show_field( $args ) ) {
				return;
			}

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label
				for="<?php echo esc_html( $fieldname ) ?>"><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		/**
		 *
		 * Button/Action field
		 *
		 * @param $args
		 *
		 * @echo string $html
		 */

		public
		function button(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			if ( ! $this->show_field( $args ) ) {
				return;
			}

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php if ( $args['post_get'] === 'get' ) { ?>
				<a <?php if ( $args['disabled'] )
					echo "disabled" ?>href="<?php echo $args['disabled']
					? "#"
					: admin_url( 'admin.php?page=cmplz-settings&action='
					             . $args['action'] ) ?>"
				   class="button"><?php echo esc_html( $args['label'] ) ?></a>
			<?php } else { ?>
				<input <?php if ( $args['warn'] )
					echo 'onclick="return confirm(\'' . $args['warn']
					     . '\');"' ?> <?php if ( $args['disabled'] )
					echo "disabled" ?> class="button" type="submit"
				                       name="<?php echo $args['action'] ?>"
				                       value="<?php echo esc_html( $args['label'] ) ?>">
			<?php } ?>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		/**
		 * Upload field
		 *
		 * @param $args
		 *
		 * @echo string $html
		 */

		public
		function upload(
			$args
		) {
			if ( ! $this->show_field( $args ) ) {
				return;
			}

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<input type="file" type="submit" name="cmplz-upload-file"
			       value="<?php echo esc_html( $args['label'] ) ?>">
			<input <?php if ( $args['disabled'] )
				echo "disabled" ?> class="button" type="submit"
			                       name="<?php echo $args['action'] ?>"
			                       value="<?php _e( 'Start',
				                       'complianz-gdpr' ) ?>">
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}


		public
		function save_button() {
			wp_nonce_field( 'complianz_save', 'complianz_nonce' );
			?>
			<th></th>
			<td>
				<input class="button button-primary" type="submit"
				       name="cmplz-save"
				       value="<?php _e( "Save", 'complianz-gdpr' ) ?>">

			</td>
			<?php
		}


		public
		function multiple(
			$args
		) {
			$values = $this->get_value( $args['fieldname'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label><?php echo esc_html( $args['label'] ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<button class="button" type="submit" name="cmplz_add_multiple"
			        value="<?php echo esc_html( $args['fieldname'] ) ?>"><?php _e( "Add new",
					'complianz-gdpr' ) ?></button>
			<br><br>
			<?php
			if ( $values ) {
				foreach ( $values as $key => $value ) {
					?>

					<div>
						<div>
							<label><?php _e( 'Description',
									'complianz-gdpr' ) ?></label>
						</div>
						<div>
                        <textarea class="cmplz_multiple"
                                  name="cmplz_multiple[<?php echo esc_html( $args['fieldname'] ) ?>][<?php echo $key ?>][description]"><?php if ( isset( $value['description'] ) )
		                        echo esc_html( $value['description'] ) ?></textarea>
						</div>

					</div>
					<button class="button cmplz-remove" type="submit"
					        name="cmplz_remove_multiple[<?php echo esc_html( $args['fieldname'] ) ?>]"
					        value="<?php echo $key ?>"><?php _e( "Remove",
							'complianz-gdpr' ) ?></button>
					<?php
				}
			}
			?>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php

		}


		public
		function cookies(
			$args
		) {
			$default_language = substr( get_locale(), 0, 2 );

			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<div id="cmplz_action_success" class="cmplz-hidden">
				<?php echo cmplz_notice( __( 'Settings saved',
					'complianz-gdpr' ), 'success' ) ?>
			</div>

			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php
			$languages  = COMPLIANZ::$cookie_admin->get_supported_languages();
			$count      = COMPLIANZ::$cookie_admin->get_supported_languages( true );

			if ( $count > 1 ) {

				?>
				<select id="cmplz_language" class="cmplz_cookie_language_selector" data-type="cookie">
					<?php
					foreach ( $languages as $language ) {
						?>
						<option
							value="<?php echo $language ?>" <?php if ( $default_language
							                                           === $language
						)
							echo "selected" ?>>
							<?php
							echo $this->get_language_descriptor( $language );
							?></option>
					<?php } ?>
				</select>
				<?php
			} else {
				?>
				<input type="hidden" id="cmplz_language" data-type="cookie"
				       value="<?php echo reset( $languages ) ?>">
				<?php
			}
			?>
			<label class="cmplz-switch">
				<input name="cmplz_show_deleted" size="40" type="checkbox"
				       value="1"/>
				<span class="cmplz-slider cmplz-round"></span>
			</label>
			<?php _e( "Show deleted cookies", "complianz-gdpr" ) ?>


			<div class="cmplz-list-container">
				<div class="cmplz-skeleton"></div>
			</div>
			<button type="button" class="button cmplz-edit-item"
			        name="cmplz_add_item" data-type='cookie' data-action="add"
			        value="<?php echo esc_html( $args['fieldname'] ) ?>"><?php _e( "Add new cookie",
					'complianz-gdpr' ) ?></button>

			<?php do_action( 'complianz_after_field', $args ); ?>

			<?php
		}

		/**
		 * @param $language
		 *
		 * @return string
		 */

		private function get_language_descriptor( $language, $type = 'cookie' ) {
			$string = $type =='cookie' ? __( 'Cookies in %s', 'complianz-gdpr' ) : __( 'Services in %s', 'complianz-gdpr' );
			if ( isset( COMPLIANZ::$config->language_codes[ $language ] ) ) {
				$string = sprintf( $string ,
					COMPLIANZ::$config->language_codes[ $language ] );
			} else {
				$string = sprintf( $string,
					strtoupper( $language ) );
			}

			return $string;
		}


		public
		function services(
			$args
		) {
			$default_language = substr( get_locale(), 0, 2 );

			if ( ! $this->show_field( $args ) ) {
				return;
			}

			?>

			<?php do_action( 'complianz_before_label', $args ); ?>

			<div id="cmplz_action_success" class="cmplz-hidden">
				<?php echo cmplz_notice( __( 'Settings saved',
					'complianz-gdpr' ), 'success' ) ?>
			</div>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php
			$languages  = COMPLIANZ::$cookie_admin->get_supported_languages();
			$count      = COMPLIANZ::$cookie_admin->get_supported_languages( true );

			if ( $count > 1 ) {
				?>
				<select id="cmplz_language" data-type="service">
					<?php
					foreach ( $languages as $language ) {
						?>
						<option
							value="<?php echo $language ?>" <?php if ( $default_language
							                                           === $language
						)
							echo "selected" ?>>
							<?php
							echo $this->get_language_descriptor( $language , 'service');
							?></option>
					<?php } ?>
				</select>
				<?php
			} else {
				?>
				<input type="hidden" id="cmplz_language" data-type="service"
				       value="<?php echo reset( $languages ) ?>">
				<?php
			}
			?>
			<div class="cmplz-list-container">
				<div class="cmplz-skeleton"></div>
			</div>

			<button type="button" class="button cmplz-edit-item"
			        name="cmplz_add_item" data-type='service' data-action="add"
			        value="<?php echo esc_html( $args['fieldname'] ) ?>"><?php _e( "Add new service",
					'complianz-gdpr' ) ?></button>

			<?php do_action( 'complianz_after_field', $args ); ?>

			<?php
		}

		public
		function processors(
			$args
		) {
			$processing_agreements
				= COMPLIANZ::$processing->processing_agreements();

			//as an exception to this specific field, we use the same data for both us and eu
			$fieldname = str_replace( "_us", "", $args['fieldname'] );
			$values    = $this->get_value( $fieldname );
			$region    = $args['region'];

			if ( ! is_array( $values ) ) {
				$values = array();
			}
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label><?php echo $args["label"] . " " . __( 'list',
						'complianz-gdpr' ) ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php
			if ( $values ) {
				foreach ( $values as $key => $value ) {
					$default_index = array(
						'name'                 => '',
						'country'              => '',
						'purpose'              => '',
						'data'                 => '',
						'processing_agreement' => 0,
					);

					$value                            = wp_parse_args( $value,
						$default_index );
					$create_processing_agreement_link = '<a href="'
					                                    . admin_url( "admin.php?page=cmplz-processing-$region" )
					                                    . '">';

					$processing_agreement_outside_c
						  = floatval( ( $value['processing_agreement'] )
						              == - 1 ) ? 'selected' : '';
					$html = '<div class="multiple-field">
                        <div>
                            <label>'
					        . sprintf( __( "Name of the %s with whom you share the data",
							'complianz-gdpr' ), $args['label'] ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
					        . esc_html( $fieldname ) . '][' . esc_html( $key ) . '][name]"
                                   value="' . esc_html( $value['name'] ) . '">
                        </div>
                        <div>
                            <label>'
					        . sprintf( __( 'Select the Processing Agreement you made with this %s, or %screate one%s',
							'complianz-gdpr' ), $args['label'],
							$create_processing_agreement_link, '</a>' ) . '</label>
                        </div>
                        <div>
                            <label>
                                <select name="cmplz_multiple['
					        . esc_html( $fieldname ) . '][' . esc_html( $key ) . '][processing_agreement]">
                                    <option value="0">'
					        . __( 'No agreement selected', 'complianz-gdpr' ) . '</option>
                                    <option value="-1" '
					        . $processing_agreement_outside_c . '>'
					        . __( 'A Processing Agreement outside Complianz Privacy Suite',
							'complianz-gdpr' ) . '</option>';
					foreach ( $processing_agreements as $id => $title ) {
						$selected = ( intval( $value['processing_agreement'] )
						              == $id ) ? 'selected' : '';
						$html     .= '<option value="' . $id . '" ' . $selected
						             . '>' . $title . '</option>';
					}
					$html .= '</select>
                                <br><br>
                        </div>
                        <div>
                            <label>' . sprintf( __( '%s country',
							'complianz-gdpr' ), $args['label'] ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
					         . esc_html( $fieldname ) . '][' . esc_html( $key )
					         . '][country]"
                                   value="' . esc_html( $value['country'] ) . '">
                        </div>

                        <div>
                            <label>' . __( 'Purpose', 'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
					         . esc_html( $fieldname ) . '][' . esc_html( $key )
					         . '][purpose]"
                                   value="' . esc_html( $value['purpose'] ) . '">
                        </div>';
					if ( $region === 'eu' ) {
						$html .= '
                        <div>
                            <label>' . __( 'What type of data is shared',
								'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
						         . esc_html( $fieldname ) . ']['
						         . esc_html( $key ) . '][data]"
                                   value="' . esc_html( $value['data'] ) . '">
                        </div>';

					}
					$html .= '<input class="button" type="submit" name="cmplz-save" value="'
					         . __( 'Save', 'complianz-gdpr' ) . '">
                            <button class="button cmplz-remove" type="submit"
                            name="cmplz_remove_multiple['
					         . esc_html( $fieldname ) . ']"
                            value="' . esc_html( $key ) . '">' . __( "Remove",
							'complianz-gdpr' ) . '</button>';

					$html .= '</div>';

					$title = esc_html( $value['name'] );
					if ( $title == '' ) {
						$title = __( 'New entry', 'complianz-gdpr' );
					}
					cmplz_panel( $title, $html );
					?>

					<?php
				}
			}
			?>
			<button class="button" type="submit" class="cmplz-add-new-processor"
			        name="cmplz_add_multiple"
			        value="<?php echo esc_html( $fieldname ) ?>"><?php printf( __( "Add new %s",
					'complianz-gdpr' ), $args['label'] ) ?></button>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php

		}

		public
		function thirdparties(
			$args
		) {
			$values = $this->get_value( $args['fieldname'] );
			if ( ! is_array( $values ) ) {
				$values = array();
			}
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<label><?php echo $args["label"] ?><?php echo $this->get_help_tip_btn( $args ); ?></label>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php
			if ( $values ) {
				foreach ( $values as $key => $value ) {
					$default_index = array(
						'name'    => '',
						'country' => '',
						'purpose' => '',
						'data'    => '',
					);

					$value = wp_parse_args( $value, $default_index );

					$html = '
                    <div class="multiple-field">
                        <div>
                            <label>'
					        . __( 'Name of the Third Party with whom you share the data',
							'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
					        . esc_html( $args['fieldname'] ) . ']['
					        . esc_html( $key ) . '][name]"
                                   value="' . esc_html( $value['name'] ) . '">
                        </div>

                        <div>
                            <label>' . __( 'Third Party country',
							'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
					        . esc_html( $args['fieldname'] ) . ']['
					        . esc_html( $key ) . '][country]"
                                   value="' . esc_html( $value['country'] ) . '">
                        </div>


                        <div>
                            <label>' . __( 'Purpose', 'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
					        . esc_html( $args['fieldname'] ) . ']['
					        . esc_html( $key ) . '][purpose]"
                                   value="' . esc_html( $value['purpose'] ) . '">
                        </div>
                        <div>
                            <label>' . __( 'What type of data is shared',
							'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                            <input type="text"
                                   name="cmplz_multiple['
					        . esc_html( $args['fieldname'] ) . ']['
					        . esc_html( $key ) . '][data]"
                                   value="' . esc_html( $value['data'] ) . '">
                        </div>


                    </div>
                    <input class="button" type="submit" name="cmplz-save" value="'
					        . __( 'Save', 'complianz-gdpr' ) . '">
                    <button class="button cmplz-remove" type="submit"
                            name="cmplz_remove_multiple['
					        . esc_html( $args['fieldname'] ) . ']"
                            value="' . esc_html( $key ) . '">' . __( "Remove",
							'complianz-gdpr' ) . '</button>';

					$title = esc_html( $value['name'] );
					if ( $title == '' ) {
						$title = sprintf( __( 'New entry', 'complianz-gdpr' ) );
					}
					cmplz_panel( $title, $html );
				}
			}
			?>
			<button class="button" type="submit"
			        class="cmplz-add-new-thirdparty" name="cmplz_add_multiple"
			        value="<?php echo esc_html( $args['fieldname'] ) ?>"><?php _e( "Add new Third Party",
					'complianz-gdpr' ) ?></button>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php

		}

		/**
		 * Get value of this fieldname
		 *
		 * @param        $fieldname
		 * @param string $default
		 *
		 * @return mixed
		 */

		public
		function get_value(
			$fieldname, $default = ''
		) {
			$fields = COMPLIANZ::$config->fields();

			if ( ! isset( $fields[ $fieldname ] ) ) {
				return false;
			}

			$source = $fields[ $fieldname ]['source'];
			if ( strpos( $source, 'CMPLZ' ) !== false
			     && class_exists( $source )
			) {
				$id = false;
				if ( isset( $_GET['id'] ) ) {
					$id = intval( $_GET['id'] );
				}
				if ( isset( $_POST['id'] ) ) {
					$id = intval( $_POST['id'] );
				}

				$banner = new CMPLZ_COOKIEBANNER( $id );
				$value  = ! empty( $banner->{$fieldname} )
					? $banner->{$fieldname} : false;

			} else {
				$options = get_option( 'complianz_options_' . $source );
				$value   = isset( $options[ $fieldname ] )
					? $options[ $fieldname ] : false;
			}

			//if no value isset, pass a default
			$value = ( $value !== false ) ? $value
				: apply_filters( 'cmplz_default_value', $default, $fieldname );

			return $value;
		}

		/**
		 * Checks if a fieldname exists in the complianz field list.
		 *
		 * @param string $fieldname
		 *
		 * @return bool
		 */

		public
		function sanitize_fieldname(
			$fieldname
		) {
			$fields = COMPLIANZ::$config->fields();
			if ( array_key_exists( $fieldname, $fields ) ) {
				return $fieldname;
			}

			return false;
		}


		public
		function get_comment(
			$args
		) {
			if ( ! isset( $args['comment'] ) ) {
				return;
			}
			?>
			<div class="cmplz-comment"><?php echo $args['comment'] ?></div>
			<?php
		}

		/**
		 *
		 * returns the button with which a user can open the help modal
		 *
		 * @param array $args
		 *
		 * @return string
		 */

		public
		function get_help_tip_btn(
			$args
		) {
			$output = '';
			if ( isset( $args['help'] ) ) {
				$output
					= '<a href="#" class="button cmplz-open-modal"><i class="fa fa-question"></i></a>';
			}

			return $output;
		}

		/**
		 * returns the modal help window
		 *
		 * @param array $args
		 *
		 * @return string
		 */

		public
		function get_help_tip(
			$args
		) {
			$output = '';
			if ( isset( $args['help'] ) ) {
				$output
					= '<div><div class="cmplz-help-modal "><span><i class="fa fa-times"></i></span>'
					  . wp_kses_post( $args['help'] ) . '</div></div>';
			}

			return $output;
		}


		/*
         * Check if all required fields are answered
         *
         *
         *
         * */

		public
		function step_complete(
			$step
		) {

		}


		/*
         * Check if all required fields in a section are answered
         *
         *
         * */

		public
		function section_complete(
			$section
		) {

		}


		public
		function has_errors() {
			if ( count( $this->form_errors ) > 0 ) {
				return true;
			}


			return false;
		}


	}
} //class closure
