import {UseMenuData} from "./MenuData";
import { __ } from '@wordpress/i18n';
import {memo} from "@wordpress/element";

const SingleDocumentMenuControl = (props) => {
	const { menu, updateMenu } = UseMenuData();
	const onChangeHandler = (e) => {
		updateMenu(props.document.page_id, e.target.value);
	}

	return (
		<div className="cmplz-single-document-menu">
			<div className="cmplz-document-menu-title">{props.document.title}</div>
			<select value={props.document.menu_id} onChange={(e) => onChangeHandler(e)}>
				<option value={-1} key={-1}>{__("Select a menu","complianz-gdpr")}</option>
				{menu.map( (menuItem, i)=> <option key={i} value={menuItem.id}>{menuItem.label}</option> )}
			</select>
		</div>
	)

}
export default memo(SingleDocumentMenuControl)
