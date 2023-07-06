import {Fragment, memo, useEffect, useState} from 'react';
import {__} from '@wordpress/i18n';
import Icon from '../../utils/Icon';

const BorderInput = ({ label, id, value, onChange, required, defaultValue, disabled, options = {}, units = ['px'] }) => {
	const defaultUnit = defaultValue.type || value.type || units[0];
	const [unit, setUnit] = useState(defaultUnit);
	const [link, setLink] = useState(false);

	// make an array of the sides with key and label
	const sides = {
		top: __('Top', 'complianz-gdpr'),
		right: __('Right', 'complianz-gdpr'),
		bottom: __('Bottom', 'complianz-gdpr'),
		left: __('Left', 'complianz-gdpr'),
	};

	useEffect(() => {
		// set link based on if all values are equal
		if (value['top'] === value['right'] && value['top'] === value['bottom'] && value['top'] === value['left']) {
			setLink(true);
		}
	}, []);

	useEffect(() => {
		if (!link) return;
		handleChange( value['top'], 'top');
	}, [link]);

	const handleChange = (changedValue, key) => {
		let valueCopy = {...value};
		if (link) {
			valueCopy = updateAllValues(changedValue);
		} else {
			valueCopy[key] = changedValue;
		}
		onChange(valueCopy);
	}

	const updateAllValues = (newValue) => {
		let valueCopy = {...value};
		valueCopy['top'] = newValue;
		valueCopy['right'] = newValue;
		valueCopy['bottom'] = newValue;
		valueCopy['left'] = newValue;
		return valueCopy;
	}

	const handleUnitChange = (newUnit) => {
		setUnit(newUnit);
		let valueCopy = {...value};
		valueCopy.type = newUnit;
		onChange(valueCopy);
	}

	return (
		<div className={'cmplz-border-input'}>
			{
				Object.keys(sides).map((key) => {
					const side = sides[key];
					const sideValue = value.hasOwnProperty(key) ? value[key] : defaultValue[key];
					return (
						<Fragment key={key}>
							<input className={'cmplz-border-input-side'} type="number" key={key} onChange={(e) => handleChange(e.target.value, key)} value={sideValue}/>
							<p className={'cmplz-border-input-side-label'}>{side}</p>
						</Fragment>
					)
				})
			}
			{link && <button className={'cmplz-border-input-link linked'} onClick={() => setLink(!link)}>
				<Icon name={'linked'} size={16} tooltip={__('Unlink values', 'complianz-gdpr')} />
			</button> }
			{!link && <button className={'cmplz-border-input-link'} onClick={() => setLink(!link)}>
				<Icon name={'unlinked'} size={16} tooltip={__('Link values together', 'complianz-gdpr')} />
			</button> }
			{units.length > 1 && (
				<div className={'cmplz-border-input-unit'}>
					<select value={unit} onChange={(e) => handleUnitChange(e.target.value)}>
						{units.map((unitItem, i) => {
							return <option key={i} value={unitItem}>{unitItem}</option>
						}
						)}
					</select>
				</div>
			)}
			{units.length === 1 && (
				<div className={'cmplz-border-input-unit'}>{unit}</div>
			)}
		</div>
	)
}
export default memo(BorderInput);
