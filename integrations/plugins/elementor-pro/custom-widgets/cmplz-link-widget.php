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
class CMPLZ_Link extends Widget_Base {

	public function __construct($data = [], $args = null)
	{
		parent::__construct($data, $args);
		wp_register_style( 'cmplz-link-widget-style', cmplz_url . 'integrations/plugins/elementor-pro/assets/css/link.min.css', [], cmplz_version );
	}

	public function get_style_depends() {
		return [ 'cmplz-link-widget-style' ];
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
        return 'cmplz-link';
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
        return esc_html__( 'Hyperlink', 'complianz-gdpr' );
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
        return 'eicon-link';
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
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 3.1.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_link',
            [
                'label' => esc_html__( 'Link', 'elementor' ),
            ]
        );

        $this->add_control(
            'cmplz_link_type',
            [
                'label' => __( 'Type', 'complianz-gdpr' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'cookie-statement',
                'options' => [
                    'cookie-statement'      => __( 'Cookie Policy', 'complianz-gdpr' ),
                    'privacy-statement'     => __( 'Privacy Statement', 'complianz-gdpr' ),
                    'impressum'             => __( 'Imprint', 'complianz-gdpr' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
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
                'prefix_class' => 'elementor%s-align-',
                'default' => '',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'elementor' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'icon_align',
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
            'icon_indent',
            [
                'label' => esc_html__( 'Icon Spacing', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => esc_html__( 'View', 'elementor' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->add_control(
            'link_css_id',
            [
                'label' => esc_html__( 'Link ID', 'elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
                'description' => cmplz_sprintf(
                /* translators: %1$s Code open tag, %2$s: Code close tag. */
                    esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'elementor' ),
                    '<code>',
                    '</code>'
                ),
                'separator' => 'before',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Link', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
				'fields_options' => [
					'font_weight' => [
						'default' => '500',
					],
					'text_decoration' => [
						'default' => 'underline',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 12
						]
					],
				],
                'selector' => '{{WRAPPER}} .elementor-link .elementor-link-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-link .elementor-link-text',
            ]
        );

        $this->start_controls_tabs( 'tabs_link_style' );

        $this->start_controls_tab(
            'tab_link_normal',
            [
                'label' => esc_html__( 'Normal', 'elementor' ),
            ]
        );

        $this->add_control(
            'link_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#191e23',
                'selectors' => [
                    '{{WRAPPER}} .elementor-link' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'elementor' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .elementor-link',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_link_hover',
            [
                'label' => esc_html__( 'Hover', 'elementor' ),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-link .elementor-link-text, {{WRAPPER}} .elementor-link  .elementor-link-text:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'link_background_hover',
                'label' => esc_html__( 'Background', 'elementor' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .elementor-link:hover, {{WRAPPER}} .elementor-link:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->add_control(
            'link_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-link:hover, {{WRAPPER}} .elementor-link:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
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
                'name' => 'border',
                'selector' => '{{WRAPPER}} .elementor-link',
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
                    '{{WRAPPER}} .elementor-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'link_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-link',
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__( 'Padding', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-link  .elementor-link-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
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

        $this->add_render_attribute( 'wrapper', 'class', 'elementor-link-wrapper' );
        $this->add_render_attribute( 'wrapper', 'class', 'cmplz-links' );
        $this->add_render_attribute( 'link', 'class', 'elementor-link' );
        $this->add_render_attribute( 'link', 'class', 'cmplz-link' );

        if ( $settings['cmplz_link_type'] === 'manage-options' || $settings['cmplz_link_type'] === 'manage-third-parties' ) {
            $this->add_render_attribute( 'link', 'class', 'cmplz-' . $settings['cmplz_link_type'] );
            $this->add_render_attribute( 'link', 'class', 'cookie-statement' );
            $this->add_render_attribute( 'link', 'href', '#' );
            $this->add_render_attribute( 'link', 'data-relative_url', '#cmplz-manage-consent-container' );
        } else {
            $this->add_render_attribute( 'link', 'class', $settings['cmplz_link_type'] );
            $this->add_render_attribute( 'link', 'href', '#' );
        }

        if ( ! empty( $settings['link_css_id'] ) ) {
            $this->add_render_attribute( 'link', 'id', $settings['link_css_id'] );
        }

        if ( ! empty( $settings['size'] ) ) {
            $this->add_render_attribute( 'link', 'class', 'elementor-size-' . $settings['size'] );
        }

        if ( $settings['hover_animation'] ) {
            $this->add_render_attribute( 'link', 'class', 'elementor-animation-' . $settings['hover_animation'] );
        }
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <a <?php $this->print_render_attribute_string( 'link' ); ?>>
                <?php $this->render_text(); ?>
            </a>
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
        ?>
        <#
        view.addRenderAttribute( 'text', 'class', 'elementor-link-text' );
        view.addInlineEditingAttributes( 'text', 'none' );
        var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
        migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
        #>
        <div class="elementor-link-wrapper cmplz-links">
            <a id="{{ settings.link_css_id }}" class="elementor-link elementor-size-{{ settings.size }} elementor-animation-{{ settings.hover_animation }} cmplz-link {{ settings.cmplz_link_type }}">
				<span class="elementor-link-content-wrapper">
					<# if ( settings.icon || settings.selected_icon ) { #>
					<span class="elementor-link-icon elementor-align-icon-{{ settings.icon_align }}">
						<# if ( ( migrated || ! settings.icon ) && iconHTML.rendered ) { #>
							{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'text' ) }}}>
					<# if ( settings.cmplz_link_type == 'cookie-statement' ) { #>
						<?php _e( 'Cookie Policy', 'complianz-gdpr' ) ?>
					<# } else if ( settings.cmplz_link_type == 'privacy-statement' ) { #>
						<?php _e( 'Privacy Statement', 'complianz-gdpr' ) ?>
					<# } else { #>
						<?php _e( 'Imprint', 'complianz-gdpr' ) ?>
					<# } #>
				</span>
                </span>
            </a>
        </div>
        <?php
    }

    /**
     * Render link text.
     *
     * Render link widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text() {
        $settings = $this->get_settings_for_display();

        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

        if ( ! $is_new && empty( $settings['icon_align'] ) ) {
            // @todo: remove when deprecated
            // added as bc in 2.6
            //old default
            $settings['icon_align'] = $this->get_settings( 'icon_align' );
        }

        $this->add_render_attribute( [
            'content-wrapper' => [
                'class' => 'elementor-link-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-link-icon',
                    'elementor-align-icon-' . $settings['icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-link-text',
            ],
        ] );

        $this->add_inline_editing_attributes( 'text', 'none' );
        ?>
        <span <?php $this->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
                <span <?php $this->print_render_attribute_string( 'icon-align' ); ?>>
				<?php if ( $is_new || $migrated ) :
                    Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                <?php endif; ?>
			</span>
            <?php endif; ?>
			<span <?php $this->print_render_attribute_string( 'text' ); ?>>
				<?php
				switch ( $settings['cmplz_link_type'] ) {
					case 'cookie-statement':
						_e( 'Cookie Policy', 'complianz-gdpr' );
						break;
					case 'privacy-statement':
						_e( 'Privacy Statement', 'complianz-gdpr' );
						break;
					case 'impressum':
						_e( 'Imprint', 'complianz-gdpr' );
						break;
				}
				?>
			</span>
		</span>
        <?php
    }

    public function on_import( $element ) {
        return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
    }

}
