<?php defined( 'ABSPATH' ) or die( "you do not have access to this page!" );?>
<?php
	if (isset($_GET['region']) && array_key_exists( $_GET['region'], cmplz_get_regions(true) ) ) {
		$region = sanitize_title($_GET['region']);
	} else {
		$region = COMPLIANZ::$company->get_default_region();
	}
?>
<div class="cmplz-documents">
	<?php
	if ( isset( COMPLIANZ::$config->pages[ $region ] ) ) {

		foreach ( COMPLIANZ::$config->pages[ $region ] as $type => $page ) {
			if ( ! $page['public'] ) {
				continue;
			}

			//get region of this page , and maybe add it to the title
			$region_img = '<img width="25px" height="5px" src="' . cmplz_url . '/assets/images/s.png">';

			if ( isset( $page['condition']['regions'] ) ) {
				$region = $page['condition']['regions'];
				$region = is_array( $region ) ? reset( $region ) : $region;
			}

			$title     = $page['title'];
			if ( COMPLIANZ::$document->page_exists( $type, $region ) ) {
				$title     = '<a href="' . get_permalink( COMPLIANZ::$document->get_shortcode_page_id( $type, $region ) ) . '">' . $page['title'] . '</a>';
				if ( ! COMPLIANZ::$document->page_required( $page, $region ) ) {
					$args = array(
						'status' => 'error',
						'title' => $title,
						'page_exists' => cmplz_icon('circle-check', 'disabled'),
						'sync_icon' => cmplz_icon('sync', 'disabled'),
						'shortcode_icon' => cmplz_icon('shortcode', 'disabled'),
						'generated' => __( "Obsolete", 'complianz-gdpr' ),
					);
				} else {
					$shortcode = COMPLIANZ::$document->get_shortcode( $type, $region, $force_classic = true );
					$generated = $checked_date = date( cmplz_short_date_format(), get_option( 'cmplz_documents_update_date' ) );
					$sync_status = COMPLIANZ::$document->syncStatus( COMPLIANZ::$document->get_shortcode_page_id( $type, $region ) );
					$status = $sync_status === 'sync' ? "success" : "disabled";

					if ($status === 'success'){
						$sync_icon = cmplz_icon( 'sync', $status, __( 'Document is kept up to date by Complianz', 'complianz-gdpr' ) );
					} else {
						$sync_icon = cmplz_icon( 'sync', $status, __( 'Document is not kept up to date by Complianz', 'complianz-gdpr' ) );
					}

					if ( $sync_status === 'sync' ) {
						$shortcode_icon = cmplz_icon( 'shortcode', 'default' , __( 'Click to copy the document shortcode', 'complianz-gdpr' ), 15, $type, $shortcode);
					} else {
						$shortcode_icon = cmplz_icon( 'shortcode', $status , __( 'Click to copy the document shortcode', 'complianz-gdpr' ), 15);
					}

					$args = array(
						'status' => $status.' shortcode-container',
						'title' => $title,
						'page_exists' => cmplz_icon('circle-check', 'success', __( 'Validated', 'complianz-gdpr' )),
						'sync_icon' => $sync_icon,
						'shortcode_icon' => $shortcode_icon,
						'generated' => $generated,
					);
				}
				echo cmplz_get_template('dashboard/documents-row.php', $args);

			} elseif ( COMPLIANZ::$document->page_required( $page, $region ) ) {
				$args = array(
						'status' => 'missing',
						'title' => $title,
						'page_exists' => cmplz_icon('bullet', 'disabled'),
						'sync_icon' => cmplz_icon('sync', 'disabled'),
						'shortcode_icon' => cmplz_icon('shortcode', 'disabled'),
						'generated' => '<a href="'.add_query_arg( array('page'=>'cmplz-wizard', 'step'=>STEP_MENU),  admin_url('admin.php') ).'">'.__( "Create", 'complianz-gdpr' ).'</a>',
				);
				echo cmplz_get_template('dashboard/documents-row.php', $args);
			}
		}
	}
	do_action('cmplz_documents_overview', $region);

 	require_once( apply_filters('cmplz_free_templates_path', cmplz_path . 'templates/' ) .'dashboard/documents-conditional.php'); ?>
</div>
