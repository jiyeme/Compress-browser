<?php
[%getuser]
$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
if($dir=='')
{
 $ss=new session($USER['uid'],'my.cmmm',0,array($eid));
}
else
 $ss=new session(0,'cmmm.'.$dir,0,array($eid));
$s=$ss[$eid];
?>[html=<%=code::html($s<('title')>)%>-删除]
资源名称：<%=code::html($s['title'])%>[br]
目前共有卷：<%=$cnt=count($s['url'])%>[br]
<?php
if($dir!='' && $s['uid']!=$USER['uid'] && $USER['uid']!=1)
 die('抱歉，这不是你的资源，你不能删除。[/html]');
if(!$_POST['go'])
{
?>
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir,'&amp;eid=',$eid,'&amp;juan=',$juan%>]
[read=,cmmm,info&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>]返回[/read][tab]
[submit=go]确定删除[/submit]
[/form]
<?php
}
else
{
unset($ss[$eid]);
echo '删除成功，返回[%read=,cmmm,list&amp;dir=',$dir,']资源列表[/read]';
}
?>
[hr]返回[read=,cmmm,list&amp;dir=<%=$dir%>]资源列表[/read]-[read=,cmmm,]分类[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]