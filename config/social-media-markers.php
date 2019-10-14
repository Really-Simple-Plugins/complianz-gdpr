<?php
//used to check if social media is used on site

$this->thirdparty_services = array(
    'google-fonts' => 'Google Fonts',
    'google-recaptcha' => 'Google reCAPTCHA',
    "googlemaps" => 'Google Maps',
    "vimeo" => 'Vimeo',
    "youtube" => 'YouTube',
    "videopress" => 'VideoPress',
    "dailymotion" => 'Dailymotion',
    "soundcloud" => 'SoundCloud',
    "paypal" => 'PayPal',
    "spotify" => 'Spotify',
    "hotjar" => 'Hotjar',
    "disqus" => 'Disqus',
    "addthis" => 'AddThis',
    "sharethis" => 'ShareThis',
    "livechat" => 'LiveChat',
);

$this->thirdparty_socialmedia = array(
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'linkedin' => 'LinkedIn',
    'whatsapp' => 'WhatsApp',
    'instagram' => 'Instagram',
    'disqus' => 'Disqus',
);

$this->stats = array(
    'google-analytics' => 'Google Analytics',
    'google-tag-manager' => 'Tag Manager',
    'matomo' => 'Matomo',
);

/*
 * This is also used for the callback function to tell the user he/she uses social media
 * Based on this the cookie warning is enabled.
 *
 * */

$this->social_media_markers = array(
    "linkedin" => array("platform.linkedin.com", 'addthis_widget.js'),
    "twitter" => array('super-socializer','sumoSiteId','addthis_widget.js', "platform.twitter.com", 'twitter-widgets.js'),
    "facebook" => array('super-socializer','sumoSiteId','addthis_widget.js', "fb-root", "<!-- Facebook Pixel Code -->", 'connect.facebook.net', 'www.facebook.com/plugins','pixel-caffeine'),
    "pinterest" => array('super-socializer','assets.pinterest.com'),
    "disqus" => array('disqus.com'),
    "instagram" => array('instawidget.net/js/instawidget.js', 'cdninstagram.com', 'instagram.com'),
);

/*
 * Scripts with this string in the content get listed in the third party list.
 * Also used in cmplz_placeholder()
 * */

$this->thirdparty_service_markers = array(
    "googlemaps" => array('new google.maps.', 'google.com/maps', 'maps.google.com', 'wp-google-maps'),
    "soundcloud" => array('w.soundcloud.com/player'),
    "vimeo" => array('player.vimeo.com'),
    "google-recaptcha" => array('google.com/recaptcha'),
    "youtube" => array('youtube.com'),
    "videopress" => array('videopress.com/embed', 'videopress.com/videopress-iframe.js'),
    "dailymotion" => array('dailymotion.com/embed/video/'),
    "hotjar" => array('static.hotjar.com'),
    "spotify" => array('open.spotify.com/embed'),
    "google-fonts" => array('fonts.googleapis.com'),
    "paypal" => array('www.paypal.com/tagmanager/pptm.js', 'www.paypalobjects.com/api/checkout.js'),
    "disqus" => array('disqus.com'),
    "addthis" => array('addthis.com'),
    "sharethis" => array('sharethis.com'),
    "livechat" => array('cdn.livechatinc.com/tracking.js'),
);

$this->stats_markers = array(
    'google-analytics' => array('google-analytics.com/ga.js', 'www.google-analytics.com/analytics.js'),
    'google-tag-manager' => array('googletagmanager.com/gtag/js', 'gtm.js'),
    'matomo' => array('piwik.js'),
);


/**
 * Some scripts need to be loaded in specific order
 * key: script or part of script to wait for
 * value: script or part of script that should wait
 * */

/**
    example:


    add_filter('cmplz_dependencies', 'my_dependency');
    function my_dependency($deps){
        $deps['wait-for-this-script'] = 'script-that-should-wait';
        return $deps;
    }

 */
$this->dependencies = array();

/**
 * placeholders for not iframes
 * */

$this->placeholder_markers = array();

/**
 * Scripts with this string in the source or in the content of the script tags get blocked.
 *
 * */

$this->script_tags = array();

/**
 * Style strings (google fonts have been removed in favor of plugin recommendation)
 * */

$this->style_tags = array();

/**
 * Scripts in this list are loaded with post scribe.js
 *
 * */

$this->async_list = array();

$this->iframe_tags = array();

/**
 * images with a URl in this list will get blocked
 * */

$this->image_tags = array();

