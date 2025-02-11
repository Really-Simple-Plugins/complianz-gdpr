import { useEffect } from '@wordpress/element';
import { useNewOnboardingData } from "../NewOnboardingData";
import { __ } from '@wordpress/i18n';
import CheckBox from '../components/CheckBox';

const Plugins = () => {
	const { currentStep, plugins, fetchPlugins, setPlugins, setEnablePluginInstallation, setIsContentLoading } = useNewOnboardingData();

	useEffect(() => {
		const loadPlugins = async () => {
			try {
				setIsContentLoading(true);
				await fetchPlugins();
			} catch (error) {
				console.log(error);

			} finally {
				setIsContentLoading(false);
			}
		}
		loadPlugins();
	}, [fetchPlugins]);

	const handleChange = (plugin, isChecked) => {
		// checkbox change update checked and toProcess
		const updatedPlugins = plugins.map(p =>
			p.slug === plugin.slug ? {
				...p,
				checked: isChecked,
				toProcess: isChecked,
			} : p
		);
		setPlugins(updatedPlugins);

		const someToProcess = updatedPlugins.some(p => p.toProcess); // bool
		setEnablePluginInstallation(someToProcess);
	};

	return (
		<div className={`cmplz-modal-content-step ${currentStep}`}>
			<p>{__("You want more Really Simple Plugins? Select below plugins you'd like to install for free! It only takes 10 seconds..", "complianz-gdpr")}</p>
			{plugins && plugins.map((plugin, i) => <CheckBox key={plugin.slug} plugin={plugin} handleChange={handleChange} />)}
		</div>
	);
};

export default Plugins;

