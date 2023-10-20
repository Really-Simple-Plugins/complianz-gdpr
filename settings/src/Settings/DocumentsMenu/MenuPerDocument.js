import {UseMenuData} from "./MenuData";
import SingleDocumentMenuControl from "./SingleDocumentMenuControl";
import {memo} from "@wordpress/element";

const MenuPerDocument = (props) => {
	const {createdDocuments} = UseMenuData();
	//filter out this region from the documents
	let regionDocuments = createdDocuments.filter( document => document.region === props.region.id );
	if ( regionDocuments.length===0 ) {
		return null
	}

	return (
		<div>
			<h3 className="cmplz-h4">{ props.region.label }</h3>
			{ regionDocuments.map( (document, i)=> <SingleDocumentMenuControl key={i} document={document}/> )}
		</div>
	)

}
export default memo(MenuPerDocument)
