import Icon from "../utils/Icon";
import {useEffect, useState} from "@wordpress/element";

const Panel = (props) => {
	const [isOpen, setIsOpen] = useState(false); // State to track the open state of details

	const handleOpen = (e) => {
		e.preventDefault();
		setIsOpen(!isOpen);
	};

	return (
	  <div className="cmplz-panel__list__item" style={props.style ? props.style : {}}>
		<details open={isOpen} >
		  <summary onClick={(e) => handleOpen(e)}>
				{props.icon && <Icon name={props.icon} />}
				<h5 className="cmplz-panel__list__item__title">{props.summary}</h5>
				<div className="cmplz-panel__list__item__comment">{props.comment}</div>
				<div className="cmplz-panel__list__item__icons">{props.icons}</div>
			  <Icon name={'chevron-down'} size={18} />
		  </summary>
			<div className="cmplz-panel__list__item__details">
				{isOpen && props.details}
		  	</div>
		</details>
	  </div>
	);
}

export default Panel
