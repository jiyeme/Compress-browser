<?php
include_once 'bmtxt.php';
include 'class/someinput.php';
include 'user/login.php';
include 'function.php';
include 'dntxt.php';
include 'data/bbs_class.php';
if(!islogin($name,$pass,$uid,$sid,$error))
{ httprun('read.php?id=login&amp;u='.urlencode($_SERVER['REQUEST_URI']));
die;
}
$po=&$_REQUEST;
$bkid=$po['bkid'];
$tzid=$po['tzid'];
?>
[html=续贴]
[read=bbs_tz&amp;bkid=<%=$bkid%>&amp;tzid=<%=$tzid%>]返回贴子[/read]-[read=bbs_ubb]UBB说明[/read][hr]
<?php
$db=new dbclass('db/bbs.db3');
$tz=$db->select('tz','where id='.floor($tzid),'','1','uid,title,nr,bkid');
$tz=$tz[1];
$bkid=$tz['bkid'];
if(!$tz)
 die('贴子不存在，可能已被删除[/html]');
if($tz['uid']!=$uid && !bbs::isbz($db,$uid,$bkid))
 die('随便修改别人的贴子不是一个好习惯哦。[/html]');
if(!$po['go'])
{
?>
[form=post,read.php?id=bbs_xu]
[h=bkid]<%=$bkid%>[/h]
[h=tzid]<%=$tzid%>[/h]
标题: <%=bmtxt($tz['title'])%>[br]
续贴内容(多框输入):[br]
<?php someinput('','nr'); ?>
[h=sid]<%$po['sid']%>[/h]
[submit=go]续贴[/submit]
[/form]
<?php
}
else
{
$nr=dntxt(resomeinput('nr'));
if(!$nr)
  die('错误！[br]内容不能为空！[/html]');
$sql="update tz set nr='".yhtp($tz['nr'].$nr)."',fttime=".time().",hftime=".time()." where id=".floor($tzid);
$ok=$db->query($sql);
if($ok)
  echo '续贴成功！[br][read=bbs_bk&amp;bkid=',$bkid,']返回本版[/read]';
else
  echo '续贴失败。[br]可能是数据库的问题，请尝试重新提交一次。';
}
?>
[hr][read=bbs]论坛[/read]-[url=index.php]首页[/url]-[read=liuyan]留言[/read][br][time][br][ad]
[/html]