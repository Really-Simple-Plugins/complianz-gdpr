import {useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import {memo} from "@wordpress/element";
import './debug.scss'

import useDebugData from "./useDebugData";
const DebugDataControl = () => {

	const { debugDataLoaded, scriptDebugEnabled, debugData, getDebugData } = useDebugData();

	useEffect ( () => {
		if (!debugDataLoaded) {
			getDebugData();
		}
	},[]);

	return (
		<div className="cmplz-debug-data-container">
			{debugDataLoaded && !scriptDebugEnabled && __("To view possible script conflicts on your site, set the SCRIPT_DEBUG constant in your wp-config.php, or install the plugin WP Debugging","complianz-gpdr")}
			{debugDataLoaded && scriptDebugEnabled && <>
				{__("Debugging enabled:","complianz-gpdr")}&nbsp;
				{debugData}
				{debugData.length===0 && __("No script errors detected","complianz-gpdr")}
			</>}
			{!debugDataLoaded && <>...</>}
		</div>
	)
}
export default memo(DebugDataControl)
