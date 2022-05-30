<div class="cmplz-wizard-menu">
	<div class="cmplz-wizard-title"><h2 class="h4">{title}</h2></div>
	<div class="cmplz-wizard-menu-menus">
		{content}
	</div>
</div>
<?php $hide = isset( $_POST['cmplz-save']) ? 'cmplz-settings-saved--fade-in': ''; ?>
<div class="cmplz-settings-saved <?php echo esc_attr($hide)?>">
	<div class="cmplz-settings-saved__text_and_icon">
		<?php echo cmplz_icon('check', 'success'); ?>
		<span><?php _e('Changes saved successfully', 'complianz-gdpr') ?> </span>
	</div>
</div>
