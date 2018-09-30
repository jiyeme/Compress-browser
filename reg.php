<?php
define('DEFINED_JIUWAP','jiuwap.cn');
include "/home/jysafec1/public_html/yl/".'/inc/common.php';
include 'admin/user.php' ;
if($reg_ol!=1) {
echo "<title>本浏览器已停止注册</title>本浏览器已停止注册<br/><a href={$_SERVER['HTTP_REFERER']}>返回上一页</a>";
}elseif($reg_num==0) {
echo "<title>本浏览器已达到注册上限，停止注册</title>本浏览器已达到注测上限，停止注册<br/><a href={$_SERVER['HTTP_REFERER']}>返回上一页</a>";
}else{
if ( isset($_GET['yes']) ){
$get_string = $browser->user_reg($_POST['name'],$_POST['pass'],$_POST['pass']);
if ( $get_string === false ){
$_reg_num=$reg_num-1;
$into='<?php
$user="'.$user.'";
$pass="'.$pass.'";
$reg_ol="'.$reg_ol.'";
$reg_num="'.$_reg_num.'";
$mianliu="'.$mianliu.'"
?>';
file_put_contents("admin/user.php",$into);
load_template('reg_success',false,'/?r='.$browser->rand,false,'utf-8',0);
}else{
load_template('reg_fail',false);
}
}else{
if($reg_num>0){
echo "本浏览器还剩{$reg_num}个注册名额！赶快注册一个号吧！<br/>";
}
load_template('reg_form');
}
}

