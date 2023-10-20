import {create} from 'zustand';
import {endOfDay, format, startOfDay, subDays} from 'date-fns';

// define the store
const useDate = create(set => ({
	startDate: format(startOfDay(subDays(new Date(), 7)), 'yyyy-MM-dd'),
	setStartDate: (startDate) => set(state => ({ startDate })),
	endDate: format(endOfDay(subDays(new Date(), 1)), 'yyyy-MM-dd'),
	setEndDate: (endDate) => set(state => ({ endDate })),
	range: 'last-7-days',
	setRange: (range) => set(state => ({ range })),
}));
export default useDate;
