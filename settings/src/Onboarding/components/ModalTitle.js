import { useNewOnboardingData } from "../NewOnboardingData";
import Placeholder from "../../Placeholder/Placeholder";

const ModalTitle = ({ title }) => {
	const { isContentLoading } = useNewOnboardingData();
	return (
		<div className="cmplz-modal-header-branding-title">
			{isContentLoading ? <Placeholder lines="1" /> :
				<p className="cmplz-h4">
					{title}
				</p>
			}
		</div>
	)
}
export default ModalTitle
