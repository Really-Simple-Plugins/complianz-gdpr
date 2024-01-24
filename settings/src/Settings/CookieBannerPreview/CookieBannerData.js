import {create} from 'zustand';
import * as cmplz_api from "../../utils/api";
import {toast} from "react-toastify";
import { __ } from '@wordpress/i18n';

const UseBannerData = create(( set, get ) => ({
	bannerDataLoaded:false,
	bannerDataLoading:false,
	setBannerDataLoaded: (bannerDataLoaded) => set({ bannerDataLoaded }),
	setCssLoading: (cssLoading) => set({ cssLoading }),
	customizeUrl:'#',
	cssLoaded:false,
	bannerHtml:'',
	pageLinks:[],
	cssFile:'',
	cssIndex:1,
	selectedBannerId:0,
	selectedBanner:{},
	banners:[],
	manageConsentHtml:'',
	bannerContainerClass:'',
	tcfActiveServerside:false,
	setBannerContainerClass: (bannerContainerClass) => set({ bannerContainerClass }),
	consentTypes:[],
	consentType:'',
	setLanguage: (language) => set({ language }),
	setConsentType: (consentType) => {
		if (typeof (Storage) !== "undefined" ) {
			sessionStorage.cmplzBannerPreviewConsentType = consentType;
		}
		set({ consentType })
	},
	setBannerId: (bannerId) => {
		let banners = get().banners;

		if (typeof (Storage) !== "undefined" ) {
			sessionStorage.cmplzBannerPreviewBannerID = bannerId;
		}
		let selectedBanner = banners.filter( (banner) => banner.ID === bannerId )[0];

		set({ selectedBanner:selectedBanner,selectedBannerId:bannerId, bannerFieldsSynced:false })
	},
	saveBanner: (fields) => {
		let selectedBanner = get().selectedBanner;
		fields = fields.filter( field => field.data_target === 'banner');
		let data = {};
		data.fields = fields;
		data.banner_id = selectedBanner.ID;
		const response = cmplz_api.doAction('update_banner_data', data).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		toast.promise(
			response,
			{
				pending: __('Saving settings...', 'complianz-gdpr'),
				success: __('Settings saved', 'complianz-gdpr'),
				error: __('Something went wrong', 'complianz-gdpr'),
			}
		);
	},
	generatePreviewCss : async (bannerFields) => {
		if (get().cssLoading ) {
			return;
		}
		set( {
			cssLoading: true,
		} );
		let consentType = get().consentType;
		let selectedBanner = get().selectedBanner;
		let banner_id = selectedBanner.ID;
		let data = {};
		data.fields = bannerFields;
		data.banner_id = banner_id;

		await cmplz_api.doAction('generate_preview_css', data).then(() => {
			return true;
		}).catch((error) => {
			console.error(error);
		});
		let cssFile = get().cssFile;
		let cssIndex = get().cssIndex;

		let link = document.createElement("link");
		cssFile = cssFile+'?'+Math.random();

		cssFile = cssFile.replace('banner-', 'banner-preview-');
		cssFile = cssFile.replace('{type}', consentType ).replace('{banner_id}', banner_id);
		link.href = cssFile;
		link.type = "text/css";
		link.rel = "stylesheet";
		let newCssIndex = cssIndex+1;
		link.classList.add('cmplz-banner-css-'+newCssIndex);
		document.getElementsByTagName("head")[0].appendChild(link);
		link.onload = function () {
			//remove old css

			let oldBannerCss = document.querySelector('.cmplz-banner-css-'+cssIndex);
			if ( oldBannerCss ) {
				oldBannerCss.parentElement.removeChild(oldBannerCss);
			}
			var event = new CustomEvent('cmplzCssLoaded');
			document.dispatchEvent(event);
			cssIndex++;
			set( {
				cssLoaded: true,
				cssIndex: newCssIndex,
				cssLoading: false,
			} );
		}
	},
	fetchBannerData: async () => {
		if ( get().bannerDataLoading ) {
			return;
		}

		set( {
			bannerDataLoading: true,
		} );
		const {customize_url, css_file, banner_html, manage_consent_html, consent_types, default_consent_type, banners, page_links, tcf_active} = await cmplz_api.doAction('get_banner_data', {}).then((response) => {
			return response;
		}).catch((error) => {
			console.error(error);
		});
		let defaultBanners = banners.filter( (banner) => banner.default === "1" );
		let defaultBanner = defaultBanners.length === 0 ? banners[0] : defaultBanners[0];

		let consentType = default_consent_type;
		let selectedBannerId = defaultBanner.ID;
		let selectedBanner = defaultBanner;
		if (typeof (Storage) !== "undefined"){
			if ( sessionStorage.cmplzBannerPreviewConsentType ) {
				//only use from local storage if the consent type is still valid
				let storedConsentType = sessionStorage.cmplzBannerPreviewConsentType;
				if (Object.keys(consent_types).includes(storedConsentType)) {
					consentType = storedConsentType;
				}
			}
			if (sessionStorage.cmplzBannerPreviewBannerID) {
				let storedBanner = banners.filter( (banner) => banner.ID === sessionStorage.cmplzBannerPreviewBannerID )[0];
				//if the found banner id isn't there anymore, don't update the selected banner
				if (typeof storedBanner !== 'undefined') {
					selectedBanner = storedBanner;
					selectedBannerId = storedBanner.ID;
				}
			}
		}

		set( {
			customizeUrl: customize_url,
			selectedBannerId: selectedBannerId,
			selectedBanner: selectedBanner,
			bannerDataLoaded: true,
			bannerDataLoading:false,
			bannerHtml: banner_html,
			cssFile: css_file,
			banners: banners,
			manageConsentHtml: manage_consent_html,
			consentTypes: consent_types,
			consentType:consentType,
			pageLinks: page_links,
			tcfActiveServerside: tcf_active,
		} );
		return true;
	},
	setSelectedBanner:(selectedBannerId) => {
		let banners = get().banners;
		let selectedBanner = banners.filter( banner => banner.ID === selectedBannerId )[0];
		set( {
			selectedBanner: selectedBanner,
		} );
	}

}));
export default UseBannerData;



