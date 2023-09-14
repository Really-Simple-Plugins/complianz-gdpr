import useFields from "../Settings/Fields/FieldsData";
import useStatistics from "./StatisticsData";
import { useState, useEffect } from "@wordpress/element";
import Icon from "../utils/Icon";
import { __ } from "@wordpress/i18n";
import {memo} from "@wordpress/element";
const StatisticsFeedback = () => {

	const {fields, getFieldValue, addHelpNotice} = useFields();
	const {regions, abTrackingCompleted, daysLeft, bestPerformerEnabled, loaded, fetchStatisticsData} = useStatistics();
	const [consentStatisticsEnabled, setConsentStatisticsEnabled] = useState(false);
	const [abTestingEnabled, setAbTestingEnabled] = useState(false);

	useEffect(() => {
		if (!loaded) {
			fetchStatisticsData();
		}
	},[])

	useEffect (() => {
		let consentStats = getFieldValue('a_b_testing')==1;
		setConsentStatisticsEnabled(consentStats);
		let ab = getFieldValue('a_b_testing_buttons')==1;
		setAbTestingEnabled(ab);

	},[fields])

	const Notice = (icon, color, text) => {
		return (
			<div className="cmplz-statistics-status">
				<Icon name={icon} color={color}/>
				{text}
			</div>
		)
	}

	useEffect(() => {
		let notice = __('The conversion graph shows the ratio for the different choices users have. When a user has made a choice, this will be counted as either a converted user, or a not converted. If no choice is made, the user will be listed in the "No choice" category.', 'complianz-gdpr');
		notice += '&nbsp;';
		if ( getFieldValue('use_country')==1 && regions.length>0) {
			const enabled_regions = regions.filter(region => region.value !== 'label').map(region => region.label);
			notice += __('As you have enabled geoip, there are several regions in which a banner is shown, in different ways. In regions apart from %s no banner is shown at all.', 'complianz-gdpr').replace('%s', enabled_regions.join(', '));
		}
		addHelpNotice('a_b_testing', 'warning', notice, __('Banners in different regions', 'complianz-gdpr') );

	},[regions])

	const options = {
		responsive: true,
		plugins: {
			legend: {
				position: 'top',
			},
		},
	};

	return (
		<>
			{ bestPerformerEnabled &&
				Notice('circle-check', 'green',__('The consent banner with the best results has been enabled as default banner.', 'complianz-gdpr') )
			}

			{ !bestPerformerEnabled && consentStatisticsEnabled && !abTestingEnabled &&
				Notice('circle-times', 'grey',__('A/B testing is disabled. Previously made progress is saved.', 'complianz-gdpr') )
			}

			{ !bestPerformerEnabled && abTestingEnabled &&
				<>
					{!abTrackingCompleted &&
						<>
							{daysLeft>1 && Notice('circle-check', 'green', __('A/B is enabled and will end in %s days.', 'complianz-gdpr').replace( '%s', daysLeft))}
							{daysLeft===1 && Notice('circle-check', 'green', __('A/B is enabled and will end in 1 day.', 'complianz-gdpr').replace( '%s', daysLeft))}
							{daysLeft===0 && Notice('circle-check', 'green', __('A/B is enabled and will end today.', 'complianz-gdpr'))}
						</>}
					{abTrackingCompleted &&
						<>
							{Notice('circle-check', 'green', __('The A/B tracking period has ended, the best performer will be enabled on the next scheduled check.', 'complianz-gdpr'))}
						</>
					}
				</>
			}
		</>
	);
};

export default memo(StatisticsFeedback);
