import Icon from "../utils/Icon";
import { __ } from '@wordpress/i18n';
import useOnboardingData from "./OnboardingData";

const InstallPlugin = ({plugin, processing}) => {
	const { pluginAction } = useOnboardingData();

	const installPlugin = async (slug) => {
		await pluginAction(slug, 'install_plugin');
		await pluginAction(slug, 'activate_plugin');
	}

	const activatePlugin = async (slug) => {
		await pluginAction(slug, 'activate_plugin');
	}

	let iconColor = 'grey';
	let iconName = processing || plugin.processing ? 'loading' : 'info';

	if (plugin.status === 'activated') {
		iconColor = 'green';
		iconName = 'circle-check';
	}
	return (
		<div className="cmplz-onboarding-item">
			<Icon name={iconName} color={iconColor} size={14} />
			{plugin.description}&nbsp;
			{ plugin.status==='not-installed' && <a href="#" onClick={(e) => installPlugin(plugin.slug) }>
				{!plugin.processing && __("Install", "complianz-gdpr")}
				{plugin.processing && __("Installing...", "complianz-gdpr")}
			</a>}
			{ plugin.status==='installed' && <a href="#" onClick={(e) => activatePlugin(plugin.slug) }>
				{!plugin.processing && __("Activate", "complianz-gdpr")}
				{plugin.processing && __("Activating...", "complianz-gdpr")}
			</a>}
			{ plugin.status==='activated' && __("Installed!", "complianz-gdpr")}
		</div>
	)
}
export default InstallPlugin;
