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
?>
<div class="wrap">

	<form id='cookie-settings' action="" method="post">
		<?php wp_nonce_field( 'complianz_save_cookiebanner', 'cmplz_nonce' ); ?>

		<?php
		if ( ! $id ) { ?>
			<input type="hidden" value="1" name="cmplz_add_new">
		<?php } ?>
		<?php //some fields for the cookies categories ?>
		<input type="hidden" name="cmplz_cookie_warning_required_stats"
		       value="<?php echo( COMPLIANZ::$cookie_admin->cookie_warning_required_stats( 'eu' ) ) ?>">

		<input type="hidden" name="cmplz_impressum_required"
		       value="<?php echo (cmplz_get_value( 'eu_consent_regions' ) === 'yes' && cmplz_get_value( 'impressum' ) !== 'none' ) ? '1' : '' ?>">
		<?php
		$active_tab    = isset( $_POST['cmplz_active_tab'] )
			? sanitize_title( $_POST['cmplz_active_tab'] ) : 'general';
		$consent_types = cmplz_get_used_consenttypes();
		$regions       = cmplz_get_regions();
		if ( isset( $_POST["cmplz_active_tab"] )
		     && $_POST["cmplz_active_tab"] !== 'general'
		) {
			$single_consenttype = sanitize_title( $_POST["cmplz_active_tab"] );
		} else {
			if ( cmplz_multiple_regions() ) {
				$single_consenttype = COMPLIANZ::$company->get_default_consenttype();
			} else {
				$single_region = $regions;
				reset( $single_region );
				$single_region = key( $single_region );
				$single_consenttype = cmplz_get_consenttype_for_region( $single_region );
			}
		} ?>
		<input type="hidden" name="cmplz_active_tab" value="<?php echo $active_tab ?>">
		<input type="hidden" name="cmplz_tcf_active" value="<?php echo cmplz_tcf_active() ?>">
		<input type="hidden" name="cmplz_banner_id" value="<?php echo isset($_GET['id']) ? intval($_GET['id']) : false ?>">
		<script>
			ccConsentType = '<?php echo $single_consenttype?>';
			<?php
			$scheme_colors = cmplz_banner_color_schemes();?>
			var color_schemes = {
				<?php
				foreach( $scheme_colors as $name => $scheme ){ ?>
				'<?=$name?>' : {
					<?php foreach ($scheme as $fieldname => $color ) {?>
					'<?=$fieldname?>' : '<?=$color?>',
					<?php } ?>
				},
				<?php } ?>
			};
		</script>

		<div class="cmplz-tab">
			<button class="cmplz-tablinks <?php if ( $active_tab === 'general' )
				echo "active" ?>" type="button"
			        data-tab="general"><?php _e( "General",
					'complianz-gdpr' ) ?></button>
			<?php
			$consent_types = apply_filters('cmplz_cookiebanner_tabs', $consent_types);
			foreach ( $consent_types as $consent_type ) {?>
				<button class="cmplz-tablinks region-link <?php if ( $active_tab
				                                                     === $consent_type
				)
					echo "active" ?>" type="button"
				        data-tab="<?php echo $consent_type ?>"><?php echo cmplz_consenttype_nicename( $consent_type ) ?></button>
			<?php } ?>
		</div>

		<!-- Tab content -->
		<div id="general"
		     class="cmplz-tabcontent <?php if ( $active_tab === 'general' )
			     echo "active" ?>">
			<div>
			<h3><?php _e( "General", 'complianz-gdpr' ) ?></h3>
				<p>
				<div id="cmplz-wizard">
					<?php
					COMPLIANZ::$field->get_fields( 'CMPLZ_COOKIEBANNER',
						'general' ); ?>
				</div>
				</p>
			</div>
		</div>

		<?php foreach ( $consent_types as $consent_type ) {?>
			<div id="<?php echo $consent_type ?>" class="cmplz-tabcontent region <?php if ( $active_tab === $consent_type ) echo "active" ?>">
				<?php
				$regions = get_regions_for_consent_type( $consent_type );
				cmplz_flag( $regions );

				?>
				<p>
					<div id="cmplz-wizard">
					<?php do_action("cmplz_cookiebanner_tab_content_$consent_type" )?>

					<?php COMPLIANZ::$field->get_fields( 'CMPLZ_COOKIEBANNER', $consent_type ); ?>
					</div>
				</p>
			</div>
		<?php } ?>

		<div class="cmplz-cookiebanner-save-button">
			<button class="button button-primary"
			        type="submit"><?php _e( 'Save',
					'complianz-gdpr' ) ?></button>
		</div>

	</form>
</div>
