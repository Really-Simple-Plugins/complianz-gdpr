import { useNewOnboardingData } from "../NewOnboardingData"

const OnboardingError = ({ children }) => {
	const { fetchErrorMessage } = useNewOnboardingData();
	return (
		<div className="cmplz-onboarding__error">
			<p>{fetchErrorMessage}</p>
			<div>{children}</div>
		</div>
	)
}
export default OnboardingError
