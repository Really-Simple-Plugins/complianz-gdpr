<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

add_filter( 'cmplz_known_script_tags', 'cmplz_twitch_script' );
function cmplz_twitch_script( $tags ) {
    $tags[] = array(
        'name' => 'twitch',
        'placeholder' => 'twitch',
        'category' => 'marketing',
        'urls' => array(
            'player.twitch.tv',
            'twitch.tv',
            'new Twitch.Embed',
        ),

        'enable_placeholder' => '1',
        'placeholder_class' => 'twitch-embed',
        'enable_dependency' => '1',
        'dependency' => [
  			//'wait-for-this-script' => 'script-that-should-wait'
  			'twitch.tv' => 'new Twitch.Embed',
  		],
    );
    return $tags;
}

/**
 * This empty function ensures Complianz recognizes that this integration has a placeholder
 * @return void
 *
 */
function cmplz_twitch_placeholder() {}
