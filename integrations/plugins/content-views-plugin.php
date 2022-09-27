<?php
function cmplz_content_views_cookieblocker($html){
	return COMPLIANZ::$cookie_blocker->replace_tags($html);
}
add_filter( 'pt_cv_view_html', 'cmplz_content_views_cookieblocker' );

