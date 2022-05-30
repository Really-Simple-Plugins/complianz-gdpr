<?php defined('ABSPATH') or die("you do not have access to this page!");
$items = array(
		1 => array(
				'content' => "Styling your cookie notice and legal documents",
				'link'    => 'https://complianz.io/docs/customization/',
		),
		2 => array(
				'content' => "Exclude your website in Chrome's new cookieless tracking",
				'link' => 'https://complianz.io/about-floc/',
		),
		3 => array(
				'content' => "Do I need a checkbox on my contact form?",
				'link' => 'https://complianz.io/do-i-need-a-checkbox-on-my-contact-form',
		),
		4 => array(
				'content' => "Configuring Google Analytics for your region",
				'link' => 'https://complianz.io/?s=Configure+Google+Analytics',
		),
		5 => array(
				'content' => "Translating your cookie notice and legal documents",
				'link' => 'https://complianz.io/?s=translations&lang=en',
		),
		6 => array(
				'content' => "Debugging issues with Complianz",
				'link' => 'https://complianz.io/debugging-issues/',
		),
);
$container = '<div class="cmplz-tips-tricks-element"><a href="{link}" target="_blank" title="{content}"><div class="cmplz-bullet"></div><div class="cmplz-tips-tricks-content">{content}</div></a></div>';
$output = '<div class="cmplz-tips-tricks-container">';

foreach ($items as $item) {
	$output .= str_replace(array(
			'{link}',
			'{content}',
	), array(
			$item['link'],
			$item['content'],
	), $container);
}
 $output .= '</div>';
echo $output;


