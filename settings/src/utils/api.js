import apiFetch from '@wordpress/api-fetch';
import axios from 'axios';
import {toast} from "react-toastify";
import {__} from '@wordpress/i18n';
const ajaxRequest = async (method, path, requestData = null) => {
	try {
		const url = method === 'GET'
			? `${siteUrl('ajax')}&rest_action=${path.replace('?', '&')}`
			: siteUrl('ajax');

		const options = {
			method,
			headers: { 'Content-Type': 'application/json; charset=UTF-8' },
		};

		if (method === 'POST') {
			options.body = JSON.stringify({ rest_action:path, data: requestData }, stripControls);
		}
		const response = await fetch(url, options);
		if ( !response.ok ) {
			generateError(response, response.statusText);
			return invalidDataError(error, 'error', 'invalid_data');
		}

		const responseData = await response.json();
		if (!responseData || !responseData.hasOwnProperty('request_success')) {
			generateError(responseData, 'Connection to the server lost. Please try reloading this page.', 'complianz-gdpr');
			return invalidDataError('invalid_data', 'error', 'The server returned invalid data. If debugging is enabled, please disable it to check if that helps.','complianz-gdpr');
		}

		delete responseData.request_success;
		return responseData;
	} catch (error) {
		generateError(false, error);
		return invalidDataError(error, 'error', 'The server returned invalid data. If debugging is enabled, please disable it to check if that helps.','complianz-gdpr');
	}
}

/**
 * All data elements with 'Control' in the name are dropped, to prevent:
 * TypeError: Converting circular structure to JSON
 * @param key
 * @param value
 * @returns {any|undefined}
 */
const stripControls = (key, value) => {
	if (!key){return value}
	if (key && key.includes("Control")) {
		return undefined;
	}
	if (typeof value === "object") {
		return JSON.parse(JSON.stringify(value, stripControls));
	}
	return value;
}
const invalidDataError = (apiResponse, status, code ) => {
	let response = {}
	let error = {};
	let data = {};
	data.status = status;
	error.code = code;
	error.data = data;
	error.message = apiResponse;
	response.error = error;
	return response;
}

const apiGet = (path) => {

	if ( usesPlainPermalinks() ) {
		let config = {
			headers: {
				'X-WP-Nonce': cmplz_settings.nonce,
			}
		}
		return axios.get(siteUrl()+path, config ).then(
			( response ) => {
				if (!response.data.request_success) {
					return ajaxRequest('GET', path);
				}
				return response.data;
			}
		).catch((error) => {
			//try with admin-ajax
			return ajaxRequest('GET', path);
		});
	} else {
		return apiFetch( { path: path } ).then((response) => {
			if ( !response.request_success ) {
				console.log("apiFetch failed, trying with ajaxGet");
				return ajaxRequest('GET', path);
			}
			return response;
		}).catch((error) => {
			//try with admin-ajax
			return ajaxRequest('GET', path);
		});
	}
}
const apiPost = (path, data) => {
	if ( usesPlainPermalinks() ) {
		let config = {
			headers: {
				'X-WP-Nonce': cmplz_settings.nonce,
			}
		}
		return axios.post(siteUrl()+path, data, config ).then( ( response ) => {return response.data;}).catch((error) => {
			return ajaxRequest('POST', path, data);
		});
	} else {
		return apiFetch(  {
			path: path,
			method: 'POST',
			data: data,
		} ).then((response) => {
			if ( !response.request_success ) {
				console.log("apiFetch failed, trying with ajaxPost");
				return ajaxRequest('POST', path, data);
			}
			return response;
		}).catch((error) => {
			//try with admin-ajax
			return ajaxRequest('POST', path, data);
		});
	}
}


/*
 * Makes a get request to the fields list
 *
 * @param {string|boolean} restBase - rest base for the query.
 * @param {object} args
 */

export const getFields = () => {
	return apiGet('complianz/v1/fields/get'+glue()+getNonce());
};

/*
 * Post our data to the back-end
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
export const setFields = (fields, finish) => {
	let data = {
		fields:fields,
		nonce:cmplz_settings.cmplz_nonce,
		finish:finish,
	};

	return apiPost('complianz/v1/fields/set'+glue(), data);
};


export const doAction = (action, data) => {
	if (typeof data === 'undefined') data = {};
	data.nonce = cmplz_settings.cmplz_nonce;
	return apiPost('complianz/v1/do_action/'+action, data);
}

const usesPlainPermalinks = () => {
	return cmplz_settings.site_url.indexOf('?') !==-1;
};

const glue = () => {
	return usesPlainPermalinks() ? '&' : '?'
}

const getNonce = () => {
	return 'nonce='+cmplz_settings.cmplz_nonce+'&token='+Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
};

const siteUrl = (type) => {
	let url;
	if (typeof type ==='undefined') {
		url = cmplz_settings.site_url;
	} else {
		url = cmplz_settings.admin_ajax_url
	}
	if ( window.location.protocol === "https:" && url.indexOf('https://')===-1 ) {
		return url.replace('http://', 'https://');
	}
	return  url;
}
let errorShown = false;
const generateError = (response, errorMsg) => {
	let error = __("Unexpected error", "complianz-gdpr");
	if (response && response.errors) {
		//get first entry of the errors object.
		//This is the error message
		for (let key in response.errors) {
			if (response.errors.hasOwnProperty(key) && typeof response.errors[key] === 'string' && response.errors[key].length > 0) {
				error = response.errors[key];
				break;
			}
		}
	} else if (errorMsg) {
		error = errorMsg;
	}

	//only show once
	if (errorShown) {
		return;
	}
	errorShown = true;
	toast.error(
		error,
		{
			autoClose: 15000,
		});
}
