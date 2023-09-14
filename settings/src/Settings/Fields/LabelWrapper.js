import {memo} from "@wordpress/element";
import FieldTooltip from "./FieldTooltip";
import Premium from "../Premium";
import {__} from '@wordpress/i18n';

const LabelWrapper = ({id, label, tooltip, premium, required, type, isParentLabel }) => {
	if (!label || label.length===0) return null;

	let parentClass = isParentLabel ? 'cmplz-parent' : '';
	return (
		<div className={"cmplz-label-container "+parentClass}>
			<label htmlFor={id}>{label}
				{required && type!=='radio' && type!=='document' && <span className="cmplz-required"> (
					{__('required', 'complianz-gdpr')}
					)</span>}
			</label>
			<FieldTooltip tooltip={tooltip}/>
			<Premium premium={premium} id={id}/>
		</div>
	);
};
export default memo(LabelWrapper);

