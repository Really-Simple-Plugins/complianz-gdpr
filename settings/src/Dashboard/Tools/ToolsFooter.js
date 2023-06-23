import {__} from "@wordpress/i18n";
import {
	useEffect, useState
} from '@wordpress/element';
import useFields from "../../Settings/Fields/FieldsData";
const ToolsFooter = () => {
	return null;
	const {fields, getFieldValue} = useFields();
	const [abTestingEnabled, setAbTestingEnabled] = useState(false);

	// useEffect (() => {
	// 	let ab = getFieldValue('use_country')==1 && getFieldValue('a_b_testing_buttons')==1;
	// 	setAbTestingEnabled(ab);
	// },[fields])

	return (
		<>
			{ abTestingEnabled && <>
				{__("What does it mean? - ", "complianz-gdpr") };<a href="https://really-simple-ssl.com/instructions/lorem-ipsum/" target="_blank">{__("Read more", "complianz-gdpr")}</a>
			</>}
		</>
	)
}
export default ToolsFooter;
