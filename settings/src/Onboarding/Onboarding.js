import {useState, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import InstallPlugin from "./InstallPlugin";
import useOnboardingData from "./OnboardingData";
import Icon from "../utils/Icon";
import useFields from "../Settings/Fields/FieldsData";
const Onboarding = () => {
	const { email, setEmail, setIncludeTips, includeTips, sendTestEmail, saveEmail, setSendTestEmail, plugins, loaded, isUpgrade, processing, dismissModal, modalVisible, getRecommendedPluginsStatus} = useOnboardingData();
	const [modalStep, setModalStep] = useState(0);
	const {updateField} = useFields();

	const [waiting, setWaiting] = useState(true);
	const [nextDisabled, setNextDisabled] = useState(true);
	const startTour = (e) => {
		e.preventDefault();
		window.location.href = window.location.href.replace('onboarding', 'tour');
	}
	const steps = [
		'plugins',
		'email',
	];

	const isValidEmail = (email) => {
		if (email.length===0) return true;

		const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return regex.test(email);
	}

	const goToWizard = async (e) => {
		e.preventDefault();
		await saveEmail();

		if ( isValidEmail(email) && email.length>0 ){
			updateField('notifications_email_address', email);
			updateField('send_notifications_email', true);
		}
		dismissModal();
		window.location.hash = '#wizard';
	}

	useEffect ( () => {
		if (!loaded) {
			getRecommendedPluginsStatus();
		}
	},[loaded]);

	useEffect ( () => {
		if (steps[modalStep] === 'plugins') {
			setNextDisabled(true);
			if (!waiting) {
				setNextDisabled(false);
			}
		}
		if (steps[modalStep] === 'email') {
			setNextDisabled(true);
			if ( isValidEmail(email) ) {
				setNextDisabled(false);
			}
		}

	},[email, modalStep, waiting]);

	useEffect ( () => {
		//set an interval, to set waiting to false after 1 second.
		const interval = setInterval(() => {
			setWaiting(false);
		}, 2000);
		return () => clearInterval(interval);
	},[]);

	if (!modalVisible) {
		return null;
	}

	let emailClass = isValidEmail(email) ? 'cmplz-valid' : 'cmplz-invalid';
	let processingClass = steps[modalStep] === 'email' && processing ? 'cmplz-processing' : '';
	return (
		<>
			<div className="cmplz-modal-backdrop">&nbsp;</div>
			<div className="cmplz-modal cmplz-onboarding">
				<div className="cmplz-modal-header">
					<div className="cmplz-modal-header-branding">
						<img className="cmplz-header-logo" src={cmplz_settings.plugin_url + 'assets/images/cmplz-logo.svg'} alt="Complianz logo"/>
						<button type="button" className="cmplz-modal-close" data-dismiss="modal" aria-label="Close" onClick={() => dismissModal() }>
							<svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" height="24" >
								<path fill="#000000" d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/>
							</svg>
						</button>
					</div>
					{steps[modalStep] === 'plugins' && <p>{__("Take a quick tour to familiarize yourself with Complianz, or discover on your own pace. If you have any questions, let us know, but for now: ","complianz-gdpr")}
						&nbsp;<a href="https://complianz.io/meet-complianz-7/ref/76/?campaign=onboarding-zero" target="_blank" rel="noopener noreferrer">{__("Meet Complianz 7.0","complianz-gdpr")}</a>
					</p>}
					{steps[modalStep] === 'email' && <p>{__("We use email notifications to explain important updates in your plugin settings. Add your email address below.","complianz-gdpr")}</p>}
				</div>

				<div className={"cmplz-modal-content "+processingClass}>
					{steps[modalStep] === 'plugins' && <>

						{plugins.map((plugin, i) =>
							<InstallPlugin key={i} plugin={plugin} processing={processing}/>)}

						<div className="cmplz-onboarding-item">
							<Icon name={waiting ? 'loading' : 'circle-check'} color={waiting ? 'grey' : 'green'} size={14} />
							{ ( waiting || !loaded )  && __("Upgrading", "complianz-gdpr") }
							{ !waiting && loaded && <>
								{ isUpgrade && __("Thanks for updating!", "complianz-gdpr")}
								{ !isUpgrade && __("Thanks for installing!", "complianz-gdpr")}
							</>}
						</div>
					</>}

					{steps[modalStep] === 'email' && <>
						<div>
							<input type="email"  className={emailClass} value={email} placeholder={__("Your email address", "complianz-gdpr")} onChange={(e) => setEmail(e.target.value)} />
						</div><div>
						<label><input onChange={ (e) => setIncludeTips(e.target.checked)} type="checkbox" checked={includeTips} />{__("Include 8 Tips & Tricks to get started with Complianz GDPR.","complianz-gdpr")}&nbsp;<a href="https://complianz.io/legal/privacy-statement/" target="_blank" rel="noopener noreferrer">{__("Privacy Statement", "complianz-gdpr")}</a></label>
					</div><div>
						<label><input onChange={ (e) => setSendTestEmail(e.target.checked)} type="checkbox" checked={sendTestEmail} />{__("Send a notification test email - Notification emails are sent from your server.","complianz-gdpr")}</label>
					</div>
					</>}
				</div>

				<div className="cmplz-modal-footer">
					{modalStep>0 && <a href="#" onClick={(e) => setModalStep(modalStep-1)}>{__("Previous","complianz-gdpr")}</a>}
					<button type="button" className="button button-default" onClick={() => dismissModal() }>{__("Dismiss","complianz-gdpr")}</button>
					{modalStep<(steps.length-1) && <button disabled={nextDisabled} className="button button-primary" onClick={(e)=> setModalStep(modalStep+1)}>{__("Next", "complianz-gdpr")}</button>}
					{modalStep===(steps.length-1) && <a disabled={nextDisabled} href="#" onClick={(e) => goToWizard(e)} className="button button-primary" >{__("Start wizard", "complianz-gdpr")}</a>}
					{modalStep===(steps.length-1) && <a href="#" onClick={(e) => startTour(e)}>{__("Take a tour","complianz-gdpr")}</a>}
				</div>
			</div>
		</>
	)
}
export default Onboarding;
