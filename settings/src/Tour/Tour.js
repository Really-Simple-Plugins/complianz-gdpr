import { useContext, useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';
import useLicense from "../Settings/License/LicenseData";
import useFields from "../Settings/Fields/FieldsData";

const onTourEnd = () => {
	//remove 'tour' query variable from url
	const url = new URL(window.location.href);
	url.searchParams.delete('tour');
	window.history.pushState({}, '', url.href);
}

const tourOptions = {
	defaultStepOptions: {
		cancelIcon: {
			enabled: true
		},
		keyboardNavigation: false
	},
	useModalOverlay: false,
	margin: 15,
};

const TourInstance = ({ShepherdTourContext}) => {
	const tour = useContext(ShepherdTourContext);
	tour.on("cancel", onTourEnd);
	useEffect(() => {
		if (tour) tour.start();
	}, [tour]);

	return null;
}

const newSteps = [
	{
		title: __('Welcome to Complianz', 'complianz-gdpr'),
		text: '<p>' +  __('Get ready for privacy legislation around the world. Follow a quick tour or start configuring the plugin!', 'complianz-gdpr') + '</p>',
		classes: 'cmplz-shepherd',
		buttons: [
			{
				type: 'cancel',
				classes: 'button button-default',
				text: __('Configure', 'complianz-gdpr'),
				action () {
					const url = new URL(window.location.href);
					url.searchParams.delete('tour');
					window.location.hash = 'wizard';
				},
			},
			{
				classes: 'button button-primary',
				text: __('Start tour', 'complianz-gdpr'),
				action () {
					window.location.hash = cmplz_settings.is_premium ? 'settings/license' : 'dashboard';
					return this.next()
				},
			}
		]
	},
	{
		title: __( 'Dashboard', 'complianz-gdpr' ),
		text: '<p>' + __( 'This is your Dashboard. When the Wizard is completed, this will give you an overview of tasks, tools, and documentation.', 'complianz-gdpr' ) + '</p>',
		classes: 'cmplz-shepherd',
		buttons: [
			{
				classes: 'button button-default',
				text: __('Previous', 'complianz-gdpr'),
				action () {
					window.location.hash = cmplz_settings.is_premium ? 'settings/license' : 'dashboard';
					return this.back()
				},
			},
			{
				classes: 'button button-primary',
				text: __('Next', 'complianz-gdpr'),
				action () {
					window.location.hash = 'wizard/consent';
					return this.next()
				},
			}
		],
	},
	{
		title: __( 'The Wizard', 'complianz-gdpr' ),
		text: '<p>' +  __( 'This is where everything regarding cookies is configured. We will come back to the Wizard soon.', 'complianz-gdpr' ) + '</p>',
		classes: 'cmplz-shepherd',
		buttons: [
			{
				classes: 'button button-default',
				text: __('Previous', 'complianz-gdpr'),
				action () {
					window.location.hash = 'dashboard';
					return this.back()
				},
			},
			{
				classes: 'button button-primary',
				text: __('Next', 'complianz-gdpr'),
				action () {
					window.location.hash = 'banner';
					return this.next()
				},
			}
		],
		// attachTo: { element: '.cmplz-cookie-scan', on: 'auto' },
	},
	{
		title: __( 'Consent Banner', 'complianz-gdpr' ),
		text: '<p>' + __( 'Here you can configure and style your consent banner if the Wizard is completed. An extra tab will be added with region-specific settings.', 'complianz-gdpr' ) + '</p>',
		classes: 'cmplz-shepherd',
		buttons: [
			{
				classes: 'button button-default',
				text: __('Previous', 'complianz-gdpr'),
				action () {
					window.location.hash = 'wizard/consent';
					return this.back()
				},
			},
			{
				classes: 'button button-primary',
				text: __('Next', 'complianz-gdpr'),
				action () {
					window.location.hash = 'integrations';
					return this.next()
				},
			}
		],
	},
	{
		title: __( 'Integrations', 'complianz-gdpr' ),
		text: '<p>' + __( 'Based on your answers in the Wizard, we will automatically enable integrations with relevant services and plugins. In case you want to block extra scripts, you can add them to the Script Center.', 'complianz-gdpr' ) + '</p>',
		classes: 'cmplz-shepherd',
		buttons: [
			{
				classes: 'button button-default',
				text: __('Previous', 'complianz-gdpr'),
				action () {
					window.location.hash = 'banner';
					return this.back()
				},
			},
			{
				classes: 'button button-primary',
				text: __('Next', 'complianz-gdpr'),
				action () {
					window.location.hash = 'tools/proof-of-consent';
					return this.next()
				},
			}
		],
	},
	{
		title: __( 'Proof of Consent', 'complianz-gdpr' ),
		text: '<p>' + __( "Complianz tracks changes in your Cookie Notice and Cookie Policy with time-stamped documents. This is your consent registration while respecting the data minimization guidelines and won't store any user data.", 'complianz-gdpr' ) + '</p>',
		classes: 'cmplz-shepherd',
		buttons: [
			{
				classes: 'button button-default',
				text: __('Previous', 'complianz-gdpr'),
				action () {
					window.location.hash = 'integrations';
					return this.back()
				},
			},
			{
				classes: 'button button-primary',
				text: __('Next', 'complianz-gdpr'),
				action () {
					window.location.hash = 'wizard/visitors';
					return this.next()
				},
			}
		],
		// attachTo: { element: '.cmplz-field-button', on: 'auto' },
	},
	{
		title: __( "Let's start the Wizard", 'complianz-gdpr' ),
		text: '<p>' + __( 'You are ready to start the Wizard. For more information, FAQ, and support, please visit Complianz.io.', 'complianz-gdpr' ) + '</p>',
		classes: 'cmplz-shepherd',
		buttons: [
			{
				classes: 'button button-default',
				text: __('Previous', 'complianz-gdpr'),
				action () {
					window.location.hash = 'tools/proof-of-consent';
					return this.back()
				},
			},
			{
				type: 'cancel',
				classes: 'button button-primary',
				text: __('End tour', 'complianz-gdpr'),
			},
		]
	},
];

const Tour = () => {
	const {licenseStatus} = useLicense();
	const [ShepherdTour, setShepherdTour] = useState(null);
	const [ShepherdTourContext, setShepherdTourContext] = useState(null);
	const {fieldsLoaded} = useFields();


	useEffect( () => {
		if (!fieldsLoaded) return;

		//import ShepherdTour and ShepherdTourContext from 'react-shepherd' and set them to the state with setShepherdTour and setShepherdTourContext
		import('react-shepherd').then( ( { ShepherdTour, ShepherdTourContext } ) => {
			setShepherdTour( () => ShepherdTour );
			setShepherdTourContext(() => ShepherdTourContext);
		});

		if ( cmplz_settings.is_premium ) {
			let licenseText;
			if ( licenseStatus === 'valid' ) {
				licenseText = __( "Great, your license is activated and valid!", 'complianz-gdpr' );
			} else {
				licenseText = __( "To unlock the wizard and future updates, please enter and activate your license.", 'complianz-gdpr' );
			}
			const additionalStep = {
				title: __('Activate your license', 'complianz-gdpr'),
				text: '<p>' +  licenseText + '</p>',
				classes: 'cmplz-shepherd',
				buttons: [
					{
						classes: 'button button-default',
						text: __('Previous', 'complianz-gdpr'),
						action () {
							window.location.hash = 'dashboard';
							return this.back()
						},
					},
					{
						classes: 'button button-primary',
						text: __('Next', 'complianz-gdpr'),
						action () {
							window.location.hash = 'dashboard';
							return this.next()
						},
					}
				],
				// attachTo: { element: '.cmplz-license', on: 'auto' },
			};
			//insert additionalStep after the first step
			newSteps.splice(1, 0, additionalStep);
		}

	},[fieldsLoaded] );


	if ( !ShepherdTour || !ShepherdTourContext ) {
		return null;
	}

	return (

		<ShepherdTour steps={newSteps} tourOptions={tourOptions} >
			<TourInstance ShepherdTourContext={ShepherdTourContext}/>
		</ShepherdTour>
	);
}

export default Tour;
