<?php
/*
 *
 *	浏览器->模板切换
 *
 *	2012/7/26 星期四 @ jiuwap.cn
 *
 */


require 'inc/common.php';
$back = isset($_GET['back']) ? $_GET['back'] : '';
$h = isset($_GET['h']) ? $_GET['h'] : '';
if ( empty($back) ){
	$url = 'index.php';
}elseif( $back == 'login'){
	$url = 'login.php';
}elseif( $back == 'set'){
	$url = 'set.php?h='.$h;
}else{
	$url = 'index.php';
}
$h = isset($_GET['h']) ? $_GET['h'] : '';
if ( $h <> ''){
	$au = '&amp;h='.$h;
}else{
	$au = '';
}

if ( isset($_GET['save']) && isset($_GET['wap']) || $_GET['wap'] == $browser->template ){
	if ( $_GET['wap'] == 0 ){
		$browser->template_set('0');
	}else if( $_GET['wap'] == '1'){
		$browser->template_set('1');
	}else{
	    $browser->template_set('2');
	}
	header('location: '.$url);
	exit;
}

$browser->template_top('设置界面');
echo '您当前正在使用' . ($browser->template?'WAP1.1':'WAP2.0') . '浏览。<br/>';
echo '您确定要将页面设置为' . ($_GET['wap']?'WAP1.1':'WAP2.0') . '吗？<br/>';
echo '<a href="wap.php?back='.$back.'&amp;save=true&amp;wap='.$_GET['wap'].$h.'">确定</a>.<a href="'.$url.'">返回</a>';
echo hr;
echo '提示：绝大多数电脑浏览器不支持WAP1.1。';
$browser->template_foot();