<?php
$PAGE['gzip']=false;
header('Content-type: application/x-down-url');
$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
if($dir=='')
{
 [%getuser]
 $s=new session($USER['uid'],'my.cmmm',0,array($eid));
}
else
 $s=new session(0,'cmmm.'.$dir,0,array($eid));
$s=$s[$eid];
echo mb_convert_encoding("[xDownSeed]\r\n[超时时间=60]\r\n[重试次数=5]\r\n[接入点=cmmm]\r\n[代理服务器=]\r\n[保存文件=QQDownload/{$s['title']}]\r\n",'gbk','utf-8');
foreach($s['url'] as $u)
{
if(!preg_match('![xor][a-zA-Z0-9_\\-]*:!',$u['url']))
 {
$u['url']=preg_replace('![xor].*(file\..[[xor]\[\]]*\.[a-z]{3}).*$!','http://\\1',$u['url']);
 }
echo $u<('url')>,"\r\n";
}
?>