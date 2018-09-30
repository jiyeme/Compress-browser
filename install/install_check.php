<?php
!defined('m') && header('location: /?r='.rand(0,999));
top('第一步,检查环境 - 安装玖玩浏览器');

echo '<b>安装玖玩浏览器<br/>第一步,检查环境</b>';

echo hr;

$next = true;
echo 'php5+：';
if ( phpversion() >= '5.0.0' ) {
	echo '通过';
}else{
	echo '<b>失败</b>';
	$next = false;
}
echo '<br/>mysql：';
if (function_exists('mysql_connect')) {
	echo '通过';
}else{
	echo '<b>失败</b>';
	$next = false;
}
echo '<br/>fsockopen：';
if (function_exists('fsockopen')) {
	echo '通过';
}else{
	echo '<b>失败</b>';
	$next = false;
}
echo '<br/>gd：';
if (function_exists('gd_info')) {
	echo '通过';
}else{
	echo '<b>失败</b>';
	$next = false;
}
echo '<br/>iconv：';
if (function_exists('iconv')) {
	echo '通过';
}else{
	echo '<b>失败</b>';
	$next = false;
}

echo '<br/>mb：';
if (function_exists('mb_detect_encoding') && function_exists('mb_convert_encoding')) {
	echo '通过';
}else{
	echo '<b>失败</b>';
	$next = false;
}

/*
echo '<br/>file：';
if ( checkfile() ) {
	echo '通过';
}else{
	echo '<b>失败</b>';
	$next = false;
}*/

echo hr;

if ( $next ){
	echo '检测通过,可以进入下一步安装。<br/><a href="index.php?do=form">进入下一步</a>';
}else{
	echo '检测不通过，请联系空间商开启相应功能后再安装玖玩浏览器，这些功能必须被支持才可以使用玖玩浏览器。';
}
