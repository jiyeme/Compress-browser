<?php
$PAGE['gzip']=false;
header('Content-type: application/x-down-url');
$po=&$_POST;
$d=floor($_GET['d']) or $d=time();
if($po['go'])
{
$timeout=$_POST['timeout'];
$restart=$_POST['restart'];
$apn=$_POST['apn'];
$every=$_POST['every'];
$proxy=$_POST['proxy'];
$count=$_POST['count'];
$icount=$_POST['icount'];
$nr=mb_convert_encoding("[xDownSeed]\r\n[超时时间=$timeout]\r\n[重试次数=$restart]\r\n[接入点=$apn]\r\n[代理服务器=$proxy]\r\n\r\n",'gbk','utf-8');
for($x=0;$x<$count;$x++)
{
if(!$po["name$x"] or $po["name$x"]=='QQDownload/')
 continue;
if($every)
 {
if($apn=$po["apn$x"])
 $nr.=mb_convert_encoding("[接入点=$apn]\r\n",'gbk','utf-8');
if($proxy=$po["proxy$x"])
 $nr.=mb_convert_encoding("[代理服务器=$proxy]\r\n",'gbk','utf-8');
 }
$nr.=mb_convert_encoding("[保存文件=".$po["name$x"]."]\r\n",'gbk','utf-8');
for($y=0;$y<$icount;$y++)
 {
if(($url=$po["url{$x}_{$y}"]) && $url!='http://') $nr.="$url\r\n";
 }
$nr.="\r\n";
}
file_put_contents("../qbtmp/xurl_$d.txt",$nr);
}
echo file_get_contents("../qbtmp/xurl_$d.txt");
?>