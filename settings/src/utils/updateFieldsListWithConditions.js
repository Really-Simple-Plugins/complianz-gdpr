import {validateConditions} from "./validateConditions";

export const updateFieldsListWithConditions = (fields) => {
	// let newFields = [];
	// fields.forEach(function(field, i) {
	// 	let enabled = !( field.hasOwnProperty('react_conditions') && !validateConditions(field.react_conditions, fields, field.id) );
	// 	//we want to update the changed fields if this field has just become visible. Otherwise the new field won't get saved.
	// 	const newField = {...field};
	// 	if (newField.condition_action === 'disable') {
	// 		newField.disabled = !enabled;
	// 	} else {
	// 		newField.conditionallyDisabled = !enabled;
	// 	}
	//
	// 	newFields.push(newField);
	// });
	// return newFields;
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
