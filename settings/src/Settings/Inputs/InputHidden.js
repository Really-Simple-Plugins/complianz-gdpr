import {memo} from "@wordpress/element";

const InputHidden = ({value}) => {
			return (
				<input type="hidden" value={value} />
		);
}
export default memo(InputHidden);
