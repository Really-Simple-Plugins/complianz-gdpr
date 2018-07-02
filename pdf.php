<?php
# No need for the template engine
define('WP_USE_THEMES', false);

#find the base path
define('BASE_PATH', find_wordpress_base_path() . "/");

# Load WordPress Core
error_log(BASE_PATH . 'wp-load.php');
require_once(BASE_PATH . 'wp-load.php');
require_once(BASE_PATH . 'wp-includes/class-phpass.php');
require_once(BASE_PATH . 'wp-admin/includes/image.php');

if (isset($_GET['nonce'])) {
    $nonce = $_GET['nonce'];
    if (!wp_verify_nonce($nonce, 'cmplz_pdf_nonce')) {
        die("invalid command");
    }
} else {
    die("invalid command");
}

if (!is_user_logged_in()) {
    die("invalid command");
}

if (!isset($_GET['post_id'])) {
    die('invalid command');
}

$plugin_url = plugin_dir_url(__FILE__);
$uploads = wp_upload_dir();
$upload_dir = $uploads['basedir'];
$upload_url = trailingslashit($uploads['baseurl']);
$post_id = intval($_GET['post_id']);

$post_type = get_post_type($post_id);
$type = str_replace('cmplz-', '', $post_type);
$pages = COMPLIANZ()->config->pages;
$title = $pages[$type]['title'];
$document_html = COMPLIANZ()->document->get_document_html($type, $post_id);

$html = '
<style>
body {
  font-family: sans;
  margin-top:100px;
}
h2 {
    font-size:12pt;
}

h3 {
    font-size:12pt;
}

h4 {
	font-size:10pt;
	font-weight: bold;
}
.center {
  text-align:center;
}



</style>

<body >
<h4 class="center">' . $title . '</h4>
' . $document_html . '
</body>';

//==============================================================
//==============================================================
//==============================================================

require cmplz_path . 'assets/vendor/autoload.php';

$margin_top = 100;
$margin_bottom = 20;
$mode = '';
$format = '';
$font_size = '12';
$font = 'Arial';
$margin_left = '20';
$margin_right = '20';
$margin_header = '60';
$margin_footer = '20';

//generate a token when it's not there, otherwise use the existing one.
if (get_option('cmplz_pdf_dir_token')) {
    $token = get_option('cmplz_pdf_dir_token');
} else {
    $token = time();
    update_option('cmplz_pdf_dir_token', $token);
}

if (!file_exists($upload_dir . '/complianz/')){
    mkdir($upload_dir . '/complianz/');
}
if (!file_exists($upload_dir . '/complianz/tmp/')){
    mkdir($upload_dir . '/complianz/tmp/');
}
$temp_dir = $upload_dir . '/complianz/tmp/' . $token;
if (!file_exists($temp_dir)){
    mkdir($temp_dir);
}
$mpdf = new Mpdf\Mpdf(array(
    'setAutoTopMargin' => 'stretch',
    'autoMarginPadding' => 5,
    'tempDir' => $temp_dir,
));

$mpdf->SetDisplayMode('fullpage');

if ($type === 'processing') {
    $mpdf->SetTitle(_x( 'Processing agreement', 'Title of PDF', 'complianz' ));
} else {
    $mpdf->SetTitle(_x( 'Dataleak report', 'Title of PDF', 'complianz' ));
}
$img = '';//'<img class="center" src="" width="150px">';
$mpdf->SetHTMLHeader($img);
if ($type === 'processing') {
    $footer_text = sprintf(__("%s Processing agreement %s %s %s", 'complianz'), get_bloginfo('name'), date("j"), __(date("F")), date("Y"));
} else {
    $footer_text = sprintf(__("%s Dataleak report %s %s %s", 'complianz'), get_bloginfo('name'), date("j"), __(date("F")), date("Y"));
}
$mpdf->SetFooter($footer_text);
$mpdf->WriteHTML($html);
// Save the pages to a file

$file_title = get_bloginfo('name') . "-export-" . date("j") . " " . __(date("F")) . " " . date("Y");
$mpdf->Output($file_title . ".pdf", 'I');


exit;

//==============================================================
//==============================================================
//==============================================================

function find_wordpress_base_path()
{
    $dir = dirname(__FILE__);
    do {
        //it is possible to check for other files here
        if (file_exists($dir . "/wp-config.php")) {
            return $dir;
        }
    } while ($dir = realpath("$dir/.."));
    return null;
}