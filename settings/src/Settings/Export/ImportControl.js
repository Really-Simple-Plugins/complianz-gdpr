import {
	FormFileUpload,
} from '@wordpress/components';
import {useState, useEffect} from "@wordpress/element";
import useFields from "../../Settings/Fields/FieldsData";
import Icon from "../../utils/Icon";
import { __ } from '@wordpress/i18n';
import {upload} from "../../utils/upload";
import {memo} from "@wordpress/element";
import './Import.scss';
import useMenu from "../../Menu/MenuData";

function ImportControl() {
	const {removeHelpNotice, addHelpNotice, fetchFieldsData, showSavedSettingsNotice} = useFields();
	const {selectedSubMenuItem } = useMenu();

	const [file, setFile] = useState(false)
	const [disabled, setDisabled] = useState(true);
	const [uploading, setUploading] = useState(false);
	useEffect(() => {
		if (!file ) return;
		if (file.type!=='application/json') {
			setDisabled(true);
			addHelpNotice('import_settings', 'warning', __("You can only upload .json files","complianz-gdpr"), __("Incorrect extension","complianz-gdpr"),false);
		} else {
			setDisabled(false);
			removeHelpNotice('import_settings');
		}

	}, [file])

	const onClickHandler = (e) => {
		setDisabled(true);
		setUploading(true);

		upload('import_settings', file).then((response) => {
			if (response.data.success) {
				fetchFieldsData(selectedSubMenuItem).then(() => {
					showSavedSettingsNotice(__("Settings imported", "complianz-gdpr"));
				});
			} else {
				addHelpNotice('import_settings', 'warning', __("You can only upload .json files","complianz-gdpr"), __("Incorrect extension","complianz-gdpr"),false);
			}
			setUploading(false);
			setFile(false);
			return true;
		}).catch((error) => {
			console.error(error);
		});
	}

	return (
		<div className="cmplz-import-form">
			<div className="cmplz-import-button-container">
			{file && file.name}
			<FormFileUpload
				accept=""
				icon={ <Icon name="upload" color = 'black' /> } //formfile upload overrides size prop. We override that in the icon component
				onChange={ ( event ) => setFile(event.currentTarget.files[0]) }
			>
				{__("Select file","complianz-gdpr")}
			</FormFileUpload>
			<button disabled={disabled} className="button button-default"  onClick={(e) => onClickHandler(e)}>
				{__("Import","complianz-gdpr")}
				{uploading && <Icon name = "loading" color = 'grey' />}
			</button>
			</div>
		</div>
	)
}

export default memo(ImportControl)
