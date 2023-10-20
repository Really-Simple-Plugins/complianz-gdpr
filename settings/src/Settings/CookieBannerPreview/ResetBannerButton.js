import { __ } from '@wordpress/i18n';
import useFields from "../../Settings/Fields/FieldsData";
import UseBannerData from "./CookieBannerData";
import Icon from "../../utils/Icon";
import {useState, useEffect, memo} from "@wordpress/element";
import {__experimentalConfirmDialog as ConfirmDialog} from "@wordpress/components";

const ResetBannerButton = () => {
	const { cssLoading, selectedBanner } = UseBannerData();
	const [active, setActive] = useState(false);
	const [disabled, setDisabled] = useState(false);
	const {updateField, setChangedField, fields} = useFields();
	const [ isOpen, setIsOpen ] = useState( false );

	//set active to false when css is loaded.
	useEffect( () => {
		if ( !cssLoading ) {
			setActive(false);
		}
	}, [cssLoading] );

	//make sure the button is disabled until any data changes again.
	useEffect( () => {
		if (!active) setDisabled(false);
	},[fields]);

	const handleClick = async () => {
		if (!ConfirmDialog) {
			await handleConfirm();
		} else {
			setIsOpen( true );
		}
	}

	const handleConfirm = async () => {
		setIsOpen( false );
		setDisabled(true);
		await setDefaults();
	};

	const setDefaults = () => {
		setActive(true);
		let bannerFields = selectedBanner.banner_fields;
		for ( const field of bannerFields ) {
			if ( field.hasOwnProperty('default') ) {
				if ( field.type==='hidden' ) {
					continue;
				}
				updateField(field.id, field.default);
				setChangedField(field.id, field.default);
			}
		}
	}

	return (
		<>
			{ ConfirmDialog && <ConfirmDialog
					isOpen={ isOpen }
					onConfirm={ () => handleConfirm() }
					onCancel={() => setIsOpen(false) }
				>
					{__('Are you sure you want to reset this banner to the default settings?', 'complianz-gdpr')}
				</ConfirmDialog> }
			<button disabled={disabled || active}
					onClick={() => handleClick(  )}
					className="button button-default"
			>
				{__('Reset', 'complianz-gdpr')}
					{active && <Icon name = "loading" color = 'grey' />}
			</button>
		</>
	);
}
export default memo(ResetBannerButton);
