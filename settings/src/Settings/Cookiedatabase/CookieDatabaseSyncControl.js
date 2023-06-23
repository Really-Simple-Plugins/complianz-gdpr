import {useEffect, useState} from "@wordpress/element";
import {UseSyncData} from './SyncData';
import Service from './Service';
import { __ } from '@wordpress/i18n';
import useFields from "../../Settings/Fields/FieldsData";
import {memo} from "react";

const CookieDatabaseSyncControl = () => {
	const {buildServicesCookiesArray, setShowDeletedCookies, servicesAndCookies, syncDataLoaded, loadingSyncData, language, setLanguage, languages, cookies, addCookie, addService, services, syncProgress, curlExists, hasSyncableData, setSyncProgress, restart, fetchSyncProgressData, errorMessage} = UseSyncData();
	const {addHelpNotice, getFieldValue} = useFields();
	const [disabled, setDisabled] = useState(false);

	useEffect ( () => {
		if ( !loadingSyncData && syncProgress <100 ) {
			fetchSyncProgressData();
		}
	},[syncProgress]);

	useEffect ( () => {
		let useCdbApi = getFieldValue('use_cdb_api')==='yes';
		if ( !useCdbApi ) {
			setDisabled(true) ;
			let explanation = __("You have opted out of the use of the Cookiedatabase.org synchronisation.", "complianz-gdpr");
			addHelpNotice('cookiedatabase_sync', 'warning', explanation, __('Cookiedatabase', 'complianz-gdpr') );
		}

	},[]);

	useEffect ( () => {
		if ( !curlExists ) {
			setDisabled(true) ;
			let explanation = __("CURL is not enabled on your site, which is required for the Cookiedatabase sync to function.", "complianz-gdpr");
			addHelpNotice('cookiedatabase_sync', 'warning', explanation, __('Cookiedatabase', 'complianz-gdpr') );
		}
	},[ curlExists]);

	useEffect ( () => {
		let useCdbApi = getFieldValue('use_cdb_api')==='yes';
		if ( !useCdbApi ) {
			setDisabled(true) ;
			let explanation = __("You have opted out of the use of the Cookiedatabase.org synchronisation.", "complianz-gdpr");
			addHelpNotice('cookiedatabase_sync', 'warning', explanation, __('Cookiedatabase', 'complianz-gdpr') );
		}
	},[getFieldValue('use_cdb_api')]);

	useEffect ( () => {

		if ( errorMessage!=='' ) {
			setDisabled(true) ;
			addHelpNotice('cookiedatabase_sync', 'warning', errorMessage, __('Cookiedatabase', 'complianz-gdpr') );
		}
	},[errorMessage]);

	useEffect ( () => {
		if ( !hasSyncableData ) {
			setDisabled(true);
			let explanation = __("Synchronization disabled: All detected cookies and services have been synchronised.", "complianz-gdpr");
			addHelpNotice('cookiedatabase_sync', 'warning', explanation, __('Cookiedatabase', 'complianz-gdpr') );
		}

		if ( syncProgress<100 && syncProgress>0) {
			setDisabled(true) ;
		}
	},[syncProgress, hasSyncableData]);

	useEffect ( () => {
		if ( !hasSyncableData ) {
			setDisabled(true);
			let explanation = __("Synchronization disabled: All detected cookies and services have been synchronised.", "complianz-gdpr");
			addHelpNotice('cookiedatabase_sync', 'warning', explanation, __('Cookiedatabase', 'complianz-gdpr') );
		}
	},[hasSyncableData]);

	useEffect ( () => {
		if ( syncProgress<100 && syncProgress>0) {
			setDisabled(true) ;
		}
	},[syncProgress]);

	useEffect ( () => {
		if ( syncDataLoaded && servicesAndCookies.length === 0) {
			let explanation = __("No cookies have been found currently. Please try another cookie scan, or check the most common causes in the article below ", "complianz-gdpr");
			addHelpNotice('cookiedatabase_sync', 'warning', explanation, __('No cookies found', 'complianz-gdpr'), 'https://complianz.io/cookie-scan-results/' );
		}
	},[servicesAndCookies, syncDataLoaded]);

	useEffect ( () => {
		buildServicesCookiesArray()
	},[services,cookies]);

	const onAddServiceHandler = () => {
		addService();
	}

	const getStyles = () => {
		return Object.assign(
			{},
			{width: syncProgress+"%"},
		);
	}

	const Start = () => {
		setSyncProgress(1);
		restart();
	}

	return (
		<>
			<div className="cmplz-cookiedatabase-controls">
				<button disabled={disabled} className="button button-default" onClick={ (e) => Start(e) }>{__("Sync","complianz-gdpr")}</button>
				{ languages.length > 1 &&
					<select value={language} onChange={(e) => setLanguage(e.target.value) }>
						{languages.map((language, i) => <option key={i} value={language}>{language}</option>)}
					</select>
				}
				<label>{__("Show deleted cookies","complianz-gdpr")}<input type="checkbox" onClick={(e)=> setShowDeletedCookies(e.target.checked)} /></label>
			</div>
			<div id="cmplz-scan-progress">
				<div className='cmplz-progress-bar' style={getStyles()}></div>
			</div>
			<div className="cmplz-panel__list">
				{ servicesAndCookies.map((service, i) => <Service key={i} addCookie={addCookie} id={service.id} cookies={service.cookies} name={service.name} service={service.service}/> ) }
			</div>
			<div className="cmplz-panel__buttons">
				<button disabled={loadingSyncData} onClick={ (e) => onAddServiceHandler() } className="button button-default">
					{__("Add service", "complianz-gdpr") }
				</button>
			</div>
		</>
	);
}

export default memo(CookieDatabaseSyncControl)
