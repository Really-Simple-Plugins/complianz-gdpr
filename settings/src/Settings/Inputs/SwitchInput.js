import {memo} from "@wordpress/element";
import * as Switch from '@radix-ui/react-switch';

const SwitchInput = ({
	value,
	onChange,
	required,
	disabled,
	className,
	label,
}) => {
	let val = value;
	//if value is "0" or "1", convert to boolean
	//cookiebanner values can be "0" or "1", because of the way they're loaded,
	// but the switch needs a boolean
	if (value === '0' || value === '1') {
		val = value === '1';
	}

	return (
		<div className={'cmplz-input-group cmplz-switch-group'}>
			<Switch.Root
				className={'cmplz-switch-root ' + className}
				checked={val}
				onCheckedChange={onChange}
				disabled={disabled}
				required={required}
			>
				<Switch.Thumb className="cmplz-switch-thumb"/>
			</Switch.Root>
			{/*{label && <label className="cmplz-switch-label">{label}</label>}*/}
		</div>
	);
};

export default memo(SwitchInput);
