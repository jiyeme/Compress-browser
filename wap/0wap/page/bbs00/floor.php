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
[html=下沉贴子]
[read=bbs_bk&amp;bkid=<%=$bkid%>]返回本版[/read][hr]
<?php
$db=new dbclass('db/bbs.db3');
$tz=$db->select('tz','where id='.floor($tzid),'','1','bkid,uid,title');
$tz=$tz[1];
$bkid=$tz['bkid'];
if(!$tz)
 die('贴子已被删除[/html]');
if($tz['uid']!=$uid && !bbs::isbz($db,$uid,$bkid))
 die('随便沉别人的贴子不是一个好习惯哦。[/html]');
if(!$po['go'])
{
?>
[form=post,read.php?id=bbs_floor]
[h=bkid]<%=$bkid%>[/h]
[h=tzid]<%=$tzid%>[/h]
标题: <%=bmtxt($tz['title'])%><hr/>
下沉操作将把这篇贴子的发贴时间和最后回复时间提前10小时，以让它从首页消失。[br]建议求助贴在得到满意的答案后及时下沉，保持论坛整洁，让更多的人有机会被帮助。[br]
你确定要下沉这篇贴子吗？[br]
[h=sid]<%$po['sid']%>[/h]
[read=bbs_tz&amp;tzid=<%=$tzid%>&amp;bkid=<%=$bkid%>]取消[/read][tab]
[submit=go]确定[/submit]
[/form]
<?php
}
else
{
$sql="update tz set fttime=fttime-36000,hftime=hftime-36000 where id=".floor($tzid);
$ok=$db->query($sql);
if($ok)
{
echo '你已经成功下沉了贴子！[br][read=bbs_tz&amp;tzid=',$tzid,'&amp;bkid=',$bkid,']返回贴子[/read]-[read=bbs_bk&amp;bkid=',$bkid,']返回本版[/read]';
$db->query($sql2);
}
else
  echo '抱歉，下沉失败。[br]可能是数据库的问题，请尝试重新提交一次。也可能贴子已经被删除。';
}
?>
[hr][read=bbs]论坛[/read]-[url=index.php]首页[/url]-[read=liuyan]留言[/read][br][time][br][ad]
[/html]