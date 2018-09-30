<?php
include_once 'bmtxt.php';
include 'data/bbs_class.php';
include 'user/login.php';
include 'function.php';
include 'dntxt.php';
if(!islogin($name,$pass,$uid,$sid,$error))
{ httprun('read.php?id=login&amp;u='.urlencode($_SERVER['REQUEST_URI']));
die;
}
$po=&$_REQUEST;
$bkid=$po['bkid'];
$tzid=$po['tzid'];
?>
[html=加精、置顶、锁贴]
[read=bbs_tz&amp;tzid=<%=$tzid%>]返回贴子[/read]-[read=bbs_bk&amp;bkid=<%=$bkid%>]返回本版[/read][hr]
<?php
$db=new dbclass('db/bbs.db3');
$tz=$db->select('tz','where id='.floor($tzid),'','1','bkid,uid,title,youxian,ztn,extra');
$tz=$tz[1];
$bkid=$tz['bkid'];
if(!$tz)
 die('贴子不存在或者已经被删了！[/html]');
if(!bbs::isbz($db,$uid,$bkid))
 die('抱歉，只有版主才能给贴子加精、置顶和锁贴。[/html]');
if(!$po['go'])
{
?>
[form=post,read.php?id=bbs_settop&amp;bkid=<%=$bkid%>&amp;tzid=<%=$tzid%>]
标题: <%=bmtxt($tz['title'])%><hr/>
加精：状态栏里面写上“[url=#][精][/url]”。[br]置顶：状态栏写“[url=#][顶][/url]”，优先级设为2以上。[br]锁贴：状态栏写“[url=#][锁][/url]”，并写上锁贴原因。<hr/>
状态栏:[input=ztn]<%=bmtxt($tz['ztn'])%>[/input][br]
优先级:[input=youxian,3]<%=$tz['youxian']%>[/input][br]
锁贴原因:[input=extra]<%=bmtxt($tz['extra'])%>[/input][br]
[h=sid]<%$po['sid']%>[/h]
[submit=go]确定[/submit]
[/form]
<?php
}
else
{
$sql="update tz set youxian=".floor($_POST['youxian']).",ztn='".yhtp($_POST['ztn'])."',extra='".yhtp($_POST['extra'])."' where id=".floor($tzid);
$ok=$db->query($sql);
if($ok)
{
echo '设置成功！[br][read=bbs_bk&amp;bkid=',$bkid,']返回本版[/read]';
}
else
  echo '设置失败。[br]可能是数据库的问题，请尝试重新提交一次。也可能贴子已经被删除。';
}
?>
[hr][read=bbs]论坛[/read]-[url=index.php]首页[/url]-[read=liuyan]留言[/read][br][time][br][ad]
[/html]