<?php
/*
 *
 *	浏览器->注册
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */

require 'inc/common.php';
if ( isset($_GET['yes']) ){
	$get_string = $browser->user_reg($_POST['name'],$_POST['pass'],$_POST['pass']);
	if ( $get_string === false ){
		load_template('reg_success',false,'/?r='.$browser->rand,false,'utf-8',0);
	}else{
		load_template('reg_fail',false);
	}
}else{
	load_template('reg_form');
}

