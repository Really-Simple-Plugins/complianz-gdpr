import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";
import Panel from "./../Panel";
import useFields from "../../Settings/Fields/FieldsData";
import {memo, useEffect} from "@wordpress/element";
import useProcessingAgreementsData from "../ProcessingAgreements/ProcessingAgreementsData";
import useMenu from "../../Menu/MenuData";

const ProcessorElement = (props) => {
	const {updateField, setChangedField, saveFields} = useFields();
	const {documentsLoaded, documents} = useProcessingAgreementsData();
	const {selectedMainMenuItem} = useMenu();
	const [name, setName] = wp.element.useState(props.processor.name ? props.processor.name : '');
	const [purpose, setPurpose] = wp.element.useState(props.processor.purpose ? props.processor.purpose : '');
	const [country, setCountry] = wp.element.useState(props.processor.country ? props.processor.country : '');
	const [data, setData] = wp.element.useState(props.processor.data ? props.processor.data : '');

	const onChangeHandler = (value, id) => {
		let processors = [...props.field.value];
		if ( !Array.isArray(processors) ) {
			processors = [];
		}

		//update processor with index props.index
		let currentProcessor = {...processors[props.index]};
		currentProcessor[id] = value;
		processors[props.index] = currentProcessor;
		updateField(props.field.id, processors);
		setChangedField(props.field.id, processors);
	}

	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(name, 'name');
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [name]);
	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(data, 'data');
		}, 500);
		return () => {
			clearTimeout(typingTimer);
		};
	}, [data]);

	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(country, 'country');
		}, 500);
		return () => {
			clearTimeout(typingTimer);
		};
	}, [country]);
	useEffect(() => {
		const typingTimer = setTimeout(() => {
			onChangeHandler(purpose, 'purpose');
		}, 500);
		return () => {
			clearTimeout(typingTimer);
		};
	}, [purpose]);

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
					<input onChange={ ( e ) => setName(e.target.value) } type="text" placeholder={__("Name", "complianz-gdpr")} value={name} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Country", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => setCountry(e.target.value) } type="text" placeholder={__("Country", "complianz-gdpr")}  value={country} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Purpose", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => setPurpose(e.target.value) } type="text" placeholder={__("Purpose", "complianz-gdpr")}  value={purpose} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Data", "complianz-gdpr")}</label>
					<input onChange={ ( e ) => setData(e.target.value) } type="text" placeholder={__("Data", "complianz-gdpr")}  value={data} />
				</div>
				<div className="cmplz-details-row">
					<label>{__("Processing Agreement", "complianz-gdpr")}</label>
					{documentsLoaded &&
						<select onChange={ ( e ) => onChangeHandler(e.target.value, 'processing_agreement') } value={processor.processing_agreement}>
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
	if (!processor.processing_agreement) processor.processing_agreement = 0;
	return (
		<><Panel summary={name} details={Details(processor)}/></>
	);
}
export default memo(ProcessorElement);
