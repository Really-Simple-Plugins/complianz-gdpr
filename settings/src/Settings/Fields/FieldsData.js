import {create} from 'zustand';
import produce from 'immer';
import * as cmplz_api from "../../utils/api";
import {toast} from 'react-toastify';
import {__} from '@wordpress/i18n';
import {updateFieldsListWithConditions} from "../../utils/updateFieldsListWithConditions";
const fetchFields = () => {
  return cmplz_api.getFields().then((response) => {
	  return response;
  }).catch((error) => {
    console.error(error);
  });
}

const useFields = create(( set, get ) => ({
	fieldsLoaded: false,
	saving:false,
	preloadFields:[],
	error:false,
	fields: [],
	selectedFields: [],
	fieldNotices: [],
	fieldNoticesLoaded:false,
	changedFields:[],
	documentSettingsChanged:false,
	notCompletedRequiredFields:[],
	notCompletedFields:[],
	completableFields:[],
	allRequiredFieldsCompleted:false,
	nextButtonDisabled:false,
	highLightField: '',
	lockedByUser: 0,
	setDocumentSettingsChanged(changed){
		set({documentSettingsChanged:changed})
	},
	setHighLightField: (highLightField) => set(state => ({ highLightField })),
	fetchAllFieldsCompleted: () => {
		let notCompletedRequiredFields = get().fields.filter(field => field.required && isCompletableField(field) && !field.conditionallyDisabled && ( field.never_saved || field.value.length===0 || !field.value) );
		let notCompletedFields = get().fields.filter(field => isCompletableField(field) && !field.conditionallyDisabled && (field.never_saved || field.value.length===0 || !field.value) );
		let completableFields = get().fields.filter(field => isCompletableField(field) && !field.conditionallyDisabled  );
		set(() => ({
			notCompletedRequiredFields: notCompletedRequiredFields,
			allRequiredFieldsCompleted: notCompletedRequiredFields.length===0,
			notCompletedFields:notCompletedFields,
			completableFields:completableFields,
		}));
	},
	setChangedField: (id, value) => {
		set(
			produce((state) => {
				//remove current reference
				const existingFieldIndex = state.changedFields.findIndex(field => {
					return field.id===id;
				});

				if (existingFieldIndex!==-1){
					state.changedFields[existingFieldIndex].value = value;
				} else {
					state.changedFields.push({id:id, value:value});
				}
			})
		)
	},
	showSavedSettingsNotice : (text) => {
		handleShowSavedSettingsNotice(text);
	},

	updateField: (id, value) => {
		let found=false;
		let index = false;

		set(
			produce((state) => {
				state.fields.forEach(function(fieldItem, i) {
					if (fieldItem.id === id ){
						index = i;
						found=true;
					}
				});
				if (index!==false) {
					state.fields[index].value = value;
					let field = state.fields[index];
					if (field.type==='document' || field.id==='regions'){
						state.documentSettingsChanged = true;
					}
				}
			})
		)
	},

	addHelpNotice : (id, label, text, title, url) => {
        //create help object
        let help = {};
        help.label=label;
        help.text=text;
        if (url) help.url=url;
        if (title) help.title=title;
		set(
			produce((state) => {
				const fieldIndex = state.fields.findIndex(field => {
					return field.id===id;
				});
				if (fieldIndex!==-1) {
					state.fields[fieldIndex].help = help;
				}
			})
		)

	},
	removeHelpNotice : (id) => {
		set(
			produce((state) => {
				const fieldIndex = state.fields.findIndex(field => {
					return field.id===id;
				});
				state.fields[fieldIndex].help = false;
			})
		)
	},
	getFieldValue : (id) => {
		let fields = get().fields;
		for (const fieldItem of fields){
			if (fieldItem.id === id ){
				return fieldItem.value;
			}
		}
		return false;
	},
	getField : (id) => {
		let fields = get().fields;
		for (const fieldItem of fields){
			if (fieldItem.id === id ){
				return fieldItem;
			}
		}
		return false;
	},

	saveFields: async (selectedSubMenuItem, showSavedNotice = true, finish = false) => {
		let fields = get().fields.filter(field => field.data_target !== 'banner');
		set({saving:true})
		const changedFields = get().changedFields;
		let saveFields = fields.filter(field => {
			const fieldIsChanged = changedFields.some(changedField => changedField.id === field.id );
			// const isRadioOrSelect = field.default && ['radio', 'select', 'document', 'license'].includes(field.type);
			//check if this field has the current subMenuItem as menu_id: we include all visible fields
			const fieldIsVisible = field.menu_id && field.menu_id === selectedSubMenuItem;
			return fieldIsChanged || fieldIsVisible;
		});
		if (saveFields.length > 0 || finish) {
			let response = cmplz_api.setFields(saveFields, finish).then((response) => {
				return response;
			});

			if (showSavedNotice ) {
				toast.promise(
					response,
					{
						pending: __('Saving settings...', 'complianz-gdpr'),
						success: __('Settings saved', 'complianz-gdpr'),
						error: __('Something went wrong', 'complianz-gdpr'),
					}
				);
			}
			await response.then((response) => {
				fields = response.fields;
				let fieldsWithPremium = applyPremiumSettings(fields);
				let conditionallyEnabledFields = updateFieldsListWithConditions(fieldsWithPremium);
				conditionallyEnabledFields = applyDefaults(conditionallyEnabledFields);
				let selectedFields = conditionallyEnabledFields.filter(field => field.menu_id === selectedSubMenuItem);

				set(
					produce((state) => {
						state.changedFields = [];
						state.fields = conditionallyEnabledFields;
						state.selectedFields = selectedFields;
						state.saving = false;
					})
				);
			});
		}
		if (showSavedNotice && saveFields.length === 0) {
			//nothing to save. show instant success.
			toast.promise(
				Promise.resolve(),
				{
					success: __('Settings saved', 'complianz-gdpr'),
				}
			);
		}
	},

	updateFieldsData: (selectedSubMenuItem) => {
		let fields = get().fields;
		fields = updateFieldsListWithConditions(fields);
		get().isNextButtonDisabled(fields, selectedSubMenuItem);
		set(
			produce((state) => {
				state.fields = fields;
			})
		)
	},
	//check if all required fields have been enabled. If so, enable save/continue button
	isNextButtonDisabled: (fields, selectedMenuItem) => {
		//get all fields with group_id this.props.group_id
		let fieldsOnPage = fields.filter(field => field.menu_id === selectedMenuItem);
		let requiredFields = fieldsOnPage.filter(field => field.required && !field.conditionallyDisabled && ( field.value.length===0 || !field.value ) );
		set({nextButtonDisabled: requiredFields.length > 0});
		return requiredFields.length > 0;
	},
	getFieldNotices: async () => {
		let data = {};
		const {fieldNotices} = await cmplz_api.doAction('get_field_notices', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		let notices = Array.isArray(fieldNotices) ? fieldNotices : [];
		set({fieldNotices:notices, fieldNoticesLoaded:true});
	},
	fetchFieldsData: async ( selectedSubMenuItem ) => {
		const { fields, error, locked_by }   = await fetchFields();
		if (!Array.isArray(fields)){
			set(() => ({
				fieldsLoaded: true,
				fields: [],
				selectedFields: [],
				error:error,
				lockedByUser:locked_by,
			}));
			return;
		}
		let fieldsWithPremium = applyPremiumSettings(fields);
		let conditionallyEnabledFields = updateFieldsListWithConditions(fieldsWithPremium);
		conditionallyEnabledFields = applyDefaults(conditionallyEnabledFields);

		let selectedFields = conditionallyEnabledFields.filter(field => field.menu_id === selectedSubMenuItem);
		let preloadFields = [];
		const encounteredTypes = {};
		fields.forEach((field) => {
			if (
				(field.type === 'radio' || field.type === 'multicheckbox' || field.type === 'select') &&
				!encounteredTypes[field.type]
			) {
				preloadFields.push(field);
				encounteredTypes[field.type] = true;
			}
		});

		set(() => ({
			fieldsLoaded: true,
			fields: conditionallyEnabledFields,
			selectedFields: selectedFields,
			error:error,
			lockedByUser:locked_by,
			preloadFields:preloadFields,
		}));
	}
}));

export default useFields;



const applyPremiumSettings = (fields) => {
	if ( cmplz_settings.is_premium ) {
		for (const field of fields ){
			const premium = field.premium;
			if ( premium ) {
				field.disabled =premium.disabled ? premium.disabled : false;
				if (premium.default) field.default = premium.default;
				if (premium.label) field.label = premium.label;
				if (premium.comment) field.comment = premium.comment;
				if (premium.tooltip) field.tooltip = premium.tooltip;
				if (premium.react_conditions) field.react_conditions = premium.react_conditions;
			}
		}
	}
	return fields;
}



const applyDefaults = (fields) => {
	//foreach field, set the value to the default value if it's empty
	for (let i = 0; i <fields.length; i++) {
		let field = fields[i];
		//prevent 'false' as default value in the input fields
		if  (!field.value && field.type !== 'checkbox' ) field.value = '';

		//set a default if we have a default value, but no current value
		//and it wasn't saved before
		if ( field.default && field.never_saved ) {
			if (field.value.length===0 || !field.value ){
				field.value = field.default;
			}
		}

		fields[i] = field;
	}
	return fields;
}

const handleShowSavedSettingsNotice = (text) => {
	if (typeof text === 'undefined') {
		text = __( 'Settings saved', 'complianz-gdpr' );
	}
	toast.success(text);
}

const isCompletableField = (field) => {
	if (field.group_id === 'finish') {
		return false;
	}
	return field.type ==='radio' ||
		field.type === 'select' ||
		field.type === 'checkbox' ||
		field.type === 'multicheckbox' ||
		field.type === 'text' ||
		field.type === 'textarea' ||
		field.type === 'document';
}




