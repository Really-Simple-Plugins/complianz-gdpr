import {useState, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import useProofOfConsentData from "./useProofOfConsentData";
import {memo} from "react";
// import * as Checkbox from '@radix-ui/react-checkbox';
// import Icon from '../../utils/Icon';
import './ProofOfConsentControl.scss';
import '../Inputs/Input.scss';

const ProofOfConsentControl = () => {
	const { documents, downloadUrl, deleteDocuments, documentsLoaded, fetchData} = useProofOfConsentData();
	const [ btnDisabled, setBtnDisabled ] = useState( '' );
	const [ selectedDocuments, setSelectedDocuments ] = useState( [] );
	const disabled = !cmplz_settings.is_premium;
	const paginationPerPage = 10;
	const [ pagination, setPagination] = useState({});
	const [ indeterminate, setIndeterminate] = useState(false);
	const [ entirePageSelected, setEntirePageSelected ] = useState( false );
	const handlePageChange = (page) => {
		setPagination({ ...pagination, currentPage: page });
	};
	const [DataTable, setDataTable] = useState(null);
	useEffect( () => {
		import('react-data-table-component').then(({ default: DataTable }) => {
			setDataTable(() => DataTable);
		});
	}, []);

	useEffect(() => {
		if (!documentsLoaded) fetchData();
	}, [documentsLoaded])


	const customStyles = {
		headCells: {
			style: {
				paddingLeft: '0',
				paddingRight: '0',
			},
		},
		cells: {
			style: {
				paddingLeft: '0',
				paddingRight: '0',
			},
		},
	};

	const onDeleteDocuments  = async (ids) => {
		setSelectedDocuments([]);
		await deleteDocuments(ids);
	}

	const downloadDocuments = async () => {
		let selectedDocumentsCopy = documents.filter(document => selectedDocuments.includes(document.id));
		setSelectedDocuments([]);
		const downloadNext = async () => {
			if (selectedDocumentsCopy.length > 0) {
				const document = selectedDocumentsCopy.shift();
				const url = downloadUrl + '/' + document.file;
				setBtnDisabled(true);
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
							element.setAttribute('download', document.filename);
							window.document.body.appendChild(element);
							//onClick property
							element.click();
							setTimeout(function() {
								window.URL.revokeObjectURL(obj);
							}, 60 * 1000);
						}
					};

					await downloadNext();
				} catch (error) {
					console.error(error);
					setBtnDisabled(false);
				}
			}
		};

		await downloadNext();
		setBtnDisabled(false);
	};
	const handleSelectEntirePage = (e) => {
		let selected = e.target.checked;
		if ( selected ) {
			setEntirePageSelected(true);
			//add all records on this page to the selectedRecords array
			let currentPage = pagination.currentPage ? pagination.currentPage : 1;
			//get records from currentPage * paginationPerPage to (currentPage+1) * paginationPerPage
			let filtered = handleFiltering(documents);
			let recordsOnPage = filtered.slice((currentPage-1) * paginationPerPage, currentPage * paginationPerPage);
			setSelectedDocuments(recordsOnPage.map(document => document.id));
		} else {
			setEntirePageSelected(false);
			setSelectedDocuments([]);
		}
		setIndeterminate(false);
	}
	const onSelectDocument = (selected, id) => {
		let docs = [...selectedDocuments];
		if (selected) {
			if ( !docs.includes(id) ){
				docs.push(id);
				setSelectedDocuments(docs);
			}
		} else {
			//remove the document from the selected documents
			docs = [...selectedDocuments.filter(documentId => documentId!==id)];
			setSelectedDocuments(docs);
		}
		//check if all records on this page are selected
		let currentPage = pagination.currentPage ? pagination.currentPage : 1;
		//get records from currentPage * paginationPerPage to (currentPage+1) * paginationPerPage
		let filtered = handleFiltering(documents);
		let recordsOnPage = filtered.slice((currentPage-1) * paginationPerPage, currentPage * paginationPerPage);
		let allSelected = true;
		let hasOneSelected = false;
		recordsOnPage.forEach(record => {
			if ( !docs.includes(record.id) ) {
				allSelected = false;
			} else {
				hasOneSelected = true;
			}
		});

		if (allSelected ) {
			setEntirePageSelected(true);
			setIndeterminate(false);
		} else if (!hasOneSelected) {
			setIndeterminate(false);
		} else {
			setEntirePageSelected(false);
			setIndeterminate(true);
		}
	}

	const handleFiltering = (documents) => {
		// //sort the plugins alphabetically by filename
		documents.sort((a, b) => {
			if (a.file < b.file) {
				return -1;
			}
			if (a.file > b.file) {
				return 1;
			}
			return 0;
		});
		return documents;
	}
	const columns = [
		{
			name: <input type="checkbox" className={indeterminate? 'indeterminate' : ''} checked={entirePageSelected} onChange={(e) => handleSelectEntirePage(e)} />,
			// name: (<div className={'cmplz-checkbox-group'}>
			// 	<div className={'cmplz-checkbox-group__item'} >
			// 		<Checkbox.Root
			// 			className={indeterminate? 'cmplz-checkbox-group__checkbox indeterminate' : 'cmplz-checkbox-group__checkbox'}
			// 			onCheckedChange={handleSelectEntirePage}
			// 		>
			// 			<Checkbox.Indicator className="cmplz-checkbox-group__indicator">
			// 				{indeterminate && <Icon className={'bullet'} size={14} color={'dark-blue'} />}
			// 				{!indeterminate && entirePageSelected && 	<Icon name={'check'} size={14} color={'dark-blue'} />}
			// 			</Checkbox.Indicator>
			// 		</Checkbox.Root>
			// 	</div>
			// </div>),
			selector: row => row.selectControl,
		},
		{
			name: __('Document',"complianz-gdpr"),
			selector: row => row.file,
			sortable: true,
		},
		{
			name: __('Region',"complianz-gdpr"),
			selector: row => <img alt="region" width="20px" height="20px" src={cmplz_settings.plugin_url+'assets/images/'+row.region+'.svg'} />,
			sortable: true,
		},
		{
			name: __('Consent',"complianz-gdpr"),
			selector: row => row.consent,
			sortable: true,
		},
		{
			name: __('Date',"complianz-gdpr"),
			selector: row => row.time,
			sortable: true,
		},
	];
	let filteredDocuments = [...documents];
	filteredDocuments = handleFiltering(filteredDocuments);

	//add the controls to the plugins
	let data = [];
	filteredDocuments.forEach(document => {
		let documentCopy = {...document}
		documentCopy.selectControl = <input type="checkbox" checked={selectedDocuments.includes(documentCopy.id)} onChange={(e) => onSelectDocument( !selectedDocuments.includes(documentCopy.id), documentCopy.id )} />;
		// documentCopy.selectControl = (<div className={'cmplz-checkbox-group'}>
		// 	<div className={'cmplz-checkbox-group__item'} >
		// 		<Checkbox.Root
		// 			className={indeterminate? 'cmplz-checkbox-group__checkbox indeterminate' : 'cmplz-checkbox-group__checkbox'}
		// 			onCheckedChange={() => onSelectDocument(selectedDocuments.includes(documentCopy.id), documentCopy.id)}
		// 		>
		// 			<Checkbox.Indicator className="cmplz-checkbox-group__indicator">
		// 				{indeterminate && <Icon className={'check'} size={14} color={'dark-blue'} />}
		// 			</Checkbox.Indicator>
		// 		</Checkbox.Root>
		// 	</div>
		// </div>);
		data.push(documentCopy);
	});

	return (
		<>
			{ (disabled ) && <>
				<div className="cmplz-settings-overlay">
					<div className="cmplz-settings-overlay-message"></div>
				</div>
			</>}
			{
				selectedDocuments.length>0 &&
					<div className="cmplz-selected-document">
						{selectedDocuments.length>1 && __("%s items selected", "complianz-gdpr").replace("%s", selectedDocuments.length)}
						{selectedDocuments.length===1 && __("1 item selected", "complianz-gdpr")}
						<div className="cmplz-selected-document-controls">
							<button disabled={btnDisabled} className="button button-default cmplz-btn-reset" onClick={() => downloadDocuments()}>{__("Download Proof of Consent", "complianz-gdpr")}</button>
							<button className="button button-default cmplz-reset-button" onClick={() => onDeleteDocuments(selectedDocuments)}>{__("Delete", "complianz-gdpr")}</button>
						</div>
					</div>
			}
			{!disabled && DataTable && <>
				<DataTable
					className="cmplz-data-table"
					columns={columns}
					data={data}
					dense
					pagination
					paginationPerPage={paginationPerPage}
					onChangePage={handlePageChange}
					paginationState={pagination}
					noDataComponent={<div className="cmplz-no-documents">{__("No documents", "really-simple-ssl")}</div>}
					persistTableHead
					theme="really-simple-plugins"
					customStyles={customStyles}
				/></>
			}
		</>
	)
}
export default memo(ProofOfConsentControl);
