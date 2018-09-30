<?php
[%getuser]
$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
$s=new session(0,'cmmm.'.$dir,0,array($eid));
$s=$s[$eid];
?>
[html=<%=code::html($s<('title')>)%>-免流资源]
<?php
if($s['uid']==$USER['uid'] or $USER['uid']==1)
{
echo '[%read=,cmmm,addjuan&amp;dir=',$dir,'&amp;eid=',$eid,']加卷[/read][or][%read=,cmmm,delinfo&amp;dir=',$dir,'&amp;eid=',$eid,']删除[/read][br]';
}
?>[read=,cmmm,xdown&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>]xDown种子[/read][or]<a href="read.php/downloads.dat?cid=cmmm&amp;pid=sdown&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>">智能下载种子</a>[br]
资源名称：<%=code::html($s['title'])%>[br]
资源介绍：<%=code::html($s['jies'])%>[br]
分卷下载：[br]
<?php
foreach($s['url'] as $u)
{
if(!preg_match('![xor][a-zA-Z0-9_\\-]*:!',$u['url']))
 {
$u['url']=preg_replace('![xor].*(file\..[[xor]\[\]]*\.[a-z]{3}).*$!','http://\\1',$u['url']);
 }
echo '[url=',code::html($u<('url')>),']',code::html($u<('name')>),'[/url]&nbsp;';
}
?>
[hr]返回[read=,cmmm,list&amp;dir=<%=$dir%>]资源列表[/read]-[read=,cmmm,]分类[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]