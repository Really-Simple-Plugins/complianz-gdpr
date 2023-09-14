/*Ensure preloading of most important fields*/
import Field from "./Field";
import {memo, useEffect, useState} from "@wordpress/element";;
import useFields from "./FieldsData";

const PreloadFields = () => {
	const { preloadFields } = useFields();
	const [shouldRender, setShouldRender] = useState(false);

	useEffect(() => {
		const timer = setTimeout(() => {
			setShouldRender(true);
		}, 100);

		return () => {
			clearTimeout(timer);
		};
	}, []);

	if (!shouldRender) {
		return null;
	}
	return (
		<div className="cmplz-hidden">
			{shouldRender && preloadFields.length > 0 &&
				preloadFields.map((field, i) => <Field key={i} field={field} />)}
		</div>
	);
};

export default memo(PreloadFields);
