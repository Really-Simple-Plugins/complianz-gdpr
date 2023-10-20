import useDataBreachReportsData from "./DataBreachReportsData";
import {useState, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import Field from "../Fields/Field";
import useFields from "../Fields/FieldsData";
import Icon from "../../utils/Icon";
import DataBreachConclusion from "./DataBreachConclusion";
import {memo} from "@wordpress/element";
import LabelWrapper from "../Fields/LabelWrapper";
import SelectInput from "../Inputs/SelectInput";

const CreateDataBreachReport = ({label, field}) => {
	const { fields, fileName, fetching, loadingFields, updating, regions, documentsLoaded, resetEditDocumentId, savedDocument, fetchData, fetchFields, updateField, save, editDocumentId, region, setRegion} = useDataBreachReportsData();
	const [ createBtnDisabled, setCreateBtnDisabled ] = useState( true );
	const [ downloadBtnDisabled, setDownloadBtnDisabled ] = useState( false );
	const [step, setStep] = useState(0);
	const {allRequiredFieldsCompleted,fetchAllFieldsCompleted, fieldsLoaded, showSavedSettingsNotice} = useFields();
	let scrollAnchor = React.createRef();

	useEffect( () => {
		if (editDocumentId) {
			setStep(0);
		}
		if ( editDocumentId && scrollAnchor.current ) {
			scrollAnchor.current.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	},[editDocumentId]);

	const fieldsPerStep = 5;
	useEffect(() => {
		if (!documentsLoaded && cmplz_settings.is_premium) fetchData();
	}, [documentsLoaded])

	useEffect (() => {
		fetchAllFieldsCompleted();
	}, [fieldsLoaded])

	useEffect(() => {
		if (region!=='' ) {
			setCreateBtnDisabled(false);
		} else {
			setCreateBtnDisabled(true);
		}
	}, [region, fetching, editDocumentId])

	const onChangeHandler = (fieldId, value) => {
		updateField(fieldId, value);
	}

	const download = async () => {
		if (savedDocument.downloadUrl !== '') {
			setDownloadBtnDisabled(true);
			const url = savedDocument.download_url;
			try {
				let request = new XMLHttpRequest();
				request.responseType = 'blob';
				request.open('get', url, true);
				request.send();
				request.onreadystatechange = function() {
					if (this.readyState === 4 && this.status === 200) {
						let obj = window.URL.createObjectURL(this.response);
						let element = window.document.createElement('a');
						element.setAttribute('href',obj);
						element.setAttribute('download', savedDocument.title);
						window.document.body.appendChild(element);
						//onClick property
						element.click();
						setDownloadBtnDisabled(false);
						setTimeout(function() {
							window.URL.revokeObjectURL(obj);
						}, 60 * 1000);
					}
				};
			} catch (error) {
				console.error(error);
				setDownloadBtnDisabled(false);
			}
		}
	};

	const saveFields = async () => {
		await save(region);
		showSavedSettingsNotice();
	}

	const saveAndExit = async () => {
		await save(region);
		showSavedSettingsNotice();
		setStep(step+1);

	}

	useEffect(() => {
		if (region!=='' && !fetching) {
			setCreateBtnDisabled(false);
		}
	}, [region, fetching])

	const onCreateHandler = async () => {
		await fetchFields(region);
		setStep(1);
	}

	//select the next 5 fields from fields
	const getStepFields = (activeFields) => {
		const start = (step-1)*fieldsPerStep;
		const end = start+fieldsPerStep;
		return activeFields.slice(start, end);
	}
	let visibleFields = fields.filter(field => typeof field.conditionallyDisabled==='undefined' || field.conditionallyDisabled ===false);
	let lastStep = Math.ceil(visibleFields.length/fieldsPerStep);
	let selectedFields = getStepFields(fields);
	return (
		<>
			{!allRequiredFieldsCompleted && <div className="cmplz-locked">
				<div className="cmplz-locked-overlay">
					<span className="cmplz-task-status cmplz-warning">{__("Incomplete","complianz-gdpr")}</span>
					<span>
						{__("The wizard has not been completed yet, but this field requires information from the wizard. Please complete the wizard first.","complianz-gdpr")}
					</span>
				</div>
			</div>}

			{step===0 &&
				<>
					{ editDocumentId &&
						<div className="cmplz-selected-document">
							{fileName}
						</div>
					}
					<LabelWrapper id={'region_for_databreaches'}
								  label={ __("Region","complianz-gdpr") }
								  required={true} type={'select'}/>

					<SelectInput innerRef={scrollAnchor}
								 disabled={updating}
								 onChange={ ( fieldValue ) => setRegion(fieldValue) }
								 options={ regions }
								 value= { region }
								 required={true}
					/>
					<div className="cmplz-table-header">
						<LabelWrapper id={'region_for_databreaches'}
									  label={__("Create Data Breach report",'complianz-gdpr') }
									  type={'button'}/>
						<div className='cmplz-table-header-controls'>
							{editDocumentId && <>
								<button disabled={updating} className="button button-default" onClick={()=>{resetEditDocumentId();setStep(0)}}>{__("Cancel",'complianz-gdpr')}</button>
								<button disabled={updating} className="button button-primary" onClick={()=>setStep(step+1)}>{__("Next",'complianz-gdpr')}</button>
								<button disabled={updating} className="button button-primary" onClick={()=>saveFields()}>{__("Save",'complianz-gdpr')}</button>
							</>}
							{!editDocumentId && <>
								<button disabled={createBtnDisabled || loadingFields} className="button button-primary" onClick={()=>onCreateHandler() }>
									{__("Create",'complianz-gdpr')}
									{loadingFields && <Icon name = "loading" color = 'grey' />}
								</button>
							</>}
						</div>
					</div>
				</>
			}
			{
				step>0 && <>
					{   step <= lastStep && selectedFields.map((field, i) =>
						<Field key={i} index={i} field={field} isCustomField={true} customChangeHandler={(field, value)=>onChangeHandler(field, value)}/>)
					}
					{ step > lastStep &&
						<>
							<DataBreachConclusion />
						</>
					}
					<div className="cmplz-table-header">
						<div className='cmplz-table-header-controls'>
							<button disabled={updating} className="button button-default" onClick={()=>{resetEditDocumentId();setStep(0)}}>
								{ step <= lastStep && __("Cancel",'complianz-gdpr')}
								{ step > lastStep && __("Exit",'complianz-gdpr')}
							</button>
							{ step <= lastStep && <button className="button button-default" onClick={()=>setStep(step-1)}>{__("Previous","complianz-gdpr")}</button>}

							{ step < lastStep &&
								<>
									<button className="button button-primary" onClick={()=>setStep(step+1)}>{__("Next","complianz-gdpr")}</button>
								</>
							}

							{ step === lastStep &&
								<>
									<button className="button button-primary" onClick={()=>saveAndExit()}>
										{__("Finish","complianz-gdpr")}
										{updating && <Icon name = "loading" color = 'grey' />}
									</button>
								</>
							}
							{ step > lastStep && savedDocument && savedDocument.has_to_be_reported && <>
								<button disabled={downloadBtnDisabled} className="button button-primary" onClick={()=>download()}>
									{__("Download","complianz-gdpr")}
								</button>
							</>}
							{editDocumentId && step < lastStep && <>
								<button disabled={updating} className="button button-primary" onClick={()=>saveFields()}>{__("Save",'complianz-gdpr')}</button>
							</>}
					</div></div>
				</>
		}
		</>
	)
}
export default memo(CreateDataBreachReport);
