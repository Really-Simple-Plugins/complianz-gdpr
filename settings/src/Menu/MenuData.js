import {create} from 'zustand';
import getAnchor from "../utils/getAnchor";
const useMenu = create(( set, get ) => ({
	menu: [],
	subMenuLoaded:false,
	previousMenuItem:false,
	nextMenuItem:false,
	selectedMainMenuItem:'dashboard',
	selectedSubMenuItem:false,
	hasPremiumItems:false,
	subMenu:{title:' ',menu_items:[]},
	setSelectedSubMenuItem: (selectedSubMenuItem) => set(state => ({ selectedSubMenuItem })),
	//we need to get the main menu item directly from the anchor, otherwise we have to wait for the menu to load in page.js
	fetchSelectedMainMenuItem: () => {
		let selectedMainMenuItem = getAnchor('main') || 'dashboard';
		set(() => ({selectedMainMenuItem: selectedMainMenuItem}));
		if (getAnchor('main')!==selectedMainMenuItem) {
			//we need to update the hash, because the menu is not loaded yet, and the anchor is not set.
			window.location.hash = '#' + selectedMainMenuItem;
		}
	},
	getMenuLinkById: (id) => {
		let wizardMenu = getSubMenu(get().menu, 'wizard');
		let menuItems = wizardMenu.menu_items;
		for (let i = 0; i < menuItems.length; i++) {
			const menuItem = menuItems[i];

			if (menuItem.id === id) {
				return '#wizard/' + menuItem.id;
			}

			if (menuItem.menu_items) {
				for (let j = 0; j < menuItem.menu_items.length; j++) {
					const subMenuItem = menuItem.menu_items[j];
					if (subMenuItem.id === id) {
						return '#wizard/' + subMenuItem.id;
					}
				}
			}
		}

		return '#general';
	},
	saveButtonsRequired: () => {
		let selectedSubMenuItem = get().selectedSubMenuItem;
		let subMenu = get().subMenu.menu_items;
		let menuItem = subMenu.filter((item) => {return (item.id===selectedSubMenuItem)});

		if (menuItem.length===0) {
			//check also if menuItem.menu_items contains the current selectedSubMenuItem
			subMenu = getMenuItemByName(selectedSubMenuItem, subMenu);
			return subMenu.save_buttons_required !== false;
		}
		menuItem = menuItem[0];

		return menuItem.save_buttons_required !== false;
	},
	fetchSelectedSubMenuItem: async () => {
		let selectedSubMenuItem = getAnchor('menu') || 'general';
		set((state) => ({selectedSubMenuItem: selectedSubMenuItem}));
	},
	fetchMenuData: (fields) => {
		let menu = cmplz_settings.menu;
		const selectedMainMenuItem = getAnchor('main') || 'dashboard';
		if (typeof fields !== 'undefined' ) {
			let subMenu = getSubMenu(menu, selectedMainMenuItem);
			const selectedSubMenuItem = getSelectedSubMenuItem(subMenu, fields);
			subMenu.menu_items = dropEmptyMenuItems(subMenu.menu_items, fields, selectedSubMenuItem);
			const { nextMenuItem, previousMenuItem }  = getPreviousAndNextMenuItems(subMenu, selectedSubMenuItem);
			const previousLink = previousMenuItem ? `#${selectedMainMenuItem}/${previousMenuItem}` : '#';
			const nextLink = `#${selectedMainMenuItem}/${nextMenuItem}`;
			const hasPremiumItems =  subMenu.menu_items.filter((item) => {return (item.premium===true)}).length>0;
			set((state) => ({subMenuLoaded:true, menu: menu, nextMenuItem:nextLink, previousMenuItem:previousLink, selectedMainMenuItem: selectedMainMenuItem, selectedSubMenuItem:selectedSubMenuItem, subMenu: subMenu, hasPremiumItems: hasPremiumItems}));
		} else {
			set((state) => ({menu: menu, selectedMainMenuItem: selectedMainMenuItem}));
		}
	},
	getMenuRegions: () => {
		let menuItems = get().subMenu.menu_items;
		let selectedSubMenuItem = get().selectedSubMenuItem;
		let menu = getMenuItemByName(selectedSubMenuItem, menuItems);
		let regions = [];
		if (menu.hasOwnProperty('region')) {
			regions = menu.region;
		}
		//
		return regions;
	}
}));
export default useMenu;


// Parses menu items and nested items in single array
const menuItemParser = (parsedMenuItems, menuItems) => {
	menuItems.forEach((menuItem) => {
		if( menuItem.visible ) {
			parsedMenuItems.push(menuItem.id);
			if( menuItem.hasOwnProperty('menu_items') ) {
				menuItemParser(parsedMenuItems, menuItem.menu_items);
			}
		}
	});
	return parsedMenuItems;
}

const getPreviousAndNextMenuItems = (subMenu, selectedSubMenuItem) => {
	let previousMenuItem;
	let nextMenuItem;
	const parsedMenuItems = [];
	menuItemParser(parsedMenuItems, [subMenu]);
	// Finds current menu item index
	const currentMenuItemIndex = parsedMenuItems.findIndex((menuItem) => menuItem === selectedSubMenuItem);
	if( currentMenuItemIndex !== -1 ) {
		previousMenuItem = parsedMenuItems[ currentMenuItemIndex === 0 ? '' : currentMenuItemIndex - 1];
		//if the previous menu item has a submenu, we should move one more back, because it will select the current sub otherwise.
		const previousMenuItemObj = getMenuItemByName(previousMenuItem, [subMenu])
		let previousMenuHasSubMenu = previousMenuItemObj.hasOwnProperty('menu_items');
		if (previousMenuHasSubMenu) {
			const previousMenuItemIndex = currentMenuItemIndex === 0 ? 0 : currentMenuItemIndex - 2;
			previousMenuItem = parsedMenuItems.hasOwnProperty(previousMenuItemIndex) ? parsedMenuItems[previousMenuItemIndex] : false;
			//if this selected previous item also has a submenu, we're at the end of our options, there's nothing to go back to, we should
			//disable the previous option.
			if (getMenuItemByName(previousMenuItem, [subMenu]).hasOwnProperty('menu_items')){
				previousMenuItem = false;
			}
		}
		nextMenuItem = parsedMenuItems[ currentMenuItemIndex === parsedMenuItems.length - 1 ? '' : currentMenuItemIndex + 1];
		previousMenuItem = previousMenuItem ? previousMenuItem : '';
		nextMenuItem = nextMenuItem ? nextMenuItem : parsedMenuItems[parsedMenuItems.length - 1]
	}
	return { nextMenuItem, previousMenuItem };
}

const dropEmptyMenuItems = (menuItems, fields, selectedSubMenuItem) => {
	const newMenuItems = menuItems;
	for (const [index, menuItem] of menuItems.entries()) {
		const menuItemFields = fields.filter((field) => {
			return (field.menu_id === menuItem.id && field.visible && !field.conditionallyDisabled )
		});
		if( menuItemFields.length === 0 && !menuItem.hasOwnProperty('menu_items') )  {
			newMenuItems[index].visible = false;
		} else {
			newMenuItems[index].visible = true;
			if( menuItem.hasOwnProperty('menu_items') ) {
				newMenuItems[index].menu_items = dropEmptyMenuItems(menuItem.menu_items, fields, selectedSubMenuItem);
			}
		}
	}
	return newMenuItems;
}

/*
* filter sidebar menu from complete menu structure
*/
const getSubMenu = (menu, selectedMainMenuItem) => {
	let subMenu = [];
	for (const key in menu) {
		if ( menu.hasOwnProperty(key) && menu[key].id === selectedMainMenuItem ){
			subMenu = menu[key];
		}
	}
	subMenu = addVisibleToMenuItems(subMenu);
	return subMenu;
}

/**
* Get the current selected menu item based on the hash, selecting subitems if the main one is empty.
*/
const getSelectedSubMenuItem = (subMenu, fields) => {
	let fallBackMenuItem = subMenu && subMenu.menu_items.hasOwnProperty(0) ? subMenu.menu_items[0].id : 'general';
	let foundAnchorInMenu;

	//get flat array of menu items
	let parsedMenuItems = menuItemParser([], subMenu.menu_items);
	let anchor = getAnchor('menu');
	//check if this anchor actually exists in our current submenu. If not, clear it
	foundAnchorInMenu = parsedMenuItems.filter(menu_item => menu_item === anchor);
	if ( !foundAnchorInMenu ) {
		anchor = false;
	}
	let selectedMenuItem =  anchor ? anchor : fallBackMenuItem;
	//check if menu item has fields. If not, try a subitem
	let fieldsInMenu = fields.filter(field => field.menu_id === selectedMenuItem);
	if ( fieldsInMenu.length===0 ) {
		//look up the current menu item
		let menuItem = getMenuItemByName(selectedMenuItem, subMenu.menu_items);
		if (menuItem && menuItem.menu_items && menuItem.menu_items.hasOwnProperty(0)) {
			selectedMenuItem = menuItem.menu_items[0].id;
		}
	}
	return selectedMenuItem;
}

//Get a menu item by name from the menu array
const getMenuItemByName = (name, menuItems) => {
	for (const key in menuItems ){
		let menuItem = menuItems[key];
		if ( menuItem.id === name ) {
			return menuItem;
		}
		if ( menuItem.menu_items ) {
			let found = getMenuItemByName(name, menuItem.menu_items);
			if (found) {
				return found;
			}
		}
	}
	return false;
}



const addVisibleToMenuItems = (menu) => {
	if (!menu.hasOwnProperty('menu_items')) return menu;
	let newMenuItems = menu.menu_items;
	for (let [index, menuItem] of newMenuItems.entries()) {
		menuItem.visible = true;
		if( menuItem.hasOwnProperty('menu_items') ) {
			menuItem = addVisibleToMenuItems(menuItem);
		}
		newMenuItems[index] = menuItem;
	}
	menu.menu_items = newMenuItems;
	menu.visible = true;
	return menu;
}
