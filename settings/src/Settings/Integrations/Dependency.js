import { __ } from '@wordpress/i18n';
import SwitchInput from "../Inputs/SwitchInput";
import SelectInput from "../Inputs/SelectInput";
import useIntegrations from "./IntegrationsData";
import CheckboxGroup from '../Inputs/CheckboxGroup';

const Dependency = (props) => {
	const { setScript, blockedScripts, fetching } = useIntegrations();
	const options = blockedScripts;
	// ensure that each url from both whitelist_scripts and blocked_scripts is in the options
	const script = props.script;

	const onChangeHandler = (value, property) => {
		let copyScript = {...script};
		copyScript[property] = value;
		setScript(copyScript, props.type);
	}

	const findSelectedDependency = (search) => {
		if (!script.dependency || script.dependency.length===0) return '';
		let deps = Object.entries(script.dependency);
		for (const [waitFor, shouldWait] of deps) {
			if (waitFor === search) {
				return shouldWait;
			}
		}
		return '';
	}

	const onChangeDependencyHandler = (shouldWait, waitFor) => {
		let copyScript = {...script};
		let dep = {...copyScript.dependency}
		dep[waitFor] = shouldWait;
		copyScript.dependency = dep;
		setScript(copyScript, props.type);
	}

	const dropOption = (options, option) => {
		let copyOptions = {...options};
		for (const [optionIndex, optionValue] of Object.entries(copyOptions)) {
			if (optionValue === option) {
				//delete 'optionIndex' from the options object
				delete copyOptions[optionIndex];
				break;
			}
		}
		return copyOptions
	}

	let urls = script.hasOwnProperty('urls') ? Object.entries(script.urls) : [''];
	return (
		<>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<CheckboxGroup
					id={script.id + 'dependency'}
					disabled={fetching}
					value={script.enable_dependency}
					onChange={(value)=>onChangeHandler(value, 'enable_dependency')}
					options={{true: __('Enable dependency', 'complianz-gdpr')}}
				/>
			</div>
			{ !!script.enable_dependency &&
				<div className="cmplz-details-row cmplz-details-row">
					{ urls.length>1 && urls.map( ([index, waitFor], i)=>
						<div key={i} className="cmplz-scriptcenter-dependencies" >
							<SelectInput
								disabled={fetching}
								value={findSelectedDependency(waitFor)}
								options={dropOption(options, waitFor)}
								onChange={(value)=>onChangeDependencyHandler(value, waitFor)}
							/>
							<div>{__("waits for: ", "complianz-gdpr")}
								{ waitFor ? waitFor : __("Empty URL","complianz-gdpr")}</div>
						</div>
					)}
					{urls.length<=1 && <>{__("Add a URL to create a dependency between two URLs", "complianz-gdpr")}</>}
				</div>
			}
		</>
	);
}
export default Dependency;
