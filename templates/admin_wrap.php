<?php $high_contrast = cmplz_get_value('high_contrast', false, 'settings') ? 'cmplz-high-contrast' : '';?>
<div class="cmplz wrap <?php echo $high_contrast ?>" id="complianz">
	<?php //this header is a placeholder to ensure notices do not end up in the middle of our code ?>
	<h1 class="cmplz-notice-hook-element"></h1>
	<div class="cmplz-{page}">
		<div class="cmplz-header-container">
			<div class="cmplz-header">
				<img alt="Complianz-GDPR/CCPA" src="<?php echo trailingslashit(cmplz_url)?>assets/images/cmplz-logo.svg">
				<div class="cmplz-header-right">
					<a href="https://complianz.io/docs/" class="link-black" target="_blank"><?php _e("Documentation", "complianz-gdpr")?></a>
					<?php if ( defined("cmplz_premium" ) ) {
						$text = __("Premium", "complianz-gdpr");
					} else {
						$text = __("Get Premium", "complianz-gdpr");
					} ?>
					<a href="https://complianz.io/pricing" class="button button-black" target="_blank"><?php echo $text ?></a>
				</div>
			</div>
		</div>
		<div class="cmplz-content-area">
			{content}
		</div>
	</div>
</div>
