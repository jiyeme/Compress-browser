<?php
session_start();
require 'user.php' ;
echo "<title>登录后台</title>登录压流后台系统<br/>";
if($_REQUEST['user']==NULL) {
echo '<form action="'.$SERVER['PHP_SELF'].'" method="get">帐号:<input type="text" name="user" /><br/>密码:<input type="password" name="pass" /><br/><input type="submit" value="登录后台" />';
}else{
if($user==$_REQUEST['user']&&$pass==$_REQUEST['pass']){
if($_SESSION['statu']==$pass){
echo "您重复登录了";
}else{
$_SESSION['statu']=$pass;
echo "您已经成功登录！<br/><a href=index.php>后台管理首页</a>";
}
}elseif($user!=$_REQUEST['user'] or $pass!=$_REQUEST['pass']){
echo "帐号或密码错误";
}
}
?>