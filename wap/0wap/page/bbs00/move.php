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
[html=贴子移版]
[read=bbs_tz&amp;tzid=<%=$tzid%>]返回贴子[/read]-[read=bbs_bk&amp;bkid=<%=$bkid%>]返回本版[/read][hr]
<?php
$db=new dbclass('db/bbs.db3');
$tz=$db->select('tz','where id='.floor($tzid),'','1','bkid,uid,title');
$tz=$tz[1];
$bkid=$tz['bkid'];
if(!$tz)
 die('贴子不存在或者已经被删了！[/html]');
if(!bbs::isbz($db,$uid,$bkid))
 die('抱歉，只有版主才能给贴子移版。[/html]');
if(!$po['go'])
{
?>
[form=post,read.php?id=bbs_move]
[h=bkid]<%=$bkid%>[/h]
[h=tzid]<%=$tzid%>[/h]
标题: <%=bmtxt($tz['title'])%>[br]
<?php
$bklst=$db->select('bk','','order by name','','id,name');
echo '移动到:[sel=tobkid]';
foreach($bklst as $v)
{$v['id']==$bkid && $bknm=$v['name'];
echo '<option value="',$v['id'],'">',$v['name'],$v['id']==$bkid ? '(当前版块)' : '','</option>';}
?>[/sel][br]
<?php echo '当前属于:',$bknm; ?>[br]
[h=sid]<%$po['sid']%>[/h]
[submit=go]确定[/submit]
[/form]
<?php
}
else
{
$sql="update tz set bkid=".floor($_POST['tobkid'])." where id=".floor($tzid);
$ok=$db->query($sql);
if($ok)
{
echo '移动成功！[br][read=bbs_bk&amp;bkid=',$bkid,']返回本版[/read]';
}
else
  echo '移动失败。[br]可能是数据库的问题，请尝试重新提交一次。也可能贴子已经被删除。';
}
?>
[hr][read=bbs]论坛[/read]-[url=index.php]首页[/url]-[read=liuyan]留言[/read][br][time][br][ad]
[/html]