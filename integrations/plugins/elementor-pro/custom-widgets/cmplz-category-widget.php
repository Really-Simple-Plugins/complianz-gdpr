<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor button widget.
 *
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class CMPLZ_Category extends Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        wp_register_style( 'cmplz-categories-widget-style', cmplz_url . 'integrations/plugins/elementor-pro/assets/css/categories.min.css', [], cmplz_version);
    }

    public function get_style_depends() {
        return [ 'cmplz-categories-widget-style' ];
    }



    /**
     * Get widget name.
     *
     * Retrieve button widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'cmplz-category';
    }

    /**
     * Get widget title.
     *
     * Retrieve button widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Cookie Categories', 'complianz-gdpr' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve button widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-checkbox';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'cmplz-category' ];
    }

	public function get_customize_texts_template( $texts ) {
		ob_start();

		?>
		<div class="elementor-nerd-box">
			<img class="elementor-nerd-box-icon" src="<?php echo esc_url( cmplz_url . 'assets/images/cmplz-logo.svg' ); ?>" />
			<div class="elementor-nerd-box-title"><?php Utils::print_unescaped_internal_string( $texts['title'] ); ?></div>
			<div class="elementor-nerd-box-message"><?php Utils::print_unescaped_internal_string( $texts['message'] ); ?></div>
			<a class="elementor-nerd-box-link elementor-button elementor-button-default" href="<?php echo esc_url( $texts['link'] ); ?>" target="_blank">
				<?php echo Utils::print_unescaped_internal_string( $texts['link_text'] ); ?>
			</a>
		</div>
		<?php

		return ob_get_clean();
	}

    /**
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_style_categories',
            [
                'label' => esc_html__( 'Table', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'custom_css_pro',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => $this->get_customize_texts_template( [
					'title' => esc_html__( 'Customize texts', 'elementor' ),
					'message' => __( 'You can change the texts displayed on the categories in Complianz - Cookie banner.', 'complianz-gdpr' ),
					'link' => add_query_arg(['page' => 'cmplz-cookiebanner', 'id' => cmplz_get_default_banner_id()], admin_url().'admin.php'),
					'link_text' => __( 'Edit categories', 'complianz-gdpr' ),
				] ),
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .cmplz-category',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cmplz-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
				'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .cmplz-category',
				'separator' => 'before',
            ]
        );

		$this->add_responsive_control(
			'margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cmplz-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '10',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => false,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'Categories', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_header',
			[
				'label' => esc_html__( 'Background', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cmplz-category-header' => 'background-color: {{VALUE}};',
				],
				'default' => '#F5F5F5',
			]
		);

		$this->add_control(
			'text_color_header',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cmplz-category-header' => 'color: {{VALUE}};',
				],
				'default' => '#333',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'selector' => '{{WRAPPER}} .cmplz-category-header h2',
				'fields_options' => [
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 14
						]
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'header_text_shadow',
				'selector' => '{{WRAPPER}} .cmplz-category-header',
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cmplz-category-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '10',
					'right' => '10',
					'bottom' => '10',
					'left' => '10',
					'unit' => 'px',
					'isLinked' => true,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_body',
			[
				'label' => esc_html__( 'Descriptions', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_body',
			[
				'label' => esc_html__( 'Background', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cmplz-body' => 'background-color: {{VALUE}};',
				],
				'default' => '#fcfcfc',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'text_color_body',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cmplz-body' => 'color: {{VALUE}};',
				],
				'default' => '#333',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'body_typography',
				'selector' => '{{WRAPPER}} .cmplz-body',
				'fields_options' => [
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 12
						]
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'body_text_shadow',
				'selector' => '{{WRAPPER}} .cmplz-body',
			]
		);

        $this->add_responsive_control(
            'body_padding',
            [
                'label' => esc_html__( 'Padding', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .cmplz-body.cmplz-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'default' => [
					'top' => '10',
					'right' => '10',
					'bottom' => '10',
					'left' => '10',
					'unit' => 'px',
					'isLinked' => true,
				],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_checkbox',
            [
                'label' => esc_html__( 'Toggles', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cmplz_checkbox_style',
            [
                'label' => __( 'Checkbox Style', 'complianz-gdpr' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'slider',
                'options' => [
                    'slider'  => __( 'Slider', 'complianz-gdpr' ),
                    'checkbox' => __( 'Checkbox', 'complianz-gdpr' ),
                ],
            ]
        );

		$this->add_control(
			'slider_color_active',
			[
				'label' => esc_html__( 'Active', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} input:checked + .cmplz-banner-slider' => 'background-color: {{VALUE}};',
				],
				'default' => '#1e73be',
				'condition' => array(
					'cmplz_checkbox_style' => 'slider',
				),
			]
		);

		$this->add_control(
			'slider_color_inactive',
			[
				'label' => esc_html__( 'Inactive', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cmplz-banner-slider' => 'background-color: {{VALUE}};',
				],
				'default' => '#f56e28',
				'condition' => array(
					'cmplz_checkbox_style' => 'slider',
				),
			]
		);

		$this->add_control(
			'slider_color_bullet',
			[
				'label' => esc_html__( 'Bullet', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cmplz-banner-slider:before' => 'background-color: {{VALUE}};',
				],
				'default' => '#ffffff',
				'condition' => array(
					'cmplz_checkbox_style' => 'slider',
				),
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_style_always_active',
			[
				'label' => esc_html__( 'Always Active', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'always_active_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#008000',
				'selectors' => [
					'{{WRAPPER}} .cmplz-always-active' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'always_active_typography',
				'selector' => '{{WRAPPER}} .cmplz-always-active',
				'fields_options' => [
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 12
						]
					],
				],
			]
		);

		$this->end_controls_section();

    }


    private function get_category_fields() {
		global $wpdb;
		$cookiebanner_id = cmplz_get_default_banner_id();
		$fields = implode(', ', [
			'category_functional',
			'functional_text',
			'category_prefs',
			'preferences_text',
			'category_stats',
			'statistics_text',
			'statistics_text_anonymous',
			'category_all',
			'marketing_text',
		]);
		$sql = "select {$fields} from {$wpdb->prefix}cmplz_cookiebanners where ID = {$cookiebanner_id}";
		$results = $wpdb->get_results($sql);
		$fields = [];
		if ( !empty($results) && isset($results[0]) ) {
			foreach ($results[0] as $field_name => $field ) {
				if ( $field_name === "category_functional" ) {
					$fields[$field_name] = $field;
				} else {
					$fields[$field_name] = unserialize($field);
				}
			}
		}

		if ( !cmplz_uses_preferences_cookies() ) {
			$fields['category_prefs']['show'] = false;
		}
		if ( !cmplz_uses_statistic_cookies() ) {
			$fields['category_stats']['show'] = false;
		}
		if ( !cmplz_uses_marketing_cookies() ) {
			$fields['category_all']['show'] = false;
		}

		return $fields;
	}


    /**
     * Render link widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */

    protected function render() {
        $settings = $this->get_settings_for_display();
		$fields = $this->get_category_fields();
		$prefs = cmplz_uses_preferences_cookies();
		$marketing = cmplz_uses_marketing_cookies();

//		$consent_type = apply_filters( 'cmplz_user_consenttype', COMPLIANZ::$company->get_default_consenttype() );
//		$path = trailingslashit( cmplz_path ).'cookiebanner/templates/';
//		$banner_html = cmplz_get_template( "cookiebanner.php", array( 'consent_type' => $consent_type ), $path);
//
//		if ( preg_match( '/<!-- categories start -->(.*?)<!-- categories end -->/s', $banner_html,  $matches ) ) {
//			$html      = $matches[0];
//			$banner_id = apply_filters( 'cmplz_user_banner_id', cmplz_get_default_banner_id() );
//			$banner = new CMPLZ_COOKIEBANNER(  $banner_id );
//			$cookie_settings = $banner->get_html_settings();
//
//			foreach($cookie_settings as $fieldname => $value ) {
//				if ( isset($value['text']) ) $value = $value['text'];
//				if ( is_array($value) ) continue;
//				$html = str_replace( '{'.$fieldname.'}', $value, $html );
//			}
//		}
        ?>
        <div class="cmplz-categories cmplz-region">
            <details class="cmplz-category cmplz-functional">
                <summary>
                    <div class="cmplz-category-header">
                        <h2><?php echo $fields['category_functional'] ?></h2>
                        <input
                                id="cmplz-functional"
                                data-category="cmplz_functional"
                                class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-functional"
                                checked
                                type="hidden"
                                value="1"/>
                        <div class='cmplz-always-active'><?php _e("Always active","complianz-gdpr") ?></div>
						<?php if ( $fields['functional_text']['show'] ) { ?>
                        	<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
                    </div>
                </summary>
				<?php if ( $fields['functional_text']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-functional"><?php echo $fields['functional_text']['text'] ?></span>
					</div>
				<?php } ?>
            </details>

			<?php if ( $prefs ) { ?>
            <details class="cmplz-category cmplz-preferences <?php if ( !$fields['category_prefs']['show'] ) echo "cmplz-hidden" ?>">
                <summary>
                    <div class="cmplz-category-header">
                        <h2><?php echo $fields['category_prefs']['text'] ?></h2>
                        <div class="cmplz-active">
                            <label for="cmplz-preferences">
                                <div class="cmplz-banner-checkbox">
                                    <input
                                            id="cmplz-preferences"
                                            data-category="cmplz_preferences"
                                            class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-preferences"
                                            size="40"
											<?php if ( $settings['cmplz_checkbox_style'] == 'slider' ) { ?>
												style="display: none !important"
											<?php } ?>
                                            type="checkbox"
                                            value="1"/>
                                    <span class="cmplz-banner-slider cmplz-round"></span>
                                </div>
                            </label>
                        </div>
						<?php if ( $fields['preferences_text']['show'] ) { ?>
							<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
                    </div>
                </summary>
				<?php if ( $fields['preferences_text']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-preferences"><?php echo $fields['preferences_text']['text'] ?></span>
					</div>
				<?php } ?>
            </details>
			<?php } ?>

            <details class="cmplz-category cmplz-statistics <?php if ( !$fields['category_stats']['show'] ) echo "cmplz-hidden" ?>">
                <summary>
                    <div class="cmplz-category-header">
                        <h2><?php echo $fields['category_stats']['text'] ?></h2>
                        <div class="cmplz-active">
                            <label for="cmplz-statistics">
                                <div class="cmplz-banner-checkbox">
                                    <input
                                            id="cmplz-statistics"
                                            data-category="cmplz_statistics"
                                            class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-statistics"
                                            size="40"
											<?php if ( $settings['cmplz_checkbox_style'] == 'slider' ) { ?>
												style="display: none !important"
											<?php } ?>
                                            type="checkbox"
                                            value="1"/>
                                    <span class="cmplz-banner-slider cmplz-round"></span>
                                </div>
                            </label>
                        </div>
						<?php if ( $fields['statistics_text']['show'] || $fields['statistics_text_anonymous']['show'] ) { ?>
							<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
                    </div>
                </summary>
				<?php if ( $fields['statistics_text']['show'] || $fields['statistics_text_anonymous']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-statistics"><?php echo $fields['statistics_text']['text'] ?></span>
						<span class="cmplz-description-statistics-anonymous cmplz-hidden"><?php echo $fields['statistics_text_anonymous']['text'] ?></span>
					</div>
				<?php } ?>
            </details>

			<?php if ($marketing) {?>
            <details class="cmplz-category cmplz-marketing <?php if ( !$fields['category_all']['show'] ) echo "cmplz-hidden" ?>">
                <summary>
                    <div class="cmplz-category-header">
                        <h2><?php echo $fields['category_all']['text'] ?></h2>
                        <div class="cmplz-active">
                            <label for="cmplz-marketing">
                                <div class="cmplz-banner-checkbox">
                                    <input
                                            id="cmplz-marketing"
                                            data-category="cmplz_marketing"
                                            class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-marketing"
                                            size="40"
											<?php if ( $settings['cmplz_checkbox_style'] == 'slider' ) { ?>
												style="display: none !important"
											<?php } ?>
                                            type="checkbox"
                                            value="1"/>
                                    <span class="cmplz-banner-slider cmplz-round"></span>
                                </div>
                            </label>
                        </div>
						<?php if ( $fields['marketing_text']['show'] ) { ?>
							<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
                    </div>
                </summary>
				<?php if ( $fields['marketing_text']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-marketing"><?php echo $fields['marketing_text']['text'] ?></span>
					</div>
				<?php } ?>
            </details>
			<?php } ?>
        </div>
        <?php
    }

    /**
     * Render link widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template() {
    	$fields = $this->get_category_fields();
		$prefs = cmplz_uses_preferences_cookies();
		$marketing = cmplz_uses_marketing_cookies();
        ?>
		<div class="cmplz-categories">
			<details class="cmplz-category cmplz-functional">
				<summary>
					<div class="cmplz-category-header">
						<h2><?php echo $fields['category_functional'] ?></h2>
						<input
							id="cmplz-functional"
							data-category="cmplz_functional"
							class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-functional"
							checked
							type="hidden"
							value="1"/>
						<div class='cmplz-always-active'><?php _e("Always active","complianz-gdpr") ?></div>
						<?php if ( $fields['functional_text']['show'] ) { ?>
							<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
					</div>
				</summary>
				<?php if ( $fields['functional_text']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-functional"><?php echo $fields['functional_text']['text'] ?></span>
					</div>
				<?php } ?>
			</details>
		<?php if ($prefs) {?>

			<details class="cmplz-category cmplz-preferences <?php if ( !$fields['category_prefs']['show'] ) echo "cmplz-hidden" ?>">
				<summary>
					<div class="cmplz-category-header">
						<h2><?php echo $fields['category_prefs']['text'] ?></h2>
						<div class="cmplz-active">
							<label for="cmplz-preferences">
								<div class="cmplz-banner-checkbox">
									<input
										id="cmplz-preferences"
										data-category="cmplz_preferences"
										class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-preferences"
										size="40"
										<# if ( settings.cmplz_checkbox_style == 'slider' ) { #>
										style="display: none !important"
										<# } #>
										type="checkbox"
										value="1"/>
									<span class="cmplz-banner-slider cmplz-round"></span>
								</div>
							</label>
						</div>
						<?php if ( $fields['preferences_text']['show'] ) { ?>
							<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
					</div>
				</summary>
				<?php if ( $fields['preferences_text']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-preferences"><?php echo $fields['preferences_text']['text'] ?></span>
					</div>
				<?php } ?>
			</details>
			<?php }?>

			<details class="cmplz-category cmplz-statistics <?php if ( !$fields['category_stats']['show'] ) echo "cmplz-hidden" ?>">
				<summary>
					<div class="cmplz-category-header">
						<h2><?php echo $fields['category_stats']['text'] ?></h2>
						<div class="cmplz-active">
							<label for="cmplz-statistics">
								<div class="cmplz-banner-checkbox">
									<input
										id="cmplz-statistics"
										data-category="cmplz_statistics"
										class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-statistics"
										size="40"
										<# if ( settings.cmplz_checkbox_style == 'slider' ) { #>
										style="display: none !important"
										<# } #>
										type="checkbox"
										value="1"/>
									<span class="cmplz-banner-slider cmplz-round"></span>
								</div>
							</label>
						</div>
						<?php if ( $fields['statistics_text']['show'] || $fields['statistics_text_anonymous']['show'] ) { ?>
							<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
					</div>
				</summary>
				<?php if ( $fields['statistics_text']['show'] || $fields['statistics_text_anonymous']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-statistics"><?php echo $fields['statistics_text']['text'] ?></span>
						<span class="cmplz-description-statistics-anonymous cmplz-hidden"><?php echo $fields['statistics_text_anonymous']['text'] ?></span>
					</div>
				<?php } ?>
			</details>
			<?php if ($marketing) {?>

			<details class="cmplz-category cmplz-marketing <?php if ( !$fields['category_all']['show'] ) echo "cmplz-hidden" ?>">
				<summary>
					<div class="cmplz-category-header">
						<h2><?php echo $fields['category_all']['text'] ?></h2>
						<div class="cmplz-active">
							<label for="cmplz-marketing">
								<div class="cmplz-banner-checkbox">
									<input
										id="cmplz-marketing"
										data-category="cmplz_marketing"
										class="cmplz-consent-checkbox cmplz-slider-checkbox cmplz-marketing"
										size="40"
										<# if ( settings.cmplz_checkbox_style == 'slider' ) { #>
										style="display: none !important"
										<# } #>
										type="checkbox"
										value="1"/>
									<span class="cmplz-banner-slider cmplz-round"></span>
								</div>
							</label>
						</div>
						<?php if ( $fields['marketing_text']['show'] ) { ?>
							<div class="cmplz-icon cmplz-open"></div>
						<?php } ?>
					</div>
				</summary>
				<?php if ( $fields['marketing_text']['show'] ) { ?>
					<div class="cmplz-body cmplz-description">
						<span class="cmplz-description-marketing"><?php echo $fields['marketing_text']['text'] ?></span>
					</div>
				<?php } ?>
			</details>
		<?php }?>

		</div>
		<?php
    }

}
