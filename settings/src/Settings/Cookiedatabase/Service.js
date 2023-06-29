import Cookie from './Cookie';
import Panel from "../Panel";
import {UseSyncData} from "./SyncData";
import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";
import useFields from "../../Settings/Fields/FieldsData";

const ServiceDetails = (service) => {
	const {getFieldValue, showSavedSettingsNotice} = useFields();
	const {saving, deleteService, serviceTypeOptions, updateService, saveService} = UseSyncData();
	let useCdbApi = getFieldValue('use_cdb_api')==='yes';

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

	const onSaveHandler = async (id) => {
		await saveService(id);
		showSavedSettingsNotice(__("Saved service", "complianz-gd[r"));
	}

	const onDeleteHandler = async (id) => {
		await deleteService(id);
	}

	const onChangeHandler = (e, id, type) => {
		updateService(id, type, e.target.value);
	}
	const onCheckboxChangeHandler = (e, id, type) => {
		updateService(id, type, e.target.checked);
	}

	return (
		<>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<label>{__("Data is shared with this service", "complianz-gdpr")}</label>
				<input disabled={disabled} onChange={ ( e ) =>  onCheckboxChangeHandler(e, service.ID, 'thirdParty') } type="checkbox"  checked={service.thirdParty} />
			</div>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<label>{__("Sync service with cookiedatabase.org", "complianz-gdpr")}</label>
				<input disabled={!useCdbApi} onChange={ ( e ) => onCheckboxChangeHandler(e, service.ID, 'sync') } type="checkbox"  checked={sync} />
			</div>
			<div className="cmplz-details-row">
				<label>{__("Name", "complianz-gdpr")}</label>
				<input disabled={disabled} onChange={ ( e ) =>  onChangeHandler(e, service.ID, 'name') } type="text" placeholder={__("Name", "complianz-gdpr")} value={service.name} />
			</div>
			<div className="cmplz-details-row">
				<label>{__("Service Types", "complianz-gdpr")}</label>
				<select disabled={disabled} onChange={ ( e ) =>  onChangeHandler(e, service.ID, 'serviceType') } value={service.serviceType} >
					<option key={-1} value={0}>{__("Select a service","complianz-gdpr")}</option>
					{ serviceTypeOptions.map((serviceType, i) => <option key={i} value={serviceType.name}>{serviceType.name}</option>)}
				</select>
			</div>
			<div className="cmplz-details-row">
				<label>{__("Privacy Statement URL", "complianz-gdpr")}</label>
				<input disabled={disabled} onChange={ ( e ) =>  onChangeHandler(e, service.ID, 'privacyStatementURL') } type="text" placeholder={__("https://domain.com/privacy", "complianz-gdpr")} value={service.privacyStatementURL} />
			</div>
			{cdbLink &&
				<div className="cmplz-details-row">
					<a href={cdbLink} target="_blank">{__( "View service on cookiedatabase.org", "complianz-gdpr" )}</a>
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

	const onAddCookieHandler = (serviceID, serviceName) => {
		props.addCookie(serviceID,serviceName);
	}

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
				{ props.service && props.service.ID>0 && props.service.hasOwnProperty('name') && <div>
					<button onClick={ (e) => onAddCookieHandler(props.service.ID, props.service.name) } className="button button-default">
					{__("Add cookie to %s", "complianz-gdpr").replace("%s", props.service.name) }
					</button>
				</div> }
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
			<Panel summary={props.name} icons={Icons()} details={Details()} />
		</>
	);

}

export default Service
