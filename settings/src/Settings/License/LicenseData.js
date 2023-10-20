import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
const UseLicenseData = create(( set, get ) => ({
    licenseStatus: cmplz_settings.licenseStatus,
	processing:false,
	licenseNotices: [],
	noticesLoaded: false,
	getLicenseNotices: async () => {
		const {licenseStatus, notices} = await cmplz_api.doAction('license_notices', {}).then( ( response ) => {
			return response;
		});
		set(state => ({ noticesLoaded:true,licenseNotices:notices, licenseStatus:licenseStatus}));
	},
	activateLicense: async (license) => {
		let data = {};
		data.license = license;
		set({processing:true})
		const {licenseStatus, notices} = await cmplz_api.doAction('activate_license', data);
		set(state => ({processing:false,licenseNotices:notices, licenseStatus:licenseStatus}));
	},
	deactivateLicense: async () => {
		set({processing:true})
		const {licenseStatus, notices} = await cmplz_api.doAction('deactivate_license');
		set(state => ({processing:false,licenseNotices:notices, licenseStatus:licenseStatus}));
	}

}));

export default UseLicenseData;

