import Modal from '../Modal';
import DialogContent from '@mui/material/DialogContent';
import DialogActions from '@mui/material/DialogActions';
import {__} from '@wordpress/i18n';

const AreYouSureModal = ({ isOpen, onCancel, onConfirm, children }) => {

	return (
		<Modal isOpen={isOpen} title={__('Are you sure?', 'complianz-gdpr')} onClose={onCancel}>
			<DialogContent>
				{children}
			</DialogContent>
			<DialogActions className={'cmplz-modal-footer'}>
				<button className={"cmplz-button cmplz-button--secondary"} onClick={onCancel}>
					{__('Cancel', 'complianz-gdpr')}
				</button>
				<button className={"cmplz-button cmplz-button--error"} onClick={onConfirm}>
					{__('Confirm', 'complianz-gdpr')}
				</button>
			</DialogActions>
		</Modal>
	);
};

export default AreYouSureModal;
