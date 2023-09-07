<?php

if (!function_exists('cmplz_get_value')) {
	//for backwards compatibility with Terms & Conditions
	function cmplz_get_value($id) {
		return cmplz_get_option($id);
	}
}
