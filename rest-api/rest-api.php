<?php defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );
/**
 *
 * API for Gutenberg blocks
 *
 * @return array documents (id, title, content)
 *
 */

add_action( 'rest_api_init', 'cmplz_documents_rest_route' );
function cmplz_documents_rest_route() {
	register_rest_route( 'complianz/v1', 'documents/', array(
		'methods'  => 'GET',
		'callback' => 'cmplz_documents_api',
		'permission_callback' => '__return_true',
	) );


}

function cmplz_documents_api( WP_REST_Request $request ) {
	$documents = COMPLIANZ::$document->get_required_pages();
	$output    = array();
	if ( is_array( $documents ) ) {
		foreach ( $documents as $region => $region_documents ) {

			foreach ( $region_documents as $type => $document ) {
				$html       = COMPLIANZ::$document->get_document_html( $type,
					$region );
				$region_ext = ( $region === 'eu' ) ? '' : '-' . $region;
				$output[]
				            = array(
					'id'      => $type . $region_ext,
					'title'   => $document['title'],
					'content' => $html,
				);
			}
		}
	}

	return $output;
}



