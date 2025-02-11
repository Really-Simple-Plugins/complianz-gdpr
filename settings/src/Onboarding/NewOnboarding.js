import { useEffect, useState } from "@wordpress/element";
import useFields from "../Settings/Fields/FieldsData";
import { useNewOnboardingData, steps } from "./NewOnboardingData"
import { OnboardingModalClose } from "./components/Close"
import ModalTitle from './components/ModalTitle';
import OnboardingButton from './components/Button';
import Welcome from './steps/Welcome';
import Terms from './steps/Terms';
import Newsletter from './steps/Newsletter';
import Plugins from './steps/Plugins';
import ThankYou from './steps/ThankYou';
import "./NewOnboarding.scss";


const NewOnboarding = () => {

	const {
		isModalOpen,
		currentStep,
		closeModal
	} = useNewOnboardingData();

	const {
		fieldsLoaded,
		getFieldValue,
	} = useFields();

	const [showModal, setShowModal] = useState(false);

	// This useEffect checks if the wscClientId and wscClientSecret exist.
	// If they don't exist, it shows the modal. Otherwise, it closes/reset the modal.
	useEffect(() => {
		if (!fieldsLoaded) return;

		const wscClientId = getFieldValue('cmplz_wsc_client_id');
		const wscClientSecret = getFieldValue('cmplz_wsc_client_secret');

		// If the url contains cmplz_force_signup, it will force the modal to show.
		const isForcedModal = window.location.href.indexOf('cmplz_force_signup') !== -1;

		if (!wscClientId || !wscClientSecret) {
			setShowModal(true);
		} else if (isForcedModal) {
			setShowModal(true);
		} else {
			// closeModal change the state and remove websitescan from url
			closeModal();
		}
	}, [fieldsLoaded, closeModal, getFieldValue, setShowModal]);

	if (!isModalOpen || !showModal) return null;

	const { title } = steps[currentStep];

	return (
		<>
			<div className="cmplz-modal-backdrop">&nbsp;</div>
			<div className="cmplz-modal cmplz-websitescan">
				<div className="cmplz-modal-header">
					<div className="cmplz-modal-header-branding">
						<ModalTitle title={title} />
						<OnboardingModalClose />
					</div>
				</div>

				<div className="cmplz-modal-content">
					<>
						{currentStep === 'welcome' && <Welcome />}
						{currentStep === 'terms' && <Terms />}
						{currentStep === 'newsletter' && <Newsletter />}
						{currentStep === 'plugins' && <Plugins />}
						{currentStep === 'thankYou' && <ThankYou />}
					</>
				</div>

				<div className="cmplz-modal-footer">
					{currentStep !== 'thankYou' && <OnboardingButton type="prev" />}
					<OnboardingButton type="next" />
				</div>
			</div>
		</>
	)
}

export default NewOnboarding;

