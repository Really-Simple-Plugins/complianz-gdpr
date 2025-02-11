import { useEffect } from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import { useNewOnboardingData } from "../NewOnboardingData";
import DOMPurify from "dompurify";
import Placeholder from "../../Placeholder/Placeholder";
import OnboardingError from "../components/OnboardingError";

const Terms = () => {
	const {
		currentStep,
		wscTerms,
		isContentLoading,
		setIsContentLoading,
		fetchError,
		fetchDoc
	} = useNewOnboardingData();

	useEffect(() => {
		const loadingTerms = async () => {
			try {
				setIsContentLoading(true);
				await fetchDoc();
			} catch (error) {
				console.log(error);
			} finally {
				setIsContentLoading(false);
			}
		}
		loadingTerms();
	}, [fetchDoc]);

	const sanitized = DOMPurify.sanitize(wscTerms, { ADD_ATTR: ['target'] });

	return (
		<div className={`cmplz-modal-content-step ${currentStep}`}>
			{isContentLoading ?
				<Placeholder />
				:
				<>
					<p>{__("Great! You're a few minutes away from getting started with the Website Scan. You just need to look over the Terms and Conditions. If you agree, please continue.", "complianz-gdpr")}</p>
					{fetchError &&
						<OnboardingError>
							<button className="button button-default" onClick={fetchDoc}>{__('Try again!', 'complianz-gdpr')}</button>
						</OnboardingError>
					}
					{sanitized && <div className='wrap-terms' dangerouslySetInnerHTML={{ __html: sanitized }}></div>}
				</>
			}
		</div>
	);
};

export default Terms;
