<?php
/*
 *
 *	浏览器->处理CSS
 *
 *	2011-1-14 @ jiuwap.cn
 *
 */



!defined('m') && header('location: /?r='.rand(0,999));

$html = $http->get_body();
if ( !$html ){
	exit;
}

$code = 'utf-8';
$mime = 'text/vnd.wap.wml';

$html = str_ireplace('@','&at;at;',$html);

$html = str_replace(array("\n","\n"),'',$html);
$html = preg_replace('@<!--(.*?)-->@','', $html);
$html = str_replace("\t",' ', $html);

$html = fix_css($html,false);

$html = str_ireplace('&at;at;','@',$html);

echo $html;