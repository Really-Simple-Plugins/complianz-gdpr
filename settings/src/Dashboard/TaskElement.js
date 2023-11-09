import { __ } from '@wordpress/i18n';
import Icon from '../utils/Icon'
import {dispatch,} from '@wordpress/data';
import * as cmplz_api from "../utils/api";
import sleeper from "../utils/sleeper";
import useFields from "../Settings/Fields/FieldsData";
import useProgress from "./Progress/ProgressData";
import useMenu from "../Menu/MenuData";
import DOMPurify from 'dompurify';

const TaskElement = ({notice, index}) => {
	const {dismissNotice, fetchProgressData} = useProgress();
	const {getField, setHighLightField, fetchFieldsData} = useFields();
	const {setSelectedSubMenuItem} = useMenu();

	const handleClick = async () => {
		setHighLightField(notice.highlight_field_id);
		let highlightField = getField(notice.highlight_field_id);
		await setSelectedSubMenuItem(highlightField.menu_id);
	}

	const handleClearCache = async (cache_id) => {
		let data = {};
		data.cache_id = cache_id;
		cmplz_api.doAction('clear_cache', data).then( async (response) => {
			const notice = dispatch('core/notices').createNotice(
				'success',
				__('Re-started test', 'complianz-gdpr'),
				{
					__unstableHTML: true,
					id: 'cmplz_clear_cache',
					type: 'snackbar',
					isDismissible: true,
				}
			).then(sleeper(3000)).then((response) => {
				dispatch('core/notices').removeNotice('rsssl_clear_cache');
			});
			await fetchFieldsData();
			await fetchProgressData();
		});
	}

	let premium = notice.icon==='premium';
	//treat links to complianz.io and internal links different.
	let urlIsExternal = notice.url && notice.url.indexOf('complianz.io') !== -1;
	let statusNice =  notice.status.charAt(0).toUpperCase() +  notice.status.slice(1);
	return(
		<div key={index} className="cmplz-task-element">
			<span className={'cmplz-task-status cmplz-' + notice.status}>{ statusNice }</span>
			<p className="cmplz-task-message"
		    	dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(notice.message) }}></p>{/* nosemgrep: react-dangerouslysetinnerhtml */}
			{urlIsExternal && notice.url && <a target="_blank" href={notice.url} rel="noopener noreferrer">{__("More info", "complianz-gdpr")}</a> }
			{notice.clear_cache_id && <span className="cmplz-task-enable button button-secondary" onClick={ () => handleClearCache(notice.clear_cache_id ) }>{__("Re-check", "complianz-gdpr")}</span> }
			{!premium && !urlIsExternal && notice.url && <a className="cmplz-task-enable button button-secondary" href={notice.url}>{__("View", "complianz-gdpr")}</a> }
			{!premium && notice.highlight_field_id && <span className="cmplz-task-enable button button-secondary" onClick={() => handleClick()}>{__("View", "complianz-gdpr")}</span> }
			{notice.plus_one && <span className='cmplz-plusone'>1</span>}
			{notice.dismissible && notice.status!=='completed' &&
				<div className="cmplz-task-dismiss">
					<button type='button' onClick={(e) => dismissNotice(notice.id) }>
						<Icon name='times' />
					</button>
				</div>
			}
		</div>
	);
}

export default TaskElement;
