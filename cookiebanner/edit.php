<?php
	/**
	 * Make sure we have at least a region, so we can show the cookie banner.
	 */
	$regions = cmplz_get_regions();
	if ( count( $regions ) == 0 ) {
		$locale = get_locale();
		if ( strpos( $locale, 'US' ) !== false ) {
			$default = 'us';
		} elseif ( strpos( $locale, 'GB' ) !== false ) {
			$default = 'uk';
		} elseif ( strpos( $locale, 'CA' ) !== false ) {
			$default = 'ca';
		} else {
			$default = 'eu';
		}
		if ( defined( 'cmplz_free' ) ) {
			cmplz_update_option( 'wizard', 'regions', $default );
		} else {
			cmplz_update_option( 'wizard', 'regions', array( $default => 1 ) );
		}
	}
	$consenttypes = apply_filters( 'cmplz_edit_banner_consenttypes',cmplz_get_used_consenttypes(true) );
	$default_consenttype = apply_filters( 'cmplz_edit_banner_default_consenttype' ,COMPLIANZ::$company->get_default_consenttype() );
	$controls = '<select class="cmplz_save_localstorage" name="cmplz_consenttype"><option value="-1">'.__("Choose an option",'complianz-gdpr').'</option>';
	foreach ($consenttypes as $consenttype_id => $consenttype){
		$selected = $consenttype_id == $default_consenttype ? 'selected' : '';
		$controls .= '<option '.$selected.' value="'.$consenttype_id.'">'.$consenttype.'</option>';
	}
	$controls .= '</select>';



// Grid
$grid_items = array(
    'general' => array(
	    'page' => 'CMPLZ_COOKIEBANNER',
        'name' => 'general',
        'header' => __('General', 'complianz-gdpr'),
        'class' => 'medium',
        'index' => '11',
    ),
    'appearance' => array(
        'page' => 'CMPLZ_COOKIEBANNER',
        'name' => 'appearance',
        'header' => __('Appearance', 'complianz-gdpr'),
        'class' => 'medium',
        'index' => '12',
    ),
    'customization' => array(
	    'page' => 'CMPLZ_COOKIEBANNER',
	    'name' => 'customization',
	    'header' => __('Customization', 'complianz-gdpr'),
	    'class' => 'big',
	    'index' => '13',
    ),
    'settings' => array(
        'page' => 'CMPLZ_COOKIEBANNER',
        'name' => 'settings',
        'header' => __('Banner settings', 'complianz-gdpr'),
        'class' => 'big',
        'index' => '14',
        'controls' => $controls,
    ),
    'banner-categories' => array(
        'page' => 'CMPLZ_COOKIEBANNER',
        'name' => 'banner-categories',
        'header' => __('Categories', 'complianz-gdpr'),
        'class' => 'big',
        'index' => '15',
    ),
    'custom_css' => array(
	    'page' => 'CMPLZ_COOKIEBANNER',
        'name' => 'custom_css',
        'header' => __('Custom CSS', 'complianz-gdpr'),
        'class' => 'regular condition-check-1',
        'index' => '16',
        'conditions' => 'data-condition-question-1="use_custom_cookie_css" data-condition-answer-1="1"',
    ),
);

$banner_id = (isset($_GET['id']) ? intval($_GET['id']) : false);
$input_elements = '<input type="hidden" name="cmplz_banner_id" value="' . $banner_id . '">';
$grid_items = apply_filters('cmplz_cookiebanner_grid_items', $grid_items);
echo $input_elements.cmplz_grid_container_settings( __( "Style your banner", 'complianz-gdpr' ), $grid_items);
