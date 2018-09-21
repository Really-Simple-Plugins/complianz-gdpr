<?php
//used to check if social media is used on site
/*
 * This is also used for the callback function to tell the user he/she uses social media
 * Based on this the cookie warning is enabled.
 *
 * */

$this->social_media_markers = array(
    "linkedin" => array("platform.linkedin.com", 'addthis_widget.js'),
    "googleplus" => array('addthis_widget.js', "https://apis.google.com", 'apis.google.com/js/plusone.js', 'apis.google.com/js/platform.js'),
    "twitter" => array('sumoSiteId','addthis_widget.js', "https://platform.twitter.com"),
    "facebook" => array('sumoSiteId','addthis_widget.js', "fb-root", "<!-- Facebook Pixel Code -->", 'connect.facebook.net', 'www.facebook.com/plugins'),
    "pinterest" => array('assets.pinterest.com'),
    "disqus" => array('disqus.com'),
);

$this->thirdparty_service_markers = array(
    "googlemaps" => array('new google.maps.'),
    "vimeo" => array('player.vimeo.com'),
    "google-fonts" => array('fonts.googleapis.com'),
    "google-recaptcha" => array('google.com/recaptcha'),
    "youtube" => array('www.youtube.com/iframe_api'),
    "hotjar" => array('static.hotjar.com'),
);

//used to block scripts on front-end
$this->script_tags = array(
    'google.com/recaptcha',
    'fonts.googleapis.com',
    'platform.twitter.com/widgets.js',
    'apis.google.com/js/plusone.js',
    'apis.google.com/js/platform.js',
    'connect.facebook.net',
    'platform.linkedin.com',
    'assets.pinterest.com',
    'www.youtube.com/iframe_api',
    'www.google-analytics.com/analytics.js',
    'google-analytics.com/ga.js',
    'new google.maps.',
    'static.hotjar.com',
    'dataset.sumoSiteId',
    '_getTracker',
    'disqus.com',
    'addthis.com',
    'sharethis.com',
    'adsbygoogle',
    'googlesyndication',
    'cdn.livechatinc.com/tracking.js',
    'googleads.g.doubleclick.net',
);

$this->iframe_tags = array(
    'youtube.com',
    'platform.twitter.com',
	'facebook.com/plugins',
    'apis.google.com',
    'www.google.com/maps/embed',
    'player.vimeo.com',
    'disqus.com',
    'platform.twitter.com/widgets.js',
);