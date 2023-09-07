import { __ } from '@wordpress/i18n';
import useFields from "../../Settings/Fields/FieldsData";
import {
	useEffect, useState
} from '@wordpress/element';
import useStatistics from "../../Statistics/StatisticsData";
import SelectInput from '../../Settings/Inputs/SelectInput';
const ToolsHeader = () => {
	const {consentType, setConsentType, consentTypes, fetchStatisticsData, loaded} = useStatistics();
	const {fields, getFieldValue} = useFields();
	const [consentStatisticsEnabled, setConsentStatisticsEnabled] = useState(false);
	useEffect (() => {
		let consentStats = getFieldValue('a_b_testing')==1;
		setConsentStatisticsEnabled(consentStats);
	},[getFieldValue('a_b_testing')])

	useEffect (() => {
		if (consentStatisticsEnabled && !loaded) {
			fetchStatisticsData();
		}
	},[consentStatisticsEnabled])
	let consentTypesOptions = [];

	// change the key 'id' to the key 'value' for all consent types in consentTypes array
	if (consentTypes) {
		consentTypesOptions = consentTypes.map((consentType) => {
			return {
				value: consentType.id,
				label: consentType.label,
			}
		})
	}

	return (
		<>
			<h3 className="cmplz-grid-title cmplz-h4">
				{ consentStatisticsEnabled && __( "Statistics", 'complianz-gdpr' ) }
				{ !consentStatisticsEnabled && __( "Tools", 'complianz-gdpr' ) }
			</h3>
			<div className="cmplz-grid-item-controls">
				{consentStatisticsEnabled && consentTypesOptions && consentTypesOptions.length>1 &&
					<SelectInput canBeEmpty={false} value ={consentType} onChange={(value) => setConsentType(value)} options={consentTypesOptions} />}
			</div>
		</>

	);

}
export default ToolsHeader;
