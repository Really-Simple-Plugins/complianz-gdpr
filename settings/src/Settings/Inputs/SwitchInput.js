import {memo} from 'react';
import * as Switch from '@radix-ui/react-switch';
import './SwitchInput.scss';

const SwitchInput = ({
	value,
	onChange,
	required,
	disabled,
}) => {
	let val = value;
	//if value is "0" or "1", convert to boolean
	//cookiebanner values can be "0" or "1", because of the way they're loaded, but the switch needs a boolean
	if (value === "0" || value === "1") {
		val = value === "1";
	}

	return (
		<Switch.Root
			className="cmplz-switch-root"
			checked={val}
			onCheckedChange={onChange}
			disabled={disabled}
			required={required}
		>
			<Switch.Thumb className="cmplz-switch-thumb" />
		</Switch.Root>
	);
};

export default memo(SwitchInput);
