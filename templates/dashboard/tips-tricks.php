<?php defined('ABSPATH') or die("you do not have access to this page!");
$items = array(
		1 => array(
				'content' => "Styling your cookie notice and legal documents",
				'link'    => 'https://complianz.io/docs/customization/',
		),
		2 => array(
				'content' => "Why plugins are better in consent management",
				'link' => 'https://complianz.io/consent-management-wordpress-native-plugin-versus-cloud-solution/',
		),
		3 => array(
				'content' => "Configure Tag Manager with Complianz",
				'link' => 'https://complianz.io/definitive-guide-to-tag-manager-and-complianz/',
		),
		4 => array(
				'content' => "Self-hosting Google Fonts",
				'link' => 'https://complianz.io/self-hosting-google-fonts-for-wordpress/',
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


