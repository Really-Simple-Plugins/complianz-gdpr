<?php
	$wizard_completed = COMPLIANZ::$wizard->wizard_completed_once();
	$cookieblocker_status = $wizard_completed && cmplz_can_run_cookie_blocker(true) ? 'success' : 'warning';
	$placeholder_status = $wizard_completed && cmplz_get_value( 'dont_use_placeholders' ) != 1 ? 'success' : 'warning';
	$cookiebanner_status = cmplz_cookiebanner_should_load(true) ? 'success' : 'warning';
	$cookieblocker_icon = 	$cookieblocker_status === 'success' ?
							cmplz_icon('circle-check', $cookieblocker_status) :
							cmplz_icon('error', $cookieblocker_status, __('Cookie blocker is disabled', 'complianz-gdpr'));

	$placeholder_icon = 	$placeholder_status === 'success' ?
							cmplz_icon('circle-check', $placeholder_status) :
							cmplz_icon('circle-times', $placeholder_status, __('Placeholder insertion is disabled', 'complianz-gdpr'));

	$cookiebanner_icon = 	$cookiebanner_status === 'success' ?
							cmplz_icon('circle-check', $cookiebanner_status) :
							cmplz_icon('error', $cookiebanner_status, __('Cookie banner is disabled', 'complianz-gdpr'));

?>
<a class="button button-primary" href="<?php echo add_query_arg( array('page' => 'cmplz-wizard' ), admin_url('admin.php'))?>" ><?php _e("Continue Wizard", "complianz-gdpr") ?></a>
<div class="cmplz-legend cmplz-flex-push-right"><?php echo $cookieblocker_icon ?><span><?php _e("Cookie blocker", "complianz-gdpr")?></span></div>
<div class="cmplz-legend"><?php echo $placeholder_icon ?><span><?php _e("Placeholders", "complianz-gdpr")?></span></div>
<div class="cmplz-legend"><?php echo $cookiebanner_icon ?><span><?php _e("Cookie banner", "complianz-gdpr")?></span></div>
