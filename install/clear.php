<?php
if ( !defined('DIR') ){
	die('hello world');
}


@include DIR.'set_config/set_config.php';
if ( !isset($_REQUEST['psw']) || $_REQUEST['psw']<>$b_set['rootpassword'] ){
	top('清理缓存 - 玖玩浏览器');
	echo '<b>清理浏览器缓存</b>';
	echo hr;
	if (  isset($_REQUEST['psw']) && $_REQUEST['psw']<>$b_set['rootpassword'] ){
		echo '密码错误！<br/>';
	}else{
		echo '需要验证超级密码！<br/>';
	}
	if ( !$iswml ){
		echo '<form action="index.php?do=clear" method="post">';
	}
	echo '密码：<input type="text" value="" name="psw"/>';
	if ( !$iswml ){
		echo '<input type="submit" value="清理"/>';
		echo '</form>';
	}else{
		echo '<anchor>';
		echo '<go href="index.php?do=clear" method="post">';
		echo '<postfield name="psw" value="$psw" />';
		echo '</go>清理</anchor>';
	}
	echo '<a href="index.php">返回</a><br/>';
	foot();
	exit;
}
quick_connect('api/?cmd=clean&psw='.$_REQUEST['psw']);
top('清理浏览器缓存');
echo '<b>清理浏览器缓存</b>';
echo hr;
echo '创建后台清理任务成功！可能需要几秒钟或几分钟。请不要频繁执行本操作！<br/><a href="index.php">返回</a><br/>';
foot();
exit;
