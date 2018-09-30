<?php
[%getuser]
$dir=str::word($_REQUEST['dir']);
$ss=new session(0,'cmmm,0,array($dir));
$s=$ss[$dir];
?>
[html=删除分类]
分类名称：[br]
<?php
if($USER['uid']!=1)
 die('抱歉，你木有权限。[/html]');
if(!$_POST['go'])
{
?>
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>]
[read=,cmmm,list&amp;dir=<%=$dir%>]返回[/read][tab]
[submit=go]确定删除[/submit]
[/form]
<?php
}
else
{
//unset($ss[$dir]);
$db=session::conn();
$db->exec("delete from session where zu='cmmm.$dir'");
echo '删除成功，返回[%read=,cmmm,list&amp;dir=',$dir,']资源列表[/read]';
}
?>
[hr]返回[read=,cmmm,list&amp;dir=<%=$dir%>]资源列表[/read]-[read=,cmmm,]分类[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]