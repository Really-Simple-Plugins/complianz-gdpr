import Icon from "../../utils/Icon";
import { __ } from '@wordpress/i18n';

import {
	useState, useEffect
} from '@wordpress/element';
import SelectInput from '../../Settings/Inputs/SelectInput';
const SingleDocument = (props) => {
	const [url, setUrl] = useState(false);
	const [disabled, setDisabled] = useState(false);
	const [options, setOptions] = useState(false);

	useEffect( () => {
		let options = props.options;
		if ( options.length===0) {
			let emptyOption = {label:props.name, value:0};
			options.unshift(emptyOption);
		} else {
			//if options does not include an option with value 0, add it.
			if ( !options.filter( (option) => option.value === 0).length>0 ) {
				let emptyOption = {label:props.name, value:0};
				options.unshift(emptyOption);
			}

		}
		setOptions(options);

	}, [props.options] );

	const Download = () => {
		if (disabled || !url || url===0) return;
		setDisabled(true);
		let request = new XMLHttpRequest();
		request.responseType = 'blob';
		request.open('get', url, true);
		request.send();
		request.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var obj = window.URL.createObjectURL(this.response);

				var element = window.document.createElement('a');
				element.setAttribute('href',obj);
				element.setAttribute('download', options.filter( (option) => option.value ===url)[0].label);
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
		<div className="cmplz-single-document-other-documents">
			<SelectInput onChange={ (value) => setUrl(value) } defaultValue={'0'} canBeEmpty={false} value={url} options={options} />
			<div onClick={()=> Download()}>
				<Icon name={'file-download'} color={ (url!=0 && !disabled) ? 'black' : 'grey'} tooltip={__("Download file","complianz-gdpr")} size={14} />
			</div>
			{options.length>0 &&
				<a href={props.link}><Icon name={'circle-chevron-right'} color="black" tooltip={__("Go to overview","complianz-gdpr")} size={14} /></a>}
			{options.length===0 &&
				<a href={props.link}><Icon name={'plus'} color="black" tooltip={__("Create new","complianz-gdpr")} size={14} /></a>}

		</div>
	)
}
export default SingleDocument
