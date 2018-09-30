<?php
$START_TIME=$_SERVER['REQUEST_TIME_FLOAT'];//microtime(true);
include './config.inc.php';
include SUB_DIR.'/ddos.sub.php';
headecho::gz_start();
$PAGE['bid']=str::word($_REQUEST['bid']);
$PAGE['cid']=str::word($_REQUEST['cid']);
$PAGE['pid']=str::word($_REQUEST['pid']);
if($PAGE['bid']!='xhtml' && $PAGE['bid']!='wml')
 $PAGE['bid']=DEFAULT_PAGE_UBB;
if($PAGE['cid']=='')
 $PAGE['cid']=DEFAULT_PAGE_NAME;
if($PAGE['pid']=='')
 $PAGE['pid']=DEFAULT_PAGE_NAME;
define('PAGE_CDIR',PAGE_DIR.'/'.$PAGE['cid']);
$PAGE['mime']=headecho::getpagemime($PAGE['bid']);
$PAGE['charset']='utf-8';
$PAGE['meta']='';
if($PAGE['bid']=='xhtml')
  $PAGE['css']='';
else
 $PAGE['ontimer']='';
$PAGE['sid']=str::word($_GET['sid'] ? $_GET['sid'] : ($_POST['sid'] ? $_POST['sid'] : $_COOKIE['sid']),false);
if($PAGE['sid']!=$_COOKIE['sid'])
 {
setcookie('sid',$PAGE['sid'],time()+DEFAULT_LOGIN_TIMEOUT);
$PAGE['u_sid']='&amp;sid='.$PAGE['sid'];
if($PAGE['bid']=='xhtml')
 $PAGE['h_sid']='<input type="hidden" name="sid" value="'.$PAGE['sid'].'"/>';
 }
if(($PAGE['path']=ubb::page($PAGE['bid'],$PAGE['cid'],$PAGE['pid']))===false)
  $PAGE['path']=ubb::page($PAGE['bid'],'error','404');
include $PAGE['path'];
?>