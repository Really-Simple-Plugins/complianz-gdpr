import { useState, useEffect, memo } from "@wordpress/element";
import { CKEditor } from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import useFields from "../Fields/FieldsData";
import './Editor.scss';
const Editor = ({id, value, onChange }) => {
	const [editorState, setEditorState] = useState(value);
	const {getFieldValue, updateField, setChangedField} = useFields();

	useEffect(() => {
		if (!editorState) {
			return;
		}

		const typingTimer = setTimeout(() => {
			updateField(id, editorState);
			setChangedField(id, editorState);
			onChange(editorState);
		}, 500);

		return () => {
			clearTimeout(typingTimer);
		};
	}, [editorState]);

	useEffect(() => {
		setEditorState(value);
	},[getFieldValue(id)] );

	return (
			<>
				<CKEditor
					editor={ ClassicEditor }
					config={{
						toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
					}}
					data={editorState}
					onChange={ ( event, editor ) => {
						const data = editor.getData();
						setEditorState(data)
					} }
				/>
			</>
	)
}

export default memo(Editor);
