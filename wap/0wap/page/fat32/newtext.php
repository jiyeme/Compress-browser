<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
if(!$_POST['go'])
{
if($PAGE['u_sid'])
 $sid='&sid='.$PAGE['sid'];
?>
[html=新建文本文档]
[head]
[form=file,read.php?[u.b]&amp;[u.c]&amp;[u.p][u.sid]]
给你的文档取一个合适的名字吧:[br][input=name][/input][br]
[submit=go]新建[/submit][anchor=post,新建,read.php?[u.b]&amp;[u.c]&amp;[u.p][u.sid]][pst=name][/anchor]
[br]如果你要上传或从其他网站下载文件，请[read=,fat32,bin_dir&amp;xx=dir&amp;u=<%=urlencode('read.php?[%%u.b]&cid=fat32&pid=newbin&u='.urlencode('read.php?[%%u.b]&cid=edit&pid=bin_totext'.$sid).$sid)%>]去这里[/read][br]
[/form]
<?php
}
else
{
$eid=url::b64e(pack('L',time()));
$title=$_POST['name'] or $title='新建文本文档';
$s=new session($USER['uid'],'text',3600*24,array($eid));
$s[$eid]=array(
'title'=>$title,
'value'=>'');
headecho::location('read.php?[%%u.b]&cid=edit&pid=edit&eid='.$eid.'[%%u.sid]',true);
}
?>
[hr]返回[read=,edit,copybk]剪切板[/read]-[read=,index,]首页[/read]
[br][time][foot]
[/html]