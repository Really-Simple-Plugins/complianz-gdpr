import Icon from "../../utils/Icon";
import { __ } from '@wordpress/i18n';
import Panel from "../Panel";
import {UseSyncData} from "./SyncData";
import useFields from "../../Settings/Fields/FieldsData";
import {useEffect, useState} from "react";
import CheckboxGroup from "../Inputs/CheckboxGroup";
import SelectInput from "../Inputs/SelectInput";

const CookieDetails = (cookie) => {
	const {getFieldValue, showSavedSettingsNotice} = useFields();
	const {saving, purposesOptions, services, updateCookie, toggleDeleteCookie, saveCookie} = UseSyncData();
	const [name, setName] = useState({ID:cookie.ID, value:cookie.name});
	const [retention, setRetention] = useState({ID:cookie.ID, value:cookie.retention});
	const [cookieFunction, setCookieFunction] = useState({ID:cookie.ID, value:cookie.cookieFunction});
	//allow for both '0'/'1' and false/true.
	let useCdbApi = getFieldValue('use_cdb_api')==='yes';
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



	const onSaveHandler = async (id) => {
		await saveCookie(id);
		showSavedSettingsNotice(__("Saved cookie", "complianz-gd[r"));
	}

	const onDeleteHandler = async (id) => {
		await toggleDeleteCookie(id);
	}

	const onChangeHandler = (value, id, type) => {
		updateCookie(id, type, value);
	}

	useEffect(() => {
		const typingTimer = setTimeout(() => {
			updateCookie(name.ID, 'name', name.value);
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [name]);

	const onNameChangeHandler = (e, id, type) => {
		let obj = {ID:id, value:e.target.value};
		setName(obj);
	}

	useEffect(() => {
		const typingTimer = setTimeout(() => {
			updateCookie(cookieFunction.ID, 'cookieFunction', cookieFunction.value);
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [cookieFunction]);
	const onFunctionChangeHandler = (e, id, type) => {
		let obj = {ID:id, value:e.target.value};
		setCookieFunction(obj);
	}

	useEffect(() => {
		const typingTimer = setTimeout(() => {
			updateCookie(retention.ID, 'retention', retention.value);
		}, 700);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [retention]);

	const onRetentionChangeHandler = (e, id, type) => {
		let obj = {ID:id, value:e.target.value};
		setRetention(obj);
	}

	const onCheckboxChangeHandler = (checked, id, type) => {
		updateCookie(id, type, checked);
	}

	let retentionDisabled = cookie.name.indexOf('cmplz_')!==-1 ? true:sync;
	let deletedClass = cookie.deleted!=1 ? 'cmplz-reset-button':'';
	let servicesOptions = services.map((service, i) => {
		return {value:service.ID, label:service.name};
	});
	return (
		<>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<CheckboxGroup
					id={cookie.ID+'_cdb_api'}
					disabled={!useCdbApi}
					value={sync}
					onChange={(value) => onCheckboxChangeHandler(value, cookie.ID, 'sync')}
					options={{'sync': __('Sync cookie with cookiedatabase.org', 'complianz-gdpr')}}
				/>
			</div>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<CheckboxGroup
					id={cookie.ID+'showOnPolicy'}
					disabled={disabled}
					value={cookie.showOnPolicy}
					onChange={(value) => onCheckboxChangeHandler(value, cookie.ID, 'showOnPolicy')}
					options={{'showOnPolicy': __('Show cookie on Cookie Policy', 'complianz-gdpr')}}
				/>
			</div>
			<div className="cmplz-details-row">
				<label>{__("Name", "complianz-gdpr")}</label>
				<input disabled={disabled} onChange={ ( e ) =>  onNameChangeHandler(e, cookie.ID, 'name') } type="text" placeholder={__("Name", "complianz-gdpr")} value={name.value} />
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
				<input disabled={retentionDisabled} onChange={ ( e ) =>  onRetentionChangeHandler(e, cookie.ID, 'retention') } type="text" placeholder={__("1 year", "complianz-gdpr")}  value={retention.value} />
			</div>
			<div className="cmplz-details-row">
				<label>{__("Cookie function", "complianz-gdpr")}</label>
				<input disabled={disabled} onChange={ ( e ) =>  onFunctionChangeHandler(e, cookie.ID, 'cookieFunction') } type="text" placeholder={__("e.g. store user ID", "complianz-gdpr")}  value={cookieFunction.value} />
			</div>
			<div className="cmplz-details-row">
				<label>{__("Purpose", "complianz-gdpr")}</label>
				<SelectInput
					disabled={disabled}
					value={cookie.purpose}
					options={purposesOptions}
					onChange={(value) => onChangeHandler(value, cookie.ID, 'purpose')}
				/>
			</div>
			{cdbLink &&
				<div className="cmplz-details-row">
					<a href={cdbLink} target="_blank">{__( "View cookie on cookiedatabase.org", "complianz-gdpr" )}</a>
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
const Cookie = ({cookie}) => {
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
	} else if ( cookie.showOnPolicy ) {
		comment =  " | "+__( 'Admin, ignored', 'complianz-gdpr' );
	} else if (cookie.isMembersOnly) {
		comment = " | "+__( 'Logged in users only, ignored', 'complianz-gdpr' );
	}
	let description = cookie.name
	return (
		<>
			<Panel summary={description} comment={comment} icons={Icons()} details={CookieDetails(cookie)} style={getStyles()}/>
		</>

	);

}

export default Cookie
