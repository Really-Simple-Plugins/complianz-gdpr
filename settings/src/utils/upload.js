import axios from 'axios';

export const upload = (action, file, details) => {
	let formData = new FormData();
	formData.append("data", file);
	if ( typeof details !== 'undefined' ){
		formData.append("details", JSON.stringify(details));
	}
	return axios.post(cmplz_settings.admin_url+'?page=complianz&cmplz_upload_file=1&action='+action, formData, {
		headers: {
			"Content-Type": "multipart/form-data",
			'X-WP-Nonce': cmplz_settings.nonce,
		},
	});
}
