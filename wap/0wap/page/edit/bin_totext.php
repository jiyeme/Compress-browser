<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$eid=str::word($_REQUEST['eid']);
$dir=str::word($_REQUEST['dir']);
$s=new session($USER['uid'],'dir.'.$dir,24*3600,array($eid),false);
if(!isset($s[$eid]))
{ ?>
[html=出错啦！]
数据库说：“抱歉，我没有发现你要打开的文件。[br]难道是放太长时间结果被老鼠吃了？？[br]你可以去[read=,fat32,newtext]新建一份文本文档[/read]，或者去[read=,index,]首页[/read]逛逛。祝你娱快，并希望我们不要再见（你懂的）。 - -”[hr]
[time][foot][/html]
<?php
exit;
}
$e=$s[$eid];
$fn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/'.$eid.'.gz';
if(!$_POST['go'])
{
$code=str::getcode(fgets(fopen($fn,'r'),8192));
?>
[html=打开文本文件]
[head]
[form=post,read.php?[u.b]&amp;cid=edit&amp;pid=bin_totext&amp;dir=<%=$dir%>&amp;eid=<%=code::html($eid)%>[u.sid]]
你正在打开文件“<%=code::html($e['title'].($e['type'] ? '.'.$e['type'] : ''))%>”（<%=round(filesize($fn)/1024,3)%>KB）[br]
为了防止文件打开后乱码，你需要选择一种正确的编码方式：[br][sel=code][op=<%=$code%>]<%if($code) echo $code; else echo '检测失败'%>(自动检测结果)[/op][op=utf-8]UTF-8(wap网页等)[/op][op=gbk]GBK(小说、www中文网页等)[/op][op=big5]BIG5(繁体中文)[/op][op=UTF-16BE]UTF-16BE(Unicode big endian)[/op][op=UTF-16LE]UTF-16LE(Unicode little endian)[/op][op=utf-7]UTF-7(中文乱码邮件)[/op][op=base64]base64(完全乱码邮件)[/op][/sel][br]或者，输入一种系统可能支持的编码方式:[input=code2][/input][br][submit=go]打开并编辑[/submit][anchor=post,打开并编辑,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>[u.sid]][post=go]打开并编辑[/post][pst=code][pst=code2][/anchor][submit=go]打开编辑器工具[/submit][anchor=post,打开浏览器工具,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>[u.sid]][post=go]打开浏览器工具[/post][pst=code][pst=code2][/anchor][br]注意：如果你选择了BIG5，可能还需要到“编辑器工具”里使用“简繁转换”功能才能正常显示繁体字。
[/form][hr]返回[read=,index,]首页[/read][br][time]
[foot][/html]
<?php
}
else
{
ini_set('memory_limit','50M');
$e['value']=mb_convert_encoding(file_get_contents($fn),'utf-8',$_POST['code2'] ? $_POST['code2'] : $_POST['code']);
if(strpos($e['value'],"\r")!==false)
{
if(strpos($e['value'],"\n")===false)
 $e['value']=str_replace("\r","\n",$e['value']);
else
 $e['value']=str_replace("\r",'',$e['value']);
}
$tname=url::b64e(pack('L',time()));
$s2=new session($USER['uid'],'text',24*3600,array($tname));
$s2[$tname]=$e;
if($_POST['go']=='打开并编辑')
 {$type='edit';$cid='edit';}
else
 {$type='text_tool';$cid='fat32';}
headecho::location('read.php?[%%u.b]&cid='.$cid.'&pid='.$type.'&eid='.$tname,true);
}
?>