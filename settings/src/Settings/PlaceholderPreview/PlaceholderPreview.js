import {memo, useEffect, useState} from "react";
import useFields from "../Fields/FieldsData";
import './PlaceholderPreview.scss'

const PlaceholderPreview = (props) => {
	const { fields, getFieldValue } = useFields();
	const [ placeholderStyle, setPlaceholderStyle ] = useState(getFieldValue('placeholder_style'));

	useEffect (() => {
		let style = getFieldValue('placeholder_style');
		if (style === '') style = 'minimal';
		setPlaceholderStyle(style);
	},[getFieldValue('placeholder_style')]);

	const url = cmplz_settings.plugin_url + 'assets/images/placeholders/default-' + placeholderStyle + '.jpg';
	return (
		<div className="cmplz-placeholder-preview">
			<img src={url} />
		</div>
	)
}
export default memo(PlaceholderPreview)
