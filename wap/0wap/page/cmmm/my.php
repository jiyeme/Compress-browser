[html=我的免流资源]
<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$ss=new session($USER['uid'],'my.cmmm',0,'');
$i=0;
foreach($ss as $eid=>$s)
{
$i++;
echo /*'[%read=,cmmm,addjuan&amp;dir=',$dir,'&amp;eid=',$eid,']加卷[/read][or]*/'[%read=,cmmm,delinfo&amp;dir=&amp;eid=',$eid,']删除[/read][or]';
?>
[read=,cmmm,xdown&amp;eid=<%=$eid%>]xDown种子[/read][or]<a href="read.php/downloads.dat?cid=cmmm&amp;pid=sdown&amp;eid=<%=$eid%>">智能下载种子</a>[br]
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
echo '[hr]';
}
if(!$i)
 echo '没有资源。';
?>
返回[read=,cmmm,]公共资源[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]