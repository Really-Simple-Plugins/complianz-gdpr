import {memo} from "@wordpress/element";
import * as Switch from '@radix-ui/react-switch';
import useFields from "../Fields/FieldsData";

const SwitchInput = ({
	value,
	onChange,
	required,
	disabled,
	className,
	label,
	id,
}) => {
	const {getField} = useFields();

	let val = value;
	//if value is "0" or "1", convert to boolean
	//cookiebanner values can be "0" or "1", because of the way they're loaded,
	// but the switch needs a boolean
	if ( value === '0' || value === '1') {
		val = value === '1';
	}
	const onChangeHandler = (value) => {
		//if this is a banner setting, prevent a 'false' value, because it would trigger a default to be set on the false value
		//non banner checkbox fields are handles with the never_saved property.
		let field = getField(id);
		if ( field.data_target==='banner' ) {
			value = value ? '1' : '0';
		}
		onChange(value)
	}

	return (
		<div className={'cmplz-input-group cmplz-switch-group'}>
			<Switch.Root
				className={'cmplz-switch-root ' + className}
				checked={val}
				onCheckedChange={onChangeHandler}
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
