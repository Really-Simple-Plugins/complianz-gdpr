import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";
import useFields from "../../Settings/Fields/FieldsData";
import useProgress from "./ProgressData";
import {useEffect, useState} from "@wordpress/element";

const ProgressFooter = () => {
	const {fields, getFieldValue} = useFields();
	const {showCookieBanner, fetchProgressData, progressLoaded} = useProgress();
	const [cookieBlockerColor, setCookieBlockerColor] = useState(false);
	const [placeholderColor, setPlaceholderColor] = useState(false);
	const [cookieBannerColor, setCookieBannerColor] = useState(false);

	useEffect( () => {
		if ( !progressLoaded ) {
			fetchProgressData();
		}
	}, [] );

	useEffect( () => {
		let color = getFieldValue('enable_cookie_blocker') === 'yes' ? 'green' : 'grey';
		setCookieBlockerColor(color);
		color = getFieldValue('dont_use_placeholders') == 1 ? 'grey' : 'green';
		setPlaceholderColor(color);
		color =  showCookieBanner ? 'green' : 'grey';
		setCookieBannerColor(color);
	}, [fields, showCookieBanner] );

	return (
		<>
			<a href="#wizard" className="button button-primary">{__("Continue Wizard", "complianz-gdpr")}</a>
			<div className="cmplz-legend cmplz-flex-push-right">
				<Icon name={'circle-check'} color={cookieBlockerColor} size={14} />
				<span>{__("Cookie Blocker","complianz-gdpr")}</span>
			</div>
			<div className="cmplz-legend">
				<Icon name={'circle-check'} color={placeholderColor} size={14} />
				<span>{__("Placeholders","complianz-gdpr")}</span>
			</div>
			<div className="cmplz-legend">
				<Icon name={'circle-check'} color={cookieBannerColor} size={14} />
				<span>{__("Consent Banner","complianz-gdpr")}</span>
			</div>
		</>

	);
    }

export default ProgressFooter;
