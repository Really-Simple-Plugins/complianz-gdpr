import { __ } from '@wordpress/i18n';
import useDocuments from "./DocumentsData";
const SingleDocument = ({document, index}) => {
	const {region } = useDocuments();
	let regions = document.regions.filter( (docRegion) => docRegion !== region);

	return (

		<div key={index} className="cmplz-single-document-other-regions">
			<a href={document.readmore} target="_blank" rel="noopener noreferrer">{document.title}</a>
				{ regions.map( (region, i)=>
					<div key={i} className="cmplz-region-indicator">
						<img alt={region} width="16px" height="16px" src={cmplz_settings.plugin_url + "/assets/images/"+region+".svg"} />
					</div>
				 )}
		</div>

	);
}
const OtherRegions = () => {
	const documents = [
		{
			id: 'privacy-statement',
			title: "Privacy Statements",
			regions: ['eu', 'us', 'uk', 'ca', 'za', 'au', 'br'],
			readmore: 'https://complianz.io/definition/what-is-a-privacy-statement/',
		},
		{
			id: 'cookie-statement',
			title: "Cookie Policy",
			regions: ['eu', 'us', 'uk', 'ca', 'za', 'au', 'br'],
			readmore: ' https://complianz.io/definition/what-is-a-cookie-policy/',
		},
		{
			id: 'impressum',
			title: "Impressum",
			regions: ['eu'],
			readmore: 'https://complianz.io/definition/what-is-an-impressum/',
		},
		{
			id: 'do-not-sell-my-info',
			title: "Opt-out preferences",
			regions: ['us'],
			readmore: 'https://complianz.io/definition/what-is-do-not-sell-my-personal-information/',
		},
		{
			id: 'privacy-statement-for-children',
			title: "Privacy Statement for Children",
			regions: ['us', 'uk', 'ca', 'za', 'au', 'br'],
			readmore: 'https://complianz.io/definition/what-is-a-privacy-statement-for-children/',
		}
	]


	return (
		<>
			<div className="cmplz-document-header">
				<h3 className="cmplz-h4">{__("Other regions")}</h3><a href="https://complianz.io/features/" target="_blank" rel="noopener noreferrer">{__("Read more","complianz-gdpr")}</a>
			</div>
			{ documents.map( (document, i)=> <SingleDocument key={i} index={i} document={document}/> )}
		</>

	)
}
export default OtherRegions;
