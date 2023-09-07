import { __ } from '@wordpress/i18n';
import readMore from "../../utils/readMore";
import TextInput from "../Inputs/TextInput";
import Icon from "../../utils/Icon";
import useIntegrations from "./IntegrationsData";

const Urls = (props) => {
	const { setScript, fetching } = useIntegrations();
	const script = props.script;
	const type = props.type;

	const onChangeUrlHandler = (index, url) => {
		let copyScript = {...script};
		let urls = {...copyScript.urls};
		urls[index] = url;
		copyScript.urls = urls;
		setScript(copyScript, props.type);
	}

	const addUrl = () => {
		let copyScript = {...script};
		let curLength = Object.keys(copyScript.urls).length;
		let urls = {...copyScript.urls};

		urls[curLength+1] = '';
		copyScript.urls = urls;
		setScript(copyScript, props.type);
	}

	const deleteUrl = (key) => {
		let copyScript = {...script};
		let urls = {...copyScript.urls};
		//delete index 'key' from copyScript.urls
		delete urls[key];
		copyScript.urls = urls;
		setScript(copyScript, props.type);
	}
	let urls = script.hasOwnProperty('urls') ? Object.entries(script.urls) : [''];
	return (
		<div className="cmplz-details-row">
			<label>
				{type==='block_script' && __("URLs that should be blocked before consent.", "complianz-gdpr")}
				{type==='whitelist_script' &&
					<>{__("URLs that should be whitelisted.", "complianz-gdpr")}
						{readMore("https://complianz.io/whitelisting-inline-script/") }
					</>
				}
			</label>
			{ urls.map( ([index, url], i)=>
				<div key={i} className="cmplz-scriptcenter-url">
					<TextInput
						disabled={fetching}
						value={url ? url : ''}
						onChange={(value)=>onChangeUrlHandler(index, value)}
						id={i+"_url"}
						name={"url"}
					/>
					{i===0 && <button className="button button-default" onClick={() => addUrl() }> <Icon name="plus" size={14}/></button>}
					{i!==0 && <button className="button button-default" onClick={() => deleteUrl(index) }> <Icon name="minus" size={14}/></button>}
				</div>
			)}
		</div>
	);
}
export default Urls;
