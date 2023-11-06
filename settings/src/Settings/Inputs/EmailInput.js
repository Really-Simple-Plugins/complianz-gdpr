import {memo, useEffect, useState} from "@wordpress/element";

const EmailInput = ({
	value,
	onChange,
	onError,
	required,
	disabled,
	id,
	name,
}) => {
	const inputId = id || name;
	const [inputValue, setInputValue] = useState(value);

	const isValidEmail = (string) => {
		//convert 'string' to string
		string = string + '';
		var res = string.match(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/);
		return (res !== null)
	};

	//ensure that the initial value is set
	useEffect(() => {
		if (!value) value = '';
		setInputValue(value);
	},[]);

	//because an update on the entire Fields array is costly, we only update after the user has stopped typing
	useEffect(() => {
		if (value=== inputValue) {
			return;
		}
		const typingTimer = setTimeout(() => {
			onChange(inputValue);
			if (!isValidEmail(inputValue)) {
				onError('invalid_email');
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
		<div className="cmplz-input-group cmplz-email-input-group">
			<input
				type='email'
				id={inputId}
				name={name}
				value={inputValue}
				onChange={(event) => handleChange(event.target.value)}
				required={required}
				disabled={disabled}
				className="cmplz-email-input-group__input"
			/>
		</div>
	);
};

export default memo(EmailInput);
