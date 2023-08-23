import Icon from "../utils/Icon";
const Panel = (props) => {
	let hasContent = true;//not working properly yet. props.details.length > 0;
	return (
	  <div className="cmplz-panel__list__item" key={props.id} style={props.style ? props.style : {}}>
		<details>
		  <summary>
				{props.icon && <Icon name={props.icon} />}
			<h5 className="cmplz-panel__list__item__title">{props.summary}</h5>
			<div className="cmplz-panel__list__item__comment">{props.comment}</div>
			<div className="cmplz-panel__list__item__icons">{props.icons}</div>
			  {hasContent && <Icon name={'chevron-down'} size={18} /> }
		  </summary>
			{hasContent && <div className="cmplz-panel__list__item__details">
			{props.details}
		  </div> }
		</details>
	  </div>
	);
}

export default Panel
