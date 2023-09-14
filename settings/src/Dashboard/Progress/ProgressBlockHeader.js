import { __ } from '@wordpress/i18n';
import {
    useEffect,
} from '@wordpress/element';
import useProgress from "./ProgressData";

const ProgressHeader = () => {
    const {setFilter, filter, fetchFilter, notices, progressLoaded} = useProgress();

    useEffect( () => {
        fetchFilter();
    }, [] );

    let all_task_count = 0;
    let open_task_count = 0;
    all_task_count = progressLoaded ? notices.length : 0;
    let openNotices = notices.filter(function (notice) {
        return notice.status!=='completed';
    });
    open_task_count = openNotices.length;
    return (
		<>
			<h3 className="cmplz-grid-title cmplz-h4">{ __( "Progress", 'complianz-gdpr' ) }</h3>
			<div className="cmplz-grid-item-controls">
				<div className={"cmplz-task-switcher-container cmplz-active-filter-"+filter}>
				<a href="#" className={"cmplz-task-switcher cmplz-all-tasks"} onClick={() => setFilter('all')} data-filter="all">
					{ __( "All tasks", "complianz-gdpr" ) }
				<span className="rsssl_task_count">({all_task_count})</span>
				</a>
					<a href="#" className={"cmplz-task-switcher cmplz-remaining-tasks"} onClick={() => setFilter('remaining')} data-filter="remaining">
                    	{ __( "Remaining tasks", "complianz-gdpr" )}
						<span className="rsssl_task_count">({open_task_count})</span>
					</a>
				</div>
			</div>
		</>

    );

}
export default ProgressHeader;
