export const validateConditions = (conditions, fields, fieldId, isSub=false) => {
	let relation = conditions.relation === 'OR' ? 'OR' : 'AND';
	let conditionApplies = relation==='AND';
	for (const key in conditions) {
		if ( conditions.hasOwnProperty(key) ) {
			let thisConditionApplies = relation==='AND';
			let subConditionsArray = conditions[key];
			//check if there's a subcondition
			if ( subConditionsArray.hasOwnProperty('relation') ) {
				thisConditionApplies = validateConditions(subConditionsArray, fields, fieldId, true)===1;
				if ( relation === 'AND' ) {
					conditionApplies = conditionApplies && thisConditionApplies;
				} else {
					conditionApplies = conditionApplies || thisConditionApplies;
				}
			}
			for ( let conditionField in subConditionsArray ) {
				if (conditionField==='hidden') {
					thisConditionApplies = false;
					continue;
				}
				let invert = conditionField.indexOf('!')===0;
				if ( subConditionsArray.hasOwnProperty(conditionField) ) {
					let conditionValue = subConditionsArray[conditionField];
					conditionField = conditionField.replace('!','');
					let conditionFields = fields.filter(field => field.id === conditionField);

					if ( conditionFields.hasOwnProperty(0) ){
						let field = conditionFields[0];
						let actualValue = field.value;

						if ( field.type==='text_checkbox' ) {
							thisConditionApplies = actualValue.hasOwnProperty('show') && actualValue['show'] == conditionValue;
						} else if ( field.type==='checkbox' ) {
							thisConditionApplies = actualValue == conditionValue; //with == it can be either true or 1
						} else if ( field.type==='multicheckbox' ) {
							//multicheckbox conditions
							//loop through objects
							thisConditionApplies = false;
							let arrayValue = actualValue;
							if ( !Array.isArray(arrayValue) ){
								arrayValue = arrayValue !== '' ? [] : [arrayValue];
							}
							if ( arrayValue.length===0 ) {
								thisConditionApplies = false;
							} else {
								for (const key of Object.keys(arrayValue)) {
									if ( !Array.isArray(conditionValue) ) conditionValue = [conditionValue];
									if ( conditionValue.includes(arrayValue[key])){
										thisConditionApplies = true;
										break;
									}
								}
							}
						} else if ( field.type==='radio' || field.type==='document') {
							//as the regions field can be both radio and multicheckbox, an array is possible for a radio field
							if ( Array.isArray(conditionValue) ) {
								thisConditionApplies = conditionValue.includes(actualValue);
							} else {
								thisConditionApplies = conditionValue === actualValue;
							}
						} else {
							if (conditionValue === true ) {
								thisConditionApplies = actualValue===1 || actualValue === "1" || actualValue === true;
							} else if (conditionValue === false ) {
								thisConditionApplies = actualValue === 0 || actualValue === "0" || actualValue === false;
							} else if (conditionValue.indexOf('EMPTY')!==-1) {
								thisConditionApplies = actualValue.length === 0;
							} else {
								thisConditionApplies = String(actualValue).toLowerCase() === conditionValue.toLowerCase();
							}
						}
					}
				}
				if ( invert ){
					thisConditionApplies = !thisConditionApplies;
				}
				if ( relation === 'AND' ) {
					conditionApplies = conditionApplies && thisConditionApplies;
				} else {
					conditionApplies = conditionApplies || thisConditionApplies;
				}
			}
			if ( relation === 'AND' ) {
				conditionApplies = conditionApplies && thisConditionApplies;
			} else {
				conditionApplies = conditionApplies || thisConditionApplies;
			}
		}
	}

	return conditionApplies ? 1 : 0;
}
