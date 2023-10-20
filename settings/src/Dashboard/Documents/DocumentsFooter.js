import {__} from '@wordpress/i18n';
import Icon from '../../utils/Icon';

const DocumentsFooter = () => {
	return (
		<>
			<div className="cmplz-legend">
				<Icon name={'sync'} color="green" size={14}/>
				<span>{__('Synchronized', 'complianz-gdpr')}</span>
			</div>
			<div className="cmplz-legend">
				<Icon name={'circle-check'} color="green" size={14}/>
				<span>{__('Validated', 'complianz-gdpr')}</span>
			</div>
	</>
	)
};
export default DocumentsFooter;
