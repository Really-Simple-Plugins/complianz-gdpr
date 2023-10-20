import {useEffect, useState} from "@wordpress/element";

import useFields from "../Fields/FieldsData";
import usePrivacyStatementData from "./PluginsPrivacyStatementsData";
import SinglePrivacyStatement from "./SinglePrivacyStatement";
import { __ } from '@wordpress/i18n';
import Placeholder from "../../Placeholder/Placeholder";
import {memo} from "@wordpress/element";

const PluginsPrivacyStatementsControl = () => {
	const [privacyStatementGenerated, setPrivacyStatementGenerated] = useState(true);
	const { getFieldValue, fields} = useFields();
	const {privacyStatementsLoaded, fetchPrivacyStatementsData, privacyStatements} = usePrivacyStatementData();

	useEffect (  () => {
		let generated = getFieldValue('privacy-statement') ==='generated';
		setPrivacyStatementGenerated( generated );
		if ( !privacyStatementsLoaded && generated) {
			fetchPrivacyStatementsData();
		}
	},[fields])



	if (!privacyStatementsLoaded && privacyStatementGenerated) {
		return (
			<>
				<Placeholder lines="3"></Placeholder>
			</>
		)
	}
	return (
		<>
			<div >
				{
					privacyStatementGenerated && privacyStatements.length===0 &&
					<>
						{  __("No plugins with suggested statements found.", 'complianz-gdpr' )}
					</>
				}
				{
					!privacyStatementGenerated &&
					<>
						{__("You have chosen to generate your own Privacy Statement, which means the option to add custom text to it is not applicable.", 'complianz-gdpr' )}
					</>

				}
				{
					privacyStatementGenerated &&
					<div className={'cmplz-panel__list'}>
						{ Array.isArray(privacyStatements) && privacyStatements.map( (plugin, i) => <SinglePrivacyStatement key={i} plugin={plugin} icon={'plugin'}/> )}
					</div>

				}
			</div>
		</>

	);

}

export default memo(PluginsPrivacyStatementsControl)
