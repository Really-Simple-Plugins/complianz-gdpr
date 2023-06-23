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
import useOtherPlugins from "../OtherPlugins/OtherPluginsData";

const SingleDocument = (props) => {
	const {document} = props;
	const {showSavedSettingsNotice} = useFields();
	const {dataLoaded, pluginData, pluginActions, fetchOtherPluginsData, error} = useOtherPlugins();
	useEffect(() => {
		if (!dataLoaded) {
			fetchOtherPluginsData();
		}
	}, [] )

	let missing = document.required && !document.exists;
	let syncColor = document.status === 'sync' ? 'green' : 'grey';
	let syncTooltip = document.status === 'sync' ? __( 'Document is kept up to date by Complianz', 'complianz-gdpr' ) : __( 'Document is not kept up to date by Complianz', 'complianz-gdpr' );
	let existsColor = document.exists ? 'green' : 'grey';
	let existsTooltip = document.exists ? __( 'Validated', 'complianz-gdpr' ) : __( 'Missing document', 'complianz-gdpr' );
	let shortcodeTooltip = document.required ? __( 'Click to copy the document shortcode', 'complianz-gdpr' ) : __( 'Not enabled', 'complianz-gdpr' );
	//if we have a not required document here, it exists, so is obsolete. Not existing docs are already filtered out.
	if ( !document.required ){
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
	let createLink = document.create_link ? document.create_link : '#wizard/manage-documents';
	let plugin = pluginData ? pluginData.find(plugin => plugin.slug === 'complianz-terms-conditions') : false;
	return (
		<>
			<div className="cmplz-single-document">
				<div className="cmplz-single-document-title" key={1}>
					{ document.permalink && <a href={document.permalink}>{document.title}</a>}
					{ !document.permalink && document.title}
				</div>
				<Icon name={'sync'} color={syncColor} tooltip={syncTooltip} size={14} key={2}/>
				<Icon name={'circle-check'} color={existsColor} tooltip={existsTooltip} size={14} key={3}/>
				<div onClick={ (e) => onClickhandler(e, document.shortcode) } key={4}><Icon name={'shortcode'} color={existsColor}  tooltip={shortcodeTooltip} size={14} /></div>
				<div className="cmplz-single-document-generated" key={5}>
					{!document.install && <>
						{ document.readmore && <><a href={document.readmore}>{__("Read more", "complianz-gdpr")}</a></>}
						{ !document.readmore &&
							<>
								{ !document.required && __("Obsolete","complianz-gdpr")}
								{
									document.required && <>
										{ !missing && document.generated}
										{ missing && <a href={createLink} >{__("Create","complianz-gdpr")}</a>}
									</>
								}
							</>
						}
					</>}
					{plugin && document.install && <>
						{ plugin.pluginAction!=='installed' &&
							<a href="#" onClick={ (e) => pluginActions(plugin.slug, plugin.pluginAction, e) } >{plugin.pluginActionNice}</a>
						}
						{plugin.pluginAction==='installed' &&
								<a href={plugin.create}>{__("Create", "complianz-gdpr")}</a>
						}
					</>}

				</div>
			</div>
		</>
	);
}

const DocumentsBlock = () => {
	const {region, documentDataLoaded, getDocuments, documents } = useDocuments();
	const [regionDocuments, setRegionDocuments] = useState([]);
	const premiumDocuments = [
		{
			title: __("Privacy Statements", 'complianz-gdpr'),
			readmore: 'https://complianz.io/definition/what-is-a-privacy-statement/',
		},
		{
			title: __("Impressum", 'complianz-gdpr'),
			readmore: 'https://complianz.io/definition/what-is-an-impressum/',
		},
	];

	useEffect( () => {
		if (!documentDataLoaded) {
			getDocuments();
		}
	},[]);

	useEffect( () => {
		let docs = documents.filter( (document) => document['region'] ===region)[0];
		//filter out documents which do not exist AND are not required
		if ( docs ) {
			docs = docs['documents'];
			//docs = docs.filter( (document) => document.exists || document.required );
			if ( !cmplz_settings.is_premium ) {
				premiumDocuments.forEach((premiumDocument) => {
					docs.push(premiumDocument);
				});
			}
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
