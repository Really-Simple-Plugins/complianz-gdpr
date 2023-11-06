import MenuItem from './MenuItem';
import { __ } from '@wordpress/i18n';
import useMenu from "./MenuData";
import CookieBannerControls from "../Settings/CookieBannerPreview/CookieBannerControls";
import MenuPlaceholder from "../Placeholder/MenuPlaceholder";

/**
 * Menu block, rendering the entire menu
 */
 const Menu = () => {
	const {subMenu, hasPremiumItems, subMenuLoaded, selectedMainMenuItem} = useMenu();
	if ( !subMenuLoaded ) {
		return(
			<MenuPlaceholder />
		)
	}
	return (
		<div className="cmplz-wizard-menu">
			<div className="cmplz-wizard-menu-header">
				<h1 className="cmplz-h4">{subMenu.title}</h1>
			</div>
				<div className="cmplz-wizard-menu-items">
					{subMenu.menu_items.map((menuItem, i) => {
						return <MenuItem key={menuItem.id} index={i + 1} menuItem={menuItem} isMain={true} />;
					})
					}
					{ hasPremiumItems && cmplz_settings.is_premium &&
						<div className="cmplz-premium-menu-item"><a target="_blank" rel="noopener noreferrer" href={cmplz_settings.upgrade_link} className='button button-black'>{__('Go Pro', 'complianz-gdpr')}</a></div>
					}
					{selectedMainMenuItem==='banner' && <CookieBannerControls /> }
				</div>
		</div>
	)
}
export default Menu;
