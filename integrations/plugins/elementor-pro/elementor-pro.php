<?php

use Elementor\Controls_Manager;
use \Elementor\TemplateLibrary\Source_Local;

defined( 'ABSPATH' ) or die( "you do not have access to this page!" );


/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class CMPLZ_Elementor_Pro {
	public $banner_active;
	public $create_legal_hub = false;
	public $create_cookiebanner = false;
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->banner_active = cmplz_get_value('create_banner_elementor', false, 'wizard') ==='yes';
		if (isset($_POST['wizard_type']) && $_POST['wizard_type'] === 'wizard' && isset($_POST['cmplz_create_banner_elementor']) && $_POST['cmplz_create_banner_elementor']==='yes') {
			$this->banner_active = true;
		}
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {
		//only if cookiebanner is activated
		if ( $this->banner_active ) {
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
			add_filter( 'cmplz_finish_wizard_target', [ $this, 'change_afer_wizard_target' ], 10, 1);
			add_action( 'cmplz_wizard_wizard', array( $this, 'redirect_after_finish_wizard' ), 100 );
			add_filter( 'init', [ $this, 'remove_actions' ], 10, 1);
			add_filter( 'cmplz_cookiebanner_grid_items', [ $this, 'edit_cookiebanner_items' ], 10, 1);
			add_action( 'cmplz_cookiebanner_settings_front_end', array( $this, 'remove_banner_css' ), 100 );
		}

		add_action( 'elementor/init', array( $this, 'maybe_import_templates' ), 10 );
		add_action( 'complianz_after_save_wizard_option', array( $this, 'after_save_wizard_option' ), 10, 4 );
		add_filter( 'cmplz_fields', [ $this, 'cmplz_filter_elementor_pro_fields' ], 10, 1);
	}

	public function on_widgets_registered() {
		if ($this->banner_active) {
			$this->includes();
			$this->register_widget();
		}
	}
	public function after_save_wizard_option( $fieldname, $fieldvalue, $prev_value, $type ) {
		if (!current_user_can('manage_options')) return;
		if ($prev_value === $fieldvalue) return;
		if ($fieldname==='create_legal_hub_elementor' && $fieldvalue==='yes'){
			$this->create_legal_hub = true;
		}
		if ($fieldname==='create_banner_elementor' && $fieldvalue==='yes'){
			$this->create_cookiebanner = true;
		}
	}
	/**
	 * Remove banner css
	 * @param array $output
	 *
	 * @return array
	 */
	public function remove_banner_css($output){
		$output['css_file'] = '';
		return $output;
	}

	public function remove_actions(){
		remove_action( 'wp_footer', array( COMPLIANZ::$cookie_admin, 'cookiebanner_html') );
	}

	/**
	 * Change the target on the finish button in the wizard
	 * @param string $target
	 *
	 * @return string|void
	 */
	public function change_afer_wizard_target($target){
		if ( !cmplz_user_can_manage() ) {
			return;
		}
		$target = 'cmplz-cookiebanner-elementor';
		return $target;
	}



	public function edit_cookiebanner_items($grid_items){
		unset($grid_items['general']);
		unset($grid_items['appearance']);
		unset($grid_items['customization']);
		unset($grid_items['settings']);
		unset($grid_items['custom_css']);
		$grid_items['categories'] = array(
			'page' => 'CMPLZ_COOKIEBANNER',
			'name' => 'categories',
			'header' => __('Categories', 'complianz-gdpr'),
			'class' => 'big',
			'index' => '15',
		);
		return $grid_items;
	}


	private function includes() {
		require_once(cmplz_path . 'integrations/plugins/elementor-pro/custom-widgets/cmplz-accept-button-widget.php');
		require_once(cmplz_path . 'integrations/plugins/elementor-pro/custom-widgets/cmplz-manage-consent-button-widget.php');
		require_once(cmplz_path . 'integrations/plugins/elementor-pro/custom-widgets/cmplz-deny-button-widget.php');
		require_once(cmplz_path . 'integrations/plugins/elementor-pro/custom-widgets/cmplz-view-save-preferences-button-widget.php');
		require_once(cmplz_path . 'integrations/plugins/elementor-pro/custom-widgets/cmplz-link-widget.php');
		require_once(cmplz_path . 'integrations/plugins/elementor-pro/custom-widgets/cmplz-category-widget.php');
	}

	/**
	 * Register a widget
	 * @return void
	 */

	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\CMPLZ_Accept_Button());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\CMPLZ_Deny_Button());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\CMPLZ_View_Save_Preferences_Button());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\CMPLZ_Link());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\CMPLZ_Category());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\CMPLZ_Manage_Consent_Button());
	}

	public function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'cmplz-category',
			[
				'title' => __( 'Complianz Cookiebanner', 'complianz-gdpr' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	public function redirect_after_finish_wizard(){
		if ( !cmplz_user_can_manage() ) {
			return;
		}

		if ( isset( $_POST['cmplz-cookiebanner-elementor'] ) ) {
			wp_redirect( add_query_arg(["post_type" => "elementor_library", "tabs_group" => 'popup', "elementor_library_type" => 'popup'], admin_url('edit.php')) );
			exit();
		}
	}

	/**
	 * Add fields for elementor
	 * @param array $fields
	 *
	 * @return array
	 */
	public function cmplz_filter_elementor_pro_fields( $fields ) {
		$link = add_query_arg(["post_type" => "elementor_library", "tabs_group" => 'popup', "elementor_library_type" => 'popup'], admin_url('edit.php'));
		if ($this->banner_active) {
			$fields['category_functional']['help'] = __( "The Elementor Cookie Banner integration has been enabled.","complianz-gdpr").'&nbsp;'. cmplz_sprintf(__("You can edit your banner in the %sElementor popup templates%s.", 'complianz-gdpr' ), '<a href="'.$link.'">', "</a>");
		}

		$fields['create_banner_elementor'] = array(
			'label' => __('Do you want to create your Cookie Banner with Elementor Pro?', "complianz-gdpr"),
			'step' => STEP_COOKIES,
			'order' => 20,
			'section' => 5,
			'source' => 'wizard',
			'type' => 'radio',
			'options' => [
				'yes' => cmplz_sprintf(__('Yes (Available in %sPremium%s)', "complianz-gdpr"),'<a href="https://complianz.io/premium" target="_blank">', '</a>'), //@todo url
				'no'  => __('No', "complianz-gdpr"),
			],
			'disabled' => array('yes'),
			'default' => 'no',
			'required' => false,
			'comment' => __('If you choose to create your Cookie Banner with Elementor Pro we will import our default template.', 'complianz-gdpr').cmplz_read_more('https://complianz.io/integrations/elementor-pro'),//@todo url
		);

		$post_id = get_option('cmplz_elementor_autogenerated');
		$post = get_post($post_id);
		if ( $post_id && !$post ) {
			delete_option('cmplz_elementor_autogenerated');
		}


		if ( $post ) {
			$fields['create_banner_elementor']['help'] = __('A template has been added to your Elementor popups.', 'complianz-gdpr').'&nbsp;'."<a href='" . add_query_arg(["post" => $post_id, "action" => "elementor"], admin_url('post.php')) . "'>".__('Edit template', 'complianz-gdpr').'</a>';
			$fields['create_banner_elementor']['help_status'] = 'help';
			$fields['create_banner_elementor']['disabled'] = true;
		}


		$fields['create_legal_hub_elementor'] = array(
			'label' => __('Do you want to create a Legal Hub with Elementor Pro?', "complianz-gdpr"),
			'step' => STEP_COOKIES,
			'section' => 5,
			'order' => 50,
			'source' => 'wizard',
			'type' => 'radio',
			'options' => [
				'yes' => __('Yes', "complianz-gdpr"),
				'no'  => __('No', "complianz-gdpr"),
			],
			'required' => false,
			'comment' => __('If you choose to create your Legal Hub with Elementor Pro we will import our default template.', 'complianz-gdpr').cmplz_read_more('https://complianz.io/integrations/elementor-pro'),//@todo url
		);

		if ( cmplz_get_value('create_banner_elementor')==='yes' ) {
			$field['cookiebanner_settings_notice'] = array(
				'source' => 'CMPLZ_COOKIEBANNER',
				'step' => 'categories',
				'type' => 'notice',
				'comment' => cmplz_sprintf(__('You enabled the Elementor cookie banner, %sConfigure your banner via Elementor%s or disable the Elementor cookie banner.', 'complianz-gdpr'), "<a target='_blank' href='" . add_query_arg(["post" => $post_id, "action" => "elementor"], admin_url('post.php')) . "'>", "</a>"),
				'required' => false,
			);
			$fields = array_merge($field, $fields);
		}

		if ( defined('cmplz_premium') ) {
			$fields['create_banner_elementor']['options'] = [
						'yes' => __('Yes', "complianz-gdpr"),
						'no'  => __('No', "complianz-gdpr"),
					];
			$fields['create_banner_elementor']['disabled'] = false;

			if ( cmplz_tcf_active() ) {
				$fields['create_banner_elementor']['disabled'] = array('yes');
				$fields['create_banner_elementor']['help'] = __('You have enabled TCF under Integrations. Due to IAB Guidelines you canʼt use this feature.','complianz-gdpr');
				$fields['create_banner_elementor']['help_status'] = 'warning';
				unset($fields['import_elementor_banner']);
			}
			if ( $this->banner_active ) {
				$fields['uses_ad_cookies_personalized']['disabled'] = array('tcf');
				$fields['uses_ad_cookies_personalized']['help'] = __("You have enabled the Elementor popup integration. You can't use TCF in combination with the Elementor popup.",'complianz-gdpr');
				$fields['uses_ad_cookies_personalized']['help_status'] = 'warning';
			}
		}
		return $fields;
	}

	/**
	 *
	 */
	public function maybe_import_templates() {
		if ( !cmplz_user_can_manage() ) {
			return;
		}
		if ( $this->create_cookiebanner ) {
			$post_id = get_option( 'cmplz_elementor_autogenerated' );
			$post = get_post($post_id);
			if ( ! $post || $post->post_status === 'trash' ) {
				//create backup. Elementor deletes the file
				copy( cmplz_path . 'integrations/plugins/elementor-pro/cookiebanner-template.json', cmplz_path . 'integrations/plugins/elementor-pro/cookiebanner-template-bkp.json' );
				require_once WP_PLUGIN_DIR . '/elementor/includes/template-library/sources/local.php';
				$local = new \Elementor\TemplateLibrary\Source_Local();
				$import = $local->import_template( 'cookiebanner-template.json', cmplz_path . 'integrations/plugins/elementor-pro/cookiebanner-template.json' );

				//restore backup
				copy( cmplz_path . 'integrations/plugins/elementor-pro/cookiebanner-template-bkp.json', cmplz_path . 'integrations/plugins/elementor-pro/cookiebanner-template.json' );
				unlink( cmplz_path . 'integrations/plugins/elementor-pro/cookiebanner-template-bkp.json');
				if ( is_array( $import ) && isset( $import[0] ) && isset( $import[0]['template_id'] ) ) {
					$post_id = $import[0]['template_id'];
					add_post_meta( $post_id, '_elementor_conditions', [ 'include/general' ] );
					add_post_meta( $post_id, '_elementor_popup_display_settings', [ 'triggers' => [ 'page_load' => 'yes' ], 'timing' => [] ] );
				}


				//set to draft by default
				$args = array(
					'post_status' => 'draft',
					'ID' => $post_id,
				);
				wp_update_post($args);
				update_option('cmplz_elementor_autogenerated', $post_id );

				//create the manage consent button
				//create backup. Elementor deletes the file
				copy( cmplz_path . 'integrations/plugins/elementor-pro/manage-consent-template.json', cmplz_path . 'integrations/plugins/elementor-pro/manage-consent-template-bkp.json' );
				require_once WP_PLUGIN_DIR . '/elementor/includes/template-library/sources/local.php';
				$local = new \Elementor\TemplateLibrary\Source_Local();
				$import = $local->import_template( 'manage-consent-template.json', cmplz_path . 'integrations/plugins/elementor-pro/manage-consent-template.json' );

				//restore backup
				copy( cmplz_path . 'integrations/plugins/elementor-pro/manage-consent-template-bkp.json', cmplz_path . 'integrations/plugins/elementor-pro/manage-consent-template.json' );
				unlink( cmplz_path . 'integrations/plugins/elementor-pro/manage-consent-template-bkp.json');
				if ( is_array( $import ) && isset( $import[0] ) && isset( $import[0]['template_id'] ) ) {
					$post_id = $import[0]['template_id'];
					add_post_meta( $post_id, '_elementor_conditions', [ 'include/general' ] );
					add_post_meta( $post_id, '_elementor_popup_display_settings', [ 'triggers' => [ 'page_load' => 'yes' ], 'timing' => [] ] );
				}

				//set to draft by default
				$args = array(
					'post_status' => 'draft',
					'ID' => $post_id,
				);
				wp_update_post($args);
			}
		}

		if ( $this->create_legal_hub ) {
			$post_id = get_option( 'cmplz_elementor_hub_autogenerated' );
			$post = get_post($post_id);
			if ( ! $post || $post->post_status === 'trash') {
				if ( file_exists( cmplz_path . 'integrations/plugins/elementor-pro/legal-hub-template.json' ) ) {
					//create backup. Elementor deletes the file
					copy( cmplz_path . 'integrations/plugins/elementor-pro/legal-hub-template.json', cmplz_path . 'integrations/plugins/elementor-pro/legal-hub-template-bkp.json' );
					require_once WP_PLUGIN_DIR . '/elementor/includes/template-library/sources/local.php';
					$local = new \Elementor\TemplateLibrary\Source_Local();
					$import = $local->import_template( 'legal-hub-template.json', cmplz_path . 'integrations/plugins/elementor-pro/legal-hub-template.json' );

					//restore backup
					copy( cmplz_path . 'integrations/plugins/elementor-pro/legal-hub-template-bkp.json', cmplz_path . 'integrations/plugins/elementor-pro/legal-hub-template.json' );
					unlink( cmplz_path . 'integrations/plugins/elementor-pro/legal-hub-template-bkp.json');
					if ( is_array( $import ) && isset( $import[0] ) && isset( $import[0]['template_id'] ) ) {
						$post_id = $import[0]['template_id'];
					}
				}

				//set to draft by default
				$args = array(
					'post_status' => 'draft',
					'ID' => $post_id,
				);
				wp_update_post($args);
				update_option('cmplz_elementor_hub_autogenerated', $post_id );
			}
		}
	}

}

new CMPLZ_Elementor_Pro();
