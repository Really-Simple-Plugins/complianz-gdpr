<?php
if (!defined('ABSPATH')) {
    die('-1');
}
// add custom post type
function cmplz_create_post_type()
{
    register_post_type(
        'cmplz-processing', //post name to use in code
        array(
            'labels' => array(
                'name' =>  _x( 'Processing agreements', 'Name of post type', 'complianz'),
                'singular_name' => _x( 'Processing agreement', 'Singular name of post type', 'complianz'),
                'add_new' => __('Add new', 'complianz'),
                'add_new_item' => __('Add new', 'complianz'),
                'parent_item_colon' => __('Processing agreement', 'complianz'),
                'parent' => __('Processing agreement parent item', 'complianz'),
            ),

            //'menu_icon' => 'dashicons-hammer',
            'menu_icon' => cmplz_url."core/assets/images/processing.png", //https://developer.wordpress.org/resource/dashicons/#editor-code
            'menu_position' => CMPLZ_PROCESSING_MENU_POSITION,
            'rewrite' => array(
                'slug' => 'processing-agreement',
                'pages' => true
            ),
            'exclude_from_search' => true,
            'supports' => array(
                'title',
                'author',
                //'editor',
                //'thumbnail',
                'revisions',
                'page-attributes'
            ),
            'publicly_queryable' => false,
            'query_var' => false,
            'public' => true,
            'has_archive' => false,
            'taxonomies' => array(),
            'hierarchical' => true,
            'map_meta_cap' => true, //enable capability handling
            'capabilities' => array(
                'create_posts' => 'do_not_allow',
                'delete_post' => true,
            )
        )
    );

    register_post_type(
        'cmplz-dataleak', //post name to use in code
        array(
            'labels' => array(
                'name' => __('Dataleaks', 'complianz'),
                'singular_name' => __('Dataleak', 'complianz'),
                'add_new' => __('Add new', 'complianz'),
                'add_new_item' => __('Add new', 'complianz'),
                'parent_item_colon' => __('Dataleak', 'complianz'),
                'parent' => __('Dataleak parent item', 'complianz'),
            ),

            //'menu_icon' => 'dashicons-hammer',
            'menu_icon' => cmplz_url."core/assets/images/dataleak.png",
            'menu_position' => CMPLZ_DATALEAK_MENU_POSITION,
            'rewrite' => array(
                'slug' => 'processing-agreement',
                'pages' => true
            ),
            'exclude_from_search' => true,
            'supports' => array(
                'title',
                'author',
                //'editor',
                //'thumbnail',
                //'revisions',
                'page-attributes'
            ),
            'publicly_queryable' => false,
            'query_var' => false,
            'public' => true,
            'has_archive' => false,
            'taxonomies' => array(),
            'hierarchical' => true,
            'map_meta_cap' => true, //enable capability handling
            'capabilities' => array(
                'create_posts' => 'do_not_allow',
                'delete_post' => true,
            )
        )
    );
}
add_action('init', 'cmplz_create_post_type', 99,1);

add_filter('manage_posts_columns', 'cmplz_add_pdf_column');
// add a thumbnail column to the edit posts screen
function cmplz_add_pdf_column($cols)
{
    if ($_GET['post_type']!=='cmplz-dataleak' && $_GET['post_type']!=='cmplz-processing') return $cols;
    $cols['PDF'] = __('Download', 'complianz');
    return $cols;
}

// go get the attached images for   the logo and thumbnail columns
add_action('manage_cmplz-processing_posts_custom_column', 'cmplz_add_pdf_icon', 10, 2);
add_action('manage_cmplz-dataleak_posts_custom_column', 'cmplz_add_pdf_icon', 10, 2);
function cmplz_add_pdf_icon($column_name, $post_id)
{
    if (('PDF' == $column_name)) {
        if (get_post_type($post_id)==='cmplz-dataleak' && !COMPLIANZ()->dataleak->dataleak_has_to_be_reported_to_involved($post_id)) return;

        echo '<a target="_blank" href="' . cmplz_url . 'pdf.php?nonce=' . wp_create_nonce("cmplz_pdf_nonce") . '&post_id=' . $post_id . '&token=' . time() . '"><img src="' . cmplz_url . 'assets/images/pdf.png" width=20px height=20px></a>';
    }

}


add_filter('manage_posts_columns', 'cmplz_add_mail_sent_column');
// add a thumbnail column to the edit posts screen
function cmplz_add_mail_sent_column($cols)
{
    if ($_GET['post_type']!=='cmplz-dataleak') return $cols;
    $cols['mail_sending_complete'] = __('Email', 'complianz');
    return $cols;
}

// go get the attached images for   the logo and thumbnail columns
add_action('manage_cmplz-dataleak_posts_custom_column', 'cmplz_add_mail_sent_icon', 10, 2);
function cmplz_add_mail_sent_icon($column_name, $post_id)
{
    if (('mail_sending_complete' == $column_name)) {
        if (COMPLIANZ()->dataleak->get_email_batch_progress($post_id)>=100) {
            echo '<i class="fa fa-check"></i>';
        } elseif (COMPLIANZ()->dataleak->get_email_batch_progress($post_id)==0) {
            echo '';
        } else {
            echo '<i class="fa fa-envelope"></i>';
        }
    }

}

//add_action('admin_init', 'cmplz_redirect_edit_page');
function cmplz_redirect_edit_page()
{
    if (isset($_GET['action']) && ($_GET['action'] == 'edit') && isset($_GET['post'])) {
        $post_id = intval($_GET['post']);
        $post_type = get_post_type($post_id);
        if ($post_type == 'cmplz-processing' || $post_type == 'cmplz-dataleak' ) {
            wp_redirect(admin_url('admin.php?page='.$post_type.'&post_id='.$post_id.'&step=2'));
            exit;
        }
    }

}
/*
 * The post-new.php page should not be visible, but if a user happens to stumble on it for cmplz pages, redirect.
 *
 *
 * */

add_action( 'admin_init', 'cmplz_redirect_post_new' );
function cmplz_redirect_post_new() {
    global $pagenow;

    # Check current admin page.
    if( $pagenow == 'post-new.php' && isset( $_GET['post_type'] ) && ( $_GET['post_type'] == 'cmplz-processing' || $_GET['post_type'] == 'cmplz-dataleak' )  ){
        wp_redirect(admin_url('edit.php?post_type='.$_GET['post_type'].'&page='.$_GET['post_type']));
        exit;
    }
}


add_action('add_meta_boxes', 'add_custom_meta_box');
function add_custom_meta_box($post_type) {
    global $post;

    if ($post_type != 'cmplz-dataleak' && $post_type != 'cmplz-processing') return;

    add_meta_box('cmplz_document_meta_box', __('Document contents','complianz'), 'cmplz_show_document', null, 'normal','high');

    //if it doesn't have to be reported, don't show the email option
    if ($post_type === 'cmplz-dataleak' && $post && COMPLIANZ()->dataleak->dataleak_has_to_be_reported_to_involved($post->ID))
        add_meta_box('cmplz_email_meta_box', __('Email','complianz'), 'cmplz_mail_option', null, 'side','high');

    if (($post_type !== 'cmplz-dataleak') || ( $post_type === 'cmplz-dataleak' && $post && COMPLIANZ()->dataleak->dataleak_has_to_be_reported_to_involved($post->ID)))
        add_meta_box('cmplz_download_meta_box', __('Download','complianz'), 'cmplz_download_option', null, 'side','high');

}

function cmplz_mail_option(){
    COMPLIANZ()->dataleak->send_mail_button();
}

function cmplz_download_option(){
    global $post;
    $permalink = cmplz_url . 'pdf.php?nonce=' . wp_create_nonce("cmplz_pdf_nonce") . '&post_id=' . $post->ID . '&token=' . time();

    ?>
    <a target="_blank" href="<?php echo $permalink?>" class="button"><?php _e('Download PDF','complianz')?></a>
    <?php
}

function cmplz_show_document(){

    global $post;
    $edit_link = admin_url('admin.php?page='.get_post_type($post).'&post_id='.$post->ID.'&step=2');
    ?>
    <div><a href="<?php echo $edit_link?>" class="button"><?php _e('Edit document','complianz')?></a></div>
    <br><br>
    <?php
    $type = str_replace('cmplz-', '', get_post_type($post));
    if ($type === 'dataleak'){
        echo '<i>';
        COMPLIANZ()->dataleak->dataleak_conclusion($post->ID);
        echo '</i>';
    }

    if ($type !== 'dataleak' || ($type === 'dataleak' && COMPLIANZ()->dataleak->dataleak_has_to_be_reported_to_involved($post->ID))){
        echo COMPLIANZ()->document->get_document_html($type, $post->ID);
    }

}