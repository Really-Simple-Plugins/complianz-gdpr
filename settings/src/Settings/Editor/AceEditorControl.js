import {memo} from "react";
import AceEditor from "react-ace";

import "ace-builds/src-noconflict/mode-css";
import "ace-builds/src-noconflict/theme-monokai";
import "ace-builds/src-noconflict/ext-language_tools";

const AceEditorControl = (props) => {
	let mode = props.mode ? props.mode : 'css';
	let height=props.height?props.height:'200px';
	let placeholder = props.field && props.field.default ? props.field.default : props.placeholder;
	return (
		<>
			{ <AceEditor
				disabled={props.disabled}
				placeholder={placeholder}
				mode={mode}
				theme="monokai"
				width="100%"
				height={height}
				onChange={(value) => props.onChangeHandler(value)}
				fontSize={14}
				showPrintMargin={true}
				showGutter={true}
				highlightActiveLine={true}
				value={props.value}
				setOptions={{
					enableBasicAutocompletion: false,
					enableLiveAutocompletion: false,
					enableSnippets: false,
					showLineNumbers: true,
					tabSize: 2	,
					useWorker: false
				}}/>
			}
		</>
	);
}
export default memo(AceEditorControl)
