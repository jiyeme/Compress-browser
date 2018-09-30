<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$tzid=$_GET['tzid'];
?>
[html=发表回复]
[head]
[url=[cid].tz.[bid]?tzid=<%=$tzid%>]返回贴子[/url]-[url=[cid].ubb.[bid]]UBB说明[/url][hr]
<?php
$db=db::conn('bbs');
$tzid>0 && $tz=$db->query('select ztn,bkid from '.DB_A.'tz where id='.floor($tzid));
if($tzid<=0 or !$tz or !$tz=$tz->fetch(db::ass))
 die('错误！[br]贴子不存在！[br]抱歉，可能是数据库的问题，请尝试重新提交一次。也可能贴子已经被删除了。[/html]');
if(!$_POST['go'])
{
form::someinput_set(array('new'=>3));
form::start('post','[%%cid].[%%pid].[%%bid]?tzid='.$tzid);
echo '回复内容(多框输入):[br]';
form::someinput_put('nr2',$_POST['nr']);
form::submit('发表','go');
form::end();
}
else
{
$nr=$_POST['nr'] or $nr=form::someinput_get('nr2');
if(!$nr)
  die('错误！[br]内容不能为空！[/html]');
$sql='insert into hf(uid,tzid,nr,hftime,youxian) values(?,?,?,?,1)';
$sql2='update tz set hfcount=hfcount+1,hftime='.time().' where id='.floor($tzid);
if(preg_match('/\[结\]/u',$tz['ztn']) or preg_match('/\[锁\]/u',$tz['ztn']))
 die('该贴子已锁或已结，不能回复！[br]也可能是数据库的问题，你可以重新提交一次。');
$rs=$db->prepare($sql);
if($rs && $rs->execute(array($USER['uid'],$tzid,$nr,time())))
{
 echo '回复成功！[br][url=[%cid].bk.[%bid]?bkid=',$tz<('bkid')>,']返回本版[/url]-[url=[%cid].hf.[%bid]?tzid=',$tzid,']回复列表[/url]';
 $db->exec($sql2);
}
else
  echo '回复失败。[br]可能是空间满了，请[url=read.php?cid=sos&amp;pid=clean]点此清理[/url]，并尝试重新提交一次。';
}
?>
[hr][url=[cid].index.[bid]]论坛[/url]-[url=index.php]首页[/url]-[url=msg.liuyan.[bid]]留言[/url][foot]
[/html]