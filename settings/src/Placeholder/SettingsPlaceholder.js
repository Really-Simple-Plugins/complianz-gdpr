import Placeholder from "./Placeholder";

/**
 * Menu block, rendering the entire menu
 */
const SettingsPlaceholder = () => {
	return(
		<div className="cmplz-wizard-settings cmplz-column-2">
			<div className="cmplz-grid-item">
				<div className="cmplz-grid-item-content">
					<div className="cmplz-settings-block-intro"><Placeholder lines="3"></Placeholder></div>
				</div>
			</div>
			<div className="cmplz-grid-item-footer"></div>
		</div>
	)
}

export default SettingsPlaceholder;
