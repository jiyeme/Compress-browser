<?php
/*
 *
 *	浏览器->退出
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */
require 'inc/common.php';
$browser->user_login_check();

if ( isset($_GET['yes']) ){
	$browser->user_logout();
	load_template('logout_success',false);
}else{
	load_template('logout_check',false);
}