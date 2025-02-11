import { useNewOnboardingData, steps } from "../NewOnboardingData";
import { memo, useEffect, useState } from "@wordpress/element";
import Icon from "../../utils/Icon";

const OnboardingButton = ({ type, ...otherProps }) => {
	const {
		goToNextStep,
		goToPrevStep,
		nextStepDisabled,
		prevStepDisabled,
		enablePluginInstallation,
		isInstalling,
		isLoading,
		currentStep
	} = useNewOnboardingData();

	const [prevButtonLabel, setPrevButtonLabel] = useState(steps[currentStep].prevButton);
	const [nextButtonLabel, setNextButtonLabel] = useState(steps[currentStep].nextButton);
	const [buttonDisabledStatus, setButtonDisabledStatus] = useState(false);

	useEffect(() => {
		if (currentStep === 'plugins') {
			if (isInstalling) {
				setNextButtonLabel(steps[currentStep].nextButtonThird); // installing ...
			} else if (enablePluginInstallation) {
				setNextButtonLabel(steps[currentStep].nextButtonSecondary); // install
			} else {
				setNextButtonLabel(steps[currentStep].nextButton); // Continue
			}
		} else {
			setNextButtonLabel(steps[currentStep].nextButton);
		}
	}, [currentStep, enablePluginInstallation, isInstalling]);

	// disable button on step processing or when input fields are not valid
	useEffect(() => {
		setButtonDisabledStatus(
			isLoading ||
			isInstalling ||
			prevStepDisabled ||
			nextStepDisabled
		);
	}, [
		isLoading,
		isInstalling,
		prevStepDisabled,
		nextStepDisabled
	]);


	const handleClick = () => {
		if (type === 'next') {
			goToNextStep();
		} else if (type === 'prev') {
			goToPrevStep();
		}
	}

	return (
		<div className={`cmplz-modal-footer-btn-wrap cmplz-btn-${type}`}>
			{(isInstalling || isLoading) && type === 'next' && <Icon name="loading" color={type === 'next' ? 'white' : 'blue'} size={14} />}
			<button
				type="button"
				onClick={handleClick}
				className={`button ${type === 'prev' ? 'button-default' : 'button-primary'}`}
				disabled={buttonDisabledStatus}
				{...otherProps}
			>
				<span>
					{type === 'next' ? nextButtonLabel : prevButtonLabel}
				</span>
			</button>
		</div>
	)
}

export default memo(OnboardingButton)
