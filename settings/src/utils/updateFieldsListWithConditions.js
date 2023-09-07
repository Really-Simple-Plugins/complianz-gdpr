import {validateConditions} from "./validateConditions";

export const updateFieldsListWithConditions = (fields) => {
	return fields.map(field => {
		const enabled = !(field.hasOwnProperty('react_conditions') && !validateConditions(field.react_conditions, fields, field.id));
		const newField = {...field};

		if (newField.condition_action === 'disable') {
			newField.disabled = !enabled;
		} else {
			newField.conditionallyDisabled = !enabled;
		}

		return newField;
	});
}
