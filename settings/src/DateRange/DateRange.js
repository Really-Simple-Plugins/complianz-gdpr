import {
	useState,
	useRef,
} from "@wordpress/element";
import Popover from '@mui/material/Popover';

// date range picker and date fns
import { DateRangePicker } from 'react-date-range';
import {format, parseISO, startOfYear, endOfYear, addYears, addDays, addMonths, isSameDay, startOfDay, endOfDay, startOfMonth, endOfMonth} from 'date-fns'
import Icon from '../utils/Icon';
import {__} from '@wordpress/i18n';
import useDate from './useDateStore';

const DateRange = () => {
	const [anchorEl, setAnchorEl] = useState(null);
	const open = Boolean(anchorEl);
	const startDate = useDate((state) => state.startDate);
	const endDate = useDate((state) => state.endDate);
	const setStartDate = useDate((state) => state.setStartDate);
	const setEndDate = useDate((state) => state.setEndDate);
	const range = useDate((state) => state.range);
	const setRange = useDate((state) => state.setRange);

	const selectionRange = {
		startDate:  parseISO(startDate),
		endDate: parseISO(endDate),
		key: 'selection',
	};
	const countClicks = useRef(0);
	// select date ranges from settings
	const selectedRanges = [
		'today',
		'yesterday',
		'last-7-days',
		'last-30-days',
		'last-90-days',
		'last-month',
		'last-year',
		'year-to-date',
	];
	const availableRanges = {
		'today': {
			label: __('Today', 'complianz-gdpr' ),
			range: () => ({
				startDate: startOfDay(new Date()),
				endDate: endOfDay(new Date())
			})
		},
		'yesterday': {
			label: __('Yesterday', 'complianz-gdpr'),
			range: () => ({
				startDate: startOfDay(addDays(new Date(), -1)),
				endDate: endOfDay(addDays(new Date(), -1))
			})
		},
		'last-7-days': {
			label: __('Last 7 days', 'complianz-gdpr'),
			range: () => ({
				startDate: startOfDay(addDays(new Date(), -7)),
				endDate: endOfDay(addDays(new Date(), -1))
			})
		},
		'last-30-days': {
			label: __('Last 30 days', 'complianz-gdpr' ),
			range: () => ({
				startDate: startOfDay(addDays(new Date(), -30)),
				endDate: endOfDay(addDays(new Date(), -1))
			})
		},
		'last-90-days': {
			label: __('Last 90 days', 'complianz-gdpr'),
			range: () => ({
				startDate: startOfDay(addDays(new Date(), -90)),
				endDate: endOfDay(addDays(new Date(), -1))
			})
		},
		'last-month': {
			label: __('Last month', 'complianz-gdpr' ),
			range: () => ({
				startDate: startOfMonth(addMonths(new Date(), -1)),
				endDate: endOfMonth(addMonths(new Date(), -1))
			})
		},
		'year-to-date': {
			label: __('Year to date', 'complianz-gdpr' ),
			range: () => ({
				startDate: startOfYear(new Date()),
				endDate: endOfDay(new Date())
			})
		},
		'last-year': {
			label: __('Last year', 'complianz-gdpr' ),
			range: () => ({
				startDate: startOfYear(addYears(new Date(), -1)),
				endDate: endOfYear(addYears(new Date(), -1))
			})
		},
	}
	function isSelected(range) {
		const definedRange = this.range();
		return (
			isSameDay(range.startDate, definedRange.startDate) &&
			isSameDay(range.endDate, definedRange.endDate)
		);
	}

	// for all selected ranges add daterange and isSelected function
	const dateRanges = [];
	for (const [key, value] of Object.entries(selectedRanges)) {
		if (value) {
			dateRanges.push(availableRanges[value]);
			dateRanges[dateRanges.length - 1].isSelected = isSelected;
		}
	}

	const handleClick = (e) => {
		setAnchorEl(e.currentTarget);
	};

	const handleClose = (e) => {
		setAnchorEl(null);
	};


	const updateDateRange = (ranges) => {
		countClicks.current++
		// setSelectionRange(ranges.selection);
		let startStr = format(ranges.selection.startDate, 'yyyy-MM-dd');
		let endStr = format(ranges.selection.endDate, 'yyyy-MM-dd');
		let range = 'custom';

		// loop through availableRanges and check if the selected range is one of them
		for (const [key, value] of Object.entries(availableRanges)) {
			if (value.isSelected(ranges.selection)) {
				range = key;
			}
		}
		let dateRange = {
			startDate: ranges.selection.startDate,
			endDate: ranges.selection.endDate,
			range: range
		}

		if ( countClicks.current === 2 || startStr !== endStr || range !== 'custom' ) {
			countClicks.current = 0;
			setStartDate(startStr);
			setEndDate(endStr);
			setRange(range);
			handleClose();
		}

	}

	const formatString = 'MMMM d, yyyy';
	const display = {
		startDate: startDate ? format(new Date(startDate), formatString) : format(defaultStart, formatString),
		endDate: endDate ? format(new Date(endDate), formatString) : format(defaultEnd, formatString),
	}
	return (
		<div className="cmplz-date-range-container">
			<button onClick={handleClick} id="cmplz-date-range-picker-open-button">
				<Icon name='calendar' size={'18'}/>

				{range === 'custom' && display.startDate +  ' - ' +  display.endDate}
				{range !== 'custom' && availableRanges[range].label}
				<Icon name='chevron-down' />
			</button>
			<Popover
				anchorEl={anchorEl}
				anchorOrigin={{vertical: 'bottom', horizontal: 'right'}}
				transformOrigin={{vertical: 'top', horizontal: 'right'}}
				open={open}
				onClose={handleClose}
				className="burst"
			>
				<div id="cmplz-date-range-picker-container">
					<DateRangePicker
						ranges={[selectionRange]}
						rangeColors={['var(--rsp-brand-primary)']}
						dateDisplayFormat={formatString}
						monthDisplayFormat="MMMM"
						// color="var(--rsp-text-color)"
						onChange={(ranges) => {updateDateRange(ranges)}}
						inputRanges={[]}
						showSelectionPreview={true}
						// moveRangeOnFirstSelection={false}
						months={2}
						direction="horizontal"
						minDate={new Date(2022, 0, 1)}
						maxDate={ new Date() }
						staticRanges={dateRanges}
					/>
				</div>
			</Popover>
		</div>
	);

}

export default DateRange;
