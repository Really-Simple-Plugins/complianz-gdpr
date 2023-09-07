import { __ } from '@wordpress/i18n';
import Hyperlink from "./Hyperlink";

const readMore = (url) => {
	return <>&nbsp;<Hyperlink url={url} text={__('For more information, please read this %sarticle%s.', 'complianz-gdpr')}/>&nbsp;</>
}

export default readMore;
