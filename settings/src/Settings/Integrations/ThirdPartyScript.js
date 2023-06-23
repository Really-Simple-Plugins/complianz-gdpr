import Panel from "../Panel";
import { __ } from '@wordpress/i18n';
import useIntegrations from "./IntegrationsData";
import {useState} from "@wordpress/element";
import AceEditorControl from "../Editor/AceEditorControl";
import SwitchInput from "../../Settings/Inputs/SwitchInput";
import {memo} from "react";
import Category from "./Category"
import Placeholder from "./Placeholder"
import Dependency from "./Dependency"
import Urls from "./Urls"

const ThirdPartyScript = (props) => {
	const { setScript, saveScript, deleteScript } = useIntegrations();
	const script = props.script;
	const checkboxControl = (script) => {
		return (
			<>
				<input onChange={ ( e ) =>  onChangeEnabledHandler(e.target.checked, 'enable') }  type="checkbox" checked={script.enable}/>
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
							onChangeHandler={ (value) => onEditorChangeHandler(value)	}
							placeholder = "console.log('marketing enabled')"
							value= { script.editor }/>
					</div>
				}

				{ (type==='block_script' || type==='whitelist_script' ) &&
					<Urls script={script} type={type} />
				}

				{ type!=='whitelist_script' &&
					<>
						<div className="cmplz-details-row cmplz-details-row__checkbox">
							<SwitchInput
								disabled={fetching}
								value={script.async}
								onChange={(value)=>onChangeHandler(value, 'async')}
							/>
							<label>{__("This script contains an async attribute.", "complianz-gdpr")}</label>
						</div>
						<div className="cmplz-details-row">
							<Category script={script} type={type} />
						</div>
						<Placeholder  script={script} type={type} />
					</>
				}

				{type==='block_script' &&
					<>
						<Dependency script={script} type={type} />
					</>
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

