import {memo, useEffect, useState} from "@wordpress/element";;
import useFields from "../Fields/FieldsData";
import './PlaceholderPreview.scss'
import {__} from "@wordpress/i18n";

const PlaceholderPreview = () => {
	const { getFieldValue } = useFields();
	const [ placeholderStyle, setPlaceholderStyle ] = useState(getFieldValue('placeholder_style'));

	useEffect (() => {
		let style = getFieldValue('placeholder_style');
		if (style === '') style = 'minimal';
		setPlaceholderStyle(style);
	},[getFieldValue('placeholder_style')]);

	let safeModeEnabled = getFieldValue('safe_mode')==1;

	const url = cmplz_settings.plugin_url + 'assets/images/placeholders/default-' + placeholderStyle + '.jpg';
	return (
		<>
			<div className="cmplz-placeholder-preview">
				<img alt="placeholder preview" src={url} />
			</div>
			{safeModeEnabled && <div className="cmplz-comment">
				{__("Safe Mode enabled. To manage integrations, disable Safe Mode under Tools - Support.","complianz-gdpr")}
			</div>}
		</>
	)
}
export default memo(PlaceholderPreview)
