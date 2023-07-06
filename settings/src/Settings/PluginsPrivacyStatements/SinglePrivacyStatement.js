import Panel from "../Panel";
import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";
import {memo} from "react";
import useFields from "../Fields/FieldsData";
/**
 * Render a help notice in the sidebar
 */
const SinglePrivacyStatement = (props) => {
	const {updateField, setChangedField, getFieldValue} = useFields();

	const Details = () =>{
		return (
			<>
				<div className="cmplz-details-row" dangerouslySetInnerHTML={{__html:props.plugin.policy_text}}>

				</div>
			</>
		);
	}

	const addPolicyHandler = (text, e) => {
		e.preventDefault();
		let newText = getFieldValue('custom_privacy_policy_text');
		newText += text;
		updateField('custom_privacy_policy_text', newText);
		setChangedField('custom_privacy_policy_text', newText);
	}

	const Icons = () => {
		return (
			<>
				<button className={'cmplz-button-icon'} onClick = { (e) => addPolicyHandler(props.plugin.policy_text, e) }>
					<Icon tooltip={__( "Add to annex of Privacy Statement", "complianz-gdpr" )} name = 'plus'  />
				</button>
				{ props.plugin.consent_api !== 'na' &&
					<>
						{ !props.plugin.consent_api && <Icon tooltip={__( "Does not conform with the Consent API", "complianz-gdpr" )} name = 'circle' color="red"  />}
						{ props.plugin.consent_api && <Icon tooltip={__( "Conforms to the Consent API", "complianz-gdpr" )} name = 'circle' color="green"  /> }
					</>

				}
			</>
		)
	}

	return (
		<>
			<Panel summary={props.plugin.plugin_name} icon={props.icon} icons={Icons()} details={Details()} />
		</>
	);

}

export default memo(SinglePrivacyStatement)
