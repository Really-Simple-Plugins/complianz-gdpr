<?php

/**
 * Get icon from predefined list
 * https://material.io/resources/icons/?search=sync&icon=sync_disabled&style=outline
 * @param string $icon_name
 * @param string $status
 * @param string $tooltip (optional)
 * @param int $size (optional)
 *
 * @return string
 */
function cmplz_icon( $icon_name, $status, $tooltip = '', $size = 14) {
	$vb = $size + 8;
	$icons = array(
		'sync' => array(
			'success' => array(
				'type' => 'svg',
				'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" height="' . $size . '" viewBox="0 0 ' . $vb . ' ' . $vb . '" width="' . $size . '"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>',
				'tooltip' =>__( 'Document is kept up to date by Complianz', 'complianz-gdpr' ),
			),
			'error' => array(
				'type' => 'svg',
				'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" height="' . $size . '" viewBox="0 0 ' . $vb . ' ' . $vb . '" width="' . $size . '"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>',
				'tooltip' =>__( 'Document is kept up to date by Complianz', 'complianz-gdpr' ),
			),
			'disabled' => array(
				'type' => 'svg',
				'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" height="' . $size . '" viewBox="0 0 ' . $vb . ' ' . $vb . '" width="' . $size . '"><path d="M10 6.35V4.26c-.8.21-1.55.54-2.23.96l1.46 1.46c.25-.12.5-.24.77-.33zm-7.14-.94l2.36 2.36C4.45 8.99 4 10.44 4 12c0 2.21.91 4.2 2.36 5.64L4 20h6v-6l-2.24 2.24C6.68 15.15 6 13.66 6 12c0-1 .25-1.94.68-2.77l8.08 8.08c-.25.13-.5.25-.77.34v2.09c.8-.21 1.55-.54 2.23-.96l2.36 2.36 1.27-1.27L4.14 4.14 2.86 5.41zM20 4h-6v6l2.24-2.24C17.32 8.85 18 10.34 18 12c0 1-.25 1.94-.68 2.77l1.46 1.46C19.55 15.01 20 13.56 20 12c0-2.21-.91-4.2-2.36-5.64L20 4z"/></svg>',
				'tooltip' => __( 'Document is not kept up to date by Complianz', 'complianz-gdpr' )
			)
		),
//		'eu' => array(
//			'success' => array(
//				'type' => 'svg',
//				'icon'    => '<svg height="' . $size . '" id="Layer_1" version="1.1" viewBox="0 0 ' . $vb . ' ' . $vb . '" width="' . $size . '" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><g><defs><circle cx="500" cy="500" id="SVGID_1_" r="500"/></defs><clipPath id="SVGID_2_"><use overflow="visible" xlink:href="#SVGID_1_"/></clipPath><rect clip-path="url(#SVGID_2_)" fill="#ED2224" height="' . $size . '" width="' . $size . '" x="-168"/><rect clip-path="url(#SVGID_2_)" fill="#FFFFFF" height="' . $size . '" width="' . $size . '" x="166"/><path clip-path="url(#SVGID_2_)" d="M499.987,229.625l-45.573,85c-5.174,9.245-14.437,8.383-23.699,3.223     l-32.996-17.083l24.587,130.565c5.172,23.85-11.416,23.85-19.611,13.537L345.114,380.4l-9.349,32.743     c-1.075,4.299-5.818,8.812-12.929,7.735l-72.815-15.311l19.128,69.534c4.096,15.473,7.286,21.879-4.137,25.957l-25.95,12.199     l125.349,101.811c4.956,3.856,7.461,10.784,5.702,17.053l-10.974,36.003c43.16-4.978,81.831-12.46,125.009-17.071     c3.81-0.405,10.196,5.883,10.171,10.3l-5.722,131.897h20.987l-3.309-131.61c-0.026-4.417,5.768-10.992,9.577-10.587     c43.179,4.611,81.851,12.094,125.009,17.071l-10.974-36.003c-1.762-6.269,0.744-13.196,5.703-17.053l125.347-101.811     l-25.95-12.199c-11.422-4.077-8.232-10.483-4.136-25.957l19.126-69.534l-72.814,15.311c-7.111,1.077-11.854-3.437-12.93-7.735     l-9.347-32.743l-57.584,64.466c-8.192,10.313-24.781,10.313-19.608-13.537l24.587-130.565l-32.996,17.083     c-9.264,5.161-18.526,6.022-23.699-3.223" fill="#ED2224"/></g></g></svg>',
//				'tooltip' =>__( 'Document is kept up to date by Complianz', 'complianz-gdpr' ),
//			),
//		),
		'shortcode' => array(
			'success' => array(
				'type' => 'dashicons',
				'icon'    => 'dashicons-admin-page',
			),
			'disabled' => array(
				'type' => 'dashicons',
				'icon'    => 'dashicons-admin-page',
			)
		),

		'bullet' => array(
			'success' => array(
				'type' => 'css',
				'icon' => 'bullet',
			),
			'completed' => array(
				'type' => 'css',
				'icon' => 'bullet',
			),
			'disabled' => array(
				'type' => 'css',
				'icon' => 'bullet',
			),
			'warning' => array(
				'type' => 'css',
				'icon' => 'bullet',
			)
		),
        'check' => array(
            'success' => array(
                'type' => 'svg',
                'icon' => '<svg width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"/></svg>',
            ),
            'green' => array(
                'type' => 'svg',
                'icon' => '<svg width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"/></svg>',
            ),
            'prefilled' => array(
                'type' => 'svg',
                'icon' => '<svg width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"/></svg>',
            ),
            'error' => array(
                'type' => 'svg',
                'icon' => '<svg width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1490 1322q0 40-28 68l-136 136q-28 28-68 28t-68-28l-294-294-294 294q-28 28-68 28t-68-28l-136-136q-28-28-28-68t28-68l294-294-294-294q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 294 294-294q28-28 68-28t68 28l136 136q28 28 28 68t-28 68l-294 294 294 294q28 28 28 68z"/></svg>',
            ),
            'failed' => array(
                'type' => 'svg',
                'icon' => '<svg width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1490 1322q0 40-28 68l-136 136q-28 28-68 28t-68-28l-294-294-294 294q-28 28-68 28t-68-28l-136-136q-28-28-28-68t28-68l294-294-294-294q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 294 294-294q28-28 68-28t68 28l136 136q28 28 28 68t-28 68l-294 294 294 294q28 28 28 68z"/></svg>',
            ),
            'empty' => array(
	            'type' => 'svg',
	            'icon' => '<svg width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"/></svg>',
            ),
        ),
        'arrow-right' => array(
            'success' => array(
                'type' => 'dashicons',
                'icon'    => 'dashicons-arrow-right-alt2',
            ),
        ),
		'arrow-left' => array(
			'success' => array(
				'type' => 'dashicons',
				'icon'    => 'dashicons-arrow-left-alt2',
			),
		),
//        'sync' => array(
//            'success' => array(
//                'type' => 'dashicons',
//                'icon' => 'dashicons-update',
//            ),
//            'error' => array(
//                'type' => 'dashicons',
//                'icon' => 'dashicons-update',
//            ),
//            'disabled' => array(
//                'type' => 'dashicons',
//                'icon' => 'dashicons-update',
//            ),
       // ),
        'file' => array(
            'success' => array(
                'type' => 'dashicons',
                'icon' => 'dashicons-media-default',
            ),
            'disabled' => array(
                'type' => 'dashicons',
                'icon' => 'dashicons-media-default',
            ),
        ),
        'calendar' => array(
            'success' => array(
                'type' => 'svg',
                'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="calendar-check" class="svg-inline--fa fa-calendar-check fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M436 160H12c-6.627 0-12-5.373-12-12v-36c0-26.51 21.49-48 48-48h48V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h128V12c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v52h48c26.51 0 48 21.49 48 48v36c0 6.627-5.373 12-12 12zM12 192h424c6.627 0 12 5.373 12 12v260c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V204c0-6.627 5.373-12 12-12zm333.296 95.947l-28.169-28.398c-4.667-4.705-12.265-4.736-16.97-.068L194.12 364.665l-45.98-46.352c-4.667-4.705-12.266-4.736-16.971-.068l-28.397 28.17c-4.705 4.667-4.736 12.265-.068 16.97l82.601 83.269c4.667 4.705 12.265 4.736 16.97.068l142.953-141.805c4.705-4.667 4.736-12.265.068-16.97z"></path></svg>',
            ),
            'error' => array(
                'type' => 'svg',
                'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="calendar-times" class="svg-inline--fa fa-calendar-times fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M436 160H12c-6.6 0-12-5.4-12-12v-36c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48v36c0 6.6-5.4 12-12 12zM12 192h424c6.6 0 12 5.4 12 12v260c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V204c0-6.6 5.4-12 12-12zm257.3 160l48.1-48.1c4.7-4.7 4.7-12.3 0-17l-28.3-28.3c-4.7-4.7-12.3-4.7-17 0L224 306.7l-48.1-48.1c-4.7-4.7-12.3-4.7-17 0l-28.3 28.3c-4.7 4.7-4.7 12.3 0 17l48.1 48.1-48.1 48.1c-4.7 4.7-4.7 12.3 0 17l28.3 28.3c4.7 4.7 12.3 4.7 17 0l48.1-48.1 48.1 48.1c4.7 4.7 12.3 4.7 17 0l28.3-28.3c4.7-4.7 4.7-12.3 0-17L269.3 352z"></path></svg>',
            ),
        ),
        'help' => array(
            'normal' => array(
                'type' => 'dashicons',
                'icon' => 'dashicons-editor-help',
            ),
        )
	);

	if ( !empty($tooltip) ) {
        $tooltip =  'cmplz-tooltip="' . $tooltip . '" flow="right"';
    } else if ( isset($icons[$icon_name][$status]['tooltip']) ) {
        $tooltip =  'cmplz-tooltip="' . $icons[$icon_name][$status]['tooltip'] . '" flow="right"';
    }

	$icon = $icons[$icon_name][$status]['icon'];
	$type = $icons[$icon_name][$status]['type'];

	if ( $type === 'svg' ){
		$html = '<div class="cmplz-tooltip-icon dashicons-before cmplz-icon cmplz-' . esc_attr( $status ) . ' ' . esc_attr($icon_name) . '" >' . $icon . '</div>';
	} else if ( $type === 'dashicons' ) {
		$html = '<div class="cmplz-tooltip-icon dashicons-before cmplz-icon cmplz-' . esc_attr( $status ) . ' ' . esc_attr($icon_name) . ' ' . $icon . '" ></div>';
	} else {
		$html = '<div class="cmplz-icon cmplz-bullet cmplz-' . esc_attr( $status ) . ' ' . esc_attr($icon_name) . ' ' . $icon . '" ></div>';
	}

	return '<span '.$tooltip.'>'.$html.'</span>';
}

/**
 * Get icon for a region
 * @param string $region
 * @param int $size
 *
 * @return string
 */
function cmplz_region_icon($region, $size = 16){
//	return cmplz_icon($region,'success', '', $size );
	return '<img width="'.intval($size).'px" height="'.intval($size).'px" src="' . cmplz_url . 'assets/images/' . strtolower($region) .'.svg">';
}
