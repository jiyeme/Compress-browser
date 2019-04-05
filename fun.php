<?php
/*
 *
 *	浏览器->菜单
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */
!defined('m') && header('location: /');

$h = isset($_GET['h']) ? $_GET['h'] : '';

if ( $h != ''){
	$au = '&amp;h='.$h;
}else{
	$au = '';
}

$url = $browser->history_get($h);
if ( $url !== false ){
	$url = $url['url'];
}else{
	$url = 'http://';
}

load_template('menu',false);