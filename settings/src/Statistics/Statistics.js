import useFields from "../Settings/Fields/FieldsData";
import useStatistics from "./StatisticsData";
import { useState, useEffect } from "@wordpress/element";
import useLicense from "../Settings/License/LicenseData";
import {memo} from "@wordpress/element";
// import { useMemo } from 'react';
const Statistics = () => {

	const {fields, getFieldValue} = useFields();
	const [abTestingEnabled, setAbTestingEnabled] = useState(false);
	const [data, setData] = useState(false);
	const {consentType, setConsentType, statisticsData, emptyStatisticsData, consentTypes, loaded, fetchStatisticsData, statisticsLoading} = useStatistics();
	const [Bar, setBar] = useState(null);
	const {licenseStatus} = useLicense();

	useEffect ( () => {
		import ('chart.js').then(({
			  Chart,
			  CategoryScale,
			  LinearScale,
			  BarElement,
			  Title,
			  Tooltip,
			  Legend
		}) => {
			Chart.register(
				CategoryScale,
				LinearScale,
				BarElement,
				Title,
				Tooltip,
				Legend
			);
		});
		import ('react-chartjs-2').then(({ Bar }) => {
			setBar(() => Bar);
		});
	}, []);

	useEffect(() => {
		if ( !loaded && abTestingEnabled) {
			fetchStatisticsData();
		}
	},[loaded, abTestingEnabled ]);

	useEffect(() => {
		if (getFieldValue('a_b_testing')==1) {
			setAbTestingEnabled(true);
		} else {
			setAbTestingEnabled(false);
		}
	},[fields]);

	useEffect(() => {
		//initial values
		if (licenseStatus==='valid' && abTestingEnabled) {
			if (emptyStatisticsData.hasOwnProperty(consentType)) {
				setData(emptyStatisticsData[consentType]);
			}
		} else {
			if (statisticsData.hasOwnProperty(consentType)) {
				setData(statisticsData[consentType]);
			}
		}

	},[])

	useEffect(() => {
		if (abTestingEnabled) {
			setData(statisticsData[consentType]);
		}
	},[consentType, abTestingEnabled, loaded])


	const options = {
		responsive: true,
		plugins: {
			legend: {
				position: 'top',
			},
		},
	};
	const loadingClass = statisticsLoading ? 'cmplz-loading' : '';
	return (
		 <>
			 {consentTypes.length>1 && <select value={consentType} onChange={(e) => setConsentType(e.target.value)}>
				 {consentTypes.map((type, i) =>
					 <option key={i} value={type.id} >{type.label}</option>)}
			 </select>}
			 {data && Bar && <Bar className={`cmplz-loading-container ${loadingClass}`} options={options} data={data} />}
		 </>
	);
};

export default memo(Statistics);

