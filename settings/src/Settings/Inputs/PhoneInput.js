import {memo, useEffect, useState} from "@wordpress/element";

const PhoneInput = ({
	value,
	onChange,
	onError,
	required,
	disabled,
	id,
	name,
}) => {
	const inputId = id || name;
	const [inputValue, setInputValue] = useState('');

	const isValidPhone = (string) => {
		var res = string.match(/^\+?[\d\-\(\)\.\s]*$/);
		return (res !== null)
	};

	//ensure that the initial value is set
	useEffect(() => {
		if (!value) value = '';
		setInputValue(value);
	},[]);

	//because an update on the entire Fields array is costly, we only update after the user has stopped typing
	useEffect(() => {
		if ( inputValue === value ) {
			return;
		}
		const typingTimer = setTimeout(() => {
			onChange(inputValue);
			if (!isValidPhone(inputValue)) {
				onError('invalid_phone');
			}
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [inputValue]);

	const handleChange = ( value ) => {
		setInputValue(value);
	};

	return (
		<div className="cmplz-input-group cmplz-phone-input-group">
			<input
				type='tel'
				id={inputId}
				name={name}
				value={inputValue}
				onChange={(event) => handleChange(event.target.value)}
				required={required}
				disabled={disabled}
				className="cmplz-phone-input-group__input"
			/>
		</div>
	);
};

export default memo(PhoneInput);
