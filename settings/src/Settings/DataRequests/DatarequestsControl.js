import {useState, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import useDatarequestsData from "./useDatarequestsData";
import {memo} from "react";
import CheckboxGroup from "../Inputs/CheckboxGroup";
const progressComponent = () => {
	return (
		<div>
			<div className="cmplz-integrations-placeholder">
				<div></div><div></div><div></div><div></div><div></div><div></div>
			</div>
		</div>
	)
}

const DatarequestsControl = () => {
	const paginationPerPage = 10;
	const [ pagination, setPagination] = useState({});
	const [ indeterminate, setIndeterminate] = useState(false);
	const [ orderBy, setOrderBy] = useState('ID');
	const [ order, setOrder] = useState('DESC');
	const [ entirePageSelected, setEntirePageSelected ] = useState( false );
	const [timer, setTimer] = useState(null)
	const { records, searchValue, setSearchValue, deleteRecords, recordsLoaded, fetchData,resolveRecords, totalRecords, fetching} = useDatarequestsData();
	const [ btnDisabled, setBtnDisabled ] = useState( '' );
	const [ selectedRecords, setSelectedRecords ] = useState( [] );
	const [DataTable, setDataTable] = useState(null);
	useEffect( () => {
		import('react-data-table-component').then(({ default: DataTable }) => {
			setDataTable(() => DataTable);
		});
	}, []);

	useEffect(() => {
		if (!recordsLoaded) fetchData(paginationPerPage, 1, orderBy, order);
	}, [recordsLoaded])

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

	const onDeleteRecords  = async (ids) => {
		setSelectedRecords([]);
		await deleteRecords(ids);
	}

	const handleSelectEntirePage = (selected) => {
		if ( selected ) {
			setEntirePageSelected(true);
			//add all records on this page to the selectedRecords array
			let currentPage = pagination.currentPage ? pagination.currentPage : 1;
			let recordsOnPage = records.slice((currentPage-1) * paginationPerPage, currentPage * paginationPerPage);
			setSelectedRecords(recordsOnPage.map(record => record.ID));
		} else {
			setEntirePageSelected(false);
			setSelectedRecords([]);
		}
		setIndeterminate(false);
	}



	const handleSearch = (search) => {
		clearTimeout(timer)
		setSearchValue(search);

		const newTimer = setTimeout(() => {
			fetchData(paginationPerPage, 1, orderBy, order);
		}, 500)

		setTimer(newTimer)
	}

	const handlePerRowsChange = async (newPerPage, page) => {
		setPagination({ ...pagination, currentPage: page });
		fetchData(newPerPage, page, orderBy, order);
	};

	const handlePageChange = (page) => {
		setPagination({ ...pagination, currentPage: page });
		fetchData(paginationPerPage, pagination.currentPage, orderBy, order);
	};

	const handleSort = async (orderBy, order) => {
		setOrderBy(orderBy.orderId);
		setOrder(order);
		fetchData(paginationPerPage, pagination.currentPage, orderBy.orderId, order);
	};

	const onSelectRecord = (selected, id) => {
		let docs = [...selectedRecords];
		if (selected) {
			if ( !docs.includes(id) ){
				docs.push(id);
				setSelectedRecords(docs);
			}
		} else {
			//remove the record from the selected records
			docs = [...selectedRecords.filter(recordId => recordId!==id)];
			setSelectedRecords(docs);
		}
		//check if all records on this page are selected
		let currentPage = pagination.currentPage ? pagination.currentPage : 1;
		let recordsOnPage = records.slice((currentPage-1) * paginationPerPage, currentPage * paginationPerPage);
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

	const columns = [
		{
			name: <CheckboxGroup options={{true: ''}} className={indeterminate? 'indeterminate' : ''} value={entirePageSelected} onChange={(value) => handleSelectEntirePage(value)} />,
			selector: row => row.selectControl,
			orderId:'select',
		},
		{
			name: __('User ID',"complianz-gdpr"),
			selector: row => row.ID,
			sortable: true,
			orderId:'ID',
		},
		{
			name: __('Name',"complianz-gdpr"),
			selector: row => row.name,
			sortable: true,
			orderId:'name',
		},
		{
			name: __('E-mail',"complianz-gdpr"),
			selector: row => row.email,
			sortable: true,
			orderId:'email',
		},
		{
			name: __('Status',"complianz-gdpr"),
			selector: row => row.resolved==1 ? __('Resolved',"complianz-gdpr") : __('Open',"complianz-gdpr"),
			sortable: true,
			orderId:'resolved',
		},
		{
			name: __('Region',"complianz-gdpr"),
			selector: row => row.region ? <img alt="region" width="20px" height="20px" src={cmplz_settings.plugin_url+'assets/images/'+row.region+'.svg'} /> : '',
			sortable: true,
		},
		{
			name: __('Date',"complianz-gdpr"),
			selector: row => row.request_date,
			sortable: true,
		},
		{
			name: __('Data Request',"complianz-gdpr"),
			selector: row => row.type ? <a target="_blank" href={"https://complianz.io/"+row.type.slug}>{row.type.short}</a> : '',
			sortable: true,
			orderId:'resolved',
			right: true,
		},
	];
	let filteredRecords = [...records];

	//add the controls to the plugins
	let data = [];
	filteredRecords.forEach(record => {
		let recordCopy = {...record}
		recordCopy.selectControl = <CheckboxGroup value={selectedRecords.includes(recordCopy.ID)} options={{true: ''}} onChange={(value) => onSelectRecord(value, recordCopy.ID)} />
		data.push(recordCopy);
	});
	return (
		<>
			<div className="cmplz-table-header">
				<div className="cmplz-table-header-controls">
					<input className="cmplz-datatable-search" type="text" placeholder={__("Search", "complianz-gdpr")} value={searchValue} onChange={ ( e ) => handleSearch(e.target.value) } />
				</div>
			</div>
			{
				selectedRecords.length>0 &&
				<div className="cmplz-selected-document">
					{selectedRecords.length>1 && __("%s items selected", "complianz-gdpr").replace("%s", selectedRecords.length)}
					{selectedRecords.length===1 && __("1 item selected", "complianz-gdpr")}
					<div className="cmplz-selected-document-controls">
						{records.filter((record) => {return selectedRecords.includes(record.ID) && record.resolved!=1 }).length>0 && <button disabled={btnDisabled} className="button button-default" onClick={() => resolveRecords(selectedRecords)}>{__("Mark as resolved", "complianz-gdpr")}</button> }
						<button className="button button-default cmplz-reset-button" onClick={() => onDeleteRecords(selectedRecords)}>{__("Delete", "complianz-gdpr")}</button>
					</div>
				</div>
			}
			{ DataTable && <>
				<DataTable
					columns={columns}
					data={data}
					dense
					// progressPending={fetching}
					// progressComponent=<progressComponent/>
					pagination
					paginationServer
					noDataComponent={<div className="cmplz-no-documents">{__("No records", "really-simple-ssl")}</div>}
					persistTableHead
					theme="really-simple-plugins"
					customStyles={customStyles}
					paginationPerPage={paginationPerPage}
					onChangePage={handlePageChange}
					paginationState={pagination}
					paginationTotalRows={totalRecords}
					onChangeRowsPerPage={handlePerRowsChange}
					onSort={handleSort}
					sortServer
				/></>
			}
		</>
	)
}
export default memo(DatarequestsControl)
