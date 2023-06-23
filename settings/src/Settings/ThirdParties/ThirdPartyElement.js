import Panel from "./../Panel";
import useFields from "../../Settings/Fields/FieldsData";
import {memo} from "react";
import { __ } from '@wordpress/i18n';
import useMenu from "../../Menu/MenuData";

const ThirdPartyElement = (props) => {
	const {updateField, setChangedField} = useFields();
	const {selectedMainMenuItem} = useMenu();

	const onChangeHandler = (e, id) => {
		let thirdParties = [...props.field.value];
		if ( !Array.isArray(thirdParties) ) {
			thirdParties = [];
		}

		//update thirdParty with index props.index
		let currentThirdParty = {...thirdParties[props.index]};
		currentThirdParty[id] = e.target.value;
		thirdParties[props.index] = currentThirdParty;
		updateField(props.field.id, thirdParties);
		setChangedField(props.field.id, thirdParties);
	}

	const onDeleteHandler = async (e) => {
		let thirdParties = props.field.value;
		if ( !Array.isArray(thirdParties) ) {
			thirdParties = [];
		}

		//remove thirdParty by props.index
		let thirdPartiesCopy = [...thirdParties];

		if ( thirdPartiesCopy.hasOwnProperty(props.index) ) {
			thirdPartiesCopy.splice(props.index, 1);
		}
		updateField(props.field.id, thirdPartiesCopy);
		setChangedField(props.field.id, thirdPartiesCopy);

		await saveFields( selectedMainMenuItem, false, false );
	}

	const Details = (thirdParty) => {
		return (
			<>
				<div className="cmplz-details-row">
					<label>{__("Name", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'name') } type="text" placeholder={__("Name", "complianz-gdpr")} value={thirdParty.name} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Country", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'country') } type="text" placeholder={__("Country", "complianz-gdpr")}  value={thirdParty.country} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Purpose", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'purpose') } type="text" placeholder={__("Purpose", "complianz-gdpr")}  value={thirdParty.purpose} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Data", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'data') } type="text" placeholder={__("Data", "complianz-gdpr")}  value={thirdParty.data} />
				</div>
				<div className="cmplz-details-row__buttons">
					<button className="button button-default cmplz-reset-button" onClick={ ( e ) => onDeleteHandler(e) }>{__("Delete", "complianz-gdpr")}</button>
				</div>
			</>
		);
	}

	//ensure defaults
	let thirdParty = {...props.thirdParty};
	if (!thirdParty.name) thirdParty.name = '';
	if (!thirdParty.purpose) thirdParty.purpose = '';
	if (!thirdParty.country) thirdParty.country = '';
	if (!thirdParty.data) thirdParty.data = '';
	return (
		<><Panel summary={thirdParty.name} details={Details(thirdParty)}/></>
	);
}
export default memo(ThirdPartyElement);
