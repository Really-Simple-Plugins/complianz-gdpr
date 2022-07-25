<details class="cmplz-dropdown cmplz-service-desc cmplz-dropdown-cookiepolicy ">
	<summary class="cmplz-service-header"><div>
		<h3>{service}</h3>
		<p>{allPurposes}</p>
		<label class="{serviceCheckboxClass}">{service}
		<input type="checkbox" class="cmplz-accept-service {serviceCheckboxClass}" data-service="{service_slug}" data-category="{topCategory}"></label></div>
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
