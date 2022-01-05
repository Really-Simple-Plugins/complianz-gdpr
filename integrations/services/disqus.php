<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_disqus_script' );
function cmplz_disqus_script( $tags ) {
	$tags[] = array(
		'name' => 'disqus',
		'category' => 'marketing',
		'urls' => array(
			'disqus.com',
		),
	);
	return $tags;
}
