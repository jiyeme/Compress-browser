<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$eid=str::word($_REQUEST['eid']);
$s=new session($USER['uid'],'text',24*3600*30,array($eid),false);
if(!isset($s[$eid]))
{ ?>
[html=出错啦！]
数据库说：“抱歉，我没有发现你要下载的文件。[br]难道是放太长时间结果被老鼠吃了？？[br]你可以去[read=,edit,newtext]新建一份文本文档[/read]，或者去[read=,index,]首页[/read]逛逛。祝你娱快，并希望我们不要再见（你懂的）。 - -”[hr]
[time][foot][/html]
<?php
exit;
}
$e=$s[$eid];
$dir=str::word($_REQUEST['dir']);
if(!$_POST['go'])
{
?>
[html=下载文本文件]
[head]
[form=post,read.php?[u.b]&amp;cid=edit&amp;pid=text_tobin&amp;eid=<%=$eid%>&amp;dir=<%=$dir%>]
你正在下载文件“<%=$e['title']%>.txt”[br]
为了防止下载后文件打开乱码，你需要选择一种适合你阅读器的编码方式：[br][sel=code][op=gbk]GBK(大部分手机)[/op][op=utf-8]UTF-8(WAP浏览器)[/op][op=big5]BIG5(繁体中文)[/op][op=UTF-16BE]UTF-16BE(Unicode big endian)[/op][op=UTF-16LE]UTF-16LE(Unicode little endian)[/op][op=utf-7]UTF-7(中文乱码邮件)[/op][op=base64]base64(完全乱码邮件)[/op][/sel][br]或者，输入一种系统可能支持的编码方式:[input=code2][/input][br]
请选择阅读器支持的换行符：[sel=br][op=LF]LF换行(大部分手机)[/op][op=CRLF]CRLF回车换行(Windows系统)[/op][op=CR]CR回车(苹果系统)[/op][/sel][br]
[submit=go]立即下载[/submit][anchor=post,立即下载,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid,'&amp;dir=',$dir%>][post=go]立即下载[/post][pst=code][pst=code2][pst=br][/anchor][submit=go]文件工具[/submit][anchor=post,文件工具,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid,'&amp;dir=',$dir%>][post=go]文件工具[/post][pst=code][pst=code2][pst=br][/anchor][br]注意：在选择BIG5繁体编码之前，可能需要先在“编辑器工具”里使用“简繁转换”功能才能正常保存为繁体字。
[/form]
[hr]返回 [read=,fat32,text_tool&amp;eid=<%=$eid%>]文本工具[/read] [read=,fat32,text_list]我的文本[/read] [read=,index,]首页[/read][br][time]
[foot][/html]
<?php
}
else
{
$value=mb_convert_encoding($e['value'],$_POST['code2'] ? $_POST['code2'] : $_POST['code'],'utf-8');
if($_POST['br']=='CRLF')
  $value=str_replace("\n","\r\n",$value);
elseif($_POST['br']=='CR')
 $value=str_replace("\n","\r",$value);
$name=md5($value);
$s2=new fat32_f($USER['uid'],'dir.'.$dir,24*3600,array($name));
$s2[$name]=array(
'title'=>$e['title'],
'type'=>'txt',
);
fat32_f::tofile($USER['uid'],$dir,$name,$value);
if($_POST['go']=='立即下载')
 headecho::location('read.php?[%%u.b]&cid=fat32&pid=download&uid='.$USER['uid'].'&dir='.$dir.'&eid='.$name,true);
else
 headecho::location('read.php?[%%u.b]&cid=fat32&pid=bin_tool&dir='.$dir.'&eid='.$name,true);
}
?>