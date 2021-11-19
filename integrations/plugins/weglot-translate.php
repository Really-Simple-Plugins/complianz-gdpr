<?php
defined( 'ABSPATH' ) or die();

add_filter( 'weglot_get_regex_checkers', 'cmplz_weglot_add_regex_checkers' );
function cmplz_weglot_add_regex_checkers( $regex_checkers ) {
	$regex_checkers[] = new \Weglot\Parser\Check\Regex\RegexChecker( '#var complianz = ({.*})#', 'JSON', 1, array( 'placeholdertext', 'title' ) );
	return $regex_checkers;
}
