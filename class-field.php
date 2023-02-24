<?php
/*100% match*/

defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

if ( ! class_exists( "cmplz_field" ) ) {
	class cmplz_field {
		private static $_this;
		public $position;
		public $fields;
		public $default_args;
		public $banner;
		public $form_errors = array();

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			//safe before the fields are loaded in config, in init
			add_action( 'plugins_loaded', array( $this, 'process_save' ), 16 );
			add_action( 'cmplz_register_translation', array( $this, 'register_translation' ), 10, 2 );
			add_action( 'complianz_before_label', array( $this, 'before_label' ), 10, 1 );
			add_action( 'complianz_before_label', array( $this, 'show_errors' ), 10, 1 );
			add_action( 'complianz_after_label', array( $this, 'after_label' ), 10, 1 );
			add_action( 'complianz_label_html', array( $this, 'label_html' ), 10, 1 );
			add_action( 'complianz_after_field', array( $this, 'after_field' ), 10, 1 );

            add_action( 'wp_ajax_cmplz_script_add', array( $this, 'ajax_script_add' ), 10, 1 );
            add_action( 'wp_ajax_cmplz_script_save', array( $this, 'ajax_script_save' ), 10, 1 );
			add_action( 'plugins_loaded', array($this, 'maybe_load_banner'));
            $this->load();
		}

		static function this() {
			return self::$_this;
		}

		public function maybe_load_banner(){
			if ( !isset($_GET['page']) || $_GET['page'] !== 'cmplz-cookiebanner' ) {
				return;
			}
			$id = false;
			if ( isset( $_GET['id'] ) ) {
				$id = intval( $_GET['id'] );
			}
			if ( isset( $_POST['id'] ) ) {
				$id = intval( $_POST['id'] );
			}
			$this->banner = new CMPLZ_COOKIEBANNER( $id );
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
				'order'				 => 100,
				"type"               => 'text',
				"required"           => false,
				"loadmore"           => false,
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
				'minimum'            => 0,
				'maximum'            => '',
			);
		}

		public function process_save() {
			if ( !cmplz_user_can_manage() ) {
				return;
			}
			if ( isset( $_POST['cmplz_nonce'] ) ) {
				//check nonce
				if ( ! isset( $_POST['cmplz_nonce'] )
				     || ! wp_verify_nonce( $_POST['cmplz_nonce'],
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
					$fieldname = $this->sanitize_fieldname( $_POST['cmplz_add_multiple'] );
					$this->add_multiple_field( $fieldname );
				}

				//save multiple field
				if ( ( isset( $_POST['cmplz-save'] )
				       || isset( $_POST['cmplz-next'] ) )
				     && isset( $_POST['cmplz_multiple'] )
				) {
					$fieldnames = $this->sanitize_array( $_POST['cmplz_multiple'] );
					$this->save_multiple( $fieldnames );
				}

				//Save the custom URLs for not Complianz generated pages.
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
						cmplz_register_translation($url, "cmplz_".$document."_custom_page_url");
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
			if ( ! cmplz_user_can_manage() ) {
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
			if ( ! cmplz_user_can_manage() ) {
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

			if (class_exists($page, false)) {
				return;
			}
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
			if ( ! cmplz_user_can_manage() ) {
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
			if ( ! cmplz_user_can_manage() ) {
				return false;
			}
			switch ( $type ) {
				case 'colorpicker':
					return is_array($value ) ? array_map( 'sanitize_hex_color', $value ) : sanitize_hex_color($value);
				case 'text_checkbox':
					$value['text'] = sanitize_text_field($value['text']);
					$value['show'] = intval($value['show']);
					return $value;
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
				case 'add_script':
				case 'block_script':
				case 'whitelist_script':
					return array_map( array($this, 'sanitize_custom_scripts'), $value );
			}

			return sanitize_text_field( $value );
		}

		/**
		 * Sanitize a custom script structure
		 * @param array $arr
		 *
		 * @return array mixed
		 */
		public function sanitize_custom_scripts($arr){
			if (isset($arr['name']) ) {
				$arr['name'] = sanitize_text_field($arr['name']);
			}
			if (isset($arr['async']) ) {
				$arr['async'] = intval($arr['async']);
			}
			if (isset($arr['category']) ) {
				$arr['category'] = sanitize_title($arr['category']);
			}
			if (isset($arr['category']) ) {
				$arr['category'] = sanitize_title($arr['category']);
			}
			if (isset($arr['enable_placeholder']) ) {
				$arr['enable_placeholder'] = intval($arr['enable_placeholder']);
			}
			if (isset($arr['iframe']) ) {
				$arr['iframe'] = intval($arr['iframe']);
			}
			if (isset($arr['placeholder_class']) ) {
				$arr['placeholder_class'] = sanitize_text_field($arr['placeholder_class']);
			}
			if (isset($arr['placeholder']) ) {
				$arr['placeholder'] = sanitize_title($arr['placeholder']);
			}
			if (isset($arr['enable_dependency']) ) {
				$arr['enable_dependency'] = intval($arr['enable_dependency']);
			}
			if (isset($arr['dependency']) ) {
				//maybe split array from ajax save
				if (is_array($arr['dependency'])) {
					foreach ($arr['dependency'] as $key => $value ) {
						if (strpos($value, '|:|')!==false) {
							$result = explode('|:|', $value);
							unset($arr['dependency'][$key]);
							$arr['dependency'][$result[0]] = $result[1];
						}
					}
				}
				//don't have to be valid URLs, so don't sanitize as such
				$arr['dependency'] = array_map('sanitize_text_field', $arr['dependency']);
				$arr['dependency'] = array_filter(array_map('trim', $arr['dependency']) );
			}

			if (isset($arr['enable']) ) {
				$arr['enable'] = intval($arr['enable']);
			}

			if (isset($arr['urls']) ) {
				//don't have to be valid URLs, so don't sanitize as such
				$arr['urls'] = array_map('sanitize_text_field', $arr['urls']);
				$arr['urls'] = array_filter(array_map('trim', $arr['urls']) );
			}
			return $arr;
		}

		/**/

		private
		function filter_complianz_fields(
			$fieldname
		) {
			if ( strpos( $fieldname, 'cmplz_' ) !== false
			     && isset( COMPLIANZ::$config->fields[ str_replace( 'cmplz_', '', $fieldname ) ] )
			) {
				return true;
			}

			return false;
		}

		public function before_label( $args )
        {
            $condition_class    = '';
            $condition_question = '';
            $condition_answer   = '';

            if ( ! empty( $args['condition'] ) ) {
				$condition_count    = 1;
				foreach ( $args['condition'] as $question => $answer ) {
				    $question = esc_attr( $question );
                    $answer = esc_attr( $answer );
                    $condition_class     .= "condition-check-{$condition_count} ";
                    $condition_question  .= "data-condition-answer-{$condition_count}='{$answer}' ";
                    $condition_answer    .= "data-condition-question-{$condition_count}='{$question}' ";
                    $condition_count++;
                }
			}

			$hidden_class    = ( $args['hidden'] ) ? 'hidden' : '';
			$cmplz_hidden    = $this->condition_applies( $args ) ? '' : 'cmplz-hidden';
			$first_class     = ( $args['first'] ) ? 'first' : '';
			$type            = $args['type'];

			$cols_class      = isset($args['cols']) && $args['cols']  ? "cmplz-cols-{$args['cols']}" : '';
            $col_class       = isset($args['col'])                    ? "cmplz-col-{$args['col']}" : '';
            $colspan_class   = isset($args['colspan'])                ? "cmplz-colspan-{$args['colspan']}" : '';

			$this->get_master_label( $args, $hidden_class . ' ' .
										   $first_class . ' ' .
										   $condition_class . ' ' .
										   $cmplz_hidden );

			echo '<div class="field-group ' .
                    esc_attr( $cols_class ) . ' ' .
                    esc_attr( $col_class ) . ' ' .
                    esc_attr( $colspan_class ) . ' ' .
                    'cmplz-'. $type . ' ' .
                    $hidden_class . ' ' .
                    $first_class . ' ' .
                    $condition_class . ' ' .
                    $cmplz_hidden
                 . '" ';

            echo $condition_question;
            echo $condition_answer;

            echo ' data-fieldname="'.$args['fieldname'].'"><div class="cmplz-field"><div class="cmplz-label">';
		}

		/**
		 * @param array $args
		 */
		public function after_label( $args ){
			?>
			</div>

			<?php
			if ( $args['type'] === 'button' ) {
				$this->get_comment( $args );
			}
		}

		public function get_master_label( $args , $classes='') {
			if ( ! isset( $args['master_label'] ) ) {
				return;
			}
			?>
			<div class="cmplz-master-label field-group <?php echo $classes?> <?php echo $args['fieldname']?>">
				<div><h2 class="h4"><?php echo esc_html( $args['master_label'] ) ?></h2></div>
			</div>
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

		public function label_html( $args ) {
			?>
			<label class="<?php if ( $args['disabled'] ) {echo 'cmplz-disabled';} ?>" for="cmplz_<?php echo $args['fieldname'] ?>">
				<div class="cmplz-title-wrap"><?php echo $args['label'] ?></div>
				<div>
					<?php
					if ( isset($args['tooltip']) ) {
						echo cmplz_icon('help', 'default', $args['tooltip']);
					}
					?>
				</div>

			</label>
			<?php
		}

		public function after_field( $args ) {

			if ( $args['type'] !== 'button' ) {
				$this->get_comment( $args );
			}
			echo '</div><!--close after field-->';
			echo '<div class="cmplz-help-warning-wrap">';
			if (  isset( $args['help'] ) ) {
				$status = isset($args['help_status']) ? $args['help_status'] : 'notice';
				cmplz_sidebar_notice( wp_kses_post( $args['help'] ), $status, $args['condition'] );
			}

			do_action( 'cmplz_notice_' . $args['fieldname'], $args );

			echo '</div>';
			echo '</div>';
		}


		public function text( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $fieldname = 'cmplz_' . $args['fieldname'];
            $value = $this->get_value( $args['fieldname'], $args['default'] );
            $required = $args['required'] ? 'required' : '';
            $is_required = $args['required'] ? 'is-required' : '';
            $check_icon = cmplz_icon('check', 'success');
            $times_icon = cmplz_icon('times');
            ?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>


			<input <?php echo $required ?>
				class="validation <?php echo $is_required ?>"
				placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
				type="text"
				value="<?php echo esc_html( $value ) ?>"
				name="<?php echo esc_html( $fieldname ) ?>"
            >
            <?php echo $check_icon ?>
            <?php echo $times_icon ?>

			<?php do_action( 'complianz_after_field', $args ); ?>

			<?php
		}

		public function url( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $fieldname = 'cmplz_' . $args['fieldname'];
            $value     = $this->get_value( $args['fieldname'], $args['default'] );
            $required = $args['required'] ? 'required' : '';
            $is_required = $args['required'] ? 'is-required' : '';
            $check_icon = cmplz_icon('check', 'success'); ;
            $times_icon = cmplz_icon('times');
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

            <input <?php echo $required ?>
                class="validation <?php echo $is_required ?>"
				placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
				type="text"
				pattern="(http(s)?(:\/\/))?(www.)?[#a-zA-Z0-9-_\.\/:].*"
				value="<?php echo esc_html( $value ) ?>"
				name="<?php echo esc_html( $fieldname ) ?>"
            >
            <?php echo $check_icon ?>
            <?php echo $times_icon ?>

            <?php do_action( 'complianz_after_field', $args ); ?>

            <?php
		}

		public function email( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $fieldname = 'cmplz_' . $args['fieldname'];
            $value     = $this->get_value( $args['fieldname'], $args['default'] );
            $required = $args['required'] ? 'required' : '';
            $is_required = $args['required'] ? 'is-required' : '';
            $check_icon = cmplz_icon('check', 'success');
            $times_icon = cmplz_icon('times');
            ?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<input <?php echo $required ?>
                class="validation <?php echo $is_required ?>"
				placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
				type="email"
				value="<?php echo esc_html( $value ) ?>"
				name="<?php echo esc_html( $fieldname ) ?>"
            >
            <?php echo $check_icon ?>
            <?php echo $times_icon ?>

			<?php do_action( 'complianz_after_field', $args ); ?>

			<?php
		}

		public function phone( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $fieldname = 'cmplz_' . $args['fieldname'];
            $value     = $this->get_value( $args['fieldname'], $args['default'] );
            $required = $args['required'] ? 'required' : '';
            $is_required = $args['required'] ? 'is-required' : '';
            $check_icon = cmplz_icon('check', 'success');
            $times_icon = cmplz_icon('times');
            ?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<input autocomplete="tel" <?php echo $required ?>
                   class="validation <?php echo $is_required ?>"
			       placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
			       type="text"
			       value="<?php echo esc_html( $value ) ?>"
			       name="<?php echo esc_html( $fieldname ) ?>"
            >
            <?php echo $check_icon ?>
            <?php echo $times_icon ?>

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
			<?php do_action( 'complianz_label_html' , $args );?>
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
				min="<?php echo esc_attr($args['minimum'])?>" min="<?php echo esc_attr($args['maximum'])?>"step="<?php echo isset($args["validation_step"]) ? intval($args["validation_step"]) : 1?>"
				>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}


		public
		function checkbox(
			$args, $force_value = false
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $force_value ?: $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<label tabindex="0" class="cmplz-switch">
				<?php
					//we set the hidden input to the actual value if the field is disabled. This ensures that a disabled field value is persistent on save.
					$hidden_value = 0;
					if ( $args['disabled'] ) {
						$hidden_value = $value;
					}
				?>
				<input tabindex="-1" name="<?php echo esc_html( $fieldname ) ?>" type="hidden" value="<?php echo $hidden_value?>" aria-checked="false"/>
				<input tabindex="-1" name="<?php echo esc_html( $fieldname ) ?>" size="40" type="checkbox" aria-checked="<?php echo esc_attr($value)?>"
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

		public function multicheckbox( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

			$fieldname = 'cmplz_' . $args['fieldname'];

            // Initialize
            $default_index = array();
            $disabled_index = array();
            $value_index = array();
			$classes = '';
            $check_icon = '';
			$loadmore = false;

            if ( ! empty( $args['options'] ) )
            {
                // Value index
                $value     = cmplz_get_value( $args['fieldname'], false, false, false, false );
                foreach ($args['options'] as $option_key => $option_label) {
                    if ( is_array( $value ) && isset( $value[$option_key] ) && $value[$option_key] ) { // If value is not set it's ''
                        $value_index[$option_key] = 'checked';
                    } else {
                        $value_index[$option_key] = '';
                    }
                }

                // Default index
                $defaults = apply_filters( 'cmplz_default_value', $args['default'], $args['fieldname'] );
                foreach ($args['options'] as $option_key => $option_label) {
                	$default_index[$option_key] = isset($defaults[$option_key]) && $defaults[$option_key] == 1 ? 'cmplz-default' : '';
                }

                // Disabled index
                foreach ($args['options'] as $option_key => $option_label) {
                    if ( is_array( $args['disabled']) && in_array($option_key, $args['disabled']) ) {
                        $disabled_index[$option_key] = 'cmplz-disabled';
                    } else {
                        $disabled_index[$option_key] = '';
                    }
                }

                // Required
                $classes = $args['required'] ? 'cmplz-validate-multicheckbox' : '';

                // Check icon
                $check_icon = cmplz_icon('check');
				if ( $args['loadmore'] ) $classes.= ' cmplz-multicheckbox-loadmore';
            }
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>
            <div class="<?php echo $classes ?>" data-cmplz_loadmore_count="<?php echo $args['loadmore']?>">
			<?php if ( ! empty( $args['options'] ) ) {
                foreach ( $args['options'] as $option_key => $option_label )
                { ?>
                    <label tabindex="0" role="button" aria-pressed="false" class="cmplz-checkbox-container <?php echo esc_html($disabled_index[$option_key]) ?>"><?php echo esc_html( $option_label ) ?>
                        <input
                            name="<?php echo esc_html( $fieldname ) ?>[<?php echo esc_html($option_key) ?>]"
                            type="hidden"
                            value="0"
							tabindex="-1"
                        >
                        <input
                            name="<?php echo esc_html( $fieldname ) ?>[<?php echo esc_html($option_key) ?>]"
                            class="<?php echo esc_html( $fieldname ) ?>[<?php echo esc_html($option_key) ?>]"
                            type="checkbox"
                            value="1"
							tabindex="-1"
                            <?php echo esc_html($value_index[$option_key]) ?>
                        >
                        <div
                            class="checkmark <?php echo esc_html($default_index[$option_key]) ?>"
                            <?php echo esc_html($value_index[$option_key]) ?>
                        ><?php echo $check_icon ?></div>
                    </label>
                <?php
                }
			} else {
				cmplz_notice( __( 'No options found', 'complianz-gdpr' ) );
			} ?>
			<?php if ( $args['loadmore'] ) { ?>
				<button class="button cmplz_load_more"><span class="cmplz-load-more"><?php _e("More options", "complianz-gdpr")?></span><span class="cmplz-load-less"><?php _e("Less options", "complianz-gdpr")?></span></button>
			<?php }?>
            </div>

			<?php do_action( 'complianz_after_field', $args );
		}

		public function radio( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

			$fieldname     = 'cmplz_' . $args['fieldname'];
			$value         = $this->get_value( $args['fieldname'], $args['default'] );
			$options       = $args['options'];
			$required      = $args['required'] ? 'required' : '';
			$check_icon    = cmplz_icon( 'bullet', 'default', '', 10);
            ?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

            <?php
            if ( ! empty( $options ) ) {
                foreach ( $options as $option_value => $option_label )
                {
					$disabled = $default_class = '';
					if ( is_array($args['disabled']) && in_array($option_value, $args['disabled']) || $args['disabled'] === true ) {
						$disabled = 'disabled';
					}
                	?>
                    <label tabindex="0" role="button" aria-pressed="false" class="cmplz-radio-container <?php echo $disabled ?>"><?php echo $option_label ?>
                        <input tabindex="-1"
                            <?php echo $required ?>
                                type="radio"
                                name="<?php echo esc_html( $fieldname ) ?>"
                                class="<?php echo esc_html( $fieldname ) ?>"
                                value="<?php echo esc_html( $option_value ) ?>"
                            <?php if ( $value == $option_value ) echo "checked" ?>
							<?php echo $disabled?>
						>
                        <div class="radiobtn <?php echo $disabled ?>"
                            <?php echo $required ?>
                        ><?php echo $check_icon ?></div>
                    </label>
					<?php
                }
            }
            ?>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		public function document( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

			$fieldname = 'cmplz_' . $args['fieldname'];
			$value     = $this->get_value( $args['fieldname'], $args['default'] );
            $required = $args['required'] ? 'required' : '';

            // Checked
            $generated  = $value == 'generated' ? 'checked' : '';
            $custom     = $value == 'custom'    ? 'checked' : '';
            $url        = $value == 'url'       ? 'checked' : '';
            $none       = $value == 'none'      ? 'checked' : '';

            // Check icon
            $check_icon = cmplz_icon('bullet', 'default', '', 10);

            // Labels
            if ($fieldname === 'cmplz_cookie-statement'){
                $generate_label = __("Generated by Complianz", "complianz-gdpr");
            } else {
                $generate_label = cmplz_sprintf(__("Generate a comprehensive and legally validated %s", "complianz-gdpr").cmplz_upgrade_to_premium('https://complianz.io/pricing/'),
                    $args['label']
                );
            }
            $generate_label = apply_filters("cmplz_generate_document_label", $generate_label, $args['fieldname']);
            $custom_label = __("Link to custom page", "complianz-gdpr");
            $url_label = __("Custom URL", "complianz-gdpr");
            $none_label = __("No document", "complianz-gdpr");

            // Document custom url
            $show_url_field = $value === 'url' ? '' : 'style="display: none;"';

            // Pages and Custom page ID
            $doc_args = array(
                'post_type' => 'page',
                'posts_per_page' => -1,
            );
            $pages = get_posts($doc_args);
            $pages = wp_list_pluck($pages, 'post_title','ID' );
            $custom_page_id = get_option('cmplz_'.$args['fieldname'].'_custom_page');
            $show_custom_field = $value === 'custom' ? '' : 'style="display: none;"';

            // If there's no active privacy statement, use the wp privacy statement, if available
            if ( $args['fieldname'] === 'privacy-statement' && !$custom_page_id ){
                $wp_privacy_policy = get_option('wp_page_for_privacy_policy');
                if ($wp_privacy_policy){
                    $custom_page_id = $wp_privacy_policy;
                }
            }
			$all_disabled = !is_array($args['disabled']) && $args['disabled'];
			$generated_disabled = $custom_disabled = $url_disabled = $none_disabled = $all_disabled;
            if (is_array($args['disabled'])) {
				$generated_disabled = in_array('generated', $args['disabled']) || $all_disabled ? 'disabled' : '';
				$custom_disabled = in_array('custom', $args['disabled']) || $all_disabled ? 'disabled' : '';
				$url_disabled = in_array('url', $args['disabled']) || $all_disabled ? 'disabled' : '';
				$none_disabled = in_array('url', $args['disabled']) || $all_disabled ? 'disabled' : '';
			}

			$generated_disabled = $generated_disabled ? 'disabled' : '';
			$custom_disabled = $custom_disabled ? 'disabled' : '';
			$url_disabled = $url_disabled ? 'disabled' : '';
			$none_disabled = $none_disabled ? 'disabled' : '';
			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args ); ?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<div class="cmplz-document-field" data-fieldname="<?php echo esc_html( $fieldname ) ?>">
                <label tabindex="0" role="button" aria-pressed="false" class="cmplz-radio-container <?php echo $generated_disabled ?>"><?php echo $generate_label ?>
                    <input
						<?php echo $generated_disabled ?>
                        <?php echo $required ?>
                        type="radio"
                        name="<?php echo esc_html( $fieldname ) ?>"
                        value="generated"
                        <?php echo $generated ?>
                        class="cmplz-document-input"
						tabindex="-1"
					>
                    <div class="radiobtn <?php echo $generated_disabled ?>"  <?php echo $required ?>><?php echo $check_icon ?></div>
                </label>

                <label tabindex="0" role="button" aria-pressed="false" class="cmplz-radio-container <?php echo $custom_disabled ?>"><?php echo $custom_label ?>
                    <input
							<?php echo $custom_disabled ?>
							<?php echo $required ?>
                        type="radio"
                        name="<?php echo esc_html( $fieldname ) ?>"
                        value="custom"
                        <?php echo $custom ?>
                        class="cmplz-document-input"
						tabindex="-1"
                    >
                    <div class="radiobtn <?php echo $custom_disabled ?>" <?php echo $custom_disabled ?> <?php echo $required ?>><?php echo $check_icon ?></div>
                </label>

                <label tabindex="0" role="button" aria-pressed="false" class="cmplz-radio-container <?php echo $url_disabled ?>"><?php echo $url_label ?>
                    <input
						<?php echo $url_disabled ?>
						<?php echo $required ?>
                        type="radio"
                        name="<?php echo esc_html( $fieldname ) ?>"
                        value="url"
                        <?php echo $url ?>
                        class="cmplz-document-input"
						tabindex="-1"
                    >
                    <div class="radiobtn <?php echo $url_disabled ?>"  <?php echo $required ?>><?php echo $check_icon ?></div>
                </label>

				<?php if ( $args['fieldname'] !== 'cookie-statement' ){ ?>
                    <label tabindex="0" role="button" aria-pressed="false" class="cmplz-radio-container <?php echo $none_disabled ?>"><?php echo $none_label ?>
                        <input
							<?php echo $none_disabled ?>
                            <?php echo $required ?>
                            type="radio"
                            name="<?php echo esc_html( $fieldname ) ?>"
                            value="none"
                            <?php echo $none ?>
                            class="cmplz-document-input"
							tabindex="-1"
                        >
                        <div class="radiobtn <?php echo $none_disabled ?>" <?php echo $required ?>><?php echo $check_icon ?></div>
                    </label>
				<?php } ?>

				<input
					<?php echo $url_disabled ?>
                    type="text"
                    class="cmplz-document-custom-url"
                    value="<?php echo get_option($fieldname."_custom_page_url")?>"
                    placeholder = "https://domain.com/your-policy"
                    name="<?php echo esc_html( $fieldname."_custom_page_url" ) ?>"
                    <?php echo $show_url_field ?>
                >

				<select
					<?php echo $custom_disabled ?>
                    class="cmplz-document-custom-page"
                    name="<?php echo esc_html( $fieldname."_custom_page" ) ?>"
                    <?php echo $show_custom_field ?>>
					<option value=""><?php _e("None selected", "complianz-gdpr")?></option>
					<?php foreach ($pages as $ID => $page){
						$selected = $ID==$custom_page_id ? "selected" : ""; ?>
						<option value="<?php echo $ID ?>" <?php echo $selected ?>><?php echo wp_kses_post($page) ?></option>
					<?php } ?>
				</select>

			</div>

			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		/**
		 * Check if this field should be visible
		 * @param $args
		 *
		 * @return bool
		 */
		public function show_field( $args ) {
			return ( $this->condition_applies( $args, 'callback_condition' ) );
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

		public function condition_applies( $args, $type = false)
        {
			$default_args = $this->default_args;
			$args         = wp_parse_args( $args, $default_args );
			if ( ! $type ) {
				if (  $args['condition']  ) {
					$type = 'condition';
				} elseif (  $args['callback_condition'] ) {
					$type = 'callback_condition';
				}
			}

			if ( ! $type || ! $args[ $type ] ) {
				return true;
			}

			//ensure the function exists, and is prefixed with cmplz_
			//pass the original, including NOT
			$maybe_is_function = is_string($args[ $type ]) ? str_replace( 'NOT ', '', $args[ $type ] ) : $args[ $type ];
			if ( is_string( $args[ $type ] ) && ! empty( $args[ $type ] ) && strpos($maybe_is_function, 'cmplz_')!==FALSE && function_exists( $maybe_is_function ) ) {
				return $this->function_callback_applies( $args[ $type ] );
			}

			$condition = $args[ $type ];
			//if we're checking the condition, but there's also a callback condition, check that one as well.
			//but only if it's an array. Otherwise it's a func.
			if ( $type === 'condition' && isset( $args['callback_condition'] ) && is_array( $args['callback_condition'] ) ) {
				$condition += $args['callback_condition'];
			}

			foreach ( $condition as $c_fieldname => $c_value_content ) {
				$c_values = $c_value_content;
				//the possible multiple values are separated with comma instead of an array, so we can add NOT.
				if ( ! is_array( $c_value_content ) && strpos( $c_value_content, ',' ) !== false ) {
					$c_values = explode( ',', $c_value_content );
				}
				$c_values = is_array( $c_values ) ? $c_values : array( $c_values );

				foreach ( $c_values as $c_value ) {
					$maybe_is_function = str_replace( 'NOT ', '', $c_value );
					//ensure the function exists, and is prefixed with cmplz_
					//pass the original, including NOT
					if ( function_exists( $maybe_is_function ) && strpos($maybe_is_function, 'cmplz_')!==FALSE ) {
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
							$match = ( $c_value === $actual_value || in_array( $actual_value, $c_values ) );

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

		/**
		 * Get current field type
		 * @param string $fieldname
		 *
		 * @return false|string
		 */
		public function get_field_type( string $fieldname ) {
			return COMPLIANZ::$config->fields[ $fieldname ]['type'] ?? false;
		}

		public
		function textarea(
			$args
		) {
			$fieldname = 'cmplz_' . $args['fieldname'];
			$check_icon = cmplz_icon('check', 'success');
			$times_icon = cmplz_icon('times');
			$value = $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<textarea name="<?php echo esc_html( $fieldname ) ?>"
                      <?php if ( $args['required'] ) {
	                      echo 'required';
                      } ?>
                        class="validation <?php if ( $args['required'] ) {
	                        echo 'is-required';
                        } ?>"
                      placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"><?php echo esc_html( $value ) ?></textarea>

			<?php echo $check_icon ?>
			<?php echo $times_icon ?>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}

		/**
         * Show field with editor
         *
         *
         * */

		public function editor( $args, $step = '' ) {
			$fieldname     = 'cmplz_' . $args['fieldname'];
			$args['first'] = true;
			$media         = $args['media'] ? true : false;

			$value = $this->get_value( $args['fieldname'], $args['default'] );

			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<?php
			$settings = array(
				'media_buttons' => $media,
				'editor_height' => 200,
				'textarea_rows' => 15,
			);?>
			<div class="cmplz-editor-container" style="position:relative;">
			<?php wp_editor( $value, $fieldname, $settings ); ?>
			</div>
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
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<div id="<?php echo esc_html( $fieldname ) ?>editor"
			     style="height: 200px; width: 100%"><?php echo $value ?></div>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<script>
				window.define = ace.define;
				window.require = ace.require;
				var <?php echo esc_html( $fieldname )?> =
				ace.edit("<?php echo esc_html( $fieldname )?>editor");
				<?php echo esc_html( $fieldname )?>.setTheme("ace/theme/tomorrow_night_bright");
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

		public function css($args)
        {
			$fieldname = 'cmplz_' . $args['fieldname'];

			$value = $this->get_value( $args['fieldname'], $args['default'] );
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>
			<div id="<?php echo esc_html( $fieldname ) ?>editor"
			     style="height: 290px; width: 100%"><?php echo $value ?></div>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<script>
				window.define = ace.define;
				window.require = ace.require;
				var <?php echo esc_html( $fieldname )?> =
				ace.edit("<?php echo esc_html( $fieldname )?>editor");
				<?php echo esc_html( $fieldname )?>.setTheme("ace/theme/tomorrow_night_bright");
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


		public function colorpicker( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }
            $fieldname = 'cmplz_' . $args['fieldname'];
            $args['cols'] = count($args['fields']);
            $values = $this->get_value( $args['fieldname'], $args['default'] );
            ?>
            <?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
            <?php do_action( 'complianz_after_label', $args ); ?>
            <?php

            foreach ($args['fields'] as $field)
            {
                $value = isset($values[$field['fieldname']]) ? $values[$field['fieldname']] : $args['default'][$field['fieldname']] ?>
                <div class="cmplz-color-picker-wrap">

					<div class="cmplz-sublabel">
						<label for="<?php echo esc_html($fieldname) . '[' . esc_html( $field['fieldname'] ) . ']' ?>"><?php echo esc_html( $field['label'] ) ?></label>
					</div>

					<input type="hidden"
						   name="<?php echo esc_html($fieldname) . '[' . esc_html( $field['fieldname'] ) . ']' ?>"
						   id="<?php echo esc_html($fieldname) . '_' . esc_html( $field['fieldname'] ) . '' ?>"
						   value="<?php echo esc_html( $value ) ?>"
						   class="cmplz-color-picker-hidden">
					<input type="text"
						   name="color_picker_container"
						   data-hidden-input='<?php echo esc_html($fieldname) . '_' . esc_html( $field['fieldname'] ) . '' ?>'
						   value="<?php echo esc_html( $value ) ?>"
						   class="cmplz-color-picker">

                </div>
                <?php
            }

            do_action( 'complianz_after_field', $args );
		}

        public function border_radius( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $fieldname = 'cmplz_' . $args['fieldname'];
            $args['cols'] = 5;
            $values = $this->get_value( $args['fieldname'], $args['default'] );
			$default_values = array(
					'top' => $args['default']['top'],
					'right' => $args['default']['right'],
					'bottom' => $args['default']['bottom'],
					'left' => $args['default']['left'],
					'type' => $args['default']['type'],
			);
			$values = wp_parse_args($values, $default_values);
            ?>
            <?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
            <?php do_action( 'complianz_after_label', $args ); ?>
            <?php

            $args['fields'] = array(
                array(
                    'fieldname' => $fieldname . '[top]',
                    'label'     => __( "Top", 'complianz-gdpr' ),
                    'value'     => esc_html($values['top']),
                ),
                array(
                    'fieldname' => $fieldname . '[right]',
                    'label'     => __( "Right", 'complianz-gdpr' ),
                    'value'     => esc_html($values['right']),
                ),
                array(
                    'fieldname' => $fieldname . '[bottom]',
                    'label'     => __( "Bottom", 'complianz-gdpr' ),
                    'value'     => esc_html($values['bottom']),
                ),
                array(
                    'fieldname' => $fieldname . '[left]',
                    'label'     => __( "Left", 'complianz-gdpr' ),
                    'value'     => esc_html($values['left']),
                ),
            );

            foreach ($args['fields'] as $field)
            {
            	$options = array('px', '%');
            	if (!in_array($values['type'], $options )){
					$values['type']='px';
				}
            	?>

                <div class="cmplz-border-radius-wrap">
                    <div class="cmplz-sublabel">
                        <label class="cmplz-sublabel" for="<?php echo esc_html( $field['fieldname'] ) ?>"><?php echo esc_html( $field['label'] ) ?></label>
                    </div>

                    <input type="number"
                           name="<?php echo esc_html( $field['fieldname'] ) ?>"
                           value="<?php echo esc_html( $field['value'] ) ?>"
                           class="cmplz-border-radius">
                </div>
                <?php
            }

            ?>

            <div class="cmplz-border-input-type-wrap">
                <input
                    class="cmplz-border-input-type"
                    type="hidden" value="<?php echo esc_html($values['type']) ?>"
                    name="<?php echo esc_html($fieldname) . '[type]' ?>">
                <span class="cmplz-border-input-type-pixel <?php echo $values['type'] === '%' ? 'cmplz-grey' : '' ?>">px</span>
                <span class="cmplz-border-input-type-percent <?php echo $values['type'] === 'px' ? 'cmplz-grey' : '' ?>">%</span>
            </div>

            <?php do_action( 'complianz_after_field', $args );
        }


        public function border_width( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $fieldname = 'cmplz_' . $args['fieldname'];
            $args['cols'] = 5;
            $values = $this->get_value( $args['fieldname'], $args['default'] );
            ?>
            <?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
            <?php do_action( 'complianz_after_label', $args ); ?>
            <?php
			$default_values = array(
					'top' => $args['default']['top'],
					'right' => $args['default']['right'],
					'bottom' => $args['default']['bottom'],
					'left' => $args['default']['left'],
			);
			$values = wp_parse_args($values, $default_values);
            $args['fields'] = array(
                array(
                    'fieldname' => $fieldname . '[top]',
                    'label'     => __( "Top", 'complianz-gdpr' ),
                    'value'     => esc_html($values['top']),
                ),
                array(
                    'fieldname' => $fieldname . '[right]',
                    'label'     => __( "Right", 'complianz-gdpr' ),
                    'value'     => esc_html($values['right']),
                ),
                array(
                    'fieldname' => $fieldname . '[bottom]',
                    'label'     => __( "Bottom", 'complianz-gdpr' ),
                    'value'     => esc_html($values['bottom']),
                ),
                array(
                    'fieldname' => $fieldname . '[left]',
                    'label'     => __( "Left", 'complianz-gdpr' ),
                    'value'     => esc_html($values['left']),
                ),
            );

            foreach ($args['fields'] as $field)
            { ?>

                <div class="cmplz-border-width-wrap">

                    <div class="cmplz-sublabel">
                        <label class="cmplz-sublabel" for="<?php echo esc_html( $field['fieldname'] ) ?>"><?php echo esc_html( $field['label'] ) ?></label>
                    </div>

                    <input type="number"
                           name="<?php echo esc_html( $field['fieldname'] ) ?>"
                           value="<?php echo esc_html( $field['value'] ) ?>"
                           class="cmplz-border-width">

                </div>
                <?php
            }

            ?>

            <div class="cmplz-border-input-type-wrap">
                <span class="cmplz-border-input-type-px">px</span>
            </div>

            <?php do_action( 'complianz_after_field', $args );
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

				$type              = ( $args['callback'] ) ? 'callback' : $args['type'];
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

			$fields = COMPLIANZ::$config->fields( $source, $step, $section, $get_by_fieldname );
			$i = 0;
			foreach ( $fields as $fieldname => $args ) {
				if ( $i === 0 ) {
					$args['first'] = true;
				}
				$i ++;
				$default_args = $this->default_args;
				$args         = wp_parse_args( $args, $default_args );


				$type              = ( $args['callback'] ) ? 'callback' : $args['type'];
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
                    case 'borderradius':
                        $this->border_radius( $args );
                        break;
                    case 'borderwidth':
                        $this->border_width( $args );
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
					case 'add_script':
						$this->add_script( $args );
						break;
                    case 'block_script':
                        $this->block_script( $args );
                        break;
                    case 'whitelist_script':
                        $this->whitelist_script( $args );
                        break;
                    case 'use_logo_complianz':
                        $this->use_logo_complianz( $args );
                        break;
                    case 'use_logo_site':
                        $this->use_logo_site( $args );
                        break;
                    case 'use_logo_custom':
                        $this->use_logo_custom( $args );
                        break;
                    case 'text_checkbox':
                        $this->text_checkbox( $args );
                        break;

				}
			}

		}

		/**
		 * Callback handler
		 * @param $args
		 */
		public
		function callback(
			$args
		) {
			$callback = $args['callback'];
			do_action( 'complianz_before_label', $args );
			?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( "cmplz_$callback", $args );?>
			<?php do_action( 'complianz_after_label', $args );?>
			<?php

			do_action( 'complianz_after_field', $args );
		}

		/**
		 * A notice field is nothing more than an empty field, with a help notice.
		 *
		 * @param $args
		 */
		public function notice(
			$args
		) {
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			do_action( 'complianz_before_label', $args );
			do_action( 'complianz_label_html' , $args );
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
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<select <?php if ( $args['required'] ) {
				echo 'required';
			} ?> <?php
				if ( !is_array( $args['disabled']) && $args['disabled'] ) echo "disabled";?> name="<?php echo esc_html( $fieldname )?>">
				<option value=""><?php _e( "Choose an option", 'complianz-gdpr' ) ?></option>
				<?php foreach (
					$args['options'] as $option_key => $option_label
				) { ?>
					<option <?php
							if ( is_array( $args['disabled']) && in_array($option_key, $args['disabled']) ) {
								echo "disabled";;
							}?> value="<?php echo esc_html( $option_key ) ?>" <?php echo ( $option_key === $value ) ? "selected" : "" ?>><?php echo esc_html( $option_label ) ?></option>
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
			<?php do_action( 'complianz_label_html' , $args );?>
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
			if ( ! $this->show_field( $args ) ) {
				return;
			}

			$red = isset($args['red']) && $args['red'] ? 'button-red' : '';
			$button_label = isset($args['button_label']) ? $args['button_label'] : $args['label'];

			?>
			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<?php if ( $args['post_get'] === 'get' ) { ?>
				<a <?php if ( $args['disabled'] )
					echo "disabled" ?>href="<?php echo $args['disabled'] ? "#" : $args['action'] ?>"
				   class="button"><?php echo esc_html( $button_label ) ?></a>
			<?php } else if ( $args['post_get'] === 'post' ){ ?>
				<input <?php if ( $args['warn'] )
					echo 'onclick="return confirm(\'' . $args['warn']
						 . '\');"' ?> <?php if ( $args['disabled'] )
					echo "disabled" ?> class="button <?php echo esc_html($red) ?>" type="submit"
									   name="<?php echo esc_html($args['action']) ?>"
									   value="<?php echo esc_html( $button_label ) ?>">
			<?php } else { ?>
				<button <?php if ( $args['disabled'] )
					echo "disabled" ?> class="button <?php echo esc_html($red) ?> <?php echo esc_html($args['action']) ?>" type="button">
					<?php echo esc_html( $button_label ) ?></button>
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
			<?php do_action( 'complianz_label_html' , $args );?>
            <?php do_action( 'complianz_after_label', $args ); ?>
			<div style="flex-grow:1"></div>
            <input type="button" class="upload_button button button-grey" value="<?php _e("Choose file", "complianz-gdpr")?>"/>
            <input type="file" type="submit" name="cmplz-upload-file" style="display: none;">

            <input <?php if ( $args['disabled'] )
                echo "disabled" ?> class="button" type="submit"
                                   name="<?php echo esc_html($args['action']) ?>"
                                   value="<?php _e( 'Import',
                                       'complianz-gdpr' ) ?>">
			<div class="cmplz-comment"><span class="cmplz-file-chosen"><?php _e("No file chosen", "complianz-gdpr")?></span></div>

            <?php do_action( 'complianz_after_field', $args ); ?>

			<?php
		}





		public
		function save_button() {
			wp_nonce_field( 'complianz_save', 'cmplz_nonce' );
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
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<button class="button" type="submit" name="cmplz_add_multiple"
			        value="<?php echo esc_html( $args['fieldname'] ) ?>"><?php _e( "Add new",
					'complianz-gdpr' ) ?></button>
			<br /><br />
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
                                  name="cmplz_multiple[<?php echo esc_html( $args['fieldname'] ) ?>][<?php echo esc_html($key) ?>][description]"><?php if ( isset( $value['description'] ) )
		                        echo esc_html( $value['description'] ) ?></textarea>
						</div>

					</div>
					<button class="button cmplz-remove" type="submit"
					        name="cmplz_remove_multiple[<?php echo esc_html( $args['fieldname'] ) ?>]"
					        value="<?php echo esc_html($key) ?>"><?php _e( "Remove",
							'complianz-gdpr' ) ?></button>
					<?php
				}
			}
			?>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php

		}


		public function cookies( $args )
        {
			if ( ! $this->show_field( $args ) ) {
				return;
			}
			do_action( 'complianz_before_label', $args );
			do_action( 'complianz_after_label', $args );
			?>

			<div class="cmplz-list-container">
				<div class="cmplz-skeleton"></div>
			</div>
            <button type="button"
                    class="button cmplz-edit-item button-primary"
                    name="cmplz_add_item"
                    data-type='cookie'
                    data-action="add"
                    value="<?php echo esc_html( $args['fieldname'] ) ?>">
                <?php _e( "Add new cookie", 'complianz-gdpr' ) ?>
            </button>

			<?php

            do_action( 'complianz_after_field', $args );
		}

		/**
		 * @param $language
		 *
		 * @return string
		 */

		private function get_language_descriptor( $language, $type = 'cookie' ) {
			$string = $type =='cookie' ? __( 'Cookies in %s', 'complianz-gdpr' ) : __( 'Services in %s', 'complianz-gdpr' );
			if ( isset( COMPLIANZ::$config->language_codes[ $language ] ) ) {
				$string = cmplz_sprintf( $string ,
					COMPLIANZ::$config->language_codes[ $language ] );
			} else {
				$string = cmplz_sprintf( $string,
					strtoupper( $language ) );
			}

			return $string;
		}


		public function services( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $default_language = substr( get_locale(), 0, 2 );
            do_action( 'complianz_before_label', $args );
            do_action( 'complianz_after_label', $args );?>
			<?php

			$languages  = COMPLIANZ::$cookie_admin->get_supported_languages();
			$count      = COMPLIANZ::$cookie_admin->get_supported_languages( true );

			if ( $count > 1 ) { ?>
				<select id="cmplz_language" data-type="service">
					<?php foreach ( $languages as $language ) { ?>
						<option value="<?php echo esc_html($language) ?>" <?php if ( $default_language === $language ) echo "selected" ?>>
							<?php echo esc_html($this->get_language_descriptor( $language , 'service') ); ?>
                        </option>
					<?php } ?>
				</select>
            <?php } else { ?>
				<input type="hidden" id="cmplz_language" data-type="service" value="<?php echo reset( $languages ) ?>">
            <?php } ?>
			<div class="cmplz-list-container">
				<div class="cmplz-skeleton"></div>
			</div>

			<button type="button"
                    class="button cmplz-edit-item button-primary"
			        name="cmplz_add_item"
                    data-type='service'
                    data-action="add"
			        value="<?php echo esc_html( $args['fieldname'] ) ?>">
                <?php _e( "Add new service", 'complianz-gdpr' ) ?>
            </button>

			<?php

            do_action( 'complianz_after_field', $args );
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
			<?php do_action( 'complianz_label_html' , $args );?>
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

					$value  = wp_parse_args( $value, $default_index );
					$create_processing_agreement_link = '<a href="' . admin_url( "admin.php?page=cmplz-processing-agreements" ) . '">';

					$processing_agreement_outside_c = floatval( ( $value['processing_agreement'] ) == - 1 ) ? 'selected' : '';
					$html = '<div class="multiple-field">
                        <div>
                            <label>'
					        . cmplz_sprintf( __( "Name of the %s with whom you share the data",
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
					        . cmplz_sprintf( __( 'Select the Processing Agreement you made with this %s, or %screate one%s',
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
                        </div>
                        <div>
                            <label>' . cmplz_sprintf( __( '%s country',
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
					$html .= '<div><button class="button button-primary" type="submit" name="cmplz-save">'
                        . __( 'Save', 'complianz-gdpr' ) . '</button>
                            <button class="button cmplz-remove button-red" type="submit"
                            name="cmplz_remove_multiple['
					         . esc_html( $fieldname ) . ']"
                            value="' . esc_html( $key ) . '">' . __( "Remove",
							'complianz-gdpr' ) . '</button></div>';

					$html .= '</div>';

					$title = esc_html( $value['name'] );
					if ( $title == '' ) {
						$title = __( 'New entry', 'complianz-gdpr' );
                        cmplz_panel( $title, $html, '', '', true, true );
					} else {
                        cmplz_panel( $title, $html );
                    }

					?>

					<?php
				}
			}
			?>
			<button class="button" type="submit" class="cmplz-add-new-processor"
			        name="cmplz_add_multiple"
			        value="<?php echo esc_html( $fieldname ) ?>"><?php cmplz_printf( __( "Add new %s",
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
			<?php do_action( 'complianz_label_html' , $args );?>
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
                        <div>
                            <button class="button button-primary" type="submit" name="cmplz-save">'
                                . __( 'Save', 'complianz-gdpr' ) . '</button>
                            <button class="button cmplz-remove button-red" type="submit"
                                name="cmplz_remove_multiple[' . esc_html( $args['fieldname'] ) . ']"
                                value="' . esc_html( $key ) . '">'
                                . __( "Remove", 'complianz-gdpr' ) . '</button>
                        </div>
                    </div>';

					$title = esc_html( $value['name'] );
					if ( $title == '' ) {
						$title = cmplz_sprintf( __( 'New entry', 'complianz-gdpr' ) );
                        cmplz_panel( $title, $html, '', '', true, true );
                    } else {
                        cmplz_panel( $title, $html );
                    }
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
		 * Add a script
		 * @param array $args
		 */

        public function add_script( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $values = $this->get_value( $args['fieldname'] );
            if ( empty( $values ) ) {
                $values = array(
						array(
						'name' => __("Example", 'complianz-gdpr'),
						'editor' => 'console.log("fire marketing script")',
						'async' => '0',
						'category' => 'marketing',
						'enable_placeholder' => '1',
						'placeholder_class' => 'your-css-class',
						'placeholder' => 'default',
						'enable' => '0',
					),
				);
            }

            do_action( 'complianz_before_label', $args );
            do_action( 'complianz_label_html' , $args );
            do_action( 'complianz_after_label', $args );

            foreach ( $values as $key => $value ) {
                echo $this->get_add_script_html( $value, $key );
            }

            ?><button type="button" class="button cmplz_script_add" data-type="add_script"><?php _e( "Add new", 'complianz-gdpr' ) ?></button><?php

            do_action( 'complianz_after_field', $args );
        }

		/**
		 * Get html block to add a script
		 *
		 * @param       $value
		 * @param       $i
		 * @param false $open
		 *
		 * @return string|void
		 */
        public function get_add_script_html( $value, $i, $open = false )
        {
            $placeholders = COMPLIANZ::$config->placeholders;

            $default_index = array(
                'name' => __("New entry","complianz-gdpr").' '.$i,
                'editor' => '',
                'async' => '0',
                'category' => 'marketing',
                'enable_placeholder' => '0',
                'placeholder_class' => '',
                'placeholder' => 'default',
                'enable' => '1',
            );

            $value = wp_parse_args( $value, $default_index );
			$enabled = $value['enable'] ? 'checked="checked"' : '';
			$action  = $value['enable'] ? 'disable' : 'enable';

            $html = '
                    <div class="multiple-field">
                        <div>
                            <label>' . __( 'Name', 'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                           <input type="hidden"
                                   class="cmplz_iframe"
                                   data-name="iframe"
                                   name="cmplz_add_script['.$i.'][iframe]"
                                   value="0">
                            <input type="text"
                                   class="cmplz_name"
                                   data-name="name"
                                   name="cmplz_add_script['.$i.'][name]"
                                   value="' . esc_html( $value['name'] ) . '">
                        </div>

                        <div id="cmplz_add_script['.$i.'][editor_div]" style="height: 200px; width: 100%">' . $value['editor'] . '</div>
                        <script>
                        	window.define = ace.define;
							window.require = ace.require;
                            var add_script_'.$i.' =
                            ace.edit("cmplz_add_script['.$i.'][editor_div]");
                            add_script_'.$i.'.setTheme("ace/theme/tomorrow_night_bright");
                            add_script_'.$i.'.session.setMode("ace/mode/javascript");
                            jQuery(document).ready(function ($) {
                                var textarea_'.$i.' = $(\'textarea[name="cmplz_add_script['.$i.'][editor]"]\');
                                add_script_'.$i.'.
                                getSession().on("change", function () {
                                    textarea_'.$i.'.val(add_script_'.$i.'.getSession().getValue()
                                )
                                });
                            });
                        </script>
                        <textarea data-name="editor" style="display:none" name="cmplz_add_script['.$i.'][editor]">' . $value['editor'] . '</textarea>

                        <label class="cmplz-checkbox-container">' . __( 'This script contains an async attribute.', 'complianz-gdpr' ) . '
                            <input
                            	data-name="async"
                                name="cmplz_add_script['.$i.'][async]"
                                type="hidden"
                                value="0">
                            <input
                            	data-name="async"
                                name="cmplz_add_script['.$i.'][async]"
                                class="cmplz_add_script['.$i.'][async]"
                                type="checkbox"
                                value="1"
                                ' . (($value['async'] == 1) ? 'checked' : '') . '>
                            <div class="checkmark">' . cmplz_icon('check', 'success') . '</div>
                        </label>

                        <div>
                            <label><h3>' . __( 'Category', 'complianz-gdpr' ) . '<h3></label>
                        </div>

                        <label class="cmplz-radio-container">' . __( 'Statistics', 'complianz-gdpr' ) . '
                            <input
                                data-name="category"
                                type="radio"
                                id="statistics"
                                name="cmplz_add_script['.$i.'][category]"
                                class="cmplz_add_script['.$i.'][category]"
                                value="statistics"
                                ' . (($value['category'] === 'statistics') ? 'checked' : '') . '>
                            <div class="radiobtn" required>' . cmplz_icon( 'bullet', 'default', '', 10) . '</div>
                        </label>
                        <label class="cmplz-radio-container">' . __( 'Marketing', 'complianz-gdpr' ) . '
                            <input
                                type="radio"
								data-name="category"
                                id="marketing"
                                name="cmplz_add_script['.$i.'][category]"
                                class="cmplz_add_script['.$i.'][category]"
                                value="marketing"
                                ' . (($value['category'] === 'marketing') ? 'checked' : '') . '>
                            <div class="radiobtn">' . cmplz_icon( 'bullet', 'default', '', 10) . '</div>
                        </label>

                        <div>
                            <label><h3>' . __( 'Placeholder', 'complianz-gdpr' ) . '<h3></label>
                        </div>

                        <label class="cmplz-checkbox-container">' . __( 'Enable placeholder', 'complianz-gdpr' ) . '
                            <input
								data-name="enable_placeholder"
                                name="cmplz_add_script['.$i.'][enable_placeholder]"
                                type="hidden"
                                value="0">
                            <input
								data-name="enable_placeholder"
                                name="cmplz_add_script['.$i.'][enable_placeholder]"
                                class="cmplz_add_script['.$i.'][enable_placeholder]"
                                type="checkbox"
                                value="1"
                                ' . (($value['enable_placeholder'] == 1) ? 'checked' : '') . '>
                            <div class="checkmark">' . cmplz_icon('check', 'success') . '</div>
                        </label>
                        <div class="condition-check-1 '. (!$value['enable_placeholder'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="1" data-condition-question-1="add_script['.$i.'][enable_placeholder]">
                            <label>' . __( 'Enter the div class or ID that should be targeted.', 'complianz-gdpr' ) .cmplz_read_more('https://complianz.io/script-center#placeholder/'). '</label>
                        </div>
                        <div class="condition-check-1 '. (!$value['enable_placeholder'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="1" data-condition-question-1="add_script['.$i.'][enable_placeholder]">
                            <input type="text"
								data-name="placeholder_class"
                                   name="cmplz_add_script['.$i.'][placeholder_class]"
                                   value="' . esc_html( $value['placeholder_class'] ) . '">
                        </div>

                        <div class="condition-check-1 '. (!$value['enable_placeholder'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="1" data-condition-question-1="add_script['.$i.'][enable_placeholder]">
                            <select data-name="placeholder" name="cmplz_add_script['.$i.'][placeholder]">';
                                foreach ( $placeholders as $placeholder => $label ) {
                                    $selected = ( esc_html( $value['placeholder'] ) === $placeholder ) ? 'selected' : '';
                                    $html     .= '<option value="' . $placeholder . '" ' . $selected . '>' . $label . '</option>';
                                }
                                $html .= '</select>
                        </div>

                        <div class="cmplz-multiple-field-button-footer">
                            <button class="button button-primary cmplz_script_save" type="button" data-id="'.$i.'" data-type="add_script" data-action="save">' . __( 'Save', 'complianz-gdpr' ) . '</button>
                           <button class="button button-primary button-red cmplz_script_save" type="button" data-id="'.$i.'" data-type="add_script" data-action="remove">' . __( "Remove", 'complianz-gdpr' ) . '</button>
                        </div>
                    </div>';

			$title = esc_html( $value['name'] ) !== '' ? esc_html( $value['name'] ) : __( 'New entry', 'complianz-gdpr' ) ;

			$custom_button = '<div class="cmplz-checkbox cmplz_script_save" data-action="'.$action.'" data-type="add_script" data-id="'.$i.'">
								<input type="hidden"
									   value="0"
										name="cmplz_add_script['.$i.'][enable]">
								<input type="checkbox"
									   data-name="enable"
									   class="cmplz-checkbox"
									   size="40"
									   value="1"
										name="cmplz_add_script['.$i.'][enable]"
									   '.$enabled.'/>
								<label class="cmplz-label" for="cmplz-enable" tabindex="0"></label>
							</div>';

			return cmplz_panel( $title, $html, $custom_button, '', false, $open );
        }

		/**
		 * Add a script
		 */

        public function ajax_script_add()
        {

            $html = "";
            $error = false;

            if ( ! cmplz_user_can_manage() ) {
            	$error = true;
            }
            if ( ! isset($_POST['type']) || ($_POST['type'] !== 'add_script' && $_POST['type'] !== 'block_script' && $_POST['type'] !== 'whitelist_script') ) {
            	$error = true;
            }

            if ( !$error ) {
                $scripts = get_option("complianz_options_custom-scripts");

                if (!is_array($scripts)) {
					$scripts = [
							'add_script' => [],
							'block_script' => [],
							'whitelist_script' => [],
					];
                }

				if ($_POST['type'] === 'add_script') {
					if ( !is_array($scripts['add_script'])) {
						$scripts['add_script'] = [];
					}
                    $new_id = !empty($scripts['add_script']) ? max(array_keys($scripts['add_script'])) + 1 : 1;
                    $scripts['add_script'][$new_id] = [
                        'name' => '',
                        'editor' => '',
                        'async' => '0',
                        'category' => 'marketing',
                        'enable_placeholder' => '0',
                        'placeholder_class' => '',
                        'placeholder' => '',
                        'enable' => '1',
                    ];
                    $html = $this->get_add_script_html([], $new_id, true);
                }

                if ($_POST['type'] === 'block_script') {
					if ( !is_array($scripts['block_script'])) {
						$scripts['block_script'] = [];
					}
                    $new_id = !empty($scripts['block_script']) ? max(array_keys($scripts['block_script'])) + 1 : 1;
                    $scripts['block_script'][$new_id] = [
                        'name' => '',
                        'urls' => [],
                        'category' => 'marketing',
                        'enable_placeholder' => '0',
                        'iframe' => '1',
                        'placeholder_class' => '',
                        'placeholder' => '',
						'enable_dependency' => '0',
						'dependency' => '',
                        'enable' => '1',
                    ];
                    $html = $this->get_block_script_html([], $new_id, true);
                }

                if ($_POST['type'] === 'whitelist_script') {
					if ( !is_array($scripts['whitelist_script'])) {
						$scripts['whitelist_script'] = [];
					}
                    $new_id = !empty($scripts['whitelist_script']) ? max(array_keys($scripts['whitelist_script'])) + 1 : 1;
                    $scripts['whitelist_script'][$new_id] = [
                        'name' => '',
                        'urls' => [],
                        'enable' => '1',
                    ];
                    $html = $this->get_whitelist_script_html([], $new_id, true);
                }
                update_option("complianz_options_custom-scripts", $scripts);
            }

            $data     = array(
                'success' => !$error,
                'html'    => $html,
            );

            $response = json_encode( $data );
            header( "Content-Type: application/json" );
            echo $response;
            exit;
        }

		/**
		 * Save script center data
		 *
		 */

        public function ajax_script_save()
        {
            $error = false;
            if ( ! cmplz_user_can_manage() ) $error = true;
            if ( ! isset($_POST['data']) ) $error = true;
            if ( ! isset($_POST['id']) ) $error = true;
            if ( ! isset($_POST['type']) ) $error = true;
            if ( $_POST['type'] !== 'add_script' && $_POST['type'] !== 'block_script' && $_POST['type'] !== 'whitelist_script' ) $error = true;
            if ( ! isset($_POST['button_action']) ) $error = true;
            if ( $_POST['button_action'] !== 'save' && $_POST['button_action'] !== 'enable' && $_POST['button_action'] !== 'disable' && $_POST['button_action'] !== 'remove') $error = true;
            if ( !$error ) {
                $id = intval($_POST['id']);
                $type = sanitize_text_field($_POST['type']);
                $action = sanitize_title($_POST['button_action']);
				$data = json_decode(stripslashes($_POST['data']), true);
				$scripts = get_option("complianz_options_custom-scripts", array() );
                if ( !$error ) {
                    if ($action === 'remove') {
                        unset($scripts[$type][$id]);
                    } else {
						$scripts[$type][$id] = $this->sanitize_custom_scripts($data);;
                    }
                    update_option("complianz_options_custom-scripts", $scripts);
                }
            }

            $data = array(
                'success' => !$error,
            );

            $response = json_encode( $data );
            header( "Content-Type: application/json" );
            echo $response;
            exit;
        }

		/**
		 * Get block script field
		 * @param array $args
		 */
        public function block_script( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $values = $this->get_value( $args['fieldname'] );
            if ( empty( $values ) ) {
				$values = array(
						array(
								'name' => __("Example", 'complianz-gdpr'),
								'urls' => array('https://block-example.com'),
								'category' => 'marketing',
								'enable_placeholder' => '1',
								'iframe' => '1',
								'placeholder_class' => 'your-css-class',
								'placeholder' => 'default',
								'enable_dependency' => '1',
								'dependency' => array(),
								'enable' => '0',
						),
				);
            }

            do_action( 'complianz_before_label', $args );
            do_action( 'complianz_label_html' , $args );
            do_action( 'complianz_after_label', $args );

            foreach ( $values as $key => $value ) {
                echo $this->get_block_script_html( $value, $key );
            }

            ?><button type="button" class="button cmplz_script_add" data-type="block_script"><?php _e( "Add new", 'complianz-gdpr' ) ?></button><?php

            do_action( 'complianz_after_field', $args );
        }

		/**
		 * Get block script html
		 * @param string $value
		 * @param int $i
		 * @param false $open
		 *
		 * @return string
		 */
        public function get_block_script_html( $value, $i, $open = false )
        {
			$known_script_tags = COMPLIANZ::$cookie_blocker->blocked_scripts();
			$placeholders = COMPLIANZ::$config->placeholders;
            $default_index = array(
				'name' => __("New entry","complianz-gdpr").' '.$i,
                'urls' => array(''),
                'category' => 'marketing',
                'enable_placeholder' => '0',
                'iframe' => '1',
                'placeholder_class' => '',
                'placeholder' => 'default',
				'enable_dependency' => '0',
				'dependency' => array(),
                'enable' => '1',
            );
            $value = wp_parse_args( $value, $default_index );
			$enabled = $value['enable'] ? 'checked="checked"' : '';
			$action  = $value['enable'] ? 'disable' : 'enable';

			$html = '
                    <div class="multiple-field">
                        <div>
                            <label>' . __( 'Name', 'complianz-gdpr' ) . '</label>
                        </div>
                        <div>
                            <input type="text"
									data-name="name"
                                    class="cmplz_name"
                                    name="cmplz_block_script['.$i.'][name]"
                                    value="' . esc_html( $value['name'] ) . '">
                        </div>
                        <div>
                            <label>' . __( 'URLs that should be blocked before consent.' , 'complianz-gdpr' ) . '</label>
                        </div>
                        <div class="cmplz-hidden cmplz-url-template">
                      	<div><input type="text"
							   data-name="urls"
							   name="cmplz_block_script['.$i.'][urls][]"
							   value=""><button type="button" class="cmplz_remove_url">'.cmplz_icon('minus', 'default').'</button></div></div>
                        <div>';
							$counter = 0;
							if ( empty($value['urls'])) $value['urls'] = array(' ');

							foreach ($value['urls'] as $url ){
								$counter++;
								$html .= '<div><input type="text"
									   data-name="urls"
									   name="cmplz_block_script['.$i.'][urls][]"
									   value="' . esc_html( $url ) . '">';
								if ($counter==1){
									$html .= '<button type="button" class="cmplz_add_url">'.cmplz_icon('plus', 'default').'</button>';
								} else {
									$html .= '<button type="button" class="cmplz_remove_url">'.cmplz_icon('minus', 'default').'</button>';
								}
								$html.='</div>';
							}

							$html.= '</div>
                        <div>
                            <label><h3>' . __( 'Category', 'complianz-gdpr' ) . '<h3></label>
                        </div>

                        <label class="cmplz-radio-container">' . __( 'Statistics', 'complianz-gdpr' ) . '
                            <input
                            	data-name="category"
                                type="radio"
                                id="statistics"
                                name="cmplz_block_script['.$i.'][category]"
                                class="cmplz_block_script['.$i.'][category]"
                                value="statistics"
                                ' . (($value['category'] === 'statistics') ? 'checked' : '') . '>
                            <div class="radiobtn" required>' . cmplz_icon( 'bullet', 'default', '', 10) . '</div>
                        </label>
                        <label class="cmplz-radio-container">' . __( 'Marketing', 'complianz-gdpr' ) . '
                            <input
                            	data-name="category"
                                type="radio"
                                id="marketing"
                                name="cmplz_block_script['.$i.'][category]"
                                class="cmplz_block_script['.$i.'][category]"
                                value="marketing"
                                ' . (($value['category'] === 'marketing') ? 'checked' : '') . '>
                            <div class="radiobtn">' . cmplz_icon( 'bullet', 'default', '', 10) . '</div>
                        </label>

                        <div>
                            <label><h3>' . __( 'Placeholder', 'complianz-gdpr' ) . '<h3></label>
                        </div>

                        <label class="cmplz-checkbox-container">' . __( 'Enable placeholder', 'complianz-gdpr' ) . '
                            <input
                            	data-name="enable_placeholder"
                                name="cmplz_block_script['.$i.'][enable_placeholder]"
                                type="hidden"
                                value="0">
                            <input
                            	data-name="enable_placeholder"
                                name="cmplz_block_script['.$i.'][enable_placeholder]"
                                class="cmplz_block_script['.$i.'][enable_placeholder]"
                                type="checkbox"
                                value="1"
                                ' . (($value['enable_placeholder'] == 1) ? 'checked' : '') . '>
                            <div class="checkmark">' . cmplz_icon('check', 'success') . '</div>
                        </label>
                        <div class="condition-check-1 '. (!$value['enable_placeholder'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="1" data-condition-question-1="block_script['.$i.'][enable_placeholder]">
                        <label class="cmplz-checkbox-container">' . __( 'The blocked content is an iframe', 'complianz-gdpr' ) . '
                            <input
                            	data-name="iframe"
                                name="cmplz_block_script['.$i.'][iframe]"
                                type="hidden"
                                value="0">
                            <input
                            	data-name="iframe"
                                name="cmplz_block_script['.$i.'][iframe]"
                                class="cmplz_block_script['.$i.'][iframe]"
                                type="checkbox"
                                value="1"
                                ' . (($value['iframe'] == 1) ? 'checked' : '') . '>
                            <div class="checkmark">' . cmplz_icon('check', 'success') . '</div>
                        </label>
                        </div>
                        <div class="condition-check-1 '. ($value['iframe'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="" data-condition-question-1="block_script['.$i.'][iframe]">
                            <label>' . __( 'Enter the div class or ID that should be targeted.' , 'complianz-gdpr' ) .cmplz_read_more('https://complianz.io/script-center#placeholder/'). '</label>
                        </div>
                        <div class="condition-check-1 '. ($value['iframe'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="" data-condition-question-1="block_script['.$i.'][iframe]">
                            <input type="text"
                            	data-name="placeholder_class"
								   name="cmplz_block_script['.$i.'][placeholder_class]"
								   value="' . esc_html( $value['placeholder_class'] ) . '">
                        </div>

                        <div class="condition-check-1 '. (!$value['enable_placeholder'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="1" data-condition-question-1="block_script['.$i.'][enable_placeholder]">
                            <select name="cmplz_block_script['.$i.'][placeholder]" data-name="placeholder">';
                                foreach ( $placeholders as $placeholder => $label ) {
                                    $selected = ( esc_html( $value['placeholder'] ) === $placeholder ) ? 'selected' : '';
                                    $html     .= '<option value="' . $placeholder . '" ' . $selected . '>' . $label . '</option>';
                                }
                                $html .= '</select>
                        </div>

                        <div>
                            <label><h3>' . __( 'Dependency', 'complianz-gdpr' ) . '<h3></label>
                        </div>

                        <div>
                            <label class="cmplz-checkbox-container">' . __( 'Enable dependency', 'complianz-gdpr' ) . '
                                <input
                                	data-name="enable_dependency"
                                    name="cmplz_block_script['.$i.'][enable_dependency]"
                                    type="hidden"
                                    value="0">
                                <input
                                	data-name="enable_dependency"
                                    name="cmplz_block_script['.$i.'][enable_dependency]"
                                    class="cmplz_block_script['.$i.'][enable_dependency]"
                                    type="checkbox"
                                    value="1"
                                    ' . (($value['enable_dependency'] == 1) ? 'checked' : '') . '>
                                <div class="checkmark">' . cmplz_icon('check', 'success') . '</div>
                            </label>
                        </div>

                        <div class="condition-check-1 '. (!$value['enable_dependency'] ? 'cmplz-hidden' : '') .'" data-condition-answer-1="1" data-condition-question-1="block_script['.$i.'][enable_dependency]">';

						foreach ($value['urls'] as $url ){
//							$deps['wait-for-this-script'] = 'script-that-should-wait';
							//for readability, key (text) is put behind the value (select) here
							$html .= '<select name="cmplz_block_script['.$i.'][dependency]['.$url.']" data-name="dependency" data-url="'.$url.'">
                                <option value="0">' . __( 'No dependency', 'complianz-gdpr' ) . '</option>';
								foreach ( $known_script_tags as $script_tag => $item ) {
									$selected = isset($value['dependency'][$url]) && $value['dependency'][$url] === $script_tag ? 'selected' : '';
									$html     .= '<option value="' . $script_tag . '" ' . $selected . '>' . $script_tag . '</option>';
								}
								$html .= '</select>';
							$html .= '<div class="cmplz_deps_desc">'. cmplz_sprintf(__('waits for %s', "complianz-gdpr") , '<b>'.esc_html($url).'</b>' ).'</div>';
						}

                        $html .= '</div>

                        <div class="cmplz-multiple-field-button-footer">
                            <button class="button button-primary cmplz_script_save" type="button" data-id="'.$i.'" data-type="block_script" data-action="save">' . __( 'Save', 'complianz-gdpr' ) . '</button>
							<button class="button button-primary button-red cmplz_script_save" type="button" data-id="'.$i.'" data-type="block_script" data-action="remove">' . __( "Remove", 'complianz-gdpr' ) . '</button>
                        </div>
                    </div>';

            $title = esc_html( $value['name'] ) !== '' ? esc_html( $value['name'] ) : __( 'New entry', 'complianz-gdpr' ) ;
			$custom_button = '<div class="cmplz-checkbox cmplz_script_save" data-action="'.$action.'" data-type="block_script" data-id="'.$i.'">
								<input type="hidden"
									   value="0"
										name="cmplz_block_script['.$i.'][enable]">
								<input type="checkbox"
									   data-name="enable"
									   name="cmplz_block_script['.$i.'][enable]"
									   class="cmplz-checkbox"
									   size="40"
									   value="1"
									   '.$enabled.'/>
								<label class="cmplz-label" for="cmplz-enable" tabindex="0"></label>
							</div>';

			return cmplz_panel( $title, $html, $custom_button, '', false, $open );
        }

		/**
		 * Field for whitelisting scripts
		 *
		 * @param array $args
		 */
        public function whitelist_script( $args )
        {
            if ( ! $this->show_field( $args ) ) {
                return;
            }

            $values = $this->get_value( $args['fieldname'] );
            if ( empty( $values ) ) {
				$values = array(
						array(
								'name' => __("Example", 'complianz-gdpr'),
								'urls' => array('https://block-example.com'),
								'enable' => '0',
						),
				);
            }

            do_action( 'complianz_before_label', $args );
            do_action( 'complianz_label_html' , $args );
            do_action( 'complianz_after_label', $args );

            foreach ( $values as $key => $value ) {
                echo $this->get_whitelist_script_html( $value, $key );
            }

            ?><button type="button" class="button cmplz_script_add" data-type="whitelist_script"><?php _e( "Add new", 'complianz-gdpr' ) ?></button><?php

            do_action( 'complianz_after_field', $args );
        }

        public function get_whitelist_script_html( $value, $i, $open = false )
        {
            $default_index = array(
				'name' => __("New entry","complianz-gdpr").' '.$i,
                'urls' => array(''),
                'enable' => '1',
            );

            $value = wp_parse_args( $value, $default_index );
			$enabled = $value['enable'] ? 'checked="checked"' : '';
			$action  = $value['enable'] ? 'disable' : 'enable';

            $html = '
            <div class="multiple-field">
                <div>
                    <label>' . __( 'Name', 'complianz-gdpr' ) . '</label>
                </div>
                <div>
                    <input type="text"
                    		data-name="name"
                           class="cmplz_name"
                           name="cmplz_whitelist_script['.$i.'][name]"
                           value="' . esc_html( $value['name'] ) . '">
                </div>
                <div>
                    <label>' . __( 'URLs that should be whitelisted.' , 'complianz-gdpr' ).cmplz_read_more( 'https://complianz.io/whitelisting-inline-script/' ). '</label>
                </div>
                      <div>
                      <div class="cmplz-hidden cmplz-url-template">
                      	<div><input type="text"
							   data-name="urls"
							   name="cmplz_whitelist_script['.$i.'][urls][]"
							   value=""><button type="button" class="cmplz_remove_url">'.cmplz_icon('minus', 'default').'</button></div></div>
                      ';
					$counter = 0;
					if ( empty($value['urls'])) $value['urls'] = array(' ');
					foreach ($value['urls'] as $url ){
						$counter++;
						$html .= '<div><input type="text"
											   data-name="urls"
											   name="cmplz_whitelist_script['.$i.'][urls][]"
											   value="' . esc_html( $url ) . '">';
						if ($counter==1){
							$html .= '<button type="button" class="cmplz_add_url">'.cmplz_icon('plus', 'default').'</button>';
						} else {
							$html .= '<button type="button" class="cmplz_remove_url">'.cmplz_icon('minus', 'default').'</button>';
						}
						$html.= '</div>';
					}

			$html.= '</div>
                <div class="cmplz-multiple-field-button-footer">
                    <button class="button button-primary cmplz_script_save" type="button" data-id="'.$i.'" data-type="whitelist_script" data-action="save">' . __( 'Save', 'complianz-gdpr' ) . '</button>
					<button class="button button-primary button-red cmplz_script_save" type="button" data-id="'.$i.'" data-type="whitelist_script" data-action="remove">' . __( "Remove", 'complianz-gdpr' ) . '</button>
                </div>
            </div>';

			$title = esc_html( $value['name'] ) !== '' ? esc_html( $value['name'] ) : __( 'New entry', 'complianz-gdpr' ) ;
			$custom_button = '<div class="cmplz-checkbox cmplz_script_save" data-action="'.$action.'" data-type="whitelist_script" data-id="'.$i.'">
								<input type="hidden"
									   value="0"
										name="cmplz_whitelist_script['.$i.'][enable]">
								<input type="checkbox"
									   name="cmplz_whitelist_script['.$i.'][enable]"
									   class="cmplz-checkbox cmplz-enable"
									   size="40"
									   value="1"
									   data-name="enable"
									   '.$enabled.'/>
								<label class="cmplz-label" for="cmplz-enable" tabindex="0"></label>
							</div>';

			return cmplz_panel( $title, $html, $custom_button, '', false, $open );
        }


        public function use_logo_complianz( $args )
		{
			$complianz_logo = file_get_contents(trailingslashit(cmplz_path) . 'assets/images/poweredbycomplianz.svg');
			do_action( 'complianz_before_label', $args );
			do_action( 'complianz_label_html' , $args );
			do_action( 'complianz_after_label', $args );
			?>
			<div class="cmplz-logo-preview cmplz-complianz-logo">
				<?php echo $complianz_logo ?>
			</div>
			<?php

			do_action( 'complianz_after_field', $args );
		}

		public function use_logo_site( $args )
		{
			$site_logo = get_custom_logo();

			if ( !$site_logo ) {
				$site_logo = __("No site logo configured.", "complianz-gdpr");
			}

			do_action( 'complianz_before_label', $args );
			do_action( 'complianz_label_html' , $args );
			do_action( 'complianz_after_label', $args );

			?>
				<input type="hidden" name="cmplz-customizer-url" value="<?php echo wp_customize_url()?>">
			<div class="cmplz-logo-preview cmplz-theme-image">
				<?php echo $site_logo ?>
			</div>
			<?php

			do_action( 'complianz_after_field', $args );
		}

		public
		function use_logo_custom($args)
		{
			$src = cmplz_url . '/assets/images/placeholders/default-light.jpg';
			$attachment_id = $this->get_value( $args['fieldname'], false );
			if ( $attachment_id ) {
				$src = wp_get_attachment_image_url( $attachment_id, 'cmplz_banner_image' );
			}

			do_action('complianz_before_label', $args);
			do_action( 'complianz_label_html' , $args );
			do_action( 'complianz_after_label', $args );
			?>
			<div style="flex-grow:1"></div>
			<input type="hidden" name="cmplz_<?php echo esc_html($args['fieldname']) ?>" value="<?php echo intval($attachment_id)?>">
			<div>
				<input <?php if ($args['disabled']) echo "disabled"?> class="button cmplz-image-uploader" type="button" value="<?php _e('Edit', 'complianz-gdpr') ?>">
			</div>
			<div class="cmplz-logo-preview cmplz-clickable">
				<img alt="preview image" src="<?php echo $src?>">
			</div>
			<?php do_action( 'complianz_after_field', $args ); ?>
			<?php
		}


		public function text_checkbox( $args )
		{
			if ( ! $this->show_field( $args ) ) {
				return;
			}

			$fieldname = 'cmplz_' . esc_html($args['fieldname']);
			$value = $this->get_value( $args['fieldname'], $args['default'] );

			//fallback for older data
			if ( !is_array($value)) {
				$temp = $value;
				$value = array();
				$value['text'] = $temp;
				$value['show'] = true;
			}

			$required = $args['required'] ? 'required' : '';
			$is_required = $args['required'] ? 'is-required' : '';
			$check_icon = cmplz_icon('check', 'success');
			$times_icon = cmplz_icon('times');

			?>

			<?php do_action( 'complianz_before_label', $args ); ?>
			<?php do_action( 'complianz_label_html' , $args );?>
			<?php do_action( 'complianz_after_label', $args ); ?>

			<div class="cmplz-text">
				<input <?php echo $required ?>
					class="validation <?php echo $is_required ?>"
					placeholder="<?php echo esc_html( $args['placeholder'] ) ?>"
					type="text"
					value="<?php echo esc_html($value['text']) ?>"
					name="<?php echo esc_attr($fieldname) ?>[text]"
				>
				<?php echo $check_icon ?>
				<?php echo $times_icon ?>
			</div>

			<label class="cmplz-switch">
				<input name="<?php echo esc_attr($fieldname) ?>[show]" type="hidden" value="0"/>
				<input name="<?php echo esc_attr($fieldname) ?>[show]" size="40" type="checkbox"
					<?php if ( $args['disabled'] ) {
						echo 'disabled';
					} ?>
					   class="<?php if ( $args['required'] ) {
						   echo 'is-required';
					   } ?>"
					   value="1" <?php checked( 1, $value['show'], true ) ?> />
				<span class="cmplz-slider cmplz-round"></span>
			</label>

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

		public function get_default( $fieldname, $default = '' ) {
			return apply_filters( 'cmplz_default_value', $default, $fieldname );
		}

		/**
		 * Get value of this fieldname
		 *
		 * @param        $fieldname
		 * @param string $default
		 *
		 * @return mixed
		 */

		public function get_value( $fieldname, $default = '' ) {
			$fields = COMPLIANZ::$config->fields();

			if ( ! isset( $fields[ $fieldname ] ) ) {
				return false;
			}

			$source = $fields[ $fieldname ]['source'];
			if ( strpos( $source, 'CMPLZ' ) !== false
			     && class_exists( $source )
			) {

				$banner = $this->banner;
				$value  = $banner->{$fieldname};


			} else {
				$options = get_option( 'complianz_options_' . $source );
				$value   = isset( $options[ $fieldname ] ) ? $options[ $fieldname ] : false;
			}

			//if no value is set, pass a default
			return ( $value !== false ) ? $value : apply_filters( 'cmplz_default_value', $default, $fieldname );
		}

		/**
		 * Checks if a fieldname exists in the complianz field list.
		 *
		 * @param string $fieldname
		 *
		 * @return bool
		 */

		public function sanitize_fieldname(
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
			$warning_class = '';
			if (isset( $args['comment_status']) && $args['comment_status']==='warning'){
				$warning_class='cmplz-comment-warning';
			}
			?>
			<div class="cmplz-comment <?=$warning_class?>"><?php echo $args['comment'] ?></div>
			<?php
		}

		public function has_errors() {
			if ( count( $this->form_errors ) > 0 ) {
				return true;
			}
			return false;
		}


	}
} //class closure
