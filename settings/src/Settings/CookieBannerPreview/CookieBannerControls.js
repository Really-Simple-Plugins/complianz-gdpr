import useFields from "../Fields/FieldsData";
import UseBannerData from "./CookieBannerData";
import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";

/**
 * Switch between banner variations
 */
const CookieBannerControls = () => {
	const {getFieldValue} = useFields();
	const { selectedBannerId, setBannerId, banners, consentType, consentTypes, setConsentType, cssLoading, bannerDataLoaded } = UseBannerData();

	let options = [];
	for (var key in consentTypes) {
		if (consentTypes.hasOwnProperty(key)) {
			let item = {};
			item.label = consentTypes[key];
			item.value = key;
			options.push(item);
		}
	}

	const handleConsentTypeChange = (value) => {
		if (cssLoading) {
			return;
		}
		setConsentType(value);
	}

	const handleBannerChange = (value) => {
		if (cssLoading) {
			return;
		}
		setBannerId(value);
	}

	const getConsentTypeClass = (option) => {
		let ctClass = cssLoading || !bannerDataLoaded ? 'loading' : 'loaded';
		ctClass += option.value === consentType ? ' active' : ' inactive';
		return ctClass;
	}

	let ab_testing_enabled = getFieldValue('a_b_testing_buttons') == true;
	return (
		<div className="cmplz-cookiebanner-preview-controls">
			<ul>
			{ options.length>1 && options.map((option, i) =>
				<li key={i} className={ getConsentTypeClass(option) } onClick={() => handleConsentTypeChange(option.value)}>
					{option.value === consentType && <Icon name='circle-check' color='green' /> }
					{option.value !== consentType && <Icon name='circle-times' color='grey' /> }
					{option.value === consentType ? __( "Editing %s Banner", "complianz-gdpr").replace('%s', option.label): __( "Edit %s Banner", "complianz-gdpr").replace('%s', option.label)}
				</li>)}
			</ul>
			<ul>
			{ ab_testing_enabled && banners.map((banner, i) =>
				<li key={i} className={ banner.ID === selectedBannerId ? 'active' : 'inactive' } onClick={() => handleBannerChange(banner.ID)}>
					{banner.ID === selectedBannerId && <Icon name='circle-check' color='green' /> }
					{banner.ID !== selectedBannerId && <Icon name='circle-times' color='grey' /> }
					{banner.ID === selectedBannerId ? __( "Editing Banner %s", "complianz-gdpr").replace('%s',banner.title): __( "Switch to Banner %s", "complianz-gdpr").replace('%s',banner.title)}
				</li>
				)
			}
			</ul>
		</div>
	);
}

export default CookieBannerControls
