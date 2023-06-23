import {TextControl, ToggleControl} from "@wordpress/components";

import useFields from "../Settings/Fields/FieldsData";

const TextCheckboxControl = (props) => {
	const {setChangedField, updateField } = useFields();
	const onChangeHandlerText = (value) => {
		let curValue = {...props.field.value};
		curValue['text'] = value;
		updateField(props.field.id, curValue);
		setChangedField(props.field.id, curValue);
	}

	const onChangeHandlerCheckbox = (value) => {
		let curValue = {...props.field.value};
		curValue['show'] = value;
		updateField(props.field.id, curValue);
		setChangedField(props.field.id, curValue);
	}

	let textValue = props.field.value.hasOwnProperty('text') ? props.field.value['text'] : '';
	let checkboxValue = props.field.value.hasOwnProperty('show') ? props.field.value['show'] : false;
	return (

		<div className="cmplz-text-control">
			{props.label}
			<div className="cmplz-text-control__field">
				<TextControl
					placeholder={ props.field.placeholder }
					onChange={ ( fieldValue ) => onChangeHandlerText(fieldValue) }
					value= { textValue }
					disabled = {props.disabled}
				/>
				<ToggleControl
					disabled = {props.disabled}
					checked= { checkboxValue==1 }
					onChange={ ( fieldValue ) => onChangeHandlerCheckbox(fieldValue) }
				/>
			</div>
			{props.field.comment && <div dangerouslySetInnerHTML={{__html:props.field.comment}}></div>}
		</div>
	);


}

export default TextCheckboxControl;


