import {
	useEffect
} from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import useFields from "../../Settings/Fields/FieldsData";
import useDocuments from "./DocumentsData";
import SelectInput from '../../Settings/Inputs/SelectInput';

const DocumentsHeader = (props) => {
	const {getFieldValue, fieldsLoaded} = useFields();
	const {getRegion, setRegion, region} = useDocuments();

	useEffect( () => {
		getRegion();
	}, [] );

	if (!fieldsLoaded) {
		return null;
	}
	let regions = getFieldValue('regions');

	if (!Array.isArray(regions)) regions = [regions];
	if (regions.length===0) regions = ['eu'];
	if (!regions) regions = [];
	//get labels from regions
	let regionsOptions = [];
	for ( const region of regions ){
		if (!cmplz_settings.regions.hasOwnProperty(region)) {
			continue;
		}
		let item = {};
		item.label = cmplz_settings.regions[region]['label_full']
		item.value = region;
		regionsOptions.push(item);
	}
	let item = {};
	item.label = __("General","complianz-gdpr");
	item.value = 'all';
	regionsOptions.push(item);

	return (
		<>
			<h3 className="cmplz-grid-title cmplz-h4">{ __( "Documents", 'complianz-gdpr' ) }</h3>
			<div className="cmplz-grid-item-controls">
				<SelectInput defaultValue={'all'} canBeEmpty={false} onChange={(value) => setRegion(value)} value={region} options={regionsOptions} />
			</div>
		</>
	);

}
export default DocumentsHeader;
