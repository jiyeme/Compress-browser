<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$tzid=floor($_GET['tzid']);
?>
[html=修改贴子]
[head]
[url=[cid].tz.[bid]?tzid=<%=$tzid%>]返回贴子[/url]-[url=[cid].ubb.[bid]]UBB说明[/url][hr]
<?php
$db=db::conn('bbs');
$tz=$db->query('SELECT uid,title,nr,bkid FROM '.DB_A.'tz where id='.$tzid);
if(!$tz or !$tz=$tz->fetch(db::ass))
 die('贴子不存在，可能已被删除[/html]');
$bk=$db->query('SELECT bzid FROM '.DB_A.'bk where id='.floor($tz['bkid']));
$bk=$bk->fetch(db::ass);
if($tz['uid']!=$USER['uid'] && strpos($bk['bzid'],",{$USER['uid']},")===false)
 die('随便修改别人的贴子不是一个好习惯哦。[/html]');
if(!$_POST['go'])
{
form::someinput_set(array('new'=>3));
form::start('post','[%%cid].gai.[%%bid]?tzid='.$tzid);
echo '标题(50字内):[br]';
form::input('title',null,code::html($tz['title']));
echo '[br]内容(多框输入):[br]'; form::someinput_put('nr',$tz['nr']);
form::submit('发表','go');
form::end();
}
else
{
$title=$_POST['title'];
$nr=form::someinput_get('nr');
if(!trim($title) or !trim($nr))
  die('错误！[br]标题和内容都不能为空！[/html]');
$sql='update tz set title=?,nr=?,fttime=?,hftime=? where id=?';
$rs=$db->prepare($sql);
if($rs && $rs->execute(array($title,$nr,time(),time(),$tzid)))
  echo '修改成功！[br][url=[%cid].bk.[%bid]?bkid=',$tz<('bkid')>,']返回本版[/url]';
else
  echo '修改失败。[br]可能是数据库的问题，请尝试重新提交一次。';
}
?>
[hr][url=[cid].index.[bid]]论坛[/url]-[url=index.index.[bid]]首页[/url]-[url=msg.liuyan.[bid]]留言[/url][br][time][foot]
[/html]