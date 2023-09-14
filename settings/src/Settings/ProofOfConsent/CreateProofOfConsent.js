import { __ } from '@wordpress/i18n';
import useProofOfConsentData from "./useProofOfConsentData";
import Icon from "../../utils/Icon";
import {memo} from "@wordpress/element";
const CreateProofOfConsent = ({label, field}) => {
	const { generateProofOfConsent, generating} = useProofOfConsentData();

	return (
		<div className={'cmplz-field-button'}>
			{__("Create Proof of Consent","complianz-gdpr")}
			<button disabled={generating} className="button button-default cmplz-field-button" onClick={()=>generateProofOfConsent()} >
				{__("Generate","complianz-gdpr")}
				{generating && <Icon name = "loading" color = 'grey' />}
			</button>
		</div>
	)
}
export default memo(CreateProofOfConsent);
