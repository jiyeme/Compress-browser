<?php
[%getuser]
if(!$USER['islogin'])
 exit;
$type=str::word($_REQUEST['type']);
switch($type)
{
case 'qidian_cn':
 $url='qidian.cn/inc/image.jsp';
break;
default:
 exit;
}
$h=new httplib;
$h->open('http://'.$url,20,10);
$h->send();
header('Content-type: '.$h->header('CONTENT-TYPE'));
$s=new session($USER['uid'],'cookies',3600*24*30,array($type),false);
$s[$type]=$h->cookie();
echo $h->response();
?>