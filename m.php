<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
//@set_time_limit(60);
if($_SERVER['PATH_INFO'])
{
$JIUWAP_PATH=explode('/',$_SERVER['PATH_INFO']);
if($JIUWAP_PATH['1'][0]=='@')
{
$JIUWAP_PATH['1']=substr($JIUWAP_PATH['1'],1);
define('JIUWAP_FETION',true);
}
else
 define('JIUWAP_FETION',false);
$_COOKIE['FREE']=$JIUWAP_SID=$JIUWAP_PATH['1'];
unset($JIUWAP_PATH[1]);
if($JIUWAP_PATH[2]=='')
 $JIUWAP_PATH[2]='index.php';
include $JIUWAP_PATH="E:/wamp64/www/".implode('/',$JIUWAP_PATH);
}
else
 include $JIUWAP_PATH="E:/wamp64/www/".'/login.php';
?>