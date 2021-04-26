<div class="cmplz-wizard-menu">
	<div class="cmplz-wizard-title">
		{title}
		<span class="cmplz-save-settings"><?php //echo cmplz_icon('save', 'error');?></span>
		<?php
		if ( isset( $_POST['cmplz-save'] ) ) {
			echo '<span class="cmplz-settings-saved">'.cmplz_icon('save', 'success').'</span>';
		} ?>
	</div>
	<div class="cmplz-wizard-menu-menus">
		{content}
	</div>
</div>
