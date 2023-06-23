import { __ } from '@wordpress/i18n';

const OtherPluginsHeader = () => {
	return (
		<>
			<h3 className="cmplz-grid-title cmplz-h4">{ __( "Other Plugins", 'complianz-gdpr' ) }</h3>
			<div className="cmplz-grid-item-controls">
				<a className="rsp-logo" href="https://really-simple-plugins.com/">
					<img src={cmplz_settings.plugin_url +'assets/images/really-simple-plugins.svg'} alt="Really Simple Plugins" />
				</a>
			</div>
		</>

	);

}
export default OtherPluginsHeader;
