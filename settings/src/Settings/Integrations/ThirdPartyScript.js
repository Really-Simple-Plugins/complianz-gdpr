import Panel from "../Panel";
import { __ } from '@wordpress/i18n';
import useIntegrations from "./IntegrationsData";
import AceEditorControl from "../Editor/AceEditorControl";
import SwitchInput from "../../Settings/Inputs/SwitchInput";
import {memo} from "@wordpress/element";
import Category from "./Category"
import Placeholder from "./Placeholder"
import Dependency from "./Dependency"
import Urls from "./Urls"
import CheckboxGroup from '../Inputs/CheckboxGroup';

const ThirdPartyScript = (props) => {
	const { setScript, fetching, saveScript, deleteScript } = useIntegrations();
	const script = props.script;
	const checkboxControl = (script) => {
		return (
			<>
				<SwitchInput className={"cmplz-switch-input-tiny"} onChange={ ( value ) =>  onChangeEnabledHandler(value, 'enable') } value={script.enable}/>
			</>
		)
	}

	const onChangeEnabledHandler = (checked, property) => {
		let copyScript = {...script};
		copyScript[property] = checked;
		setScript(copyScript, props.type);
		saveScript(copyScript, props.type);
	}

	const onChangeHandler = (value, property) => {
		let copyScript = {...script};
		copyScript[property] = value;
		setScript(copyScript, props.type);
	}

	const onSaveHandler = () => {
		saveScript(script, props.type);
	}
	const onDeleteHandler = () => {
		deleteScript(script, props.type);
	}

	const onEditorChangeHandler = (value) => {
		onChangeHandler(value, 'editor');
	}
	const ScriptDetails = (script, type) => {
		const { fetching } = useIntegrations();

		return (
			<>
				<div className="cmplz-details-row">
					<label>{__("Name", "complianz-gdpr")}</label>
					<input
						disabled={fetching}
						onChange={ ( e ) =>  onChangeHandler(e.target.value, 'name') }
						type="text" placeholder={__("Name", "complianz-gdpr")} value={script.name} />
				</div>

				{type==='add_script' &&
					<div className="cmplz-details-row">
						<AceEditorControl
							disabled={fetching}
							onChange={ (value) => onEditorChangeHandler(value)	}
							placeholder = "Enter your script here"
							value= { script.editor ? script.editor : console.log('marketing enabled') }/>
					</div>
				}

				{ (type==='block_script' || type==='whitelist_script' ) &&
					<Urls script={script} type={type} />
				}

				{ type!=='whitelist_script' &&
					<>
						<div className="cmplz-details-row cmplz-details-row__checkbox">
							<CheckboxGroup
								id={script.id}
								disabled={fetching}
								value={script.async}
								onChange={(value)=> onChangeHandler(value, 'async')}
								options={{true: __('This script contains an async attribute.','complianz-gdpr')}}
							/>
						</div>
						<div className="cmplz-details-row">
							<Category script={script} type={type} />
						</div>
						<Placeholder  script={script} type={type} />
					</>
				}

				{type==='block_script' &&
					<div className="cmplz-details-row cmplz-details-row__checkbox">
						<label>{__('Dependency', 'complianz-gdpr')}</label>
						<Dependency script={script} type={type} />
					</div>
				}

				<div className="cmplz-details-row cmplz-details-row__buttons">
					<button disabled={fetching} onClick={ ( e ) => onSaveHandler() } className="button button-default">{__("Save", "complianz-gdpr")}</button>
					<button disabled={fetching} className="button button-default cmplz-reset-button" onClick={ ( e ) => onDeleteHandler() }>
						{__("Delete", "complianz-gdpr")}
					</button>
				</div>
			</>
		)
	}
	if (!script) {
		return (
			<Panel summary={'...'} />
		)
	}
	return (

		<>
			<Panel summary={script.name} icons={checkboxControl(script)} details={ScriptDetails(script, props.type)} />
		</>
	)
}
export default memo(ThirdPartyScript)
