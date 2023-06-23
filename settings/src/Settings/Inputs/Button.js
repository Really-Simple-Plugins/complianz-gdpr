import './Buttons.scss'
import {memo, useState} from 'react';
import * as cmplz_api from '../../utils/api';
import useFields from '../Fields/FieldsData';
import useMenu from '../../Menu/MenuData';
import {
	__experimentalConfirmDialog as ConfirmDialog
} from '@wordpress/components';
// import AreYouSureModal from '../AreYouSureModal';

const Button = ({
	type = 'action',
	style = 'tertiary',
	label,
	onClick,
	href = '',
	target = '',
	disabled,
	action,
	field,
	children
}) =>
{
	if ( !label && !children ) return null;
	const buttonLabel = field && field.button_text ? field.button_text : label;
	const content = buttonLabel ? buttonLabel : children;
	const {fetchFieldsData, showSavedSettingsNotice} = useFields();
	const {selectedSubMenuItem } = useMenu();
	const [ isOpen, setIsOpen ] = useState( false );
	const classes = `button cmplz-button button--${style} button-${type}`;

	const clickHandler = async (e) => {
		if (type === 'action' && onClick) {
			onClick(e);
			return;
		}
		if (type === 'action' && action) {
			if (field && field.warn) {
				setIsOpen( true );
			} else {
				await executeAction();
			}
			return;
		}
		window.location.href=field.url;
	}

	const handleConfirm = async () => {
		setIsOpen( false );
		await executeAction();
	};

	const handleCancel = () => {
		setIsOpen( false );
	};

	const executeAction = async (e) => {
		let data = {};
		await cmplz_api.doAction(field.action, data).then((response) => {
			if (response.success) {
				fetchFieldsData(selectedSubMenuItem);
				showSavedSettingsNotice(response.message);
			}
		});
	}
	const warningText = field && field.warn ? field.warn : '';
	if ( type === 'action' ) {
		return (
			<>
				<ConfirmDialog
					isOpen={ isOpen }
					onConfirm={ handleConfirm }
					onCancel={ handleCancel }
				>
					{warningText}
				</ConfirmDialog>
				{/*Commented out because the below still needs css */}
				{/*<AreYouSureModal isOpen={isOpen} onConfirm={handleConfirm} onCancel={handleCancel} >*/}
				{/*	{warningText}*/}
				{/*</AreYouSureModal>*/}

				<button
					className={classes}
					onClick={clickHandler}
					disabled={disabled}
				>
					{content}

				</button>
			</>


		)
	}
	if (type === 'link') {
		return (
			<a
				className={classes}
				href={href}
				target={target}
				>
				{content}
			</a>
		)
	}
}

export default memo(Button);
