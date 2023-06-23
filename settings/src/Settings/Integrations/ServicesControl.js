import useIntegrations from "./IntegrationsData";
import {useState, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import useFields from "../Fields/FieldsData";
import readMore from "../../utils/readMore";
import {ToggleControl} from "@wordpress/components";
import useMenu from "../../Menu/MenuData";
import {memo} from "react";
import Hyperlink from "../../utils/Hyperlink";

const ServicesControl = () => {
	const { updatePlaceholderStatus, integrationsLoaded, services, fetchIntegrationsData} = useIntegrations();
	const [ updatedServices, setUpdatedServices ] = useState( [] );
	const [ searchValue, setSearchValue ] = useState( '' );
	const [ disabled, setDisabled ] = useState( false );
	const [ disabledText, setDisabledText ] = useState( '' );
	const [ disabledReadmore, setDisabledReadmore ] = useState( '' );
	const { updateField, getField, getFieldValue, saveFields, setChangedField, addHelpNotice} = useFields();
	const {selectedSubMenuItem} = useMenu();

	const [DataTable, setDataTable] = useState(null);
	useEffect( () => {
		import('react-data-table-component').then(({ default: DataTable }) => {
			setDataTable(() => DataTable);
		});
	}, []);

	useEffect(() => {
		if (!integrationsLoaded) fetchIntegrationsData();

		if (integrationsLoaded) {
			//filter enabled services
			if ( getFieldValue( 'safe_mode' ) == 1 ) {
				setDisabledText( __( 'Safe Mode enabled. To manage integrations, disable Safe Mode in the general settings.', 'complianz-gdpr' ) );
				setDisabled( true );
			} else if (
				getFieldValue( 'uses_thirdparty_services' ) !== 'yes' &&
				getFieldValue( 'uses_social_media' ) !== 'yes' &&
				getFieldValue( 'uses_ad_cookies' ) !== 'yes'
				 ) {
				setDisabledText( __( 'Third-party services and social media are marked as not being used on your website in the wizard.', 'complianz-gdpr' ) );
				setDisabledReadmore('#wizard/services');
				setDisabled( true );
			}
		}
	}, [integrationsLoaded])

	useEffect(() => {
		syncServicesWithFields();
	}, [services])

	const syncServicesWithFields = () => {
		//for each service, update the value from the field
		let servicesCopy = [...services];
		servicesCopy.forEach(function(service, i) {
			let serviceCopy = {...service};
			let field = getField(service.source);
			if ( field.type==='multicheckbox' ) {
				let value = field.value;
				if (!Array.isArray(value)) value = [];
				serviceCopy.enabled = value.includes(service.id);
			} else {
				serviceCopy.enabled = field.value === 'yes';
			}
			servicesCopy[i] = serviceCopy;
		});

		setUpdatedServices(servicesCopy);
		let reCaptcha = getFieldValue('block_recaptcha_service') === 'yes';
		//get service with id == recaptcha from the services list, and check if it's enabled
		let recaptchaService = services.filter(service => service.id === 'google-recaptcha')[0];
		if (reCaptcha && recaptchaService && recaptchaService.enabled ) {
			addHelpNotice('integrations-services', 'warning', __( "reCaptcha is connected and will be blocked before consent. To change your settings, disable reCaptcha in the list.", 'complianz-gdpr' ) , __('reCaptcha blocking enabled', 'complianz-gdpr'),'#wizard/services');
		}
	}

	const customStyles = {
		headCells: {
			style: {
				paddingLeft: '0',
				paddingRight: '0',
			},
		},
		cells: {
			style: {
				paddingLeft: '0',
				paddingRight: '0',
			},
		},
	};


	const onChangePlaceholderHandler = async (service, enabled) => {
		//set placeholder to 'disabled' or 'enabled' in updatedServices
		let services = [...updatedServices];
		let serviceIndex = services.findIndex(item => item.id === service.id);
		services[serviceIndex].placeholder = enabled ? 'enabled' : 'disabled';
		setUpdatedServices(services);
		await updatePlaceholderStatus(service.id, enabled);
	}

	const onChangeHandler = async (service, enabled) => {
		let field = getField(service.source);
		if (service.source.indexOf('service')!==-1) {
			updateField('uses_thirdparty_services', enabled ? 'yes' : 'no');
			setChangedField('uses_thirdparty_services', enabled ? 'yes' : 'no');
		} else if (service.source.indexOf('social_media')!==-1) {
			updateField('uses_social_media', enabled ? 'yes' : 'no');
			setChangedField('uses_social_media', enabled ? 'yes' : 'no');
		}
		let value;
		if ( field.type==='multicheckbox' ) {
			value = [...field.value];
			if (!Array.isArray(value)) value = [];
			if (enabled) {
				value.push(service.id);
			} else {
				value = value.filter(item => item !== service.id);
			}
		} else {
			value = enabled ? 'yes' : 'no';
		}

		updateField(service.source, value);
		setChangedField(service.source, value);
		saveFields(selectedSubMenuItem, false).then(() => {
			fetchIntegrationsData().then(() => {
				syncServicesWithFields();
			});
		});
	}

	const enabledDisabledPlaceholderSort = (rowA, rowB) => {
		const a = rowA.placeholder;
		const b = rowB.placeholder;
		if (a > b) {
			return 1;
		}
		if (b > a) {
			return -1;
		}
		return 0;
	}

	const enabledDisabledSort = (rowA, rowB) => {
		const a = rowA.enabled;
		const b = rowB.enabled;
		if (a > b) {
			return 1;
		}
		if (b > a) {
			return -1;
		}
		return 0;
	}

	const columns = [
		{
			name: __('Service',"complianz-gdpr"),
			selector: row => row.label,
			sortable: true,
		},
		{
			name: __('Placeholder',"complianz-gdpr"),
			selector: row => row.placeholderControl,
			sortable: true,
			sortFunction: enabledDisabledPlaceholderSort

		},
		{
			name: __('Status',"complianz-gdpr"),
			selector: row => row.enabledControl,
			sortable: true,
			sortFunction: enabledDisabledSort

		},
	];

	//filter the services by search value
	let filteredServices = updatedServices.filter(service => {
		return service.label.toLowerCase().includes(searchValue.toLowerCase());
	})

	//sort the services alphabetically by label
	filteredServices.sort((a, b) => {
		if (a.label < b.label) {
			return -1;
		}
		if (a.label > b.label) {
			return 1;
		}
		return 0;
	});

	filteredServices.forEach(service => {
		let value = getFieldValue(service.source);
		if ( Array.isArray(value) ) {
			service.enabled = value.includes(service.id);
		} else {
			service.enabled = value === 'yes';
		}

		service.enabledControl = <ToggleControl
			checked= { service.enabled }
			onChange={ ( fieldValue ) => onChangeHandler(service, fieldValue) }
		/>
		service.placeholderControl = <ToggleControl
			label={service.placeholder==='none' ? __('N/A',"complianz-gdpr") : __("Placeholder", "complianz-gdpr")}
			disabled = {service.placeholder==='none'}
			checked = { service.placeholder==='enabled' }
			onChange = { ( fieldValue ) => onChangePlaceholderHandler(service, fieldValue) }
		/>
	});

	return (
		<>
			<p>
				{ __( "Enabled services will be blocked on the front-end of your website until the user has given consent (opt-in), or after the user has revoked consent (opt-out). When possible a placeholder is activated. You can also disable or configure the placeholder to your liking.", 'complianz-gdpr' )}
				{ readMore( "https://complianz.io/blocking-recaptcha-manually/" )}
			</p>
			<div className="cmplz-table-header">
				<div className="cmplz-table-header-controls">
					<input type="text" placeholder={__("Search", "complianz-gdpr")} value={searchValue} onChange={ ( e ) => setSearchValue(e.target.value) } />
				</div>
			</div>
			{ (disabled || filteredServices.length===0) && <>
				<div className="cmplz-settings-overlay">
					<div className="cmplz-settings-overlay-message" >{disabledText}
						{disabledReadmore && <>&nbsp;<a href={disabledReadmore} >{__('View services.', 'complianz-gdpr')}</a></>}
					</div>
				</div>
			</>}
			{ (filteredServices.length===0) && <>
				<div className="cmplz-integrations-placeholder">
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</div>
			</>}
			{!disabled && filteredServices.length>0 && DataTable && <>
				<DataTable
					columns={columns}
					data={filteredServices}
					dense
					pagination
					paginationPerPage={5}
					noDataComponent={<div className="cmplz-no-documents">{__("No services", "really-simple-ssl")}</div>}
					persistTableHead
					theme="really-simple-plugins"
					customStyles={customStyles}
				/></>
			}
		</>
	)
}
export default memo(ServicesControl)
