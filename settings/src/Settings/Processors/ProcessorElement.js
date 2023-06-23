import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";
import Panel from "./../Panel";
import useFields from "../../Settings/Fields/FieldsData";
import {memo} from "react";
import useProcessingAgreementsData from "../ProcessingAgreements/ProcessingAgreementsData";
import useMenu from "../../Menu/MenuData";

const ProcessorElement = (props) => {
	const {updateField, setChangedField, saveFields} = useFields();
	const {documentsLoaded, documents} = useProcessingAgreementsData();
	const {selectedMainMenuItem} = useMenu();

	const onChangeHandler = (e, id) => {
		let processors = [...props.field.value];
		if ( !Array.isArray(processors) ) {
			processors = [];
		}

		//update processor with index props.index
		let currentProcessor = {...processors[props.index]};
		currentProcessor[id] = e.target.value;
		processors[props.index] = currentProcessor;
		updateField(props.field.id, processors);
		setChangedField(props.field.id, processors);
	}

	const onDeleteHandler = async (e) => {
		let processors = props.field.value;
		if ( !Array.isArray(processors) ) {
			processors = [];
		}
		let processorsCopy = [...processors];
		if ( processorsCopy.hasOwnProperty(props.index) ) {
			processorsCopy.splice(props.index, 1);
		}
		updateField(props.field.id, processorsCopy);
		setChangedField(props.field.id, processorsCopy);
		await saveFields( selectedMainMenuItem, false, false );
	}
	let processingAgreements = documentsLoaded ? [...documents] : [];
	processingAgreements.push({id:-1, title:__('A Processing Agreement outside Complianz Privacy Suite', 'complianz-gdpr'),region:'',service:'',date:''});

	const Details = (processor) => {
		return (
			<>
				<div className="cmplz-details-row">
					<label>{__("Name", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'name') } type="text" placeholder={__("Name", "complianz-gdpr")} value={processor.name} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Country", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'country') } type="text" placeholder={__("Country", "complianz-gdpr")}  value={processor.country} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Purpose", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'purpose') } type="text" placeholder={__("Purpose", "complianz-gdpr")}  value={processor.purpose} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Data", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => onChangeHandler(e, 'data') } type="text" placeholder={__("Data", "complianz-gdpr")}  value={processor.data} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Processing Agreement", "complianz-gdpr")}</label>
					{documentsLoaded &&
						<select onChange={ ( e ) => onChangeHandler(e, 'processing_agreement') } value={processor.processing_agreement}>
							<option value="0">{__("Select an option", "complianz-gdpr")}</option>
							{processingAgreements.map((processingAgreement, i) =>
								<option key={i} value={processingAgreement.id}>{processingAgreement.title}</option>
							)}
						</select>
					}
					{!documentsLoaded &&
						<div className="cmplz-documents-loader">
							<div><Icon name = "loading" color = 'grey' /></div><div>{__("Loading...", "complianz-gdpr")}</div>
						</div>
					}
				</div>
				<div className="cmplz-details-row__buttons">
					<button className="button button-default cmplz-reset-button" onClick={ ( e ) => onDeleteHandler(e) }>{__("Delete", "complianz-gdpr")}</button>
				</div>
			</>
		);
	}

	//ensure defaults
	let processor = {...props.processor};

	if (!processor.name) processor.name = '';
	if (!processor.purpose) processor.purpose = '';
	if (!processor.country) processor.country = '';
	if (!processor.processing_agreement) processor.processing_agreement = 0;
	if (!processor.data) processor.data = '';
	return (
		<><Panel summary={processor.name} details={Details(processor)}/></>
	);
}
export default memo(ProcessorElement);
