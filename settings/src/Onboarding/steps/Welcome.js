import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import { useNewOnboardingData } from "../NewOnboardingData";
import OnboardingInput from '../components/Input';
import useFields from '../../Settings/Fields/FieldsData';
import Placeholder from '../../Placeholder/Placeholder';
const Welcome = () => {
	const {
		wscEmail,
		setWscEmail,
		emailError,
		setEmailError,
		setNextStepDisabled,
		setEnableWsc,
		isValidEmail,
		currentStep,
		isContentLoading,
		setIsContentLoading,
	} = useNewOnboardingData();

	const { fields, fieldsLoaded } = useFields();

	useEffect(() => {
		try {
			setIsContentLoading(true);
			if (fields) {
				const { default: defaultEmail } = fields.find((f) => f.id === "cmplz_wsc_email");
				setWscEmail(defaultEmail);
			} else {
				console.log('Fields not loaded');
			}
		} catch (error) {
			console.log(error);
		} finally {
			setIsContentLoading(false);
		}

	}, [fields, fieldsLoaded]);


	const handleEmailChange = (e) => {
		const email = e.target.value;
		setWscEmail(email);
		const isValid = isValidEmail(email);
		setEmailError(isValid ? '' : __('Please enter a valid email address.', 'complianz-gdpr'));
		setNextStepDisabled(!isValid);
		// if email.length !== 0 && isValid
		setEnableWsc(email.length !== 0 ? isValid : false);
	};

	return (
		<div className={`cmplz-modal-content-step ${currentStep}`}>
			{
				isContentLoading ? <Placeholder /> : <>
					<p>{__("In the latest release of Complianz, we introduce our newest Website Scan. This scan will not only retrieve services and cookies but also help you configure our plugin and keep you up-to-date if changes are made that might need legal changes.", "complianz-gdpr")}</p>
					<p>{__("To use our newest Website Scan we need to verify your website and confirm your access by email. Register below and get the latest from Complianz!", "complianz-gdpr")}</p>
					<OnboardingInput
						type="email"
						value={wscEmail}
						onChange={handleEmailChange}
						placeholder={__("Your email address", "complianz-gdpr")}
						error={emailError}
					/>
				</>
			}
		</div>
	)
}

export default Welcome;
