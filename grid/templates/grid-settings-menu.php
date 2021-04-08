<div class="cmplz-wizard-menu">
	<div class="cmplz-wizard-title">
		{title}
		<span class="cmplz-save-settings"><?php _e("Save settings", "complianz-gdpr")?></span>
		<?php
		if ( isset( $_POST['cmplz-save'] ) ) {
			cmplz_notice( __( "Changes saved", 'complianz-gdpr' ), 'success', false);
		} ?>
	</div>
	<div class="cmplz-wizard-menu-menus">
		{content}
	</div>
</div>
