<?php 

require('PhpSimple/simplehtmldom_1_5/simple_html_dom.php');
 
// Create DOM from URL or file
$html = file_get_html('https://www.youtube.com/feed/trending');

printme($html);

function printme($x)
{
  echo '<pre>'.print_r($x,true).'</pre';
}