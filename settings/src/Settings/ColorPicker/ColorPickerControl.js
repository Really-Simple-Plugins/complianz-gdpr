import {useState, useEffect, memo} from "@wordpress/element";
import ColorPicker from '../Inputs/ColorPicker';
import * as Popover from '@radix-ui/react-popover';
import './ColorPicker.scss'

import useFields from "../Fields/FieldsData";

const ColorPickerElement = (props) => {
	const {updateField, setChangedField} = useFields();
	//parse value from field value
	const [anchorEl, setAnchor] = useState();
	const colorValue = props.field.value.hasOwnProperty(props.item.fieldname) ? props.field.value[props.item.fieldname] : props.field.default[props.item.fieldname];
	const colorName = props.item.label;

	const handleClick = (e) => {
		setAnchor(e.currentTarget);
	}

	const handleColorChange = (color, event) => {
		let valueCopy = {...props.field.value};
		valueCopy[props.item.fieldname] = color.hex;
		updateField(props.field.id, valueCopy);
		setChangedField(props.field.id, valueCopy);
	}

	return (
		<Popover.Root>
			<Popover.Trigger >
				<div className="cmplz-color-picker-control-item" onClick={handleClick} >
					<div className="cmplz-color-picker-color" style={{backgroundColor: colorValue}}></div>
					{colorName}
				</div>
			</Popover.Trigger>
			<Popover.Portal>
				<Popover.Content>
					<ColorPicker colorValue={colorValue} onChangeComplete={handleColorChange} />
				</Popover.Content>
			</Popover.Portal>
		</Popover.Root>
	)
}

const ColorPickerControl = (props) => {

	return (
			<div className="cmplz-color-picker-control">
				{ props.field.fields.map((item, i) => <ColorPickerElement key={i} item={item} field={props.field} />)}
			</div>
	);
}
export default memo(ColorPickerControl)
