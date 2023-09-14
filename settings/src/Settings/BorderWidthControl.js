import { __ } from '@wordpress/i18n';
import useFields from "./Fields/FieldsData";
const BorderWidthControl = (props) => {
	const {updateField, setChangedField} = useFields();

	const handleChange = (key, value) => {
		let valueCopy = {...props.field.value};
		valueCopy[key] = value;
		updateField(props.field.id, valueCopy);
		setChangedField(props.field.id, valueCopy);
	}

	const top = props.field.value.hasOwnProperty('top') ? props.field.value['top'] : props.field.default['top'];
	const right = props.field.value.hasOwnProperty('right') ? props.field.value['right'] : props.field.default['right'];
	const bottom = props.field.value.hasOwnProperty('bottom') ? props.field.value['bottom'] : props.field.default['bottom'];
	const left = props.field.value.hasOwnProperty('left') ? props.field.value['left'] : props.field.default['left'];
	const type = props.field.value.hasOwnProperty('type') ? props.field.value['type'] : props.field.default['type'];
	return (
		<div >
			<div className="cmplz-borderradius-label">
				{props.label}
			</div>
			<div className="cmplz-borderradius-control">
				<div className="cmplz-borderradius-element">
					<div className="cmplz-borderradius-element-label">{__("Top", "complianz-gdpr")}</div>
					<input type="number" key="1" onChange={(e) => handleChange('top', e.target.value)} value={top}/>
				</div>
				<div className="cmplz-borderradius-element">
					<div className="cmplz-borderradius-element-label">{__("Right", "complianz-gdpr")}</div>
					<input type="number" key="2" onChange={(e) => handleChange('right', e.target.value)} value={right}/>
				</div>
				<div className="cmplz-borderradius-element">
					<div className="cmplz-borderradius-element-label">{__("Bottom", "complianz-gdpr")}</div>
					<input type="number" key="3" onChange={(e) => handleChange('bottom', e.target.value)} value={bottom}/>
				</div>
				<div className="cmplz-borderradius-element">
					<div className="cmplz-borderradius-element-label">{__("Left", "complianz-gdpr")}</div>
					<input type="number" key="4" onChange={(e) => handleChange('left', e.target.value)} value={left}/>
				</div>
				<div className="cmplz-borderradius-inputtype">
					<div className={"cmplz-borderradius-inputtype-pixel "} >px</div>
				</div>
			</div>
		</div>
	);
}
export default BorderWidthControl;
