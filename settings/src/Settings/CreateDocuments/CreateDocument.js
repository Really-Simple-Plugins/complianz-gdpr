import {UseDocumentsData} from "./DocumentsData";
import Icon from "../../utils/Icon";
import useFields from "../Fields/FieldsData";
import { __ } from '@wordpress/i18n';

/**
 * Render a help notice in the sidebar
 */
const CreateDocument = (props) => {
	const {saving, updateDocument } = UseDocumentsData();
	const {showSavedSettingsNotice} = useFields();

	const onChangeHandler = (e, id) => {
		updateDocument(id, e.target.value);
	}
	const onClickhandler = (e, shortcode) => {
		let success;
		e.target.classList.add('cmplz-click-animation');
		let temp = document.createElement("input");
		document.getElementsByTagName("body")[0].appendChild(temp);
		temp.value = shortcode;
		temp.select();
		try {
			success = document.execCommand("copy");
		} catch (e) {
			success = false;
		}
		temp.parentElement.removeChild(temp);
		if (success) {
			showSavedSettingsNotice(__("Copied shortcode", "complianz-gdpr"));
		}
	}
	let isCreated = !!props.page.page_id;
	return (
		<div className="cmplz-create-document">
			{ isCreated && <Icon name='success' color='green'/>}
			{ !isCreated && <Icon name='times'/>}
			<input disabled={saving} onChange={ ( e ) =>  onChangeHandler(e, props.page.page_id) }  type="text" value={props.page.title}/>
			<div className="cmplz-shortcode-container" onClick={ ( e ) =>  onClickhandler(e, props.page.shortcode) }><Icon  name='shortcode'/></div>
		</div>
	);

}

export default CreateDocument
