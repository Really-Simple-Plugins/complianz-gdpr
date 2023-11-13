import {memo} from "@wordpress/element";
import * as Select from '@radix-ui/react-select';
import Icon from '../../utils/Icon';
import { __ } from '@wordpress/i18n';

const SelectInput = ({
	value = false,
	onChange,
	required,
	defaultValue,
	disabled,
	options = {},
	canBeEmpty = true,
	label,
}) => {
	// convert options to object if array
	if (Array.isArray(options)) {
		let newOptions = {};
		options.map((option) => {
			newOptions[option.value] = option.label;
		});
		options = newOptions;
	}
	// add empty option
	if ( canBeEmpty ) {
		//only add this if no value is selected yet.
		let valueIsEmpty = value === '' || value === false || value === 0;

		if (valueIsEmpty) {
			value = '0';
			options = {
				0: __('Select an option', 'complianz-gdpr'),
				...options,
			};
		}
	} else {
		// set first option as default
		if (!value) {
			value = Object.keys(options)[0];
		}
	}
	return (
		<div className="cmplz-input-group cmplz-select-group" key={label}>
			<Select.Root
				//ref={innerRef}
				value={value}
				defaultValue={defaultValue}
				onValueChange={onChange}
				required={required}
				disabled={disabled && !Array.isArray(disabled)}
			>
				<Select.Trigger className="cmplz-select-group__trigger">
					<Select.Value/>
					<Icon name={'chevron-down'}/>
				</Select.Trigger>
					<Select.Content
						className="cmplz-select-group__content"
						position="popper"
					>
						<Select.ScrollUpButton
							className="cmplz-select-group__scroll-button">
							<Icon name={'chevron-up'}/>
						</Select.ScrollUpButton>
						<Select.Viewport className="cmplz-select-group__viewport">
							<Select.Group>
								{Object.entries(options).map(([optionValue, optionText]) => (
									<Select.Item
										disabled={Array.isArray(disabled) && disabled.includes(optionValue) }
										className={'cmplz-select-group__item'}
										key={optionValue}
										value={optionValue}>
										<Select.ItemText>{optionText}</Select.ItemText>
									</Select.Item>
								))}
							</Select.Group>
						</Select.Viewport>
						<Select.ScrollDownButton
							className="cmplz-select-group__scroll-button">
							<Icon name={'chevron-down'}/>
						</Select.ScrollDownButton>
					</Select.Content>
			</Select.Root>
		</div>
	);
};

export default memo(SelectInput);
