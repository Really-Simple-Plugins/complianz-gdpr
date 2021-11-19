<?php
cmplz_notice( __( "The script center should be used to add and block third-party scripts and iFrames before consent is given, or when consent is revoked. For example Hotjar and embedded video’s.",
'complianz-gdpr' ) ) .
cmplz_read_more('https://complianz.io/script-center/');
?>
<?php
COMPLIANZ::$field->get_fields( 'custom-scripts' );
