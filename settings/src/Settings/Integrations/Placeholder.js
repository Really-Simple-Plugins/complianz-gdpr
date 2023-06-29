import SwitchInput from "../Inputs/SwitchInput";
import readMore from "../../utils/readMore";
import TextInput from "../Inputs/TextInput";
import SelectInput from "../Inputs/SelectInput";
import { __ } from '@wordpress/i18n';
import useIntegrations from "./IntegrationsData";

const Category = (props) => {
	const { setScript, fetching, placeholders } = useIntegrations();
	const script = props.script;
	const type = props.type;

	const onChangeHandler = (value, property) => {
		let copyScript = {...script};
		copyScript[property] = value;
		setScript(copyScript, props.type);
	}

	return (
		<>
			<div className="cmplz-details-row cmplz-details-row__checkbox">
				<SwitchInput
					disabled={fetching}
					value={script.enable_placeholder}
					onChange={(value)=>onChangeHandler(value, 'enable_placeholder')}
				/>
				<label>{__("Placeholder", "complianz-gdpr")}</label>
			</div>

			{ !!script.enable_placeholder && <>

				{type==='block_script' &&
					<div className="cmplz-details-row cmplz-details-row__checkbox">
						<SwitchInput
							disabled={fetching}
							value={script.iframe || ''}
							onChange={(value)=>onChangeHandler(value || '', 'iframe')}
						/>
						<label>{__("The blocked content is an iframe", "complianz-gdpr")}</label>
					</div>
				}

				{ !script.iframe &&
					<div className="cmplz-details-row cmplz-details-row">
						<>{__('Enter the div class or ID that should be targeted.','complianz-gdpr')}
							{readMore('https://complianz.io/integrating-plugins/#placeholder/')}</>
						<TextInput
							disabled={fetching}
							value={script.placeholder_class || ''}
							onChange={(value)=>onChangeHandler(value || '', 'placeholder_class')}
							name={"placeholder_class"}
							placeholder={__("Your CSS class", "complianz-gdpr")}
						/>
					</div>
				}

				<div className="cmplz-details-row cmplz-details-row__checkbox">
					<SelectInput
						disabled={fetching}
						value={script.placeholder ? script.placeholder : 'default'}
						options={placeholders}
						onChange={(value)=>onChangeHandler(value || 'default', 'placeholder')}
					/>
				</div>
			</>
			}
		</>
	);
}
export default Category;


