<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );

// cookies used
// mailchimp_user_email
// mailchimp_landing_site
// mailchimp_campaign_id
// mailchimp_user_previous_email
// the cookie name will be whatever we're trying to set. return true if allowed, false if not allowed.


function cmplz_custom_cookie_callback_function($mailchimp_landing_site) {
    return false;
}

add_filter( 'mailchimp_allowed_to_use_cookie', 'cmplz_custom_cookie_callback_function', 10, 1 );
