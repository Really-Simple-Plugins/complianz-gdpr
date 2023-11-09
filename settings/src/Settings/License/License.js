import TaskElement from '../../Dashboard/TaskElement';
import Placeholder from '../../Placeholder/Placeholder';
import {__} from '@wordpress/i18n';
import useFields from './../Fields/FieldsData';
import useLicense from './LicenseData';
import {useEffect} from '@wordpress/element';
import {memo} from "@wordpress/element";
import useProgress from "../../Dashboard/Progress/ProgressData";

const License = (props) => {
	const {fields, setChangedField, updateField} = useFields();
	const {setProgressLoaded} = useProgress();

	const {
		licenseStatus,
		licenseNotices,
		getLicenseNotices,
		noticesLoaded,
		activateLicense,
		deactivateLicense,
		processing,
	} = useLicense();
	useEffect(() => {
		if (!noticesLoaded) {
			getLicenseNotices();
		}
	}, []);

	const onChangeHandler = (fieldValue) => {
		setChangedField(field.id, fieldValue);
		updateField(field.id, fieldValue);
	};

	const toggleActivation = async () => {
		if (licenseStatus === 'valid') {
			await deactivateLicense();
		} else {
			await activateLicense(props.field.value);
		}
		//ensure a reload of the progress notices
		setProgressLoaded(false);
	};

	let field = props.field;
	/**
	 * There is no "PasswordControl" in WordPress react yet, so we create our own
	 * license field.
	 */
	let processingClass = processing ? 'cmplz-processing' : '';
	return (
		<div className="components-base-control">
			<div className="cmplz-license-field">
				<input className="components-text-control__input"
							 type="password"
							 id={field.id}
							 value={field.value}
							 onChange={(e) => onChangeHandler(e.target.value)}
				/>
				<button className="button button-default" disabled={processing}
								onClick={() => toggleActivation()}>
					{licenseStatus === 'valid' &&
						__('Deactivate', 'complianz-gdpr') }
					{licenseStatus !== 'valid' &&
						__('Activate', 'complianz-gdpr')}
				</button>
			</div>
			{!noticesLoaded && <Placeholder></Placeholder>}
			{noticesLoaded && <div className={processingClass}>
				{licenseNotices.map(
					(notice, i) => <TaskElement key={i} index={i} notice={notice} highLightField=""/>)}
			</div>}
		</div>
	);
};

export default memo(License)
