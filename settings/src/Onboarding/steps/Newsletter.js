import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import { useNewOnboardingData } from "../NewOnboardingData";
import DOMPurify from "dompurify";
import OnboardingInput from '../components/Input';
import Placeholder from '../../Placeholder/Placeholder';
import OnboardingError from "../components/OnboardingError";

const Newsletter = () => {
	const {
		wscEmail,
		newsletterEmail,
		setNewsletterEmail,
		emailError,
		setEmailError,
		enableWsc,
		isValidEmail,
		setNextStepDisabled,
		currentStep,
		newsletterTerms,
		isContentLoading,
		setIsContentLoading,
		fetchError,
		fetchDoc
	} = useNewOnboardingData();

	useEffect(() => {
		setNewsletterEmail(wscEmail)
	}, [wscEmail]);

	useEffect(() => {
		const loadTerms = async () => {
			try {
				setIsContentLoading(true);
				await fetchDoc();
			} catch (error) {
				console.log(error);

			} finally {
				setIsContentLoading(false);
			}
		}
		loadTerms();
	}, [fetchDoc]);

	const sanitized = DOMPurify.sanitize(newsletterTerms, { ADD_ATTR: ['target'] });

	const handleEmailChange = (e) => {
		const email = e.target.value;
		setNewsletterEmail(email);
		const isValid = isValidEmail(email);
		setEmailError(isValid ? '' : __('Please enter a valid email address.', 'complianz-gdpr'));
		setNextStepDisabled(!isValid);
	};

	return (
		<div className={`cmplz-modal-content-step ${currentStep}`}>
			{isContentLoading ? <Placeholder /> :
				<>
					<p>{__("We want you to get the most out of Complianz. So over the next week we'll be sending eight tips and tricks to your inbox - be sure to keep a lookout.", "complianz-gdpr")}</p>
					{fetchError &&
						<OnboardingError>
							<button className="button button-default" onClick={fetchDoc}>{__('Try again!', 'complianz-gdpr')}</button>
						</OnboardingError>
					}
					{sanitized &&
						<>
							<div className='wrap-terms' dangerouslySetInnerHTML={{ __html: sanitized }}></div>
							{!enableWsc &&
								<OnboardingInput
									type="email"
									onChange={handleEmailChange}
									value={newsletterEmail}
									placeholder={__("Your email address", "complianz-gdpr")}
									error={emailError}
								/>
							}
						</>
					}
				</>
			}
		</div>
	);
};

export default Newsletter;
