<?php defined('ABSPATH') or die("you do not have access to this page!"); ?>

<?php

$plugins = array(
	'BURST' => array(
			'constant_free' => 'burst_version',
			'constant_premium' => 'burst_version',
			'website' => 'https://burst-statistics.com/',
			'search' => 'burst+statistics+really+simple+plugins+self-hosted',
			'url' => 'https://wordpress.org/plugins/burst-statistics/?src=complianz-plugin',
			'title' => 'Burst Statistics - '. __("Self-hosted, Privacy-friendly analytics tool.", "complianz-gdpr"),
	),
	'RSSSL' => array(
			'constant_free' => 'rsssl_version',
			'constant_premium' => 'rsssl_pro_version',
			'website' => 'https://really-simple-ssl.com/premium/?src=complianz-plugin',
			'search' => 'really-simple-ssl%20HSTS%20complianz&tab=search',
			'url' => 'https://wordpress.org/plugins/really-simple-ssl/',
			'title' => 'Really Simple SSL - '. __("Easily migrate your website to SSL", "complianz-gdpr"),
	),
	'COMPLIANZTC' => array(
			'constant_free' => 'cmplz_tc_version',
			'constant_premium' => 'cmplz_tc_version',
			'url' => 'https://wordpress.org/plugins/complianz-terms-conditions/',
			'website' => 'https://complianz.io?src=complianz-plugin',
			'search' => 'complianz+terms+conditions+stand-alone',
			'title' => 'Complianz - '. __("Terms and Conditions", "complianz-gdpr"),

	),
);
?>
<div class="cmplz-other-plugins-container">
	<?php foreach ($plugins as $id => $plugin) {
		$prefix = strtolower($id);
		?>
			<div class="cmplz-other-plugins-element cmplz-<?php echo $prefix?>">
				<a href="<?php echo esc_url_raw($plugin['url'])?>" target="_blank" title="<?php echo esc_html($plugin['title'])?>">
					<div class="cmplz-bullet"></div>
					<div class="cmplz-other-plugins-content"><?php echo esc_html($plugin['title'])?></div>
				</a>
				<div class="cmplz-other-plugin-status">
					<?php echo COMPLIANZ::$admin->get_status_link($plugin)?>
				</div>
			</div>
	<?php }?>
</div>
