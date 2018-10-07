<?php
defined('ABSPATH') or die("you do not have acces to this page!");

add_filter('cmplz_document_elements', 'cmplz_dynamic_document_elements', 10, 2);
function cmplz_dynamic_document_elements($elements, $fields)
{
    return $elements;
}