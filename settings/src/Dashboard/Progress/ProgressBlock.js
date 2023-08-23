import {
    useEffect
} from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import TaskElement from "./../TaskElement";
import Placeholder from '../../Placeholder/Placeholder';
import useProgress from "./ProgressData";
import useFields from "../../Settings/Fields/FieldsData";

const ProgressBlock = () => {
    const {percentageCompleted, filter, notices, progressLoaded, fetchProgressData, error} = useProgress();
	const {fetchAllFieldsCompleted, allRequiredFieldsCompleted, fields} = useFields();

    useEffect( () => {
		const run = async () => {
			if ( !progressLoaded ) {
				await fetchProgressData();
			}
			fetchAllFieldsCompleted();

		}
		run();
    }, [filter, fields] );

    const getStyles = () => {
        return Object.assign(
            {},
            {width: percentageCompleted+"%"},
        );
    }

    let progressBarColor = '';
    if ( percentageCompleted<80 ) {
        progressBarColor += 'cmplz-orange';
    }
    if ( !progressLoaded || error ) {
        return (
            <Placeholder lines='9' error={error}></Placeholder>
        );
    }

    let noticesOutput = notices;
    if ( filter==='remaining' ) {
        noticesOutput = noticesOutput.filter(function (notice) {
            return notice.status!=='completed';
        });
    }

	if ( !allRequiredFieldsCompleted && noticesOutput.filter(notice => notice.id==='all_fields_completed').length===0 ) {
		let notice = {
			id: 'all_fields_completed',
			status: 'urgent',
			message: __( 'Not all fields have been entered, or you have not clicked the "finish" button yet.', 'complianz-gdpr' ),
		}
		noticesOutput.push(notice);
	}
	if (allRequiredFieldsCompleted) {
		noticesOutput = noticesOutput.filter(notice => notice.id!=='all_fields_completed');
	}

	//sorting by status
	noticesOutput.sort(function(a, b) {
		if (a.status === b.status) {
			return 0;
		}
		else {
			return (a.status < b.status) ? 1 : -1;
		}
	});
	let openNotices = noticesOutput.filter(notice => notice.status==='open' || notice.status==='urgent');
    return (
        <div className="cmplz-progress-block">
            <div className="cmplz-progress-bar">
                <div className="cmplz-progress">
                    <div className={'cmplz-bar ' + progressBarColor} style={getStyles()}></div>
                </div>
            </div>

            <div className="cmplz-progress-text">
                <h1 className="cmplz-progress-percentage">
                    {percentageCompleted}%
                </h1>
                <h5 className="cmplz-progress-text-span">
                    {percentageCompleted < 100 && __( 'Consent Management is activated on your site.',  'complianz-gdpr' )+' ' }
                    {percentageCompleted< 100 && openNotices.length===1 && __( 'You still have 1 task open.', 'complianz-gdpr' )}
                    {percentageCompleted < 100 && openNotices.length>1 && __( 'You still have %s tasks open.','complianz-gdpr' ).replace('%s', openNotices.length) }
					{percentageCompleted===100  && __( 'Well done! Your website is ready for your selected regions.', 'complianz-gdpr' )}

                </h5>
            </div>

            <div className="cmplz-scroll-container">
                {noticesOutput.map((notice, i) => <TaskElement key={i} index={i} notice={notice}/>)}
            </div>
        </div>
    );

}
export default ProgressBlock;
