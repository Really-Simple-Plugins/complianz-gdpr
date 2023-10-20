import { ChromePicker } from 'react-color';
import {memo, useState} from "@wordpress/element";

const ColorPicker = ({colorValue, onChangeComplete}) => {
	const [color, setColor] = useState(colorValue);

	const onChange = (color) => {
		setColor(color.hex);
	}
	return (
		<ChromePicker
			color={color}
			onChange={onChange}
			onChangeComplete={onChangeComplete}
			disableAlpha={true}
		/>
	)
}
export default memo(ColorPicker)
