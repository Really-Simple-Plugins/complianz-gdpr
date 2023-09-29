import * as RadioGroupRadix from '@radix-ui/react-radio-group';
import {memo} from "@wordpress/element";

const RadioGroup = ({ label, id, value, onChange, required, defaultValue, disabled, options = {} }) => {
	return (
		<RadioGroupRadix.Root
			disabled={disabled && !Array.isArray(disabled)}
			className="cmplz-input-group cmplz-radio-group"
			value={value}
			aria-label={label}
			onValueChange={onChange}
			required={required}
			default={defaultValue}
		>
			{Object.entries(options).map(([key, optionLabel]) => (
				<div key={key} className={'cmplz-radio-group__item'}>
					<RadioGroupRadix.Item
						disabled={Array.isArray(disabled) && disabled.includes(key) }
						value={key}
						id={id + '_' + key}>
						<RadioGroupRadix.Indicator className={'cmplz-radio-group__indicator'} />
					</RadioGroupRadix.Item>
					<label className="cmplz-radio-label" htmlFor={id + '_' + key}>
						{optionLabel}
					</label>
				</div>
			))}
		</RadioGroupRadix.Root>
	);
};

export default memo(RadioGroup);
