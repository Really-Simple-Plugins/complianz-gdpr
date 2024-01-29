<?php
defined( 'ABSPATH' ) or die();

add_filter( 'cmplz_fields', 'cmplz_security_fields', 100 );
function cmplz_security_fields($fields){
	return array_merge($fields, [
		[
			'id'       => 'install-really-simple-ssl',
			'type'     => 'install-plugin',
			'plugin_data' => [
				'title' => "Really Simple SSL",
				'summary' => __("Lightweight plugin. Heavyweight security features.", 'complianz-gdpr'),
				'slug' => 'really-simple-ssl',
				'description' => __("Leverage your SSL certificate to the fullest, with health checks, security headers, hardening, vulnerability detection and more.", 'complianz-gdpr'),
				'image' => "really-simple-ssl.png"
			],
			'help' => [
				'label' => 'default',
				'title' => "Really Simple SSL & Security",
				'text'  => __( "5+ million websites are secured with Really Simple SSL & Security", 'complianz-gdpr'),
				'url'   => 'https://really-simple-ssl.com/pro',
			],
			'menu_id'  => 'security',
			'group_id' => 'security-install',
			'label'    => '',
		],
		[
			'id'       => 'security-rsssl',
			'type'     => 'security_measures',
			'menu_id'  => 'security',
			'group_id' => 'security-privacy',
			'label'    => '',
		],
	]);


}
