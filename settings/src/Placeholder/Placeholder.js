import Error from "../utils/Error";


const Placeholder = (props) => {
	let lines = props.lines;
	if ( !lines ) lines = 4;
	return (
		<div className={"cmplz-placeholder cmplz-placeholder-count-"+lines}>
			{props.error && <Error error={props.error} /> }
			{Array.from({length: lines}).map((item, i) => (<div className="cmplz-placeholder-line" key={i} ></div>))}
		</div>
	);
}

export default Placeholder;
