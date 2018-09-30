<?php
[%getuser]
$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
$juan=floor($_REQUEST['juan']) or $juan=5;
$ss=new session(0,'cmmm.'.$dir,0,array($eid));
$s=$ss[$eid];
?>
[html=<%=code::html($s<('title')>)%>-加卷]
资源名称：<%=code::html($s['title'])%>[br]
目前共有卷：<%=$cnt=count($s['url'])%>[br]
<?php
if($s['uid']!=$USER['uid'])
 die('抱歉，这不是你的资源，你不能加卷。[/html]');
if(!$_POST['go'])
{
?>
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir,'&amp;eid=',$eid,'&amp;juan=',$juan%>]
<?php
for($i=0;$i<$juan;$i++)
{
echo '<input type="text" name="name',$i,'" value="分卷',$i+$count+1,'" size="5" /><input type="text" name="url',$i,'" />[br]';
}
?>
[submit=go]提交[/submit]
[/form]
<?php
}
else
{
for($i=0,$ok=0; $i<$juan; $i++)
 {
if(trim($_POST['name'.$i]) && trim($_POST['url'.$i]))
 {$s[url][]=array('name'=>$_POST['name'.$i], 'url'=>$_POST['url'.$i],); $ok++;}
 }
$ss[$eid]=$s;
echo '加卷成功，共添加',$ok,'卷。[%read=,cmmm,info&amp;dir=',$dir,'&amp;eid=',$eid,']返回[/read]';
}
?>
[hr]返回[read=,cmmm,list&amp;dir=<%=$dir%>]资源列表[/read]-[read=,cmmm,]分类[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]