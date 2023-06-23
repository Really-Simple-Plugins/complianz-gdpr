// import Icon from "../utils/Icon";
import { __ } from '@wordpress/i18n';
import useFields from "./Fields/FieldsData";
import * as cmplz_api from "../utils/api";
import {useEffect, useState} from "@wordpress/element";
import {memo} from "react";

/**
 * Render a help notice in the sidebar
 */
const FinishControl = () => {
	const {fields, updateField, setChangedField, updateFieldsData, addHelpNotice, fetchAllFieldsCompleted, allRequiredFieldsCompleted, notCompletedRequiredFields} = useFields();
	const [cookiebannerRequired, setCookiebannerRequired] = useState(true);

	useEffect ( () => {
			let data = {};
			cmplz_api.doAction( 'get_cookiebanner_required', data).then( ( response ) => {
				setCookiebannerRequired(response.required);
			});
	},[fields]);

	useEffect ( () => {
		updateField('cookie_banner_required', cookiebannerRequired);
		setChangedField('cookie_banner_required', cookiebannerRequired);
		updateFieldsData();
	}, [cookiebannerRequired])

	useEffect ( () => {
		if ( cookiebannerRequired ) {
			let explanation =
				__( "The cookie banner and cookie blocker are required on your website.","complianz-gdpr")
				+" "+__( "You can enable them both here, then you should check your website if your configuration is working properly.","complianz-gdpr")
				+" "+__("Please read the below article to debug any issues while in safe mode. Safe mode is available under settings.","complianz-gdpr")
				+' '+__("You will find tips and tricks on your dashboard after you have configured your cookie banner.", 'complianz-gdpr' )
			addHelpNotice('last-step-feedback', 'default', explanation, __('A consent banner is required', 'complianz-gdpr'), 'https://complianz.io/debugging-manual');
		} else {
			let explanation = __( "Your site does not require a cookie banner. If you think you need a cookie banner, please review your wizard settings.","complianz-gdpr")
			addHelpNotice('last-step-feedback', 'warning', explanation, __('A consent banner is not required', 'complianz-gdpr'));
		}
	},[ cookiebannerRequired ]);

	useEffect ( () => {
		fetchAllFieldsCompleted();
	}, [fields ]);

	return (
		<>
			{ allRequiredFieldsCompleted && <div>{__( "Click '%s' to complete the configuration. You can come back to change your configuration at any time.", 'complianz-gdpr' ).replace('%s', __( "Finish", 'complianz-gdpr' ) ) }</div>}
			{ !allRequiredFieldsCompleted &&
				<div>
					{ cookiebannerRequired && <p>{
						__( "The cookie banner and the cookie blocker are now ready to be enabled.", "complianz-gdpr") + ' ' +
						__( "Please check your website after finishing the wizard to verify that your configuration is working properly.", "complianz-gdpr")
					}
					</p> }
					<p>
						{__("Not all required fields are completed yet.", "complianz-gdpr")
						+ " " +
						__("Please check the wizard to complete all required questions.", 'complianz-gdpr')}
					</p>
					<p>{__("The following required fields have not been completed:", 'complianz-gdpr' )}</p>
					<ul>
						{notCompletedRequiredFields.map( (field, i) => <li key={i}>{field.parent_label ? field.parent_label : field.label}</li>)}
					</ul>
				</div>
			}

		</>

	);

}

export default memo(FinishControl)
