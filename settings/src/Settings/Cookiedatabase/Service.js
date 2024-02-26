import Cookie from './Cookie';
import Panel from "../Panel";
import UseSyncData from "./SyncData";
import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";
import useFields from "../../Settings/Fields/FieldsData";
import CheckboxGroup from "../Inputs/CheckboxGroup";
import SelectInput from "../Inputs/SelectInput";
import {memo, useEffect, useState} from "@wordpress/element";

const ServiceDetails = (service) => {
	const {getFieldValue, showSavedSettingsNotice} = useFields();
	const [serviceName, setServiceName] = useState('');
	const [privacyStatementUrl, setPrivacyStatementUrl] = useState('');
	const {language, saving, deleteService, serviceTypeOptions, updateService, saveService} = UseSyncData();
	let useCdbApi = getFieldValue('use_cdb_api')==='yes';
	const [serviceTypesByLanguage, setServiceTypesByLanguage] = useState([]);

	useEffect (() => {
		let serviceTypesByLanguage = serviceTypeOptions && serviceTypeOptions.hasOwnProperty(language) ? serviceTypeOptions[language] : [];
		serviceTypesByLanguage = serviceTypesByLanguage.map(serviceType => {
			return {label:serviceType.label,value:serviceType.label};
		});
		setServiceTypesByLanguage(serviceTypesByLanguage);
	},[language, serviceTypeOptions]);

	const onSaveHandler = async (id) => {
		await saveService(id);
		showSavedSettingsNotice(__("Saved service", "complianz-gdpr"));
	}

	const onDeleteHandler = async (id) => {
		await deleteService(id);
	}

	const onChangeHandler = (value, id, type) => {
		updateService(id, type, value);
	}
	const onCheckboxChangeHandler = (checked, id, type) => {
		updateService(id, type, checked);
	}

	useEffect(() => {
		if ( service && service.name ) {
			setServiceName(service.name);
		}
	},[service]);

	useEffect(() => {
		if (!service) {
			return;
		}

		if ( service.name === serviceName ) {
			return;
		}
		if (serviceName.length<2) {
			return;
		}
		const typingTimerServiceName = setTimeout(() => {
			onChangeHandler(serviceName, service.ID, 'name')
		}, 500);

		return () => {
			clearTimeout(typingTimerServiceName);
		};
	}, [serviceName]);

	useEffect(() => {
		if ( service && service.privacyStatementURL ) {
			setPrivacyStatementUrl(service.privacyStatementURL);
		}
	},[service]);

	useEffect(() => {
		if ( !service ) {
			return;
		}
		if ( service.privacyStatementURL === privacyStatementUrl ) {
			return;
		}
		if (privacyStatementUrl.length===0) {
			return;
		}
		const typingTimerPrivacyStatementUrl = setTimeout(() => {
			onChangeHandler(privacyStatementUrl, service.ID, 'privacyStatementURL')
		}, 400);

		return () => {
			clearTimeout(typingTimerPrivacyStatementUrl);
		};
	}, [privacyStatementUrl]);

	if (!service) {
		return null;
	}
	//allow for both '0'/'1' and false/true.
	let sync = useCdbApi ? service.sync==1 : false;
	let disabled = sync;
	if (saving) {
		disabled = true;
	}

	let cdbLink = false;
	if ( service.slug.length>0 ) {
		cdbLink = 'https://cookiedatabase.org/service/' + service.slug;
	}
	return (
		<>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<CheckboxGroup
					id={service.ID+'thirdParty'}
					disabled={disabled}
					value={service.thirdParty}
					onChange={(value) => onCheckboxChangeHandler(value, service.ID, 'thirdParty')}
					options={{true: __('Data is shared with this service', 'complianz-gdpr')}}
				/>
			</div>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<CheckboxGroup
					id={service.ID+'sync'}
					disabled={!useCdbApi}
					value={sync}
					onChange={(value) => onCheckboxChangeHandler(value, service.ID, 'sync')}
					options={{true: __('Sync service with cookiedatabase.org', 'complianz-gdpr')}}
				/>
			</div>
			<div className="cmplz-details-row">
				<label>{__("Name", "complianz-gdpr")}</label>
				<input disabled={disabled}
					   onChange={ ( e ) =>  setServiceName(e.target.value) }
					   type="text"
					   placeholder={__("Name", "complianz-gdpr")}
					   value={serviceName} />
			</div>

			<div className="cmplz-details-row">
				<label>{__("Service Types", "complianz-gdpr")}</label>
				<SelectInput
					disabled={disabled}
					value={service.serviceType}
					options={serviceTypesByLanguage}
					onChange={(value) => onChangeHandler(value, service.ID, 'serviceType')}
				/>
			</div>
			<div className="cmplz-details-row">
				<label>{__("Privacy Statement URL", "complianz-gdpr")}</label>
				<input
					disabled={disabled}
					onChange={ ( e ) =>  setPrivacyStatementUrl(e.target.value) }
					type="text"
					value={privacyStatementUrl} />
			</div>
			{cdbLink &&
				<div className="cmplz-details-row">
					<a href={cdbLink} target="_blank" rel="noopener noreferrer">{__( "View service on cookiedatabase.org", "complianz-gdpr" )}</a>
				</div>
			}
			<div className="cmplz-details-row cmplz-details-row__buttons">
				<button disabled={saving} onClick={ ( e ) => onSaveHandler(service.ID) }  className="button button-default">{__("Save", "complianz-gdpr")}</button>
				<button className="button button-default cmplz-reset-button" onClick={ ( e ) => onDeleteHandler(service.ID) }>
					{__("Delete Service", "complianz-gdpr")}
				</button>
			</div>
		</>
	);
}

const Service = (props) => {
	const {adding} = UseSyncData();

	const onAddCookieHandler = (serviceID, serviceName) => {
		props.addCookie(serviceID,serviceName);
	}
	const serviceIsSaved = props.service && props.service.ID>0 && props.service.hasOwnProperty('name');
	const isUnknownService = !props.service || props.service.ID<=0;
	const serviceName = props.service && props.service.name ? props.service.name : __("New Service", "complianz-gdpr");
	const Details = () =>{

		return (
			<>
				<div>
					{ ServiceDetails(props.service)}
				</div>
				{props.cookies && props.cookies.length > 0 &&
					<div className="cmplz-panel__cookie_list">
						{props.cookies.map((cookie, i) => <Cookie key={i} cookie={cookie}/>)}
					</div>
				}
				{!isUnknownService &&
					<div>
						<button disabled={adding || !serviceIsSaved} onClick={ (e) => onAddCookieHandler(props.service.ID, serviceName) } className="button button-default">
							{__("Add cookie to %s", "complianz-gdpr").replace("%s", serviceName) }
							{adding && <Icon name = "loading" color = 'grey' />}
						</button>
						{!serviceIsSaved && <div className="cmplz-comment">{__("Save service to be able to add cookies", "complianz-gdpr")}</div>}
					</div>
				}
			</>
		);
	}

	const Icons = () => {
		if ( !props.service ) {
			return (<></>)
		}

		return (
			<>
				{props.service.complete && <Icon tooltip={__( "The data for this service is complete", "complianz-gdpr" )} name = 'success' color = 'green' />}
				{!props.service.complete && <Icon tooltip={__( "This service has missing fields", "complianz-gdpr" )} name = 'times' color = 'red' />}
				{props.service.synced && <Icon tooltip={__( "This service has been synchronized with cookiedatabase.org", "complianz-gdpr" )} name = 'rotate' color = 'green' />}
				{!props.service.synced && <Icon tooltip={__( "This service is not synchronized with cookiedatabase.org", "complianz-gdpr" )} name = 'rotate-error' color = 'red' />}
			</>
		)
	}

	return (
		<>
			<Panel id={props.id} summary={props.name} icons={Icons()} details={Details()} />
		</>
	);

}

export default memo(Service);
