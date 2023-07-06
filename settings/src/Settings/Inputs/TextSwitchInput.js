import TextInput from './TextInput';
import SwitchInput from './SwitchInput';
import {__} from '@wordpress/i18n';
import {memo, useEffect, useState} from 'react';

const TextSwitchInput = ({
	label,
	value,
	onChange,
	placeholder = ''
}) => {
	const [textDisabled, setTextDisabled] = useState(false);

	useEffect(() => {
		if (value['show']) {
			setTextDisabled(false);
		} else {
			setTextDisabled(true);
		}
	}, [value]);

	const onTextChange = (text) => {
		let newValue = {...value};
		newValue['text'] = text;
		onChange(newValue);
	}

	const onSwitchHandler = (switched) => {
		let newValue = {...value};
		newValue['show'] = switched;
		onChange(newValue);
	}

	return (
		<div className="cmplz-text-checkbox-input">
			<TextInput
				value={value['text']}
				onChange={onTextChange}
				placeholder={placeholder}
				disabled={textDisabled}
				/>
			<SwitchInput
				label={__('Show', 'complianz-gdpr')}
				value={value['show']}
				onChange={onSwitchHandler}
				/>
		</div>
	);
}

export default memo(TextSwitchInput);
