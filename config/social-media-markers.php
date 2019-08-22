<?php
//used to check if social media is used on site
/*
 * This is also used for the callback function to tell the user he/she uses social media
 * Based on this the cookie warning is enabled.
 *
 * */

$this->social_media_markers = array(
    "linkedin" => array("platform.linkedin.com", 'addthis_widget.js'),
    "twitter" => array('super-socializer','sumoSiteId','addthis_widget.js', "platform.twitter.com", 'twitter-widgets.js'),
    "facebook" => array('super-socializer','sumoSiteId','addthis_widget.js', "fb-root", "<!-- Facebook Pixel Code -->", 'connect.facebook.net', 'www.facebook.com/plugins'),
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
    "youtube" => array('www.youtube.com/iframe_api'),
    "videopress" => array('videopress.com/embed', 'videopress.com/videopress-iframe.js'),
    "dailymotion" => array('dailymotion.com/embed/video/'),
    "hotjar" => array('static.hotjar.com'),
    "spotify" => array('open.spotify.com/embed'),
    "google-fonts" => array('fonts.googleapis.com'),
    "paypal" => array('www.paypal.com/tagmanager/pptm.js', 'www.paypalobjects.com/api/checkout.js'),
);

/*
 * placeholders, not iframes
 * */

$this->placeholder_markers = array(
    'facebook' => "fb-page",
);

/*
 * Scripts with this string in the source or in the content of the script tags get blocked.
 *
 * */

$this->script_tags = array(
    'super-socializer', //super socializer
    'theChampFBKey',   //super socializer
    'google.com/recaptcha',
    'grecaptcha',
    'recaptcha.js',
    'platform.twitter.com',
    'twitter-widgets.js',
    'apis.google.com/js/platform.js',
    'connect.facebook.net',
    'assets.pinterest.com',
    'www.youtube.com/iframe_api',
    'www.google-analytics.com/analytics.js',
    'google-analytics.com/ga.js',
    'new google.maps.',
    //'maps.googleapis.com',
    'gmw.map.min.js',
    'new GMW_Map',
    'wpgmaps.js',
    'gmw.js',
    //'wp-google-maps.min.js',
//    'wpgmza_rectangle_data_array',
    'static.hotjar.com',
    'dataset.sumoSiteId',
    '_getTracker',
    'disqus.com',
    'addthis.com',
    'sharethis.com',
    'google_ad_client', //google adsense
    'pagead/js/adsbygoogle.js', //google adsense
    'doubleclick.net', //google adsense
    'googlesyndication.com', //google adsense
    'cdn.livechatinc.com/tracking.js',
    'googleads.g.doubleclick.net',
    'advads_tracking_ads',
    'advanced_ads',
    'googletagmanager.com/gtag/js',
    'instawidget.net/js/instawidget.js',
    'videopress.com/videopress-iframe.js',
    'plugins/instagram-feed/js/sb-instagram.min.js',
    'www.instagram.com/embed.js',
    'www.paypal.com/tagmanager/pptm.js',
    'www.paypalobjects.com/api/checkout.js',
);

/*
 * Style strings (google fonts have been removed in favor of plugin recommendation)
 * */

$this->style_tags = array(
);

/*
 * Scripts in this list are loaded with post scribe.js
 *
 * */

$this->async_list = array(
    'instawidget.net/js/instawidget.js',
    'platform.linkedin.com',
);

$this->iframe_tags = array(
    'open.spotify.com/embed',
    'soundcloud.com/player',
    'googleads',
    'maps.google.com',
    'doubleclick',
    'youtube.com',
    'youtube-nocookie.com',
    'youtu.be',
    'platform.twitter.com',
	'facebook.com/plugins',
    'apis.google.com',
    'google.com/maps/embed',
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
    'maps.googleapis.com/maps/api/staticmap',
);

