import {useState, useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import useDatarequestsData from "./useDatarequestsData";
import {memo} from "@wordpress/element";
import './requests.scss';

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
	const [ entirePageSelected, setEntirePageSelected ] = useState( false );
	const [timer, setTimer] = useState(null)
    const {
        records,
        searchValue,
        setSearchValue,
        deleteRecords,
        recordsLoaded,
        fetchData,
        status,
        setStatus,
        resolveRecords,
        totalRecords,
        fetching,
        paginationPerPage,
        pagination,
        setPagination,
        orderBy,
        setOrderBy,
        order,
        setOrder,
        selectedRecords,
		setSelectedRecords,
		setIndeterminate,
		indeterminate,
    } = useDatarequestsData();
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

	const handleStatusFilter = (status) => {
		setStatus(status);
		const newTimer = setTimeout(() => {
			fetchData(paginationPerPage, 1, orderBy, order);
		}, 500)
	}

	const handlePerRowsChange = async (newPerPage, page) => {
		setPagination({ ...pagination, currentPage: page });
		fetchData(newPerPage, page, orderBy, order);
	};

	const handlePageChange = (page) => {
		setPagination({ ...pagination, currentPage: page });
		fetchData();
	};

	const handleSort = async (orderBy, order) => {
		setOrderBy(orderBy.orderId);
		setOrder(order);
		fetchData();
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
			if ( !docs.includes(record.ID) ) {
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
			name: <CheckboxGroup options={{true: ''}} indeterminate={indeterminate} value={entirePageSelected} onChange={(value) => handleSelectEntirePage(value)} />,
			selector: row => row.selectControl,
			orderId:'select',
			grow: 1,
		},
		// {
		// 	name: __('User ID',"complianz-gdpr"),
		// 	selector: row => row.ID,
		// 	sortable: true,
		// 	orderId:'ID',
		// },
		// {
		// 	name: __('Name',"complianz-gdpr"),
		// 	selector: row => row.name,
		// 	sortable: true,
		// 	orderId:'name',
		// },
		{
			name: __('Email',"complianz-gdpr"),
			selector: row => row.email,
			sortable: true,
			orderId:'email',
			grow: 3,
		},
		{
			name: __('Status',"complianz-gdpr"),
			selector: row => row.resolved==1 ? __('Resolved',"complianz-gdpr") : __('Open',"complianz-gdpr"),
			sortable: true,
			orderId:'resolved',
			grow: 1,

		},
		{
			name: __('Region',"complianz-gdpr"),
			selector: row => row.region ? <img alt="region" width="20px" height="20px" src={cmplz_settings.plugin_url+'assets/images/'+row.region+'.svg'} /> : '',
			sortable: true,
			grow: 1,
		},
		{
			name: __('Date',"complianz-gdpr"),
			selector: row => row.request_date,
			sortable: true,
			grow: 3,

		},
		{
			name: __('Data Request',"complianz-gdpr"),
			selector: row => row.type ? <a target="_blank" rel="noopener noreferrer" href={"https://complianz.io/"+row.type.slug}>{row.type.short}</a> : '',
			sortable: true,
			orderId:'resolved',
			right: true,
			grow: 3,
		},
	];
	let filteredRecords = [...records];

	//add the controls to the plugins
	let data = [];
	filteredRecords.forEach(record => {
		let recordCopy = {...record}
		recordCopy.selectControl = <CheckboxGroup disabled={fetching} value={selectedRecords.includes(recordCopy.ID)} options={{true: ''}} onChange={(value) => onSelectRecord(value, recordCopy.ID)} />
		data.push(recordCopy);
	});
	return (
		<>
			<div className="cmplz-table-header">
				<div className="cmplz-table-header-controls">
					<select value={status} onChange={(e) => handleStatusFilter(e.target.value)}>
						<option value='all'>{__('All',"complianz-gdpr")}</option>
						<option value='open'>{__('Open',"complianz-gdpr")}</option>
						<option value='resolved'>{__('Resolved',"complianz-gdpr")}</option>
					</select>
					<input className="cmplz-datatable-search" type="text" placeholder={__("Search", "complianz-gdpr")} value={searchValue} onChange={ ( e ) => handleSearch(e.target.value) } />
				</div>
			</div>
			{
				selectedRecords.length>0 &&
				<div className="cmplz-selected-document">
					{selectedRecords.length>1 && __("%s items selected", "complianz-gdpr").replace("%s", selectedRecords.length)}
					{selectedRecords.length===1 && __("1 item selected", "complianz-gdpr")}
					<div className="cmplz-selected-document-controls">
						{records.filter((record) => {return selectedRecords.includes(record.ID) && record.resolved!=1 }).length>0 && <button disabled={fetching} className="button button-default" onClick={() => resolveRecords(selectedRecords)}>{__("Mark as resolved", "complianz-gdpr")}</button> }
						<button disabled={fetching} className="button button-default cmplz-reset-button" onClick={() => onDeleteRecords(selectedRecords)}>{__("Delete", "complianz-gdpr")}</button>
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
					noDataComponent={<div className="cmplz-no-documents">{__("No records", "complianz-gdpr")}</div>}
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
