<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @since 1.0.0
 */

function cmplz_editor_assets() {
	$asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
	wp_enqueue_script(
		'cmplz-block',
		plugins_url( 'gutenberg/build/index.js', dirname( __FILE__ ) ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	wp_localize_script(
		'cmplz-block',
		'complianz',
		array(
			'site_url' => get_rest_url(),
			'cmplz_preview' => cmplz_url.  'assets/images/gutenberg-preview.png',
			'user_can_unfiltered_html' => current_user_can('unfiltered_html'),
		)
	);

	wp_set_script_translations( 'cmplz-block', 'complianz-gdpr' , cmplz_path . '/languages');
	$load_css = cmplz_get_value('use_document_css');
	if ($load_css) {
		wp_enqueue_style(
			'cmplz-block', // Handle.
			cmplz_url . "assets/css/document.min.css",
			array( 'wp-edit-blocks' ), cmplz_version
		);
	} else {
		wp_enqueue_style(
			'cmplz-block', // Handle.
			cmplz_url . "assets/css/document-grid.min.css",
			array( 'wp-edit-blocks' ), cmplz_version
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'cmplz_editor_assets' );

register_block_type('complianz/document', array(
	'render_callback' => 'cmplz_render_document_block',
));
register_block_type('complianz/consent-area', array(
	'render_callback' => 'cmplz_render_consent_area_block',
));

/**
 * Handles the front end rendering of the complianz consent area block
 *
 * @param array $attributes
 * @param string $content
 * @return string
 */

function cmplz_render_consent_area_block($attributes, $content)
{
	$category = isset($attributes['category']) ? cmplz_sanitize_category( $attributes['category'] ) : 'marketing';
	$service = isset($attributes['service']) ? COMPLIANZ::$cookie_blocker->sanitize_service_name( $attributes['service'] ) : 'general';
	$post_id = (int)  $attributes['postId'];
	$block_id = sanitize_title($attributes['blockId']);
	$placholder_content = $attributes['placeholderContent'];
	ob_start();
	?><div class="cmplz-consent-area cmplz-placeholder" data-post_id="<?php echo esc_attr($post_id)?>" data-block_id="<?php echo esc_attr($block_id)?>" data-category="<?php echo esc_attr($category); ?>" data-service="<?php echo esc_attr($service); ?>">
		<?php echo wp_kses_post($placholder_content) ?>
	</div><?php
	return  ob_get_clean();
}
/**
 * Handles the front end rendering of the complianz block
 *
 * @param $attributes
 * @param $content
 * @return string
 */
function cmplz_render_document_block($attributes, $content): string {
	$html = '';
	if (isset($attributes['selectedDocument'])) {
		if (isset($attributes['documentSyncStatus']) && $attributes['documentSyncStatus']==='unlink' && isset($attributes['customDocument'])){
			$html = $attributes['customDocument'];
		} else {
			$type = $attributes['selectedDocument'];
			$region = cmplz_get_region_from_legacy_type($type);
			if ($region){
				$type = str_replace('-'.$region, '', $type);
			}
			$html = COMPLIANZ::$document->get_document_html($type, $region);
		}
	}

	return $html;
}




