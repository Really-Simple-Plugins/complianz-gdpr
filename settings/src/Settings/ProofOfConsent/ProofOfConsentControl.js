import {useState, useEffect} from "@wordpress/element";
import CheckboxGroup from '../Inputs/CheckboxGroup';
import { __ } from '@wordpress/i18n';
import useProofOfConsentData from "./useProofOfConsentData";
import {memo} from "@wordpress/element";
import './ProofOfConsentControl.scss';

const ProofOfConsentControl = () => {
	const { documents, downloadUrl, deleteDocuments, documentsLoaded, fetchData} = useProofOfConsentData();
	const [ btnDisabled, setBtnDisabled ] = useState( '' );
	const [ selectedDocuments, setSelectedDocuments ] = useState( [] );
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
							element.setAttribute('download', document.file);
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
	const handleSelectEntirePage = (selected) => {
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
			name: <CheckboxGroup  options={{true: ''}} indeterminate={indeterminate} value={entirePageSelected} onChange={(value) => handleSelectEntirePage(value)} />,
			selector: row => row.selectControl,
			grow: 1,
			minWidth: '50px',
		},
		{
			name: __('Document',"complianz-gdpr"),
			selector: row => row.file,
			sortable: true,
			grow: 5,
		},
		{
			name: __('Region',"complianz-gdpr"),
			selector: row => <img alt="region" width="20px" height="20px" src={cmplz_settings.plugin_url+'assets/images/'+row.region+'.svg'} />,
			sortable: true,
			grow: 2,
			right: true,
		},
		{
			name: __('Consent',"complianz-gdpr"),
			selector: row => row.consent,
			sortable: true,
			grow: 2,
			right: true,
		},
		{
			name: __('Date',"complianz-gdpr"),
			selector: row => row.time,
			sortable: true,
			grow: 4,
			right: true,
		},
	];
	let filteredDocuments = [...documents];
	filteredDocuments = handleFiltering(filteredDocuments);

	//add the controls to the plugins
	let data = [];
	filteredDocuments.forEach(document => {
		let documentCopy = {...document}
		documentCopy.selectControl = <CheckboxGroup value={selectedDocuments.includes(documentCopy.id)} options={{true: ''}} onChange={(e) => onSelectDocument( !selectedDocuments.includes(documentCopy.id), documentCopy.id )} />;
		data.push(documentCopy);
	});

	return (
		<>
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
			{DataTable && <>
				<DataTable
					className="cmplz-data-table"
					columns={columns}
					data={data}
					dense
					pagination
					paginationPerPage={paginationPerPage}
					onChangePage={handlePageChange}
					paginationState={pagination}
					noDataComponent={<div className="cmplz-no-documents">{__("No documents", "complianz-gdpr")}</div>}
					persistTableHead
					theme="really-simple-plugins"
					customStyles={customStyles}
				/></>
			}
		</>
	)
}
export default memo(ProofOfConsentControl);
