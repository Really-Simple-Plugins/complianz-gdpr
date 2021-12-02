<?php
cmplz_notice( __( "The script center should be used to add and block third-party scripts and iFrames before consent is given, or when consent is revoked. For example Hotjar and embedded video’s.",
'complianz-gdpr' ) ) .
cmplz_read_more('https://complianz.io/script-center/');

if ( cmplz_get_value( 'disable_cookie_block' ) == 1 ) {
	cmplz_settings_overlay( __( 'Safe Mode enabled. To manage integrations, disable Safe Mode in the general settings.', 'complianz-gdpr' ) );
}
COMPLIANZ::$field->get_fields( 'custom-scripts' );
