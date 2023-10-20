import { __ } from '@wordpress/i18n';
import useFields from "../../Settings/Fields/FieldsData";
import {memo} from "@wordpress/element";
import ThirdPartyElement from "./ThirdPartyElement";

const ThirdPartyControl = (props) => {
	const {updateField, setChangedField} = useFields();

	const onAddNewHandler = () => {
		//add new processor
		let thirdparties = props.field.value;
		if (!Array.isArray(thirdparties) ) {
			thirdparties = [];
		}
		let newThirdparty = {};
		//create deep copy
		let thirdpartiesCopy = [...thirdparties];
		newThirdparty.name=__("New Third Party", "complianz-gdpr");
		thirdpartiesCopy.push(newThirdparty);
		updateField(field.id, thirdpartiesCopy);
		setChangedField(field.id, thirdpartiesCopy);
	}

	let field = props.field;
	let thirdParties = field.value;
	if ( !Array.isArray(thirdParties) ) {
		thirdParties = [];
	}

	return (
		<div className="components-base-control cmplz-thirdparty">
			<div>
				<button onClick={ () => onAddNewHandler() } className="button button-default">{__("Add new Third Party", "complianz-gdpr")}</button>
			</div>
			<div className="cmplz-panel__list">
				{thirdParties.map((thirdParty, i) =>
					<ThirdPartyElement field={props.field} updateField={props.updateField} index={i} key={i} thirdParty={thirdParty}/>
				)}
			</div>
		</div>
	);
}

export default memo(ThirdPartyControl);
