import { __ } from '@wordpress/i18n';
import ProcessorElement from "./ProcessorElement";
import useFields from "../../Settings/Fields/FieldsData";
import {memo, useEffect} from "@wordpress/element";
import useProcessingAgreementsData from "../ProcessingAgreements/ProcessingAgreementsData";

const ProcessorControl = (props) => {
	const {updateField, setChangedField} = useFields();
	const {documentsLoaded, fetchData} = useProcessingAgreementsData();

	useEffect(() => {
		if ( !documentsLoaded ) {
			fetchData();
		}
	}, []);

	const onAddNewHandler = () => {
		//add new processor
		let processors = props.field.value;
		if (!Array.isArray(processors) ) {
			processors = [];
		}
		let newProcessor = {};
		//create deep copy
		let processorsCopy = [...processors];
		newProcessor.name=__("New processor", "complianz-gdpr");
		processorsCopy.push(newProcessor);
		updateField(field.id, processorsCopy);
		setChangedField(field.id, processorsCopy);
	}

	let field = props.field;
	let processors = field.value;
	if ( !Array.isArray(processors) ) {
		processors = [];
	}

	return (
		<div className="components-base-control cmplz-processor">
			<div>
				<button onClick={ () => onAddNewHandler() } className="button button-default">{__("Add new Processors & Service Providers", "complianz-gdpr")}</button>
			</div>
			<div className="cmplz-panel__list">
			{processors.map((processor, i) =>
				 <ProcessorElement field={props.field} key={i} index={i} processor={processor}/>
			)}
			</div>
		 </div>
	);
}

export default memo(ProcessorControl);
