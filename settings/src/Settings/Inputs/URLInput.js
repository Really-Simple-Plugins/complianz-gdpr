import {memo, useEffect, useState} from 'react';

const URLInput = ({
	value,
	onChange,
	onError,
	required,
	defaultValue,
	disabled,
	id,
	name,
}) => {
	const inputId = id || name;
	const [inputValue, setInputValue] = useState('');

	const isValidURL = (string) => {
		var res = string.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/);
		return (res !== null)
	};

	//ensure that the initial value is set
	useEffect(() => {
		setInputValue(value);
	},[]);

	//because an update on the entire Fields array is costly, we only update after the user has stopped typing
	useEffect(() => {
		if ( inputValue === value ) {
			return;
		}
		const typingTimer = setTimeout(() => {
			onChange(inputValue);
			if (!isValidURL(inputValue)) {
				onError('invalid_url');
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
		<div className="cmplz-input-group cmplz-url-input-group">
			<input
				type='url'
				id={inputId}
				name={name}
				value={inputValue}
				onChange={(event) => handleChange(event.target.value)}
				required={required}
				disabled={disabled}
				className="cmplz-url-input-group__input"
			/>
		</div>
	);
};

export default memo(URLInput);
