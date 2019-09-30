<?php
defined('ABSPATH') or die("you do not have acces to this page!");
/**
 * If disabled in the wizard, the consent checkbox is disabled, and personal data is not stored.
 */

function cmplz_wordpress_maybe_disable_wordpress_personaldata_storage(){

    if (cmplz_get_value('uses_wordpress_comments')==='yes' && cmplz_get_value('block_wordpress_comment_cookies')==='yes'){
        add_filter( 'pre_comment_user_ip', 'cmplz_wordpress_remove_commentsip');
        remove_action( 'set_comment_cookies', 'wp_set_comment_cookies', 10);
        add_filter('comment_form_default_fields', 'cmplz_wordpress_comment_form_hide_cookies_consent');
    }

}
add_action('init', 'cmplz_wordpress_maybe_disable_wordpress_personaldata_storage');


/**
 * Helper function to disable storing of comments ip
 * @param $comment_author_ip
 * @return string
 */

function cmplz_wordpress_remove_commentsip( $comment_author_ip ) {
    return '';
}

/**
 * Remove the WP consent checkbox for comment fields
 * @param $fields
 * @return mixed
 */


function cmplz_wordpress_comment_form_hide_cookies_consent( $fields ) {
    unset( $fields['cookies'] );
    return $fields;
}
