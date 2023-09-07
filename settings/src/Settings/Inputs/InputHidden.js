import {memo} from 'react';

const InputHidden = ({value}) => {
			return (
				<input type="hidden" value={value} />
		);
}
export default memo(InputHidden);
