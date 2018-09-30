<?php
$go=$_GET['go'];
$uid=floor($_GET['uid']);
$dir=str::word($_GET['dir']);
$eid=str::word($_GET['eid']);
$path='/'.$uid.'/'.$dir.'/'.$eid;
$rpath=USERFILE_RDIR.$path.'.gz';
$zpath='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/down.php'.$path.'/';
$fsize=filesize($rpath);
if(!$fsize)
{ ?>
[html=出错啦！]
文件系统：抱歉，你想要下载的文件不存在或者大小为0B，不能下载。[br]出现这种情况的原因可能是：[br] 1.文件被所有者或管理员删除[br] 2.文件太久没被使用，被系统自动删除[br] 3.这个文件下载链接是错误的[br]你可以后退并继续访问，或者去[read=,index,]首页[/read]逛逛。祝你娱快，并真切地希望我们不要再见面。（呃，看到我准没好事，哈哈--）
[hr][time][foot][/html]
<?php
exit;
}
$s=new fat32_f($uid,'dir.'.$dir,0,array($eid),false);
$s=$s[$eid];
$fname=$s['title'] or $fname=substr($eid,0,8);
$pinyin=$fname;//str::word(str::pinyin($fname),false);
if($s['type'])
{
$fname.='.'.$s['type'];
$pinyin.='.'.str::word($s['type']);
}
$zpath='http://f.10086.cn/d/dlkjava.fcc?clientId=938&commondownloadUrl='.urlencode($zpath.'x.php?mime=text/vnd.wap.wml');
if($go=='xdown')
 {
$PAGE['gzip']=false;
header('Content-type: application/x-down-url');
echo mb_convert_encoding("[xDownSeed]\r\n[超时时间=120]\r\n[重试次数=0]\r\n[接入点=cmmm]\r\n[代理服务器=10.0.0.172:80]\r\n[保存文件=QQDownload/{$fname}]\r\n{$zpath}\r\n",'gbk','utf-8');
 }
else
 { ?>
[html=url.fetion.wml]<a href="<%=code::html($zpath)%>"><%=code::html($fname)%>.wml</a>[/html]
<?php }
?>