import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import useMenu from './MenuData';
import useFields from '../Settings/Fields/FieldsData';
import Icon from '../utils/Icon';
import {UseDocumentsData} from "../Settings/CreateDocuments/DocumentsData";

const useMenuItem = (menuItem, isMain) => {
	const {hasMissingPages, fetchDocumentsData, documentsDataLoaded, documentsDataLoading} = UseDocumentsData();

	const {
		selectedSubMenuItem,
		selectedMainMenuItem,
	} = useMenu();
	const {
		fieldsLoaded,
		fields,
		notCompletedRequiredFields,
		fetchAllFieldsCompleted
	} = useFields();
	const [completed, setCompleted] = useState(false);

	useEffect (  () => {
		if (selectedSubMenuItem ==='create-documents' && !documentsDataLoaded && !documentsDataLoading) {
			fetchDocumentsData();
		}
	}, [menuItem.id])

	useEffect(() => {
		if ( fieldsLoaded && !isMain ) {
			if (menuItem.id ==='create-documents') {
				setCompleted(!hasMissingPages);
			} else {
				const notCompletedFieldsOnPage = notCompletedRequiredFields.filter(
					(field) => field.menu_id === menuItem.id
				);
				setCompleted(notCompletedFieldsOnPage.length === 0);
			}
		}
	}, [notCompletedRequiredFields, hasMissingPages, documentsDataLoaded, selectedSubMenuItem]);

	useEffect(() => {
		fetchAllFieldsCompleted();
	},[fields]);

	return {
		completed,
		selectedSubMenuItem,
		selectedMainMenuItem,
	};
};

const MenuItem = ({ index, menuItem, isMain }) => {
	const {
		completed,
		selectedSubMenuItem,
		selectedMainMenuItem,
	} = useMenuItem(menuItem, isMain);
	const menuIsSelected = isSelectedMenuItem(selectedSubMenuItem, menuItem);
	const menuClass = getMenuClass(menuItem, isMain, menuIsSelected);
	const { icon, iconColor } = getIconProps(menuIsSelected, completed);

	const attributes = getMenuAttributes(
		menuItem,
		selectedMainMenuItem,
		selectedSubMenuItem,
	);

	if (menuItem.visible
		//&& menuHasFields
		) {
		return (
			<>
				<a {...attributes} className={`cmplz-wizard-menu-item ${menuClass}`}>
					{!isMain && <Icon name={icon} size={11} color={iconColor} />}
					{menuItem.title}
					{menuItem.featured && (
						<span className="cmplz-menu-item-featured-pill">
              {__('New', 'complianz-gdpr')}
            </span>
					)}
				</a>
				{menuItem.menu_items && menuIsSelected && (
					<div className="cmplz-submenu-items">
						{menuItem.menu_items.map(
							(subMenuItem, i) =>
								subMenuItem.visible && (
									<MenuItem key={subMenuItem.id} menuItem={subMenuItem} />
								),
						)}
					</div>
				)}
			</>
		);
	} else {
		return null;
	}
};

/**
 * Utility function to check if selected menu item is a child of the current menu item
 * @param menuItem
 * @param selectedSubMenuItem
 * @returns {boolean}
 */
const isSelectedMenuItemChild = (menuItem, selectedSubMenuItem) => {
	if (Array.isArray(menuItem.menu_items)) {
		return menuItem.menu_items.filter((item) => item.id === selectedSubMenuItem).length > 0;
	}
	return false;
};

/**
 * Utility function to check if selected menu item is the current menu item or a child of the current menu item
 * @param selectedSubMenuItem
 * @param menuItem
 * @returns {boolean}
 */
const isSelectedMenuItem = (selectedSubMenuItem, menuItem) => {
	if (selectedSubMenuItem === menuItem.id) {
		return true;
	}
	if (menuItem.menu_items) {
		for (const item of menuItem.menu_items) {
			if (item.id === selectedSubMenuItem) {
				return true;
			}
		}
	}
	return false;
};

/**
 * Utility function to get the menu class
 * @param menuItem
 * @param isMain
 * @param menuIsSelected
 * @returns {string}
 */
const getMenuClass = (menuItem, isMain, menuIsSelected) => {
	let menuClass = '';

	if (menuIsSelected) {
		menuClass += ' cmplz-active';
	}

	menuClass += isMain ? ' cmplz-main' : ' cmplz-sub';
	menuClass += menuItem.featured ? ' cmplz-featured' : '';
	menuClass += menuItem.premium && !cmplz_settings.is_premium ? ' cmplz-premium' : '';
	return menuClass;
};

/**
 * Utility function to get the icon props
 * @param menuIsSelected
 * @param completed
 * @returns {{icon: string, iconColor: string}}
 */
const getIconProps = (menuIsSelected, completed) => {
	let icon = 'circle';
	let iconColor = 'grey';

	if (completed) {
		icon = 'circle-check';
		iconColor = 'green';
	}

	if (menuIsSelected) {
		icon = 'bullet';
		iconColor = 'dark-blue';
	}

	if (menuIsSelected && completed) {
		icon = 'circle-check';
		iconColor = 'dark-blue';
	}

	return { icon, iconColor };
};

/**
 * Utility function to get the attributes for the menu item
 * @param menuItem
 * @param selectedMainMenuItem
 * @param selectedSubMenuItem
 * @returns {{}}
 */
const getMenuAttributes = (menuItem, selectedMainMenuItem, selectedSubMenuItem) => {
	const attributes = {};
	const selectedMenuItemIsChildOfThisItem = isSelectedMenuItemChild(
		menuItem,
		selectedSubMenuItem,
	);

	if (!menuItem.menu_items || !selectedMenuItemIsChildOfThisItem) {
		attributes.href = '#' + selectedMainMenuItem + '/' + menuItem.id;
	}

	return attributes;
};

export default MenuItem;
