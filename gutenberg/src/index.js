
/**
 * BLOCK:Complianz Documents block
 *
 * Registering the Complianz Privacy Suite documents block with Gutenberg.
 */

import * as api from './utils/api';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { SelectControl } = wp.components;
const { PanelBody, PanelRow } = wp.components;
const { RichText } = wp.blockEditor;
const el = wp.element.createElement;
import {useState, useEffect, useRef} from "@wordpress/element";

/**
 *  Set custom Complianz Icon
 */

const iconEl =
	el('svg', { width: 20, height: 20 ,viewBox : "0 0 133.62 133.62"},
		el('path', { d: "M113.63,19.34C100.37,6.51,84.41,0,66.2,0A64.08,64.08,0,0,0,19.36,19.36,64.08,64.08,0,0,0,0,66.2c0,18.25,6.51,34.21,19.34,47.43s28.61,20,46.86,20,34.2-6.72,47.45-20,20-29.21,20-47.45S126.89,32.21,113.63,19.34Zm-2.85,91.44c-12.47,12.46-27.47,18.77-44.58,18.77s-31.89-6.31-43.94-18.75A62.11,62.11,0,0,1,4.07,66.2a60.14,60.14,0,0,1,18.17-44,60.1,60.1,0,0,1,44-18.17c17.12,0,32.12,6.12,44.6,18.19s18.75,26.86,18.75,43.94S123.23,98.32,110.78,110.78Z" } ),
		el('path', { d: "M99.49,30.71a6.6,6.6,0,0,0-9.31,0L40.89,80,35.3,74.41a6.58,6.58,0,0,0-9.31,0l-2.12,2.12a6.6,6.6,0,0,0,0,9.31l9.64,9.64a6.67,6.67,0,0,0,.56.65l2.12,2.12L41,102.8l4-4a8.39,8.39,0,0,0,.65-.56l2.12-2.12a8.39,8.39,0,0,0,.56-.65l53.34-53.34a6.6,6.6,0,0,0,0-9.31Z" } ),
		el('path', { d: "M94.91,86.63H65.15L48.86,102.8H94.91a6.6,6.6,0,0,0,6.58-6.58v-3A6.61,6.61,0,0,0,94.91,86.63Z" } ),
		el('path', { d: "M47.09,45H68.71L85,28.79H47.09a6.6,6.6,0,0,0-6.58,6.58v3A6.6,6.6,0,0,0,47.09,45Z" } ),
	);

const selectDocument = ({ className, isSelected, attributes, setAttributes }) => {
	const [documents, setDocuments] = useState([]);
	const [documentDataLoaded, setDocumentDataLoaded] = useState(false);
	const [selectedDocument, setSelectedDocument] = useState(attributes.selectedDocument);
	const [documentSyncStatus, setDocumentSyncStatus] = useState(attributes.documentSyncStatus);
	const [customDocumentHtml, setCustomDocumentHtml] = useState('');
	const divAttributes = useRef([]);


	useEffect(() => {
		api.getDocuments().then( ( response ) => {
			let documents = response.data;
			setDocuments(documents);
			setDocumentDataLoaded(true);
			let documentData = false;
			if ( documents && 0 !== selectedDocument ) {
				// If we have a selected document, find that document and add it.
				documentData = documents.find( ( item ) => { return item.id === selectedDocument } );
				if (documents.length === 0) {
					setAttributes({
						hasDocuments: false,
					});
				}
			}

			let tempHtml = '';
			if ( documentData ) {
				tempHtml = documentData.content;
			}
			if ( attributes.customDocument && attributes.customDocument.length>0 ){
				tempHtml = attributes.customDocument;
			}
			tempHtml = convertHtmlForBlock(tempHtml);
			setCustomDocumentHtml(tempHtml);
		});
	}, [])

	const onChangeSelectDocument = (value) => {
		// Set the state
		setSelectedDocument(value);

		// Set the attributes
		setAttributes({
			selectedDocument: value,
		});
	}

	const onChangeCustomDocument = (html) => {
		setCustomDocumentHtml(html);
	}

	useEffect(() => {
		const timer = setTimeout(() => {
			let html = convertBlockToHtml(customDocumentHtml);
			setAttributes( {
				customDocument: html,
			} );		}, 500)

		return () => clearTimeout(timer)
	}, [customDocumentHtml])

	/**
	 * The html in the block is stripped of some divs, and the root div. We put this back here.
	 * @param html
	 * @returns {string}
	 */
	const convertBlockToHtml = (html) => {
		const fragment = document.createRange().createContextualFragment(html);
		//restore manage consent divs
		fragment.querySelectorAll('span#cmplz-manage-consent-container-nojavascript, span#cmplz-manage-consent-container, span.cmplz-datarequest').forEach(obj => {
			let div = document.createElement('div');
			div.innerHTML = obj.innerHTML;
			//copy attributes
			Array.from(obj.attributes).forEach(attribute => {
				div.setAttribute(
					attribute.nodeName,
					attribute.nodeValue,
				);
			});
			obj.replaceWith( div )
		});

		let divContainer = document.createElement('div');
		divContainer.appendChild( fragment.cloneNode(true) );
		//add root div again.
		Array.from(divAttributes.current).forEach(attribute => {
			divContainer.setAttribute(
				attribute.nodeName,
				attribute.nodeValue,
			);
		});
		let rootContainer = document.createElement('div');
		rootContainer.appendChild( divContainer );
		return rootContainer.innerHTML;
	}

	/**
	 * Convert our document html to html we can use in the block, without a root div, and removed divs in the manage html
	 *
	 * @param html
	 * @returns {string}
	 */
	const convertHtmlForBlock = (html) => {
		let fragment = document.createRange().createContextualFragment(html);
		//first, get html from root element, if exists. Otherwise the root div gets replicated by gutenberg.
		fragment.querySelectorAll('div#cmplz-document').forEach(obj => {
			html = obj.innerHTML;
			divAttributes.current = obj.attributes;
			fragment = document.createRange().createContextualFragment(html);
		});
		//add keys
		let counter = 0;
		fragment.querySelectorAll('details,li').forEach(obj => {
			counter++;
			obj.setAttribute('key', counter )
		});
		//remove manage consent divs to prevent weird layout issues
		fragment.querySelectorAll('div#cmplz-manage-consent-container-nojavascript, div#cmplz-manage-consent-container, div.cmplz-datarequest').forEach(obj => {
			let span = document.createElement('span');
			span.innerHTML = obj.innerHTML;
			//copy attributes
			Array.from(obj.attributes).forEach(attribute => {
				span.setAttribute(
					attribute.nodeName,
					attribute.nodeValue,
				);
			});
			obj.replaceWith( span )
		});

		//put back
		let div = document.createElement('div');
		div.appendChild( fragment.cloneNode(true) );
		return div.innerHTML;
	}

	const onChangeSelectDocumentSyncStatus = (value) =>{
		setDocumentSyncStatus(value);
		setAttributes({
			documentSyncStatus: value,
		});

		//we reset the customDocument data
		const selectedDocumentData = documents.find((item) => {
			return item.id === selectedDocument
		});
		let html = convertHtmlForBlock(selectedDocumentData.content);
		setCustomDocumentHtml(html);
		setAttributes({
			customDocument: selectedDocumentData.content,
		});
	}

	let options = [{value: 0, label: __('Select a document', 'complianz-gdpr')}];
	let output = __('Loading...', 'complianz-gdpr');
	let document_status_options = [
		{value: 'sync', label: __('Synchronize document with Complianz', 'complianz-gdpr')},
		{value: 'unlink', label: __('Edit document and stop synchronization', 'complianz-gdpr')},
	];

	if ( !attributes.hasDocuments ){
		output = __('No documents found. Please finish the Complianz Privacy Suite wizard to generate documents', 'complianz-gdpr');
	}

	//preview
	if ( attributes.preview ) {
		return(
			<img alt="preview" src={complianz.cmplz_preview} />
		);
	}

	if ( documentDataLoaded ) {
		output = isSelected ? __("Select a document type from the dropdownlist", 'complianz-gdpr') : __('Click this block to show the options', 'complianz-gdpr');
		documents.forEach((item) => {
			options.push({value: item.id, label: item.title});
		});
	}

	//load content
	if ( attributes.selectedDocument!==0 && documentDataLoaded && attributes.selectedDocument.length>0 ) {
		const documentData = documents.find((item) => {
			return item.id === attributes.selectedDocument
		});
		if (documentData) output = documentData.content;
	}

	if ( documentSyncStatus==='sync' ) {
		return [
			!!isSelected && (
				<InspectorControls key='inspector-document'>
					<PanelBody title={ __('Document settings', 'complianz-gdpr' ) } initialOpen={ true } >
						<PanelRow key="1">
							<SelectControl onChange={ (e) => onChangeSelectDocument(e) }
										   value={ selectedDocument }
										   label={__('Select a document', 'complianz-gdpr')}
										   options={options}/>
						</PanelRow>
						<PanelRow key="2">
							<SelectControl onChange={(e) => onChangeSelectDocumentSyncStatus(e) }
									   value={documentSyncStatus}
									   label={__('Document sync status', 'complianz-gdpr')}
									   options={document_status_options}/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			),
			<div key={attributes.selectedDocument} className={className} dangerouslySetInnerHTML={{__html: output}}></div>
		]
	} else {
		let html = documentDataLoaded ? customDocumentHtml : __('Loading...', 'complianz-gdpr');
		let syncClassName = className + ' cmplz-unlinked-mode';
		return [
			!!isSelected && (
				<InspectorControls key='inspector-document-settings'>
					<PanelBody title={ __('Document settings', 'complianz-gdpr' ) } initialOpen={ true } >
						<PanelRow key="1">
							<SelectControl onChange={(e) => onChangeSelectDocument(e) }
										   value={attributes.selectedDocument}
										   label={__('Select a document', 'complianz-gdpr')}
										   options={options}/>
						</PanelRow>
						<PanelRow key="2">

							<SelectControl onChange={ (e) => onChangeSelectDocumentSyncStatus(e) }
									   value={documentSyncStatus}
									   label={__('Document sync status', 'complianz-gdpr')}
									   options={document_status_options}/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			),

			<RichText key="rich-text-cmplz"
				className={syncClassName}
				value={html}
				onChange={ (e) => onChangeCustomDocument(e) }
			/>
		]
	}

}

/**
 * Register: a Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */



registerBlockType('complianz/document', {
	title: __('Legal document - Complianz', 'complianz-gdpr'), // Block title.
	icon: iconEl, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'widgets',
	example: {
		attributes: {
			'preview' : true,
		},
	},
	keywords: [
		__('Privacy Statement', 'complianz-gdpr'),
		__('Cookie Policy', 'complianz-gdpr'),
		__('Disclaimer', 'complianz-gdpr'),
	],
	//className: 'cmplz-document',
	attributes: {
		documentSyncStatus: {
			type: 'string',
			default: 'sync'
		},
		customDocument: {
			type: 'string',
			default: ''
		},
		hasDocuments: {
			type: 'string',
			default: 'false',
		},
		content: {
			type: 'string',
			source: 'children',
			selector: 'p',
		},
		selectedDocument: {
			type: 'string',
			default: '',
		},
		documents: {
			type: 'array',
		},
		document: {
			type: 'array',
		},
		preview: {
			type: 'boolean',
			default: false,
		}
	},
	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */

	edit:selectDocument,

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */

	save: function() {
		// Rendering in PHP
		return null;
	},

	// save: () => {
	// 	const blockProps = useBlockProps.save();
	//
	// 	return (
	// 		<div { ...blockProps }>
	// 			<InnerBlocks.Content />
	// 		</div>
	// 	);
	// },
});
