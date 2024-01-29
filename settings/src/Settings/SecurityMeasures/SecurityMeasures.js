import {useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import {memo} from "@wordpress/element";
import './measures.scss'

import useSecurityMeasuresData from "./useSecurityMeasuresData";
import useInstallPluginData from "../InstallPlugin/InstallPluginData";
const SecurityMeasures = () => {
	const { measuresDataLoaded, measures, has_7, getMeasuresData } = useSecurityMeasuresData();
	const {statusLoaded, startPluginAction, pluginAction} = useInstallPluginData();
	const slug = 'really-simple-ssl';


	//on update of plugin status
	useEffect ( () => {
		if (pluginAction==='status') {
			return;
		}
		getMeasuresData();
	},[pluginAction]);

	let status = statusLoaded ? pluginAction : 'loading';
	if ( statusLoaded && status!=='installed' && status!=='upgrade-to-premium') {
		let notice  = status === 'activate' ? __("Please activate Really Simple SSL & Security to unlock this feature.", "complianz-gdpr") : __("Please install Really Simple SSL & Security to unlock this feature.", "complianz-gdpr");
		if (status === 'loading' ) {
			notice = '...';
		}
		return (
			<div className="cmplz-locked">
				<div className="cmplz-locked-overlay">
					<span className="cmplz-task-status cmplz-open">{__("Not installed", "complianz-gdpr")}</span>
					<span>
					{notice}
				</span>
				</div>
			</div>
		)
	}

	const measuresList = {
		vulnerability_detection : __("Vulnerability Detection",'complianz-gdpr'),
		recommended_headers : __("HTTP Strict Transport Security and related security headers",'complianz-gdpr'),
		ssl : __("TLS / SSL",'complianz-gdpr'),
		hardening : __("Recommended site hardening features",'complianz-gdpr'),
	};

	const Measure = ({measure}) => {
		//get properties of the measure
		let enabledClass = measure.enabled ? 'cmplz-measure-enabled' : 'cmplz-measure-disabled';
		return (
			<ul className="cmplz-measure">
				<li className={"cmplz-measure-description "+enabledClass}>{measuresList[measure.id]}</li>
			</ul>
		)
	}

	return (
		<div className="cmplz-measures-container">
			{measuresDataLoaded && has_7 && measures.length>0 && <>
				<p>{__("We are committed to the security of personal data. We take appropriate security measures to limit abuse of and unauthorized access to personal data. This ensures that only the necessary persons have access to your data, that access to the data is protected, and that our security measures are regularly reviewed.",'complianz-gdpr')}</p>
				<p>{__("The security measures we use consist of, but are not limited to:",'complianz-gdpr')}</p>
				{measures.map((measure, index) => <Measure key={index} measure={measure}/>)}
			</>}
			{measuresDataLoaded && measures.length===0 && has_7 && __("No security measures enabled in Really Simple SSL & Security",'complianz-gdpr')}
			{measuresDataLoaded && !has_7 && __("Please upgrade Really Simple SSL & Security to the latest version to unlock this feature.",'complianz-gdpr')}
			{!measuresDataLoaded && <>...</>}
		</div>
	)
}
export default memo(SecurityMeasures)

