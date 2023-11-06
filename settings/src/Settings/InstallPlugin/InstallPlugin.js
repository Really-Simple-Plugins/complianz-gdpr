import {useEffect} from "@wordpress/element";
import Icon from "../../utils/Icon";
import {__} from '@wordpress/i18n';
import useInstallPluginData from "./InstallPluginData";
import {memo} from "@wordpress/element";

const InstallPlugin = ( {field} ) => {
	const {statusLoaded, startPluginAction, apiRequestActive, pluginAction, rating, upgradeUrl, setStatusLoaded} = useInstallPluginData();
	const title = field.plugin_data.title;
	const summary = field.plugin_data.summary;
	const description = field.plugin_data.description;
	const image = field.plugin_data.image;
    useEffect(() => {
    	//get initial data
		if (!statusLoaded) {
			startPluginAction(field.plugin_data.slug, 'status');
		}
    }, [statusLoaded])

	useEffect(() => {
		setStatusLoaded(false);
	}, [field.plugin_data.slug])

	const onClickHandler = () => {
		startPluginAction(field.plugin_data.slug);
	}

	const wpStarRating = () => {

		// Calculate the number of each type of star needed.
		let fullStars  = Math.floor( rating );
		let halfStars  = Math.ceil( rating - fullStars );
		let emptyStars = 5 - fullStars - halfStars;
		fullStars = createArrayFromInt(fullStars);
		halfStars = createArrayFromInt(halfStars);
		emptyStars = createArrayFromInt(emptyStars);
		return (
			<div className="star-rating">
				<span className="screen-reader-text">{__('%s rating based on %d ratings', 'complianz-gdpr').replace('%s', '5').replace('%d', '84')}</span>
				{ fullStars.map((star, i) => <div key={i} className="star star-full" aria-hidden="true"></div> ) }
				{ halfStars.map((star, i) => <div key={i} className="star star-half" aria-hidden="true"></div> ) }
				{ emptyStars.map((star, i) => <div key={i} className="star star-empty" aria-hidden="true"></div> ) }
			</div>
		)
	}

	const createArrayFromInt = (n) => {
      let arr = [];
      for (let i = 1; i <= n; i++) {
        arr.push(i);
      }
      return arr;
    }

	let disabled = apiRequestActive;
	let buttonString;

	switch(pluginAction) {
      case 'upgrade-to-premium':
        buttonString = __("Upgrade","complianz-gdpr");
        break;
      case 'activate':
        buttonString = apiRequestActive ? __("Activating", "complianz-gdpr") : __("Activate","complianz-gdpr");
        break;
	  case 'download':
		buttonString = apiRequestActive ? __("Installing", "complianz-gdpr") : __("Install","complianz-gdpr");
		break;
      default:
      	disabled = true;
        buttonString = !statusLoaded ? __("Checking status","complianz-gdpr") : __("Installed","complianz-gdpr");
    }

	return (
		<div className="cmplz-suggested-plugin">
			<img className="cmplz-suggested-plugin-img" src={cmplz_settings.plugin_url+'/upgrade/img/'+image} />
			<div className="cmplz-suggested-plugin-group">
				<div className="cmplz-suggested-plugin-group-title">{title}</div>
				<div className="cmplz-suggested-plugin-group-desc">{summary}</div>
				<div className="cmplz-suggested-plugin-group-rating">
				{wpStarRating()}
				</div>
			</div>
			<div className="cmplz-suggested-plugin-desc-long">
				{ description }
			</div>
			<div>
				{pluginAction!=='upgrade-to-premium' &&
					<button type="button" disabled={disabled} onClick={ ( e ) => onClickHandler(e) } className="button-secondary cmplz-install-plugin">
						{ buttonString }
						{ apiRequestActive && <Icon name = "loading" color = 'grey' /> }
					</button>
				}
				{pluginAction ==='upgrade-to-premium' &&
					<a target="_blank" rel="noopener noreferrer" href={upgradeUrl} type="button" className="button-secondary cmplz-install-plugin">
						{ buttonString }
					</a>
				}
				</div>
		</div>
	)
}

export default memo(InstallPlugin)
