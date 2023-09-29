import { __ } from '@wordpress/i18n';
import ProgressBlock from "./Progress/ProgressBlock";
import ProgressHeader from "./Progress/ProgressBlockHeader";
import ProgressFooter from "./Progress/ProgressFooter";
import DocumentsBlock from "./Documents/DocumentsBlock";
import DocumentsHeader from "./Documents/DocumentsHeader";
import DocumentsFooter from "./Documents/DocumentsFooter";
import Tools from "./Tools/Tools";
import ToolsHeader from "./Tools/ToolsHeader";
import ToolsFooter from "./Tools/ToolsFooter";
import OtherPlugins from "./OtherPlugins/OtherPlugins";
import TipsTricks from "./TipsTricks/TipsTricks";
import TipsTricksFooter from "./TipsTricks/TipsTricksFooter";
import OtherPluginsHeader from "./OtherPlugins/OtherPluginsHeader";
import ErrorBoundary from "../utils/ErrorBoundary";

/*
 * Mapping of components, for use in the config array
 * @type {{SslLabs: JSX.Element}}
 */
var dynamicComponents = {
    "ProgressBlock": ProgressBlock,
    "ProgressHeader": ProgressHeader,
    "ProgressFooter": ProgressFooter,
	"DocumentsBlock": DocumentsBlock,
	"DocumentsHeader": DocumentsHeader,
	"DocumentsFooter": DocumentsFooter,
	"TipsTricks": TipsTricks,
	"TipsTricksFooter": TipsTricksFooter,
	"ToolsHeader": ToolsHeader,
	"ToolsFooter": ToolsFooter,
	"Tools": Tools,
    "OtherPluginsHeader": OtherPluginsHeader,
    "OtherPlugins": OtherPlugins,
};

const GridBlock = ({block}) => {
	const blockData = block;
	const className = "cmplz-grid-item "+blockData.class+" cmplz-"+blockData.id;
	const footer =block.footer ? block.footer.data : false;

	return (
		<ErrorBoundary fallback={"Could not load:"+' '+blockData.id}>
			<div key={"block-"+blockData.id} className={className}>
				<div className="cmplz-grid-item-header">
					{blockData.header.type==='text' && <>
						<h3 className="cmplz-grid-title cmplz-h4">{ blockData.header.data }</h3>
						<div className="cmplz-grid-item-controls">
							{blockData.controls && blockData.controls.type==='url' && <a href={blockData.controls.data}>{__("Instructions", "complianz-gdpr")}</a>}
							{blockData.controls && blockData.controls.type==='react' && wp.element.createElement(dynamicComponents[blockData.controls.data])}
						</div>
					</>}
					{blockData.header.type==='react' && <>
						{ wp.element.createElement(dynamicComponents[blockData.header.data])}
					</>}
				</div>
				 <div className="cmplz-grid-item-content">{wp.element.createElement(dynamicComponents[block.content.data])}</div>

				{!footer && <div className="cmplz-grid-item-footer"></div>}
				{footer && <div className="cmplz-grid-item-footer">{wp.element.createElement(dynamicComponents[block.footer.data])}</div>}

			</div>
		</ErrorBoundary>
	);

}

export default GridBlock;
