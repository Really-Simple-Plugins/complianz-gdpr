import Icon from "../../utils/Icon";
import {__} from "@wordpress/i18n";
import useStatistics from "../../Statistics/StatisticsData";
import {
	useEffect, useState
} from '@wordpress/element';

const Statistics = () => {
	const [data, setData] = useState(false);
	const [total, setTotal] = useState(1);
	const [fullConsent, setFullConsent] = useState(0);
	const [noConsent, setNoConsent] = useState(0);
	const {consentType, statisticsData, loaded, fetchStatisticsData, labels, setLabels} = useStatistics();
	useEffect ( () => {
		if (!loaded && cmplz_settings.is_premium) {
			fetchStatisticsData();
		}
	},[]);

	useEffect(() => {
		if ( consentType==='' || !loaded ) {
			return;
		}

		if ( !statisticsData || !statisticsData.hasOwnProperty(consentType) ) {
			return;
		}

		let temp = [ ...statisticsData[consentType]['labels'] ];
		//get categories
		let categories = statisticsData[consentType]['categories'];

		//if it's optin, slice these indexes from the labels.
		if ( consentType==='optin' ) {
			categories = categories.filter((category) => category==='functional' || category==='no_warning' || category==='do_not_track');
		} else {
			//get array of indexes for categories functional, marketing, statistics, preferences
			categories = categories.filter((category) => category==='functional' || category==='marketing' || category==='statistics' || category==='preferences');
		}

		//get indexes for these categories
		let categoryIndexes = categories.map((category) => statisticsData[consentType]['categories'].indexOf(category));
		//remove these indexes from the labels array
		for (let i = categoryIndexes.length - 1; i >= 0; i--) {
			temp.splice(categoryIndexes[i], 1);
		}
		setLabels(temp);
	},[loaded, consentType])

	useEffect(() => {
		if ( consentType==='' || !loaded || !statisticsData ) {
			return;
		}

		let data = statisticsData[consentType]['datasets'];
		//get the dataset with default flag
		let defaultDatasets = data.filter((dataset) => dataset.default);
		if (defaultDatasets.length>0) {
			let defaultDataset = defaultDatasets[0]['data'];
			//sum all values of the default dataset
			let total = defaultDataset.reduce((a, b) => parseInt(a) + parseInt(b), 0);
			total = total>0 ? total : 1;
			setTotal(total);
			setFullConsent(defaultDatasets[0].full_consent);
			setNoConsent(defaultDatasets[0].no_consent);
			defaultDataset = defaultDataset.slice(2);
			setData(defaultDataset);
		}
	},[loaded, consentType])

	const getPercentage = (value) => {
		value = parseInt(value);
		return Math.round((value/total)*100);
	}

	const getRowIcon = (index) => {
		let name = 'dial-med-low-light';
		if (index===1) {
			name = 'dial-med-light';
		} else if (index===2) {
			name = 'dial-light';
		}else if (index===3) {
			name = 'dial-off-light';
		} else if (index===4) {
			name = 'dial-min-light';
		}
		return (
			<>
				<Icon name = {name} color = 'black' />
			</>
		)
	}

	return (
		<div className="cmplz-statistics">
			<div className="cmplz-statistics-select">
				<div className="cmplz-statistics-select-item">
					<Icon name = "dial-max-light" color={"green"} size="22"/>
					<h2>{fullConsent}</h2>
					<span>{__('Full Consent', 'complianz-gdpr')}</span>
				</div>
				<div className="cmplz-statistics-select-item">
					<Icon name = "dial-min-light" color={"red"} size="22"/>
					<h2>{noConsent}</h2>
					<span>{__('No Consent', 'complianz-gdpr')}</span>
				</div>
			</div>
			<div className="cmplz-statistics-list">
				{labels.length>0 && labels.map((label, index) =>
					<div className="cmplz-statistics-list-item" key={index}>
						{getRowIcon(index)}
						<p className="cmplz-statistics-list-item-text">{label}</p>
						<p className="cmplz-statistics-list-item-number">{data.hasOwnProperty(index) ? getPercentage(data[index]) : 0}%</p>
					</div>
				)}
			</div>
		</div>
	)
}
export default Statistics;
