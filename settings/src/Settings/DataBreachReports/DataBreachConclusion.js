import useDataBreachReportsData from "./DataBreachReportsData";
import {useEffect} from "@wordpress/element";
import { __ } from '@wordpress/i18n';
import useFields from "../Fields/FieldsData";
import DataBreachConclusionItem from "./DataBreachConclusionItem";
import {memo} from "@wordpress/element";

const DataBreachConclusion = () => {
	const {
		savedDocument,
		conclusions,
	} = useDataBreachReportsData();
	const { addHelpNotice} = useFields();

	useEffect(() => {
		if  (savedDocument.has_to_be_reported ) {
			addHelpNotice('create-data-breach-reports', 'warning',
				__("This wizard is intended to provide a general guide to a possible data breach.", "complianz-gdpr") + ' ' +
				__("Specialist legal advice should be sought about your specific circumstances.", "complianz-gdpr"),
				__("Specialist legal advice required", "complianz-gdpr"),
				false);
		}
	},[savedDocument]);

	return (
		<>
			<div id="cmplz-conclusion"><h3>{ __( "Your dataleak report:", 'complianz-gdpr' )}</h3>
				<ul className="cmplz-conclusion__list">
					{ conclusions.length>0 && conclusions.map((conclusion, i) =>
						<DataBreachConclusionItem conclusion={conclusion} key={i} delay={i * 1000}/>
					)}
			</ul></div>

		</>
	)
}
export default memo(DataBreachConclusion);
