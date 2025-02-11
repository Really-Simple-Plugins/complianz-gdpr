import { useEffect, useState } from "@wordpress/element";
import Icon from "../../utils/Icon";
import { useNewOnboardingData } from "../NewOnboardingData";
// status: not-installed / activated / installed

const statuses = {
	'not-installed': {
		label: 'Check to Install!',
		iconColor: 'blue',
		iconName: 'info',
		checked: false,
	},
	'installed': {
		label: 'Check to Activate!',
		iconColor: 'orange',
		iconName: 'info',
		checked: false,
	},
	'activated': {
		label: 'Installed!',
		iconColor: 'green',
		iconName: 'circle-check',
		checked: true,
	},
	'processing': {
		label: 'Processing ...',
		iconColor: 'grey',
		iconName: 'loading',
		checked: true,
	}
}

const CheckBox = ({ plugin, className = '', handleChange, ...otherProps }) => {

	const { isInstalling } = useNewOnboardingData();

	const handleCheckboxChange = (e) => {
		handleChange(plugin, e.target.checked);
	};

	return (
		<div className={`cmplz-websitescan-input-wrapper plugin-checkbox ${plugin.slug}`}>
			<label>
				<input
					className={`${className} cmplz-websitescan-input`}
					checked={plugin.checked}
					disabled={isInstalling ? true : statuses[plugin.status].checked}
					onChange={handleCheckboxChange}
					type="checkbox"
					{...otherProps}
				/>
				<span className="checkmark"></span>
				<span className="description">{plugin.description}</span>
			</label>
			{plugin.status &&
				<Icon
					name={statuses[plugin.status]?.iconName}
					color={statuses[plugin.status]?.iconColor}
					size={14}
					tooltip={statuses[plugin.status]?.label}
				/>
			}
		</div>
	)
}

export default CheckBox
