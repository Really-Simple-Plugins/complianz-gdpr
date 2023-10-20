import {TextareaControl,} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import * as cmplz_api from "../utils/api";
import {useState} from "@wordpress/element";
import {memo} from "@wordpress/element";

const Support = () => {
	const [message, setMessage] = useState('');
	const [sending, setSending] = useState(false);

	const onChangeHandler = (message) => {
		setMessage(message);
	}

	const onClickHandler = () => {
		setSending(true);
		let data = {};
		return cmplz_api.doAction('supportData', data).then( ( response ) => {
			let encodedMessage = message.replace(/(?:\r\n|\r|\n)/g, '--br--');
			let url = 'https://complianz.io/support'
				+ '?user=' + encodeURIComponent(response.customer_name)
				+ '&email=' + response.email
				+ '&website=' + response.domain
				+ '&license=' + encodeURIComponent(response.license_key)
				+ '&question=' + encodeURIComponent(encodedMessage)
				+ '&details=' + encodeURIComponent(response.system_status);
			window.location.assign(url);
		});
	}

	let disabled = sending || message.length===0;
	return (
		<>
			<TextareaControl
				disabled={sending}
				placeholder={__("Type your question here","complianz-gdpr")}
				onChange={ ( message ) => onChangeHandler(message) }
			/>
			<div>
				<button className="button button-primary"
					disabled={disabled}
					variant="secondary"
					onClick={ ( e ) => onClickHandler(e) }>
					{ __( 'Send', 'complianz-gdpr' ) }
				</button>
			</div>
		</>
	);
}
export default memo(Support);
