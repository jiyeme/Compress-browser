<?php
try{
$url=$_GET['url'];
$range=$_GET['range'];
$mime=$_GET['mime'];
if(!$mime)
 $mime='text/vnd.wap.wml; charset=utf-8';
include "./config.inc.php";
//include SUB_DIR.'/ddos.sub.php';
set_time_limit(0);
ignore_user_abort(false);

header('Content-type: '.$mime);
$h=new http(array());
if(!$h->open($url,30,5))
 throw new exception('网址错误');
if($range)
 $h->header('Range',$range);
if(!$h->send())
 throw new exception('连接失败');
if($range && !$h->isrange() && (int)$range)
 throw new exception('断点续传错误');
if($len=$h->header('CONTENT-LENGTH'))
 header('Content-length: '.$len);
$fp=$h->fp;
if(!$fp)
 throw new exception('文件句柄错误');
while(!feof($fp))
{
echo fread($fp,8192);
flush();
}
}catch(exception $e)
{
//header('Content-length: 0');
echo $e->getmessage();
}
?>