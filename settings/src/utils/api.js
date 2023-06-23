import apiFetch from '@wordpress/api-fetch';
import axios from 'axios';

const ajaxPost = (path, requestData) => {
	return new Promise(function (resolve, reject) {
		let url = siteUrl('ajax');
		let xhr = new XMLHttpRequest();
		xhr.open('POST', url );
		xhr.onload = function () {
			let response = JSON.parse(xhr.response);
			if (xhr.status >= 200 && xhr.status < 300) {
				resolve(response);
			} else {
				resolve(invalidDataError(xhr.response, xhr.status, xhr.statusText) );
			}
		};
		xhr.onerror = function () {
			resolve(invalidDataError(xhr.response, xhr.status, xhr.statusText) );
		};

		let data = {};
		data['rest_action'] = path;
		data['data'] = requestData;
		data = JSON.stringify(data, stripControls);
		xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
		xhr.send(data);
	});
}
const ajaxGet = (path) => {

	return new Promise(function (resolve, reject) {
		let url = siteUrl('ajax');
		url+='&rest_action='+path.replace('?', '&');
		let xhr = new XMLHttpRequest();
		xhr.open('GET', url);
		xhr.onload = function () {
			let response;
			try {
				response = JSON.parse(xhr.response);
			} catch (error) {
				resolve(invalidDataError(xhr.response, 500, 'invalid_data') );
			}
			if (xhr.status >= 200 && xhr.status < 300) {
				if ( !response.hasOwnProperty('request_success') ) {
					resolve(invalidDataError(xhr.response, 500, 'invalid_data') );
				}
				resolve(response);
			} else {
				resolve(invalidDataError(xhr.response, xhr.status, xhr.statusText) );
			}
		};
		xhr.onerror = function () {
			resolve(invalidDataError(xhr.response, xhr.status, xhr.statusText) );
		};
		xhr.send();
	});

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
					return ajaxGet(path);
				}
				return response.data;
			}
		).catch((error) => {
			//try with admin-ajax
			return ajaxGet(path);
		});
	} else {
		return apiFetch( { path: path } ).then((response) => {
			if ( !response.request_success ) {
				console.log("apiFetch failed, trying with ajaxGet");
				return ajaxGet(path);
			}
			return response;
		}).catch((error) => {
			console.log("apiFetch failed with catch error, trying with ajaxGet");
			return ajaxGet(path);
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
			return ajaxPost(path, data);
		});
	} else {
		return apiFetch( {
			path: path,
			method: 'POST',
			data: data,
		} ).catch((error) => {
			return ajaxPost(path, data);
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
