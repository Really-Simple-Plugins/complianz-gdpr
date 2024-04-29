import Hyperlink from "../utils/Hyperlink";
import { __ } from '@wordpress/i18n';
import useMenu from "../Menu/MenuData";
import useLicense from "./License/LicenseData";
import {useEffect,useState} from '@wordpress/element';
import useFields from './Fields/FieldsData';
import UseBannerData from "./CookieBannerPreview/CookieBannerData";
import ErrorBoundary from "../utils/ErrorBoundary";
import DOMPurify from "dompurify";
/**
 * Render a grouped block of settings
 */
const SettingsGroup = (props) => {
	const { highLightField, getFieldValue} = useFields();
	const {licenseStatus} = useLicense();
	const { bannerDataLoaded} = UseBannerData();
	let upgrade='https://complianz.io/pricing/';
	const {subMenu, getMenuRegions, selectedSubMenuItem} = useMenu();

	let regions = getMenuRegions();
	//get selected regions from the regions field
	let selectedRegions = getFieldValue('regions');
	if (!Array.isArray(selectedRegions)) selectedRegions = [selectedRegions];
	//filter out regions from 'regions' that do not exist in selectedRegions
	regions = regions.filter(region => selectedRegions.includes(region));

	const [Field, setField] = useState(null);
	useEffect( () => {
		import("./Fields/Field").then(({ default: Field }) => {
			setField(() => Field);
		});
	}, []);

	let selectedFields = [];
	//get all fields with group_id props.group_id
	for (const selectedField of props.fields){
		if (selectedField.group_id === props.group ){
			selectedFields.push(selectedField);
		}
	}

	let activeGroup;
	//first, set the selected menu item as active group, so we have a default in case there are no groups
	for (const item of subMenu.menu_items){
		if (item.id === selectedSubMenuItem ) {
			activeGroup = item;
		} else if (item.menu_items) {
			activeGroup = item.menu_items.filter(menuItem => menuItem.id === selectedSubMenuItem)[0];
		}
		if ( activeGroup ) {
			break;
		}
	}

	//now check if we have actual groups
	for (const item of subMenu.menu_items){
		if (item.id === selectedSubMenuItem && item.hasOwnProperty('groups')) {
			let currentGroup = item.groups.filter(group => group.id === props.group);
			if (currentGroup.length>0) {
				activeGroup = currentGroup[0];
			}
		}
	}

	if ( !activeGroup ) {
		return null;
	}
	let msg = activeGroup.premium_text ? activeGroup.premium_text : __("Learn more about %sPremium%s", "complianz-gdpr");
	if ( cmplz_settings.is_premium ) {
		if ( licenseStatus === 'empty' || licenseStatus === 'deactivated' ) {
			msg = cmplz_settings.messageInactive;
		} else {
			msg = cmplz_settings.messageInvalid;
		}
	}

	//if free, all premium items are disabled
	let disabled = false;
	if ( activeGroup.premium ) {
		disabled = !cmplz_settings.is_premium;
	}
	//if this is the premium plugin, it's only disabled if the license is not valid.
	if (cmplz_settings.is_premium) {
		disabled = licenseStatus !== 'valid' && activeGroup.id !== 'license';
	}
	//if a feature can only be used on networkwide or single site setups, pass that info here.
	upgrade = activeGroup.upgrade ? activeGroup.upgrade : upgrade;
	let helplinkText = activeGroup.helpLink_text ? activeGroup.helpLink_text : __("Instructions","complianz-gdpr");
	let disabledClass = disabled ? 'cmplz-disabled' : '';

	//if all fields are conditionally disabled, hide the entire group
	if ( selectedFields.filter((field)=> ( field.conditionallyDisabled && field.conditionallyDisabled===true ) || (field.visible && field.visible===false) ).length===selectedFields.length ) {
		return null;
	}

	return (
		<div className={"cmplz-grid-item cmplz-"+activeGroup.id + ' ' +  disabledClass} key={activeGroup.id}>
			{activeGroup.title && <div className="cmplz-grid-item-header">
				<h3 className="cmplz-h4">{activeGroup.title}</h3>
				{regions.length>0 && <div className="cmplz-grid-item-controls">
					{regions.map((region, i) =>
						<div key={i}><img className="cmplz-settings-region" src={cmplz_settings.plugin_url+'/assets/images/'+region+'.svg'}  alt="region"/></div>
					)}
				</div>}

				{regions.length===0 && activeGroup.helpLink && <div className="cmplz-grid-item-controls">
					<Hyperlink
						target="_blank"
						rel="noopener noreferrer"
						className="cmplz-helplink"
						text={helplinkText}
						url={activeGroup.helpLink}
					/>
				</div>}
			</div>}
			<div className="cmplz-grid-item-content">
				{activeGroup.intro &&
					<div className="cmplz-settings-block-intro" dangerouslySetInnerHTML={{__html: DOMPurify.sanitize( activeGroup.intro ) } }></div>} {/* nosemgrep: react-dangerouslysetinnerhtml */}
				{Field && selectedFields.map((field, i) =>
					<ErrorBoundary key={"field-"+field.id} fallback={"Could not load field "+field.id}><Field key={field.id} field={field} highLightField={highLightField} /></ErrorBoundary>)
				}
			</div>
			{ disabled && <div className="cmplz-locked">
				<div className="cmplz-locked-overlay">
					<span className="cmplz-task-status cmplz-premium">{__("Upgrade","complianz-gdpr")}</span>
					<span>
						{ cmplz_settings.is_premium && <span>{msg}&nbsp;<a className="cmplz-locked-link" href={cmplz_settings.license_url}>{__("Check license", "complianz-gdpr")}</a></span>}
						{ !cmplz_settings.is_premium &&
							<Hyperlink
								target="_blank"
								rel="noopener noreferrer"
								text={msg}
								url={upgrade}
							/>
						}
					</span>
				</div>
			</div>}
			{ subMenu.id==='banner' && !bannerDataLoaded && <div className="cmplz-locked">
				<div className="cmplz-locked-overlay"></div>
			</div>}
		</div>
	)
}

export default SettingsGroup
