import {UseMenuData} from "./MenuData";
import SingleDocumentMenuControl from "./SingleDocumentMenuControl";
import {memo} from "@wordpress/element";

const MenuPerDocumentType = (props) => {
	const {genericDocuments } = UseMenuData();
	// filter out this region from the documents
	let typeDocuments = genericDocuments.filter( document => document.type === props.pageType.type );
	if ( typeDocuments.length===0 ) {
		return null
	}

	return (
		<div>
			<h3 className="cmplz-h4">{ props.type }</h3>
			{ typeDocuments.map( (document, i)=> <SingleDocumentMenuControl key={i} document={document}/> )}
		</div>
	)

}
export default memo(MenuPerDocumentType)
