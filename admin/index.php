<?php
require 'user.php' ;
require 'function.php';
require '../set_config/set_config.php' ;
session_start();
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("PRC");
if($_SESSION['statu']!=$pass or $_SESSION['statu']==null){
header("location:login.php");
}else{
$db=mysql_connect($b_set['db']['server'],$b_set['db']['user'],$b_set['db']['pass']);
mysql_select_db($b_set['db']['table'],$db);

$do=isset($_GET['do'])?$_GET['do']:NULL;
if($do==null) {
echo "<title>压流后台系统</title><div>压流后台系统</div>";
echo "<hr>压流统计<br/><a href={$_SERVER['PHP_SELF']}?do=yhlb>用户列表</a><br/><a href={$_SERVER['PHP_SELF']}?do=yltj>压流统计</a><br/><a href={$_SERVER['PHP_SELF']}?do=adinfo>基本设置</a><br/><a href={$_SERVER['PHP_SELF']}?do=ad>广告管理</a>";
}elseif($do==yhlb) {
include 'yhlb.php';
}elseif($do==yltj) {
include 'yltj.php';
}elseif($do==adinfo) {
include 'adinfo.php';
}
}

echo "<br/><a href={$_SERVER['HTTP_REFERER']}>返回上级</a><br/><a href={$_SERVER['PHP_SELF']}>返回后台首页</a><br/>报时:".date("Y-m-d H:i:s");
?>
<?php
echo "<div>Powered by <font color='blue'><blink>sunshine</blink></font></div>";
?>