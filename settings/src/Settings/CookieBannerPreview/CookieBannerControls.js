import useFields from '../Fields/FieldsData';
import UseBannerData from './CookieBannerData';
import {__} from '@wordpress/i18n';
import Icon from '../../utils/Icon';

/**
 * Switch between banner variations
 */

const CookieBannerControls = () => {
	const {getFieldValue} = useFields();
	const {
		selectedBannerId,
		setBannerId,
		banners,
		consentType,
		consentTypes,
		setConsentType,
		cssLoading,
		bannerDataLoaded,
	} = UseBannerData();

	let options = [];
	for (var key in consentTypes) {
		if (consentTypes.hasOwnProperty(key)) {
			let item = {};
			item.label = consentTypes[key];
			item.value = key;
			options.push(item);
		}
	}
	const getConsentTypeClass = (option) => {
		let ctClass = cssLoading || !bannerDataLoaded ? 'loading' : 'loaded';
		ctClass += option.value === consentType ? ' active' : ' inactive';
		return ctClass;
	};

	const handleConsentTypeChange = (value) => {
		if (cssLoading) {
			return;
		}
		setConsentType(value);
	};

	const handleBannerChange = (value) => {
		if (cssLoading) {
			return;
		}
		setBannerId(value);
	};

	const ab_testing_enabled = getFieldValue('a_b_testing_buttons') == true;
	return (
		<div className="cmplz-cookiebanner-preview-controls">
			{ab_testing_enabled && banners.length > 1 &&
				<h6>{__('Switch between banners', 'complianz-gdpr')}</h6>}
			<div className="cmplz-cookiebanner-preview-controls-buttons">
				{ab_testing_enabled && banners.length > 1 && banners.map((banner, i) =>
					<button key={i} className={banner.ID === selectedBannerId
						? 'active'
						: 'inactive'} onClick={() => handleBannerChange(banner.ID)}>
						{banner.title !== '' ? banner.title :  __('Banner', 'complianz-gdpr') + ' ' + (i + 1)}
					</button>,
				)}
			</div>

			{options.length > 1 &&
				<h6>{__('Edit consent types', 'complianz-gdpr')}</h6>}
				<div className="cmplz-cookiebanner-preview-controls-buttons">
					{options.length > 1 && options.map((option, i) =>
						<button key={i} className={getConsentTypeClass(option)}
										onClick={() => handleConsentTypeChange(option.value)}>
							{option.label}
						</button>,
					)}
					{cssLoading && <Icon name="loading"/>}
				</div>
		</div>
	);
};
export default CookieBannerControls;
