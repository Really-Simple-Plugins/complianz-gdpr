import useDataBreachReportsData from "./DataBreachReportsData";
import {useState, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import Icon from "../../utils/Icon";
import {memo} from "@wordpress/element";
import CheckboxGroup from '../Inputs/CheckboxGroup';

const DataBreachReportsControl = () => {
	const { documents, documentsLoaded, fetchData, deleteDocuments, editDocument} = useDataBreachReportsData();
	const [ searchValue, setSearchValue ] = useState( '' );
	const [ regionFilter ] = useState( '' );
	const [ btnDisabled, setBtnDisabled ] = useState( '' );
	const [ selectedDocuments, setSelectedDocuments ] = useState( [] );
	const [ downloading, setDownloading ] = useState( false );
	const paginationPerPage = 5;
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
		if (!documentsLoaded ) fetchData();
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
		setDownloading(true);
		setBtnDisabled(true);
		const downloadNext = async () => {
			if (selectedDocumentsCopy.length > 0) {
				const document = selectedDocumentsCopy.shift();
				const url = document.download_url;

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
							element.setAttribute('download', document.title);
							window.document.body.appendChild(element);
							//onClick property
							element.click();
							setSelectedDocuments(selectedDocumentsCopy);
							setDownloading(false);
							setBtnDisabled(false);

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
	};

	const handleSelectEntirePage = (value) => {
		let selected = value
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
	const onSelectDocument = (value, id) => {
		let selected = value;
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

		if (allSelected) {
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
		let newDocuments = [...documents];
		if ( regionFilter!=='') {
			newDocuments = newDocuments.filter(document => {
				return document.region===regionFilter;
			})
		}

		//sort the plugins alphabetically by title
		newDocuments.sort((a, b) => {
			if (a.title < b.title) {
				return -1;
			}
			if (a.title > b.title) {
				return 1;
			}
			return 0;
		});

		newDocuments.filter(document => {
			return document.title.toLowerCase().includes(searchValue.toLowerCase());
		})

		return newDocuments;
	}

	const columns = [
		{
			name: <CheckboxGroup options={{true: ''}} indeterminate={indeterminate} value={entirePageSelected} onChange={(value) => handleSelectEntirePage(value)} />,
			selector: row => row.selectControl,
			grow: 1,
			minWidth: '50px',
		},
		{
			name: __('Document',"complianz-gdpr"),
			selector: row => row.title,
			sortable: true,
			grow: 6,
		},
		{
			name: __('Region',"complianz-gdpr"),
			selector: row => <img alt="region" width="20px" height="20px" src={cmplz_settings.plugin_url+'assets/images/'+row.region+'.svg'} />,
			sortable: true,
			grow: 2,
			right: true,
		},
		{
			name: __('Date',"complianz-gdpr"),
			selector: row => row.date,
			sortable: true,
			grow: 4,
			right: true,
			minWidth: '200px',
		},
	];

	//filter the plugins by search value
	let filteredDocuments = handleFiltering(documents);

	//add the controls to the plugins
	let data = [];
	filteredDocuments.forEach(document => {
		let documentCopy = {...document};
		documentCopy.selectControl = <CheckboxGroup value={selectedDocuments.includes(documentCopy.id)} options={{true: ''}} onChange={(value) => onSelectDocument(value,documentCopy.id)} />
		data.push(documentCopy);
	});

	let showDownloadButton = selectedDocuments.length>1;
	if (!showDownloadButton && selectedDocuments.length===1) {
		let currentSelected = documents.filter(document => selectedDocuments.includes(document.id));
		showDownloadButton = currentSelected[0].download_url!=='';
	}
	return (
		<>
			<div className="cmplz-table-header">
				<div className="cmplz-table-header-controls">
				{/*<select onChange={(e) => setRegionFilter(e.target.value)} value={regionFilter}>*/}
				{/*	{documentsLoaded && regions.length>0 && regions.map((region, i) => <option key={i} value={region.value} >{region.label}</option>)}*/}
				{/*</select>*/}
				<input className="cmplz-datatable-search" type="text" placeholder={__("Search", "complianz-gdpr")} value={searchValue} onChange={ ( e ) => setSearchValue(e.target.value) } />
				</div>
			</div>

			{
				selectedDocuments.length>0 &&
				<div className="cmplz-selected-document">
					{selectedDocuments.length>1 && __("%s items selected", "complianz-gdpr").replace("%s", selectedDocuments.length)}
					{selectedDocuments.length===1 && __("1 item selected", "complianz-gdpr")}
					<div className="cmplz-selected-document-controls">
						<button disabled={btnDisabled || selectedDocuments.length>1} className="button button-default" onClick={() => editDocument(selectedDocuments[0])}>{__("Edit","complianz-gdpr")}</button>
						{showDownloadButton && <button disabled={btnDisabled} className="button button-default cmplz-btn-reset" onClick={() => downloadDocuments()}>
							{__("Download Data Breach Report", "complianz-gdpr")}
							{downloading && <Icon name = "loading" color = 'grey' />}
						</button> }
						{!showDownloadButton && <button disabled={true} className="button button-default cmplz-btn-reset" onClick={() => downloadDocuments()}>
							{__("Reporting not required", "complianz-gdpr")}
						</button> }
						<button className="button button-default cmplz-reset-button" onClick={() => onDeleteDocuments(selectedDocuments)}>{__("Delete", "complianz-gdpr")}</button>
					</div>
				</div>
			}

			{ DataTable && <>
				<DataTable
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
export default memo(DataBreachReportsControl);
