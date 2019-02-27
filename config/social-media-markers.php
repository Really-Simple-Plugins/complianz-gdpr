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
    "instagram" => array('instawidget.net/js/instawidget.js', 'cdninstagram.com', 'instagram.com'),
);

/*
 * Scripts with this string in the content get blocked.
 *
 * */

$this->thirdparty_service_markers = array(
    "googlemaps" => array('new google.maps.'),
    "vimeo" => array('player.vimeo.com'),
    "google-fonts" => array('fonts.googleapis.com'),
    "google-recaptcha" => array('google.com/recaptcha'),
    "youtube" => array('www.youtube.com/iframe_api'),
    "videopress" => array('videopress.com/embed', 'videopress.com/videopress-iframe.js'),
    "dailymotion" => array('dailymotion.com/embed/video/'),
    "hotjar" => array('static.hotjar.com'),
);

/*
 * Scripts with this string in the source get blocked.
 *
 * */

$this->script_tags = array(
    'google.com/recaptcha',
    'grecaptcha',
    'fonts.googleapis.com',
    'platform.twitter.com',
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
    'cdn.livechatinc.com/tracking.js',
    'googleads.g.doubleclick.net',
    'advads_tracking_ads',
    'advanced_ads',
    'googletagmanager.com/gtag/js',
    'instawidget.net/js/instawidget.js',
    'videopress.com/videopress-iframe.js',
    'plugins/instagram-feed/js/sb-instagram.min.js',
    'www.instagram.com/embed.js',
);

/*
 * Scripts in this list are loaded with post scribe.js
 *
 * */

$this->async_list = array(
    'instawidget.net/js/instawidget.js',
);

$this->iframe_tags = array(
    'googleads',
    'doubleclick',
    'youtube.com',
    'youtube-nocookie.com',
    'youtu.be',
    'platform.twitter.com',
	'facebook.com/plugins',
    'apis.google.com',
    'www.google.com/maps/embed',
    'player.vimeo.com',
    'disqus.com',
    'platform.twitter.com/widgets.js',
    'dailymotion.com/embed/video/',
    'videopress.com/embed',
    'instagram.com',
);

/*
 * images with a URl in this list will get blocked, if the cmplz_has_async_documentwrite_scripts() function returns true.
 * */

$this->image_tags = array(
    'cdninstagram.com',
);

