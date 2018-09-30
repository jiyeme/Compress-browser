<?php
static $info;
$info=substr($_SERVER['PATH_INFO'],1);
if(strpos($info,'/')!==false)
{
$info=explode('/',$info);
$_GET['sid']=$_POST['sid']=$_REQUEST['sid']=$info[0];
$info=$info[1];
}
$info=explode('.',$info);
if(count($info)==3)
{
$_GET['cid']=$_POST['cid']=$_REQUEST['cid']=$info[0];
$_GET['pid']=$_POST['pid']=$_REQUEST['pid']=$info[1];
$_GET['bid']=$_POST['bid']=$_REQUEST['bid']=$info[2];
}
include './read.php';
?>