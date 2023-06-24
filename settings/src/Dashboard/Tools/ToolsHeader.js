import { __ } from '@wordpress/i18n';
import useFields from "../../Settings/Fields/FieldsData";
import {
	useEffect, useState
} from '@wordpress/element';
import useStatistics from "../../Statistics/StatisticsData";
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

	return (
		<>
			<h3 className="cmplz-grid-title cmplz-h4">
				{ consentStatisticsEnabled && __( "Statistics", 'complianz-gdpr' ) }
				{ !consentStatisticsEnabled && __( "Tools", 'complianz-gdpr' ) }
			</h3>
			<div className="cmplz-grid-item-controls">
				{consentStatisticsEnabled && consentTypes && consentTypes.length>1 && <select onChange={(e) => setConsentType(e.target.value)} value={consentType}>
					{consentTypes.map((type, i) =>
						<option key={i} value={type.id} >{type.label}</option>)}
				</select>}
			</div>
		</>

	);

}
export default ToolsHeader;
