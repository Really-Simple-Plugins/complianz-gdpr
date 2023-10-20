import * as Checkbox from '@radix-ui/react-checkbox';
import { __ } from '@wordpress/i18n';
import Icon from '../../utils/Icon';
import {useEffect, useState, memo} from "@wordpress/element";
import Button from "../Inputs/Button";

const CheckboxGroup = ({ indeterminate, label, value, id, onChange, required, disabled, options = {} }) => {
	const [isBoolean, setIsBoolean] = useState(false);
	const [loadMoreExpanded, setLoadMoreExpanded] = useState(false);

	let valueValidated = value;
	if ( !Array.isArray(valueValidated) ){
		valueValidated = valueValidated === '' ? [] : [valueValidated];
	}

	useEffect (() => {
		let isBool = (Object.keys(options).length === 1) && Object.keys(options)[0] === 'true';
		setIsBoolean(isBool);
	},[]);

	if (indeterminate){
		value = true;
	}

	const selected = valueValidated;
	const loadMoreCount = 10;

	// check if there are more options than the loadmore count
	let loadMoreEnabled = false;

	if (Object.keys(options).length > loadMoreCount) {
		loadMoreEnabled = true;
	}

	const handleCheckboxChange = (e, option) => {
		if (isBoolean) {
			onChange(!value);
		} else {
			const newSelected = selected.includes(""+option) || selected.includes(parseInt(option))
				? selected.filter((item) => item !== ""+option && item !== parseInt(option) )
				: [...selected, option];
			onChange(newSelected);
		}
	};

	const isEnabled = (id) => {
		// if there is only one option, we use the value as a boolean
		//selected can both be array of strings or integers.
		return isBoolean ? value : selected.includes(""+id) || selected.includes(parseInt(id));
	};

	const loadMoreHandler = () => {
		setLoadMoreExpanded(!loadMoreExpanded);
	};
	let allDisabled = disabled && !Array.isArray(disabled);

	if (Object.keys(options).length===0){
		return (
			<>{__("No options found", "complianz-gdpr")}</>
		)
	}

	return (
		<div className={'cmplz-checkbox-group'}>
			{Object.entries(options).map(([key, optionLabel], i) => (
				<div
					key={key}
					className={`cmplz-checkbox-group__item${
						!loadMoreExpanded && i > loadMoreCount-1 ? ' cmplz-hidden' : ''
					}`}
				>
					<Checkbox.Root
						className="cmplz-checkbox-group__checkbox"
						id={id + '_' + key}
						checked={isEnabled(key)}
						aria-label={label}
						disabled={allDisabled || (Array.isArray(disabled) && disabled.includes(key)) }
						required={required}
						onCheckedChange={(e) => handleCheckboxChange(e, key)}
					>
						<Checkbox.Indicator className="cmplz-checkbox-group__indicator">
							<Icon name={indeterminate ? 'indeterminate' : 'check'} size={14} color={'dark-blue'} />
						</Checkbox.Indicator>
					</Checkbox.Root>
					<label className="cmplz-checkbox-group__label" htmlFor={id + '_' + key}>
						{optionLabel}
					</label>
				</div>
			))}
			{!loadMoreExpanded && loadMoreEnabled && (
				<Button onClick={()=>loadMoreHandler()}>
					{__('Show more', 'complianz-gdpr')}
				</Button>
			)}
			{loadMoreExpanded && loadMoreEnabled && (
				<Button onClick={() => loadMoreHandler()}>
					{__('Show less', 'complianz-gdpr')}
				</Button>
			)}
		</div>
	);
};

export default memo(CheckboxGroup);
