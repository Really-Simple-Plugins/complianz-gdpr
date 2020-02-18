<div class="cmplz-cookie-field cmplz-field {ignored}"
     data-cookie_id="{cookie_id}">
	<div class="{disabledClass}">
		<div><label><?php _e( 'Name', 'complianz-gdpr' ) ?></label></div>

		<input type="text" {disabled}
		       class="cmplz_name" name="cmplz_name"
		       value="{name}">
	</div>
	<div class="{disabledClass}">
		<div><label><?php _e( 'Service', 'complianz-gdpr' ) ?></label></div>

		<select class="cmplz-select2 cmplz_service" type="text" {disabled}
		        name="cmplz_service">
			{services}
		</select>
	</div>
	<div class="{disabledClass}">
		<div><label><?php _e( 'Retention', 'complianz-gdpr' ) ?></label></div>

		<input type="text" {disabled}
		       class="cmplz_retention" name="cmplz_retention"
		       value="{retention}">
	</div>
	<div class="{disabledClass}">
		<div><label><?php _e( 'Cookie function', 'complianz-gdpr' ) ?></label>
		</div>

		<input type="text" {disabled}
		       class="cmplz_cookieFunction" name="cmplz_cookieFunction"
		       value="{cookieFunction}">
	</div>
	<div class="{disabledClass}">
		<div><label><?php _e( 'Purpose', 'complianz-gdpr' ) ?></label></div>

		<select class="cmplz-select2-no-additions cmplz_purpose" type="text"
		        {disabled} name="cmplz_purpose">
			{purposes}
		</select>
	</div>

	<div class="{disabledClass}">
		<label>
			<input type="checkbox" {disabled}
			       name="cmplz_isPersonalData"
			       class="cmplz_isPersonalData"
			       {isPersonalData}">
			<?php _e( 'Stores personal data', 'complianz-gdpr' ) ?>
		</label>
	</div>

	<div class="{disabledClass}">
		<div><label><?php _e( 'Collected Personal Data',
					'complianz-gdpr' ) ?></label></div>

		<input type="text" {disabled}
		       class="cmplz_collectedPersonalData"
		       name="cmplz_collectedPersonalData"
		       value="{collectedPersonalData}">
	</div>

	<div class="{syncDisabledClass}">
		<label>
			<input {syncDisabled} type="checkbox"
			       name="cmplz_sync"
			       class="cmplz_sync"
			       {sync}">
			<?php _e( 'Sync cookie info with cookiedatabase.org',
				'complianz-gdpr' ) ?>
		</label>
	</div>
	<div>
		<label>
			<input type="checkbox"
			       name="cmplz_showOnPolicy"
			       class="cmplz_showOnPolicy"
			       {showOnPolicy}">
			<?php _e( 'Show cookie on Cookie Policy', 'complianz-gdpr' ) ?>
		</label>
	</div>
	<div>
		{link}
	</div>
	<button class="button cmplz-edit-item" type="button" data-action="save"
	        data-type="cookie" name="cmplz-save-item"><?php _e( 'Save',
			'complianz-gdpr' ) ?></button>
	<button class="button cmplz-edit-item" type="button" data-action="delete"
	        data-type="cookie" name="cmplz_remove_item"><?php _e( "Delete",
			'complianz-gdpr' ) ?></button>
	<button class="button cmplz-edit-item" type="button" data-action="restore"
	        data-type="cookie" name="cmplz_restore_item"><?php _e( "Restore",
			'complianz-gdpr' ) ?></button>
</div>
