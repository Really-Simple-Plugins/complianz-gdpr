import useFields from "../../Settings/Fields/FieldsData";
import useIntegrations from "../../Settings/Integrations/IntegrationsData";
import Icon from "../../utils/Icon";
import {useEffect, useState} from "@wordpress/element";;
import { __ } from '@wordpress/i18n';
import useLicense from "../../Settings/License/LicenseData";

const ToolItem = (props) => {
	const { fields, getFieldValue} = useFields();
	const [fieldEnabled, setFieldEnabled] = useState(false);
	const { integrationsLoaded, plugins, fetchIntegrationsData} = useIntegrations();
	const {licenseStatus} = useLicense();
	useEffect( () => {
		let item = props.item;
		if (item.field) {
			let enabled = getFieldValue(item.field.name) == item.field.value;
			setFieldEnabled(enabled);
		}
	}, [fields] );

	useEffect( () => {
		if (!integrationsLoaded) {
			fetchIntegrationsData();
		}
	}, [] );

	let item = props.item;
	//linked to a plugin, e.g. woocommerce
	if ( item.plugin ) {
		let pluginActive = plugins.filter(plugin => plugin.id === item.plugin).length > 0;
		if ( !pluginActive) return null;

		return (
			<div className="cmplz-tool">
				<div className="cmplz-tool-title">{item.title}</div>
				<div className="cmplz-tool-link">
					<a href={item.link} target="_blank" rel="noopener noreferrer">{<Icon name={'circle-chevron-right'} color="black" size={14} />}</a>
				</div>
			</div>
		)
	}

	//not a plugin condition.
	let isPremiumUser = cmplz_settings.is_premium && licenseStatus === 'valid'
	let linkText = __("Read more","complianz-gdpr");
	let link = item.link;

	if ( isPremiumUser ) {
		if ( !fieldEnabled && item.enableLink ) {
			link = item.enableLink;
		}
		if ( (!item.field || fieldEnabled) && item.viewLink ) {
			link = item.viewLink;
		}
	}
	let isExternal = link.indexOf('https://') !== -1;
	let target = isExternal ? '_blank' : '_self';
	let icon = isExternal ? 'external-link' : 'circle-chevron-right';

	return (
		<div className="cmplz-tool">
			<div className="cmplz-tool-title">{item.title}
				{item.plusone && item.plusone }
			</div>
			<div className="cmplz-tool-link">
				<a href={link} target={target} rel={isExternal ? "noopener noreferrer" : ""}><Icon name={icon} color="black" size={14} /></a>
			</div>
		</div>
	);
};
export default ToolItem;
