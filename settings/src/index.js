import {
    render,createRoot
} from '@wordpress/element';
import Page from './Page';

/**
 * Initialize
 */

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'complianz' );
	if ( container ) {
		if ( createRoot ) {
			createRoot( container ).render( <Page/> );
		} else {
			render( <Page/>, container );
		}
	}
});

