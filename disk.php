<?php
/*
 *
 *	浏览器->网盘入口点
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */

define('m','true');

require_once 'inc/common.php';

if ( !$b_set['switch']['disk'] ){
	error_show('网盘功能已经被关闭。');
}

if ( $b_set['server_method'] == 'ace' && isset($_SERVER['QUERY_STRING']) && isset($_SERVER['REQUEST_URI']) && $_SERVER['QUERY_STRING'] && strpos($_SERVER['REQUEST_URI'],'/disk.php?/')!==false ){
	$_SERVER['PATH_INFO'] = $_SERVER['QUERY_STRING'];
}
require 'tools/disk/inc.php';
if ( isset($_SERVER['PATH_INFO']) && substr($_SERVER['PATH_INFO'],0,3) == '/d/' ){
	$browser->user_login_check();
	$id = substr($_SERVER['PATH_INFO'],3);
	$id = substr($id,0,strpos($id,'/'));
	if ( $id ){
		$id = password2id($id,'4hr5h5da');
		if ( $id !== false){
			define('no_ob_gzip','true');
			require ROOT_DIR.'tools/down_file.php';
		}else{
			require ROOT_DIR.'tools/disk/_nofoundfile.php';
		}
	}else{
		require ROOT_DIR.'tools/disk/_nofoundfile.php';
	}
	unset($id);
}elseif ( isset($_SERVER['PATH_INFO']) && substr($_SERVER['PATH_INFO'],0,3) == '/z/' ){
	$id = substr($_SERVER['PATH_INFO'],3);
	$id = substr($id,0,strpos($id,'/'));
	if ( $id ){
		$id = password2id($id,'4gsfghs');
		if ( $id  !== false){
			define('no_ob_gzip','true');
			require ROOT_DIR.'tools/disk/down_file_zip.php';
		}else{
			require ROOT_DIR.'tools/disk/_nofoundfile.php';
		}
	}else{
		require ROOT_DIR.'tools/disk/_nofoundfile.php';
	}
	unset($id);
}elseif ( isset($_SERVER['PATH_INFO']) && substr($_SERVER['PATH_INFO'],0,1) == '/' ){
	$id = substr($_SERVER['PATH_INFO'],1);
	$id = substr($id,0,strpos($id,'/'));
	if ( $id ){
		$id = password2id($id);
		if ( $id !== false){
			define('no_ob_gzip','true');
			require ROOT_DIR.'tools/disk/_downfile.php';
		}else{
			require ROOT_DIR.'tools/disk/_nofoundfile.php';
		}
	}else{
		require ROOT_DIR.'tools/disk/_nofoundfile.php';
	}
	unset($id);
}else{
	$browser->user_login_check();
}


$u = isset($_GET['h']) ? $_GET['h'] : '';
if ( $u != ''){
	$h = '&amp;h='.$u;
}else{
	$h = '';
}

init_disk();

$fun = array('newzip','newdir','newtxt','info','upload');


if ( isset($_GET['cmd']) && in_array($_GET['cmd'],$fun) ){
	require ROOT_DIR.'tools/disk/'.$_GET['cmd'].'.php';
}else{
	require ROOT_DIR.'tools/disk/index.php';
}