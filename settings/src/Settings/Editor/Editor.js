import { useState, useEffect, memo } from "@wordpress/element";
import { CKEditor } from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {__} from '@wordpress/i18n';

import useFields from "../Fields/FieldsData";

import './Editor.scss';
const Editor = ({id, value, onChange }) => {
	const [editorState, setEditorState] = useState(value);
	const [view, setView] = useState('wysiwyg');
	const [htmlValue, setHtmlValue] = useState(value);
	const {getFieldValue, updateField, setChangedField} = useFields();

	useEffect(() => {
		if ( htmlValue === value ) {
			return;
		}

		const typingTimerHtml = setTimeout(() => {
			setEditorState(htmlValue);
		}, 500);

		return () => {
			clearTimeout(typingTimerHtml);
		};
	}, [htmlValue]);

	useEffect(() => {
		if ( editorState === value ) {
			return;
		}

		const typingTimer = setTimeout(() => {
			updateField(id, editorState);
			setHtmlValue(editorState);
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

	const toggleView = () => {
		if (view === 'wysiwyg') {
			setView('html');
		} else {
			setView('wysiwyg');
		}
	}

	const onChangeHtml = (html) => {
		setHtmlValue(html);
		setEditorState(html);
	}

	return (
			<>
				<button className="button button-default" onClick={(e) => toggleView()}>{view==='wysiwyg' && 'HTML'}
					{view==='html' && __('Editor', 'complianz-gdpr') }
				</button>
				{view==='html' && <>
					<textarea rows="8" onChange={(e)=>onChangeHtml(e.target.value)} value={htmlValue}></textarea>
				</> }
				{view==='wysiwyg' && <>
					<CKEditor
						editor={ ClassicEditor }
						config={{
							toolbar: ['undo', 'redo', 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable'],
						}}
						data={editorState}
						onChange={ ( event, editor ) => {
							const data = editor.getData();
							setEditorState(data)
						} }
					/>
				</>
				}
			</>
	)
}

export default memo(Editor);
