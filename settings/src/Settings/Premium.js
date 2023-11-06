import { __ } from '@wordpress/i18n';

/**
 * Render a premium tag
 */
const Premium = ({premium, id}) => {


	if ( cmplz_settings.is_premium || !premium ) {
		 return null
	}

	let url = premium.url ? premium.url : 'https://complianz.io/pricing';
	url+='?ref='+id;
	return (
			<div className="cmplz-premium">
				<a target="_blank" rel="noopener noreferrer" href={url}>{__("Upgrade", "complianz-gdpr")}</a>
			</div>

	);

}

export default Premium
