import {
	useEffect, useState
} from '@wordpress/element';
import useDocuments from "./DocumentsData";
import OtherRegions from "./OtherRegions";
import OtherDocuments from "./OtherDocuments";
import Icon from "../../utils/Icon";
import { __ } from '@wordpress/i18n';
import useFields from "../../Settings/Fields/FieldsData";
import Placeholder from "../../Placeholder/Placeholder";
import {UseDocumentsData} from "../../Settings/CreateDocuments/DocumentsData";

const SingleDocument = (props) => {
	const {document} = props;
	const {showSavedSettingsNotice} = useFields();

	// let missing = document.required && !document.exists;
	let syncColor = document.status === 'sync' ? 'green' : 'grey';
	let syncTooltip = document.status === 'sync' ? __( 'Document is kept up to date by Complianz', 'complianz-gdpr' ) : __( 'Document is not kept up to date by Complianz', 'complianz-gdpr' );
	let existsColor = document.exists ? 'green' : 'grey';
	let existsTooltip = document.exists ? __( 'Validated', 'complianz-gdpr' ) : __( 'Missing document', 'complianz-gdpr' );
	let shortcodeTooltip = document.required ? __( 'Click to copy the document shortcode', 'complianz-gdpr' ) : __( 'Not enabled', 'complianz-gdpr' );
	//if we have a not required document here, it exists, so is obsolete.
	if ( !document.required || !document.exists ) {
		existsColor = syncColor = 'grey';
		existsTooltip = syncTooltip = __( 'Not enabled', 'complianz-gdpr' );
	}
	const onClickhandler = (e, shortcode) => {
		let success;
		e.target.classList.add('cmplz-click-animation');
		let temp = window.document.createElement("input");
		window.document.getElementsByTagName("body")[0].appendChild(temp);
		temp.value = shortcode;
		temp.select();
		try {
			success = window.document.execCommand("copy");
		} catch (e) {
			success = false;
		}
		temp.parentElement.removeChild(temp);
		if (success) {
			showSavedSettingsNotice(__("Copied shortcode", "complianz-gdpr"));
		}
	}
	// let createLink = document.create_link ? document.create_link : '#wizard/manage-documents';
	// let plugin = pluginData ? pluginData.find(plugin => plugin.slug === 'complianz-terms-conditions') : false;
	return (
			<div className="cmplz-single-document">
				<div className="cmplz-single-document-title" >
					{ document.permalink && <a href={document.permalink}>{document.title}</a>}
					{ !document.permalink && document.readmore && <a href={document.readmore}>{document.title}</a>}
					{ !document.permalink && !document.readmore && document.title }
				</div>
				<Icon name={'sync'} color={syncColor} tooltip={syncTooltip} size={14} />
				<Icon name={'circle-check'} color={existsColor} tooltip={existsTooltip} size={14} />
				<div onClick={ (e) => onClickhandler(e, document.shortcode) } ><Icon name={'shortcode'} color={existsColor}  tooltip={shortcodeTooltip} size={14} /></div>
			</div>
		);
}

const DocumentsBlock = () => {
	const {region, documentDataLoaded, getDocuments, documents } = useDocuments();
	const {documentsChanged} = UseDocumentsData();

	const [regionDocuments, setRegionDocuments] = useState([]);
	// const premiumDocuments = [
	// 	{
	// 		title: __("Privacy Statements", 'complianz-gdpr'),
	// 		readmore: 'https://complianz.io/definition/what-is-a-privacy-statement/',
	// 	},
	// 	{
	// 		title: __("Impressum", 'complianz-gdpr'),
	// 		readmore: 'https://complianz.io/definition/what-is-an-impressum/',
	// 	},
	// ];

	useEffect( () => {
		if (!documentDataLoaded) {
			getDocuments();
		}
	},[]);

	useEffect( () => {
		//if not loaded yet, wait for the data to be loaded, otherwise it runs twice
		if (!documentDataLoaded) {
			return;
		}
		if ( documentsChanged ) {
			getDocuments();
		}
	},[documentsChanged]);

	useEffect( () => {
		let docs = documents.filter( (document) => document['region'] ===region)[0];
		if ( docs ) {
			docs = docs['documents'];
			// if ( !cmplz_settings.is_premium ) {
			// 	premiumDocuments.forEach((premiumDocument) => {
			// 		if (!docs.find((doc) => doc.title === premiumDocument.title)){
			// 			docs.push(premiumDocument);
			// 		}
			// 	});
			// }
			setRegionDocuments(docs);
		}
	}, [region, documents] );

	if ( !documentDataLoaded ) {
		return (
			<Placeholder lines="3"></Placeholder>
		)
	}

	return (
		<>
			{ regionDocuments.map( (document, i) => <SingleDocument key={i} document={document}/> )}
			{ !cmplz_settings.is_premium && <OtherRegions/> }
			{ cmplz_settings.is_premium && <OtherDocuments/> }
		</>
	);

}
export default DocumentsBlock;
