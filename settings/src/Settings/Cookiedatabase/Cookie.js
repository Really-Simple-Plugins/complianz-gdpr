import Icon from "../../utils/Icon";
import { __ } from '@wordpress/i18n';
import Panel from "../Panel";
import UseSyncData from "./SyncData";
import useFields from "../../Settings/Fields/FieldsData";
import {memo,useEffect, useState} from "@wordpress/element";
import CheckboxGroup from "../Inputs/CheckboxGroup";
import SelectInput from "../Inputs/SelectInput";

const CookieDetails = (cookie) => {
	const {getFieldValue, showSavedSettingsNotice} = useFields();
	const {language, saving, purposesOptions, services, updateCookie, toggleDeleteCookie, saveCookie} = UseSyncData();
	const [name, setName] = useState('');
	const [retention, setRetention] = useState('');
	const [cookieFunction, setCookieFunction] = useState('');
	const [purposesByLanguage, setPurposesByLanguage] = useState([]);

	//allow for both '0'/'1' and false/true.
	let useCdbApi = getFieldValue('use_cdb_api')!=='no';
	let sync = useCdbApi ? cookie.sync==1 : false;
	let disabled = sync;
	if (saving) {
		disabled = true;
	}
	let cdbLink = false;
	if ( cookie.slug.length>0 ) {
		let service_slug = !cookie.service ? 'unknown-service' : cookie.service;
		cdbLink = 'https://cookiedatabase.org/cookie/' + service_slug + '/' + cookie.slug;
	}

	useEffect(() => {
		if ( cookie && cookie.cookieFunction ) {
			setCookieFunction(cookie.cookieFunction);
		}
	},[cookie]);

	const onSaveHandler = async (id) => {
		await saveCookie(id);
		showSavedSettingsNotice(__("Saved cookie", "complianz-gdpr"));
	}

	const onDeleteHandler = async (id) => {
		await toggleDeleteCookie(id);
	}

	const onChangeHandler = (value, id, type) => {
		updateCookie(id, type, value);
	}

	useEffect(() => {
		if ( cookie && cookie.name ) {
			setName(cookie.name);
		}
	},[cookie.name]);

	useEffect(() => {
		if (!cookie){
			return;
		}
		if (cookie.name=== name) {
			return;
		}
		const typingTimer = setTimeout(() => {
			updateCookie(cookie.ID, 'name', name);
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [name]);

	useEffect(() => {
		if ( !cookie ) {
			return;
		}
		if ( cookie.cookieFunction === cookieFunction ){
			return;
		}
		const typingTimer = setTimeout(() => {
			updateCookie(cookie.ID, 'cookieFunction', cookieFunction);
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [cookieFunction]);

	useEffect(() => {
		if ( cookie && cookie.retention ) {
			setRetention(cookie.retention);
		}
	},[cookie.retention]);

	useEffect(() => {
		if ( !cookie ) {
			return;
		}
		if ( cookie.retention === retention ) {
			return;
		}
		const typingTimer = setTimeout(() => {
			updateCookie(cookie.ID, 'retention', retention);
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [retention]);

	useEffect (() => {
		let purposes = purposesOptions && purposesOptions.hasOwnProperty(language) ? purposesOptions[language] : [];
		purposes = purposes.map(purpose => {
			return {label:purpose.label,value:purpose.label};
		});
		setPurposesByLanguage(purposes);
	},[language, purposesOptions]);

	const onCheckboxChangeHandler = (checked, id, type) => {
		updateCookie(id, type, checked);
	}
	if ( !cookie ) {
		return null;
	}
	let retentionDisabled = cookie.name.indexOf('cmplz_')!==-1 ? true:sync;
	let deletedClass = cookie.deleted!=1 ? 'cmplz-reset-button':'';
	let servicesOptions = services.map((service, i) => {
		return {value:service.ID, label:service.name};
	});

	//convert legacy marketing/tracking label to marketing, if found.
	let purposesHasSlash = false;
	let purposeMarketing = 'Marketing';
	purposesByLanguage.forEach(function(purpose, i) {
		if (purpose.value && purpose.value.indexOf('/')!==-1){
			purposesHasSlash = true;
			purposeMarketing = purpose.value;
			//strip off string after slash, including the slash
			purposeMarketing = purposeMarketing.substring(0, purposeMarketing.indexOf('/'));
		}
	});
	let cookieHasSlash =  cookie.purpose && cookie.purpose.indexOf('/')!==-1;
	if ( cookieHasSlash ){
		purposeMarketing = cookie.purpose.substring(0, cookie.purpose.indexOf('/'));
	}

	if ( purposesHasSlash && !cookieHasSlash ) {
		//find the first purpose with a slash in purposeOptions, and change it to purposeMarketing
		purposesByLanguage.forEach(function(purpose, i) {
			if (purpose.value && purpose.value.indexOf('/')!==-1){
				purpose.value = purposeMarketing;
				purpose.label = purposeMarketing;
				purposesByLanguage[i] = purpose;
			}
		});
	}

	let cookiePurpose = cookie.purpose;
	if ( !purposesHasSlash && cookieHasSlash ) {
		cookiePurpose = purposeMarketing;
	}

	return (
		<>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<CheckboxGroup
					id={cookie.ID+'_cdb_api'}
					disabled={!useCdbApi}
					value={sync}
					onChange={(value) => onCheckboxChangeHandler(value, cookie.ID, 'sync')}
					options={{true: __('Sync cookie with cookiedatabase.org', 'complianz-gdpr')}}
				/>
			</div>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<CheckboxGroup
					id={cookie.ID+'showOnPolicy'}
					disabled={disabled}
					value={cookie.showOnPolicy}
					onChange={(value) => onCheckboxChangeHandler(value, cookie.ID, 'showOnPolicy')}
					options={{true: __('Show cookie on Cookie Policy', 'complianz-gdpr')}}
				/>
			</div>
			<div className="cmplz-details-row">
				<label>{__("Name", "complianz-gdpr")}</label>
				<input disabled={disabled}
					   onChange={ ( e ) =>  setName(e.target.value) }
					   type="text" placeholder={__("Name", "complianz-gdpr")}
					   value={name} />
			</div>
			<div className="cmplz-details-row">
				<label>{__("Service", "complianz-gdpr")}</label>
				<SelectInput
					disabled={disabled}
					value={cookie.serviceID}
					options={servicesOptions}
					onChange={(value) => onChangeHandler(value, cookie.ID, 'serviceID')}
				/>
			</div>
			<div className="cmplz-details-row">
				<label>{__("Expiration", "complianz-gdpr")}</label>
				<input disabled={retentionDisabled}
					   onChange={ ( e ) =>  setRetention(e.target.value) }
					   type="text" placeholder={__("1 year", "complianz-gdpr")}
					   value={retention} />
			</div>
			<div className="cmplz-details-row">
				<label>{__("Cookie function", "complianz-gdpr")}</label>
				<input disabled={disabled}
					   onChange={ ( e ) =>  setCookieFunction(e.target.value) }
					   type="text" placeholder={__("e.g. store user ID", "complianz-gdpr")}
					   value={cookieFunction} />
			</div>
			<div className="cmplz-details-row">
				<label>{__("Purpose", "complianz-gdpr")}</label>
				<SelectInput
					disabled={disabled}
					value={cookiePurpose}
					options={purposesByLanguage}
					onChange={(value) => onChangeHandler(value, cookie.ID, 'purpose')}
				/>
			</div>
			{cdbLink &&
				<div className="cmplz-details-row">
					<a href={cdbLink} target="_blank" rel="noopener noreferrer">{__( "View cookie on cookiedatabase.org", "complianz-gdpr" )}</a>
				</div>
			}
			<div className="cmplz-details-row cmplz-details-row__buttons">
				<button disabled={saving} onClick={ ( e ) => onSaveHandler(cookie.ID) } className="button button-default">{__("Save", "complianz-gdpr")}</button>
				<button className={"button button-default "+ deletedClass } onClick={ ( e ) => onDeleteHandler(cookie.ID) }>
					{cookie.deleted==1 && __("Restore", "complianz-gdpr")}
					{cookie.deleted!=1 && __("Delete", "complianz-gdpr")}
				</button>
			</div>
		</>
	);
}
/**
 * Render a help notice in the sidebar
 */
const Cookie = ({cookie, id}) => {
	const Icons = () => {
		return (
			<>
				{cookie.complete && <Icon tooltip={__( "The data for this cookie is complete", "complianz-gdpr" )} name = 'success' color = 'green' />}
				{!cookie.complete && <Icon tooltip={__( "This cookie has missing fields", "complianz-gdpr" )} name = 'times' color = 'red' />}
				{cookie.sync && cookie.synced && <Icon name='rotate' color = 'green'/>}
				{!cookie.synced || !cookie.sync && <Icon tooltip={__( "This cookie is not synchronized with cookiedatabase.org.",'complianz-gdpr')} name = 'rotate-error' color = 'red'/>}

				{cookie.showOnPolicy && <Icon tooltip={__( "This cookie will be on your Cookie Policy", "complianz-gdpr" )} name = 'file' color = 'green'/>}
				{!cookie.showOnPolicy && <Icon tooltip={__( "This cookie is not shown on the Cookie Policy", "complianz-gdpr" )}  name = 'file-disabled' color = 'grey'/>}

				{cookie.old && <Icon tooltip={__( "This cookie has not been detected on your site in the last three months", "complianz-gdpr" )} name = 'calendar-error' color = 'red' />}
				{!cookie.old && <Icon tooltip={__( "This cookie has recently been detected", "complianz-gdpr" )} name = 'calendar' color = 'green' />}
			</>
		)
	}

	const getStyles = () => {
		if (!cookie.deleted) return;

		return Object.assign(
			{},
			{"backgroundColor": "var(--rsp-red-faded)"},
		);
	}

	let comment = '';
	if (cookie.deleted) {
		comment =  " | "+__( 'Deleted', 'complianz-gdpr' );
	} else if ( !cookie.showOnPolicy ) {
		comment =  " | "+__( 'Admin, ignored', 'complianz-gdpr' );
	} else if (cookie.isMembersOnly) {
		comment = " | "+__( 'Logged in users only, ignored', 'complianz-gdpr' );
	}
	let description = cookie.name
	return (
		<>
			<Panel id={id}  summary={description} comment={comment} icons={Icons()} details={CookieDetails(cookie)} style={getStyles()}/>
		</>

	);

}

export default memo(Cookie)
