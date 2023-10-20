import {useState} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import {memo} from "@wordpress/element";

function ExportControl({ field, label }) {
	const [disabled, setDisabled] = useState(false);

	const Download = () => {
		if (disabled) return;
		setDisabled(true);
		let request = new XMLHttpRequest();
		request.responseType = 'blob';
		request.open('get', field.url, true);
		request.send();
		request.onreadystatechange = function() {
			if (this.readyState === 4 && this.status === 200) {
				var obj = window.URL.createObjectURL(this.response);
				var element = window.document.createElement('a');
				element.setAttribute('href',obj);
				element.setAttribute('download', 'complianz-export.json');
				window.document.body.appendChild(element);
				//onClick property
				element.click();
				setTimeout(function() {
					window.URL.revokeObjectURL(obj);
				}, 60 * 1000);
			}
		};

		request.onprogress = function(e) {
			setDisabled(true);
		};
	}

	return (
		<div className="cmplz-export-container">
				<button className="button button-default" onClick={() => Download()}>{__("Export","complianz-gdpr")}</button>
		</div>
	)
}

export default memo(ExportControl)
