<div class="cmplz-document-header">
	<h3 class="h4"><?php _e( "Other tools", "complianz-gdpr" ) ?></h3>
</div>

<div class="cmplz-tools-row">
	<div>
		<span><?php _e( "Priority Support", 'complianz-gdpr' ); ?></span>
	</div>
	<div>
		<a target="_blank" href="https://complianz.io/support/#priority" class="cmplz-premium">
			<?php _e( 'Read more', 'complianz-gdpr' ) ?>
		</a>
	</div>
</div>
<div class="cmplz-tools-row">
	<div>
		<?php _e( "Create Processing Agreements", 'complianz-gdpr' ); ?>
	</div>
	<div>
		<a target="_blank" href="https://complianz.io/definition/what-is-a-processing-agreement/" class="cmplz-premium">
			<?php _e( 'Read more', 'complianz-gdpr' ) ?>
		</a>
	</div>
</div>
<div class="cmplz-tools-row">
	<div>
		<?php _e( "Create a Data Leak Inventory", 'complianz-gdpr' ); ?>
	</div>
	<div>
		<a target="_blank" href="https://complianz.io/definition/what-is-a-data-breach/" class="cmplz-premium">
			<?php _e( 'Read more', 'complianz-gdpr' ) ?>
		</a>
	</div>
</div>
<div class="cmplz-tools-row">
	<div>
		<?php _e( "Records of consent", 'complianz-gdpr' ); ?>
	</div>
	<div>
		<?php
		if ( cmplz_get_value('records_of_consent') === 'yes' ) {
			$text = __( 'View', 'complianz-gdpr' );
			$link = add_query_arg(array('page' => 'cmplz-proof-of-consent'), admin_url('admin.php') );
		} else {
			$text = __( 'Read more', 'complianz-gdpr' );
			$link = "https://complianz.io/records-of-consent/";
		} ?>
		<a target="_blank" href="<?php echo $link ?>" class="cmplz-premium">
			<?php echo $text ?>
		</a>
	</div>
</div>
<div class="cmplz-tools-row">
	<div>
		<?php _e( "Consent Statistics & A/B Testing", 'complianz-gdpr' ); ?>
	</div>
	<div>
		<a target="_blank" href="https://complianz.io/a-quick-introduction-to-a-b-testing/" class="cmplz-premium">
			<?php _e( 'Read more', 'complianz-gdpr' ) ?>
		</a>
	</div>
</div>
