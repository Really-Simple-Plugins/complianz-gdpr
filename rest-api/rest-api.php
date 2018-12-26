<?php defined('ABSPATH') or die("you do not have acces to this page!");
/**
 *
 * API for Gutenberg blocks
 * @return array documents (id, title, content)
 *
 */

add_action('rest_api_init', 'cmplz_documents_rest_route');
function cmplz_documents_rest_route()
{
    register_rest_route('complianz/v1/', 'documents/', array(
        'methods' => 'GET',
        'callback' => 'cmplz_documents_api',
    ));
}

function cmplz_documents_api(WP_REST_Request $request)
{
    $documents = COMPLIANZ()->document->get_required_pages();
    $output = array();
    if (is_array($documents)) {
        foreach ($documents as $type => $document) {
            $html = COMPLIANZ()->document->get_document_html($type);
            $output[] =
                array(
                    'id' => $type,
                    'title' => $document['title'],
                    'content' => $html,
                );
        }
    }
    return $output;
}