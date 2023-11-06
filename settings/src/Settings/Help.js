import Icon from "../utils/Icon";
import { __ } from '@wordpress/i18n';
import DOMPurify from "dompurify";

/**
 * Render a help notice in the sidebar
 */
const Help = (props) => {
	let notice = props.help;
	if ( !notice.title ){
		notice.title = notice.text;
		notice.text = false;
	}
	let openStatus = props.noticesExpanded ? 'open' : '';
	//we can use notice.linked_field to create a visual link to the field.

	let target = notice.url && notice.url.indexOf("complianz.io") !==-1 ? "_blank" : '_self';
	return (
		<>
			{ notice.title && notice.text &&
				<details className={"cmplz-wizard-help-notice cmplz-" + notice.label.toLowerCase()} open={openStatus}>
					<summary>{notice.title} <Icon name='chevron-down' /></summary>
					{/*some notices contain html, like for the htaccess notices. A title is required for those options, otherwise the text becomes the title. */}
					<div
						dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(notice.text) }}  ></div> {/* nosemgrep: react-dangerouslysetinnerhtml */}
					{notice.url && <div className="cmplz-help-more-info"><a target={target} href={notice.url}>{__("More info", "complianz-gdpr")}</a></div>}
				</details>
			}
			{ notice.title && !notice.text &&
				<div className={"cmplz-wizard-help-notice cmplz-" + notice.label.toLowerCase()}><p>{notice.title}</p></div>
			}
		</>

	);

}

export default Help
