import AsyncSelect from 'react-select/async';
import { __ } from '@wordpress/i18n';
import * as cmplz_api from "../utils/api";
import {useState, useEffect, useRef, memo} from "@wordpress/element";
import {useUpdateEffect} from 'react-use';
import Icon from "../utils/Icon";
import useFields from "./Fields/FieldsData";
import RadioGroup from './Inputs/RadioGroup';

const DocumentControl = ({id, value, options, defaultValue, disabled}) => {
    const [pageId, setPageId] = useState(false);
    const [pageUrl, setPageUrl] = useState('');
    const [pages, setPages] = useState([]);
    const [pagesListLoaded, setPagesListLoaded] = useState(false);
    const [pageUrlLoaded, setPageUrlLoaded] = useState(false);
    const [timer, setTimer] = useState(null)
	const currentType = useRef(value);
	const {updateField, setChangedField} = useFields();

	const loadSubFields = (onLoad) => {
		let loadData = (currentType.current !== value) || onLoad;
		if ( value==='custom' && !pagesListLoaded ) {
			currentType.current = value
			if (loadData) loadOptions(false);
		}

		if ( value==='url' && !pageUrlLoaded) {
			let data = {};
			currentType.current = value
			data.type = id;
			cmplz_api.doAction('get_custom_legal_document_url', data).then( ( response ) => {
				setPageUrl(response.pageUrl);
				setPageUrlLoaded(true);
			});
		}
	}

	useEffect( () => {
		let values = options.map(option => option.value);
    	if ( !values.includes(value) ) {
    		//we need to save it in the page props, otherwise it's not seen by the conditions validator
    		updateField(id, defaultValue);
    	}
		loadSubFields(true);
	}, [])

	useUpdateEffect( () => {
		loadSubFields(false);
	})

	const loadOptions = (search) => {
		let data = {};
		data.type = id;
		data.search = search;
		return cmplz_api.doAction('get_pages_list', data).then( ( response ) => {
			//get option from pages pages list
			let selectedPage = response.pages.filter(function (element) {
				return element.value === response.pageId;
			});
			setPageId(selectedPage);
			setPagesListLoaded(true);
			setPages(response.pages);
			return response.pages;
		});
	}

	const onChangeHandler = (value) => {
        updateField(id, value);
        setChangedField(id, value);
    }

    const promisePages = (inputValue) =>
      new Promise( (resolve) => {
		  setTimeout(() => {
          	resolve(loadOptions(inputValue));
		  }, 1000);
    });

	const onChangeSelectHandler = (element) => {
		let data = {};
		data.pageId = element.value;
		data.type = id;
		setPageId(element);
		cmplz_api.doAction('update_custom_legal_document_id', data).then( ( response ) => {});
    }

	/*
	* Only call api if user stops typing, after 500 ms.
	*/
	const onChangeUrlHandler = (e) => {
		let data = {};
		let value = e.target.value;
		data.pageUrl = value;
		data.type = id;
		setPageUrl(value);
        clearTimeout(timer)
        const newTimer = setTimeout(() => {
			cmplz_api.doAction('update_custom_legal_document_url', data).then( ( response ) => {});
        }, 500)

        setTimer(newTimer)
	}

	const formattedOptions = {};
	for (const key in options) {
		const item = options[key];
		formattedOptions[item.value] = item.label;
	}

	return (
		<>

			<RadioGroup id={id} options={formattedOptions} value={value} onChange={onChangeHandler} disabled={disabled} />
			{ value==='custom' && !pagesListLoaded &&
				<div className="cmplz-documents-loader">
					<div><Icon name = "loading" color = 'grey' /></div><div>{__("Loading...", "complianz-gdpr")}</div>
				</div>
			}
			{ value==='custom' && pagesListLoaded &&
				<>
	                  <AsyncSelect
						  label={ __("Link to custom page", "complianz-gdpr") }
                          defaultOptions={pages}
                          loadOptions={promisePages}
						  menuPortalTarget={document.body}
						  menuPosition={'fixed'}
						  placeholder={__("Type at least two characters", "complianz-gdpr")}
						  onChange={ ( fieldValue ) => onChangeSelectHandler(fieldValue) }
						  value= { pageId }
						  styles={{ menuPortal: baseStyles => ({ ...baseStyles, zIndex: 9999 }) }}
                        />


				</>
			}
			{ value==='url' && !pageUrlLoaded &&
				<div className="cmplz-documents-loader">
					<div><Icon name = "loading" color = 'grey' /></div><div>{__("Loading...", "complianz-gdpr")}</div>
				</div>
			}
			{ value==='url' && pageUrlLoaded &&
				<><input type="text" value={pageUrl} onChange={onChangeUrlHandler} placeholder="https://domain.com/your-policy"/></>
			}
		</>
	);
}
export default memo(DocumentControl);
