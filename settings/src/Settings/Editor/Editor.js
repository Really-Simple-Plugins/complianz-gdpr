import { useState, useEffect, memo } from 'react';
import useFields from "../../Settings/Fields/FieldsData";

let SimpleRichTextEditor;
const toolbarConfig = {
	display: ['INLINE_STYLE_BUTTONS', 'BLOCK_TYPE_BUTTONS', 'HISTORY_BUTTONS'],
	INLINE_STYLE_BUTTONS: [
		{ label: 'Bold', style: 'BOLD', className: 'custom-css-class' },
		{ label: 'Italic', style: 'ITALIC' },
		{ label: 'Underline', style: 'UNDERLINE' },
	],
	BLOCK_TYPE_BUTTONS: [
		{ label: 'UL', style: 'unordered-list-item' },
		{ label: 'OL', style: 'ordered-list-item' },
	],
}

const Editor = ({ value, field, label }) => {
	const [editorState, setEditorState] = useState(null);
	const [isEditorLoaded, setEditorLoaded] = useState(false);

	useEffect(() => {
		import('react-rte').then(({default: loadedSimpleRichTextEditor}) => {
			SimpleRichTextEditor = loadedSimpleRichTextEditor;
			setEditorState(loadedSimpleRichTextEditor.createValueFromString(value, 'html'));
			setEditorLoaded(true);
		});
	}, []);

	const {changedFields, updateField, setChangedField} = useFields();

	useEffect(() => {
		if (isEditorLoaded) {
			setEditorState(SimpleRichTextEditor.createValueFromString(value, 'html'));
		}
	}, [changedFields, isEditorLoaded]);

	useEffect(() => {
		setChangedField(field.id, value);
	}, [])

	function editorChangeHandler(editorValue) {
		setEditorState(editorValue)
		updateField(field.id, editorValue.toString('html'));
	}

	if (!isEditorLoaded) {
		return null;  // or return a loader
	}

	return (
			<SimpleRichTextEditor
				value={editorState}
				onChange={editorChangeHandler}
				toolbarConfig={toolbarConfig}
			/>
	)
}

export default memo(Editor);
