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

/**
 * Register our block
 */
function cmplz_editor_register_block(){

}
add_action( 'init', 'cmplz_editor_register_block' );



register_block_type('complianz/document', array(
	'render_callback' => 'cmplz_render_document_block',
));

/**
 * Handles the front end rendering of the complianz block
 *
 * @param $attributes
 * @param $content
 * @return string
 */
function cmplz_render_document_block($attributes, $content)
{
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
