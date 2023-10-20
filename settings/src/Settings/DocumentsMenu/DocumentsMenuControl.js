import { __ } from '@wordpress/i18n';
import {useEffect} from "@wordpress/element";
import useFields from "../../Settings/Fields/FieldsData";
import {UseMenuData} from "./MenuData";
import MenuPerDocument from "./MenuPerDocument";
import MenuPerDocumentType from "./MenuPerDocumentType";
import Placeholder from '../../Placeholder/Placeholder';
import SingleDocumentMenuControl from "./SingleDocumentMenuControl";
import {memo} from "@wordpress/element";
import {useState} from "@wordpress/element";

/**
 * Render a help notice in the sidebar
 */
const DocumentsMenuControl = (props) => {
	const {pageTypes, menuDataLoaded, fetchMenuData, menu, emptyMenuLink, genericDocuments, createdDocuments, documentsNotInMenu, regions } = UseMenuData();
	const { getFieldValue, addHelpNotice, documentSettingsChanged, setDocumentSettingsChanged} = useFields();
	const [isRegionRedirected, setIsRegionRedirected] = useState(false);

	useEffect ( () => {
		if (!menuDataLoaded || documentSettingsChanged) {
			setDocumentSettingsChanged(false);
			fetchMenuData();
		}
	}, [documentSettingsChanged])

	useEffect( () => {
		setIsRegionRedirected(getFieldValue('region_redirect')==='yes');
	},[getFieldValue('region_redirect')])

	useEffect ( () => {
		if (!menuDataLoaded) {
			return;
		}
		let text = '';
		let title = '';
		let field_id = isRegionRedirected ? 'add_pages_to_menu_region_redirected' : 'add_pages_to_menu';
		if ( menu.length===0 ) {
			title = __("No menus found.","complianz-gdpr");
			text = __("No menus were found. Skip this step, or create a menu first.","complianz-gdpr");
			addHelpNotice( field_id, 'warning', text, title, emptyMenuLink);
		} else if ( documentsNotInMenu.length>0 ) {
			title = __("Pages not included in a menu","complianz-gdpr");
			if (documentsNotInMenu.length===1) {
				let document = documentsNotInMenu[0];
				text = __( 'The generated document %s has not been assigned to a menu yet, you can do this now, or skip this step and do it later.','complianz-gdpr').replace('%s',document);
			} else {
				text = __( 'Not all generated documents have been assigned to a menu yet, you can do this now, or skip this step and do it later.','complianz-gdpr');
			}
			addHelpNotice( field_id, 'warning', text, title, false );
		} else if ( documentsNotInMenu.length===0 ) {
			title = __("All pages generated!","complianz-gdpr");
			text = __('Great! All your generated documents have been assigned to a menu, so you can skip this step.', 'complianz-gdpr');
			addHelpNotice( field_id, 'warning', text, title, false );
		}

	}, [menuDataLoaded, documentsNotInMenu, menu])

	if ( !menuDataLoaded ) {
		return (
			<Placeholder lines="3"></Placeholder>
		)
	}

	if (!isRegionRedirected) {
		return (
			<>
				{ regions.map((region,i) =>
					<MenuPerDocument key={i} region={region}/>
				)}
			</>

		);
	} else {
		//get all documents which can't be region redirected
		let nonRedirectingGenericDocuments = genericDocuments.filter(doc =>!doc.can_region_redirect);
		let nonRedirectingDocuments = [];
		nonRedirectingGenericDocuments.forEach(function(nonRedirectingGenericDoc, i){
			let docs = createdDocuments.filter(doc => nonRedirectingGenericDoc.type === doc.type );
			if (docs.length>0){
				nonRedirectingDocuments.push(docs[0]);
			}
		})

		return (
			<>
				{pageTypes.map((type,i) =>
					<MenuPerDocumentType key={i} pageType={type}/>
				)}
				{ nonRedirectingDocuments.map( (document, i)=> <SingleDocumentMenuControl key={i} document={document}/> )}
			</>
		);
	}
}
export default memo(DocumentsMenuControl)
