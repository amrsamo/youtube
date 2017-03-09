<?php

 namespace Sunra\PhpSimple;

require 'simplehtmldom_1_5'.DIRECTORY_SEPARATOR.'simple_html_dom.php';

class HtmlDomParser {
	
	/**
	 * @return \simplehtmldom_1_5\simple_html_dom
	 */
	static public function file_get_html() {
		return call_user_func_array ( '\simplehtmldom_1_5\file_get_html' , func_get_args() );
	}

	/**
	 * get html dom from string
	 * @return \simplehtmldom_1_5\simple_html_dom
	 */
	static public function str_get_html() {
		return call_user_func_array ( '\simplehtmldom_1_5\str_get_html' , func_get_args() );
	}
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Sunra\PhpSimple\HtmlDomParser;

// $dom = HtmlDomParser::str_get_html( $str );
$file_name = 'https://www.youtube.com/en/results?search_query=brasil';
$dom = HtmlDomParser::file_get_html( $file_name );

// $elems = $dom->find($elem_name);

printme($dom);
var_dump($dom);
// printme($elems);

function printme($x)
{
  echo '<pre>'.print_r($x,true).'</pre';
}