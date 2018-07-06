<?php
//used to check if social media is used on site
$this->social_media_markers = array(
    "linkedin" => array("platform.linkedin.com", 'addthis_widget.js'),
    "googleplus" => array('addthis_widget.js', "https://apis.google.com", 'apis.google.com/js/plusone.js', 'apis.google.com/js/platform.js'),
    "twitter" => array('addthis_widget.js', "https://platform.twitter.com"),
    "facebook" => array('addthis_widget.js', "fb-root", "<!-- Facebook Pixel Code -->", 'connect.facebook.net', 'www.facebook.com/plugins'),
    "pinterest" => array('assets.pinterest.com'),
    "youtube" => array('www.youtube.com/iframe_api'),
    "googlemaps" => array('new google.maps.'),
    "disqus" => array('disqus.com'),
    "vimeo" => array('player.vimeo.com'),
);

//used to block scripts on front-end
$this->script_tags = array(
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
    '_getTracker',
    'disqus.com',
    'addthis.com',
    'sharethis.com',
    'adsbygoogle',
    'googlesyndication',
    'cdn.livechatinc.com/tracking.js',
);

$this->iframe_tags = array(
    'youtube.com',
    'platform.twitter.com',
	'facebook.com/plugins',
    'apis.google.com',
    'www.google.com/maps/embed',
    'player.vimeo.com',
    'disqus.com',
);