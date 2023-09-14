import Panel from "./../Panel";
import useFields from "../../Settings/Fields/FieldsData";
import {memo, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import useMenu from "../../Menu/MenuData";

const ThirdPartyElement = (props) => {
	const {updateField, setChangedField} = useFields();
	const {selectedMainMenuItem} = useMenu();
	const [name, setName] = wp.element.useState(props.thirdParty.name ? props.thirdParty.name : '');
	const [purpose, setPurpose] = wp.element.useState(props.thirdParty.purpose ? props.thirdParty.purpose : '');
	const [country, setCountry] = wp.element.useState(props.thirdParty.country ? props.thirdParty.country : '');
	const [data, setData] = wp.element.useState(props.thirdParty.data ? props.thirdParty.data : '');

	const onChangeHandler = (value, id) => {
		let thirdParties = [...props.field.value];
		if ( !Array.isArray(thirdParties) ) {
			thirdParties = [];
		}

		//update thirdParty with index props.index
		let currentThirdParty = {...thirdParties[props.index]};
		currentThirdParty[id] = value;
		thirdParties[props.index] = currentThirdParty;
		updateField(props.field.id, thirdParties);
		setChangedField(props.field.id, thirdParties);
	}

	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(name, 'name');
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [name]);
	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(data, 'data');
		}, 500);
		return () => {
			clearTimeout(typingTimer);
		};
	}, [data]);

	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(country, 'country');
		}, 500);
		return () => {
			clearTimeout(typingTimer);
		};
	}, [country]);
	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(purpose, 'purpose');
		}, 500);
		return () => {
			clearTimeout(typingTimer);
		};
	}, [purpose]);


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

	const Details = () => {
		return (
			<>
				<div className="cmplz-details-row">
					<label>{__("Name", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => setName(e.target.value) } type="text" placeholder={__("Name", "complianz-gdpr")} value={name} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Country", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => setCountry(e.target.value) } type="text" placeholder={__("Country", "complianz-gdpr")}  value={country} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Purpose", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => setPurpose(e.target.value) } type="text" placeholder={__("Purpose", "complianz-gdpr")}  value={purpose} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Data", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => setData(e.target.value) } type="text" placeholder={__("Data", "complianz-gdpr")}  value={data} />
				</div>
				<div className="cmplz-details-row__buttons">
					<button className="button button-default cmplz-reset-button" onClick={ ( e ) => onDeleteHandler(e) }>{__("Delete", "complianz-gdpr")}</button>
				</div>
			</>
		);
	}

	return (
		<><Panel summary={name} details={Details()}/></>
	);
}
export default memo(ThirdPartyElement);
