import { __ } from '@wordpress/i18n';
import {useState, useEffect} from "@wordpress/element";
import Panel from "./Panel";
import useIntegrations from "./Integrations/IntegrationsData";
import useFields from "./Fields/FieldsData";
import {memo} from "@wordpress/element";

const PluginsOverviewControl = () => {
	const { services, integrationsLoaded, plugins, fetchIntegrationsData} = useIntegrations();
	const [ activeServices, setActiveServices ] = useState( [] );
	const { fields, getField } = useFields();

    useEffect(() => {
        if ( !integrationsLoaded ) {
			fetchIntegrationsData();
        }
    }, [integrationsLoaded]);

	useEffect(() => {
		syncServicesWithFields();
    }, [fields, integrationsLoaded]);

	const syncServicesWithFields = () => {
		// //for each service, update the value from the field
		let servicesCopy = [...services];
		servicesCopy.forEach(function(service, i) {
			let serviceCopy = {...service};
			let field = getField(service.source);
			if ( field.type==='multicheckbox' ) {
				let value = field.value;
				if (!Array.isArray(value)) value = [];
				serviceCopy.enabled = value.includes(service.id);
			}
			else {
				serviceCopy.enabled = field.value === 'yes';
			}
			servicesCopy[i] = serviceCopy;
		});
		// //filter out all services that are not enabled
		servicesCopy = servicesCopy.filter(service => service.enabled);
		setActiveServices(servicesCopy);
	}

	const integrationsList = (items) => {
		if (!Array.isArray(items)) {return null}
		return (
			items.map((item, i) =>
				<div key={i} >
					{item.label}
				</div>
			)
		);
	}

	let servicesCount = !Array.isArray(activeServices) ? 0 : activeServices.length;
	let pluginsCount = !Array.isArray(plugins) ? 0 : plugins.length;
	return (
		<div className="cmplz-plugins_overview">
			<div className="cmplz-panel__list">
				<Panel summary={__("We found %s active plugin integrations","complianz-gdpr").replace('%s', pluginsCount)} details={integrationsList(plugins)} icon={'plugin'}/>
				<Panel summary={__("We found %s active service integrations","complianz-gdpr").replace('%s', servicesCount)} details={integrationsList(activeServices)} icon={'services'}/>
			</div>
		 </div>
	);
}

export default memo(PluginsOverviewControl);
