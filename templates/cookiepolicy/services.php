<details class="cmplz-dropdown cmplz-service-desc cmplz-dropdown-cookiepolicy ">
	<summary class="cmplz-service-header"><div>
		<h3>{service}</h3>
		<p>{allPurposes}</p>
		<label for="cmplz_service_{service_slug}" class="cmplz_consent_per_service_label"><span class="screen-reader-text">Consent to service {service_slug}</span></label>
		<input type="checkbox" id="cmplz_service_{service_slug}" class="cmplz-accept-service {serviceCheckboxClass}" data-service="{service_slug}" data-category="{topCategory}"></div>
	</summary>
	<div class="cmplz-service-description">
		<h4><?php _ex("Usage", 'cookie policy', 'complianz-gdpr') ?></h4>
		<p>{purposeDescription}</p>
	</div>
	<div class="cmplz-sharing-data">
		<h4><?php _ex("Sharing data", 'Legal document cookie policy', 'complianz-gdpr') ?></h4>
		<p>{sharing}</p>
	</div>
	{cookies}
</details>
