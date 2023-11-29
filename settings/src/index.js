import {
    render,createRoot
} from '@wordpress/element';
import Page from './Page';

/**
 * Initialize
 */
function cmplzRenderSettings(container){
	console.log("render settings");
	if (createRoot) {
		createRoot(container).render(<Page/>);
	} else {
		render(<Page/>, container);
	}
}

document.addEventListener( 'DOMContentLoaded', () => {
	console.log("dom content loaded");
	const container = document.getElementById( 'complianz' );
	if ( container ) {
		console.log("default render container");
		cmplzRenderSettings(container);
	} else {
		console.log("no container found, retrying in 1000ms");
		//delay 1000 ms and try again
		setTimeout(() => {
			if (container) {
				cmplzRenderSettings(container);
			}
		},1000);
	}
});

