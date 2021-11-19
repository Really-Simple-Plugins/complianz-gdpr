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
class CMPLZ_View_Save_Preferences_Button extends Widget_Button {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		wp_register_style( 'cmplz-view-save-preferences-widget-style', cmplz_url . 'integrations/plugins/elementor-pro/assets/css/view-save-preferences.min.css', [], cmplz_version);
		wp_register_script( 'cmplz-view-save-preferences-widget-script', cmplz_url . 'integrations/plugins/elementor-pro/assets/js/view-save-preferences.min.js', [ 'elementor-frontend' ], cmplz_version, true );
	}

	public function get_style_depends() {
		return [ 'cmplz-view-save-preferences-widget-style' ];
	}

	public function get_script_depends() {
		return [ 'cmplz-view-save-preferences-widget-script' ];
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
        return 'cmplz-view-save-preferences-button';
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
        return esc_html__( 'View/Save Preferences Button', 'complianz-gdpr' );
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
        return 'eicon-button';
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


	/**
	 * Controls for View button content
	 */
    private function register_controls_view_button_content() {

		$this->start_controls_section(
			'section_view_button',
			[
				'label' => esc_html__( 'View Button', 'elementor' ),
			]
		);

		$this->add_control(
			'view_text',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'View Preferences', 'complianz-gdpr' ),
				'placeholder' => esc_html__( 'View Preferences', 'complianz-gdpr' ),
			]
		);

		$this->add_responsive_control(
			'view_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'justify',
			]
		);

		$this->add_control(
			'view_size',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'view_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'view_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'elementor' ),
					'right' => esc_html__( 'After', 'elementor' ),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'view_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'view_view',
			[
				'label' => esc_html__( 'View', 'complianz-gdpr' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'view_button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
				'description' => sprintf(
				/* translators: %1$s Code open tag, %2$s: Code close tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'elementor' ),
					'<code>',
					'</code>'
				),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

	}


	/**
	 * Controls for Save button content
	 */
	private function register_controls_save_button_content() {

		$this->start_controls_section(
			'section_save_button',
			[
				'label' => esc_html__( 'Save Button', 'elementor' ),
			]
		);

		$this->add_control(
			'save_text',
			[
				'label' => esc_html__( 'Save Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Save Preferences', 'complianz-gdpr' ),
				'placeholder' => esc_html__( 'Save Preferences', 'complianz-gdpr' ),
			]
		);

		$this->add_responsive_control(
			'save_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'justify',
			]
		);

		$this->add_control(
			'save_size',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'save_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'save_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'elementor' ),
					'right' => esc_html__( 'After', 'elementor' ),
				],
				'condition' => [
					'save_selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'save_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-save-preferences .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button.cmplz-save-preferences .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'save_view',
			[
				'label' => esc_html__( 'View', 'elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'save_button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
				'description' => sprintf(
				/* translators: %1$s Code open tag, %2$s: Code close tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'elementor' ),
					'<code>',
					'</code>'
				),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

	}


	/**
	 * Controls for View button style
	 */
	private function register_controls_view_button_style() {

		$this->start_controls_section(
			'section_view_style',
			[
				'label' => esc_html__( 'View Button', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'view_typography',
				'fields_options' => [
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 15
						]
					],
				],
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-view-preferences',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'view_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-view-preferences',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_view_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'view_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'view_background',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-view-preferences',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#f9f9f9',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_view_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'view_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences:hover, {{WRAPPER}} .elementor-button.cmplz-view-preferences:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences:hover svg, {{WRAPPER}} .elementor-button.cmplz-view-preferences:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'view_button_background_hover',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-view-preferences:hover, {{WRAPPER}} .elementor-button.cmplz-view-preferences:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'view_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences:hover, {{WRAPPER}} .elementor-button.cmplz-view-preferences:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'view_border',
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-view-preferences',
				'separator' => 'before',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => false,
						],
					],
					'color' => [
						'default' => '#f2f2f2',
					],
				],
			]
		);

		$this->add_control(
			'view_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'view_button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-view-preferences',
			]
		);

		$this->add_responsive_control(
			'view_text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-view-preferences' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}


	/**
	 * Controls for Save button style
	 */
	private function register_controls_save_button_style() {

		$this->start_controls_section(
			'section_save_style',
			[
				'label' => esc_html__( 'Save Button', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'save_typography',
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-save-preferences',
				'fields_options' => [
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 15
						]
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'save_text_shadow',
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-save-preferences',
			]
		);

		$this->start_controls_tabs( 'save_tabs_button_style' );

		$this->start_controls_tab(
			'save_tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'save_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'save_background',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-save-preferences',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#f9f9f9',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'save_tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'save_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-save-preferences:hover, {{WRAPPER}} .elementor-button.cmplz-save-preferences:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button.cmplz-save-preferences:hover svg, {{WRAPPER}} .elementor-button.cmplz-save-preferences:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'save_button_background_hover',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-save-preferences:hover, {{WRAPPER}} .elementor-button.cmplz-save-preferences:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'save_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-save-preferences:hover, {{WRAPPER}} .elementor-button.cmplz-save-preferences:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'save_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'save_border',
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-save-preferences',
				'separator' => 'before',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => false,
						],
					],
					'color' => [
						'default' => '#f2f2f2',
					],
				],
			]
		);

		$this->add_control(
			'save_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-save-preferences' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'save_button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button.cmplz-save-preferences',
			]
		);

		$this->add_responsive_control(
			'save_text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button.cmplz-save-preferences' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

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

		$this->register_controls_view_button_content();
		$this->register_controls_save_button_content();
		$this->register_controls_view_button_style();
		$this->register_controls_save_button_style();

	}


    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'view_wrapper', 'class', 'elementor-button-wrapper' );
        $this->add_render_attribute( 'view_wrapper', 'class', 'cmplz-region' );
        $this->add_render_attribute( 'view_button', 'class', 'elementor-button' );
        $this->add_render_attribute( 'view_button', 'class', 'cmplz-view-preferences' );
        $this->add_render_attribute( 'view_wrapper', 'class', 'elementor-align-'.$settings['view_align'] );

        if ( ! empty( $settings['view_button_css_id'] ) ) {
            $this->add_render_attribute( 'view_button', 'id', $settings['view_button_css_id'] );
        }

        if ( ! empty( $settings['view_size'] ) ) {
            $this->add_render_attribute( 'view_button', 'class', 'elementor-size-' . $settings['view_size'] );
		}

		if ( $settings['view_hover_animation'] ) {
			$this->add_render_attribute( 'view_button', 'class', 'elementor-animation-' . $settings['view_hover_animation'] );
		}

		$this->add_render_attribute( 'save_wrapper', 'class', 'elementor-button-wrapper' );
		$this->add_render_attribute( 'save_wrapper', 'class', 'cmplz-region' );
		$this->add_render_attribute( 'save_button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'save_button', 'class', 'cmplz-save-preferences' );
		$this->add_render_attribute( 'save_button', 'href', '#elementor-action%3Aaction%3Dpopup%3Aclose%26settings%3DeyJkb19ub3Rfc2hvd19hZ2FpbiI6InllcyJ9' );
		$this->add_render_attribute( 'save_wrapper', 'class', 'elementor-align-'.$settings['save_align'] );

		if ( ! empty( $settings['save_button_css_id'] ) ) {
			$this->add_render_attribute( 'save_button', 'id', $settings['save_button_css_id'] );
		}

		if ( ! empty( $settings['save_size'] ) ) {
			$this->add_render_attribute( 'save_button', 'class', 'elementor-size-' . $settings['save_size'] );
		}

		if ( $settings['save_hover_animation'] ) {
			$this->add_render_attribute( 'save_button', 'class', 'elementor-animation-' . $settings['save_hover_animation'] );
		}

		?>
        <div <?php $this->print_render_attribute_string( 'view_wrapper' ); ?>>
            <a <?php $this->print_render_attribute_string( 'view_button' ); ?>>
                <?php $this->cmplz_render_text('view_'); ?>
            </a>
		</div>
		<div <?php $this->print_render_attribute_string( 'save_wrapper' ); ?>>
			<a <?php $this->print_render_attribute_string( 'save_button' ); ?>>
				<?php $this->cmplz_render_text('save_'); ?>
			</a>
        </div>
        <?php
    }

    /**
     * Render button widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template() {
        ?>
		<#
		view.addRenderAttribute( 'view_text', 'class', 'elementor-button-text' );
		view.addInlineEditingAttributes( 'view_text', 'none' );
		var view_iconHTML = elementor.helpers.renderIcon( view, settings.view_selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
		view_migrated = elementor.helpers.isIconMigrated( settings, 'view_selected_icon' );

		view.addRenderAttribute( 'save_text', 'class', 'elementor-button-text' );
		view.addInlineEditingAttributes( 'save_text', 'none' );
		var save_iconHTML = elementor.helpers.renderIcon( view, settings.save_selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
		save_migrated = elementor.helpers.isIconMigrated( settings, 'save_selected_icon' );
		#>

        <div class="elementor-button-wrapper elementor-align-{{ settings.view_align }}">

			<a id="{{ settings.view_button_css_id }}" class="elementor-button elementor-size-{{ settings.view_size }} elementor-animation-{{ settings.view_hover_animation }} cmplz-view-preferences" href="" role="button">
				<span class="elementor-button-content-wrapper">
					<# if ( settings.view_icon || settings.view_selected_icon ) { #>
					<span class="elementor-button-icon elementor-align-icon-{{ settings.view_icon_align }}">
						<# if ( ( view_migrated || ! settings.view_icon ) && view_iconHTML.rendered ) { #>
							{{{ view_iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.view_icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'view_text' ) }}}>{{{ settings.view_text }}}</span>
                </span>
			</a>

		</div>

		<div class="elementor-button-wrapper elementor-align-{{ settings.save_align }}">

			<a id="{{ settings.save_button_css_id }}" class="elementor-button elementor-size-{{ settings.save_size }} elementor-animation-{{ settings.save_hover_animation }} cmplz-save-preferences" href="" role="button">
				<span class="elementor-button-content-wrapper">
					<# if ( settings.save_icon || settings.save_selected_icon ) { #>
					<span class="elementor-button-icon elementor-align-icon-{{ settings.save_icon_align }}">
						<# if ( ( save_migrated || ! settings.save_icon ) && save_iconHTML.rendered ) { #>
							{{{ save_iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.save_icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'save_text' ) }}}>{{{ settings.save_text }}}</span>
				</span>
			</a>

        </div>
        <?php
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    public function cmplz_render_text( $prefix ) {
        $settings = $this->get_settings_for_display();

        $migrated = isset( $settings['__fa4_migrated'][$prefix.'selected_icon'] );
        $is_new = empty( $settings[$prefix.'icon'] ) && Icons_Manager::is_migration_allowed();

        if ( ! $is_new && empty( $settings[$prefix.'icon_align'] ) ) {
            // @todo: remove when deprecated
            // added as bc in 2.6
            //old default
            $settings[$prefix.'icon_align'] = $this->get_settings( $prefix.'icon_align' );
        }

        $this->add_render_attribute( [
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings[$prefix.'icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ] );

        $this->add_inline_editing_attributes( $prefix.'text', 'none' );
        ?>
        <span <?php $this->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings[$prefix.'icon'] ) || ! empty( $settings[$prefix.'selected_icon']['value'] ) ) : ?>
                <span <?php $this->print_render_attribute_string( $prefix.'icon-align' ); ?>>
				<?php if ( $is_new || $migrated ) :
                    Icons_Manager::render_icon( $settings[$prefix.'selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings[$prefix.'icon'] ); ?>" aria-hidden="true"></i>
                <?php endif; ?>
			</span>
            <?php endif; ?>
			<span <?php $this->print_render_attribute_string( $prefix.'text' ); ?>><?php $this->print_unescaped_setting( $prefix.'text' ); ?></span>
		</span>
        <?php
    }

    public function on_import( $element ) {
        return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
    }

}
