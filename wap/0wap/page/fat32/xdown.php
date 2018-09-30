<?php
$go=$_GET['go'];
if(!$go)
{ ?>
[html=fetion.wml中转服务]
<?php
/*『服务停止通知』[br]

由于大家过度使用，造成虎绿林服务器严重不稳定。所以中转服务已经停止。[br]想用的人自己去找免费空间搭建hu60wap5，然后进后台地图＞采集文件。把[url]http://hu60.cn/wap/0wap/xdown.php.gz[/url]采集并保存为[url]0wap/xdown.php[/url]，采集[url]http://hu60.cn/wap/0wap/page/fat32/xdown.php.gz[/url]为[url]0wap/page/fat32/xdown.php[/url]。[br]最后，访问“[url]你的域名/wap/0wap/?cid=fat32&pid=xdown[/url]”自己用去吧。[hr]
<?php
*/
form::start('get','read.php');
form::hidden(array('bid'=>$PAGE['bid'],'cid'=>'fat32','pid'=>'xdown'));
echo '文件下载地址：[br]';
form::input('url',0,'http://');
echo '[br]xdown文件名：[br]';
form::input('name',0,'newfile');
echo '[br]';
form::submit('url','go');
form::submit('xdown','go');
form::end();
echo '[hr]xdown的默认接入点是cmmm，自己编辑任务改成cmwap。[/html]';
exit;
}
$url=$_GET['url'];
$zpath='http://hu60.sinaapp.com/cmd.php/hu60wap4sae'
/*.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])*/
.'/xdown.php?url='.urlencode($url);
$zpath='http://f.10086.cn/d/dlkjava.fcc?clientId=938&commondownloadUrl='.urlencode($zpath);
if($go=='xdown')
 {
$PAGE['gzip']=false;
header('Content-type: application/x-down-url');
echo mb_convert_encoding("[xDownSeed]\r\n[超时时间=120]\r\n[重试次数=0]\r\n[接入点=cmmm]\r\n[代理服务器=10.0.0.172:80]\r\n[保存文件=QQDownload/{$_GET['name']}]\r\n{$zpath}\r\n",'gbk','utf-8');
 }
else
 { ?>

[html=url.fetion.wml]<a href="<%=code::html($zpath)%>">url.fetion.wml</a>[/html]
<?php }
?>