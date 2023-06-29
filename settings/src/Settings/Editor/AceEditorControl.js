import {memo, useEffect, useState} from "react";
import AceEditor from "react-ace";
import './AceEditor.scss';
import "ace-builds/src-noconflict/mode-css";
import "ace-builds/src-noconflict/theme-monokai";
import "ace-builds/src-noconflict/ext-language_tools";

const AceEditorControl = (props) => {
	let mode = props.mode ? props.mode : 'css';
	let height=props.height?props.height:'200px';
	let placeholder = props.field && props.field.default ? props.field.default : props.placeholder;
	const [inputValue, setInputValue] = useState(props.value);
	console.log(props);
	//because an update on the entire Fields array is costly, we only update after the user has stopped typing
	useEffect(() => {
		const typingTimer = setTimeout(() => {
			props.onChange(inputValue);
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [inputValue]);

	const handleChange = ( value ) => {
		setInputValue(value);
	};
	let editorClass = props.disabled ? 'cmplz-editor-disabled' : '';
	return (
		<div className={editorClass}>
			{ <AceEditor
				readOnly={props.disabled}
				placeholder={placeholder}
				mode={mode}
				theme="monokai"
				width="100%"
				height={height}
				onChange={(value) => handleChange(value)}
				fontSize={14}
				showPrintMargin={true}
				showGutter={true}
				highlightActiveLine={true}
				value={inputValue}
				setOptions={{
					enableBasicAutocompletion: false,
					enableLiveAutocompletion: false,
					enableSnippets: false,
					showLineNumbers: true,
					tabSize: 2	,
					useWorker: false
				}}/>
			}
		</div>
	);
}
export default memo(AceEditorControl)
