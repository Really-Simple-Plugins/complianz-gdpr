import Error from '../utils/Error';
import {__} from "@wordpress/i18n";
import {useEffect, useState} from "@wordpress/element";;

const PagePlaceholder = (props) => {
    let plugin_url = cmplz_settings.plugin_url;
	const [ToastContainer, setToastContainer] = useState(null);

	useEffect(() => {
		import('react-toastify').then((module) => {
			const ToastContainer = module.ToastContainer;
			setToastContainer(() => ToastContainer);
		});
	}, []);
    return (
        <>
          <div className="cmplz-header-container">

            <div className="cmplz-settings-header">
              <img className="cmplz-header-logo"
                   src={plugin_url + 'assets/images/cmplz-logo.svg'}
                   alt="Complianz logo"/>
            </div>
          </div>
          <div className="cmplz-content-area cmplz-grid cmplz-dashboard cmplz-page-placeholder">
            <div className="cmplz-grid-item  cmplz-column-2 cmplz-row-2 ">
				{props.error && <Error error={props.error} /> }
				{props.lockedByUser>0 && <>
					<p>{__("The wizard is currently in use by user with ID: ","complianz-gdpr") + props.lockedByUser }</p>
					<p>{__("To prevent conflicts during saving the wizard is temporarily locked.","complianz-gdpr") }</p>
					<p>{__("The lock will automatically clear after two minutes.","complianz-gdpr") }</p>
				</>}
			</div>
            <div className="cmplz-grid-item cmplz-row-2"></div>
            <div className="cmplz-grid-item cmplz-row-2"></div>
            <div className="cmplz-grid-item  cmplz-column-2"></div>
          </div>
			{ToastContainer && (
				<ToastContainer
					position="bottom-right"
					autoClose={2000}
					limit={3}
					hideProgressBar
					newestOnTop
					closeOnClick
					pauseOnFocusLoss
					pauseOnHover
					theme="light"
				/>
			)}
        </>
    );
}

export default PagePlaceholder;

