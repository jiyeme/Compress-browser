<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$tzid=floor($_GET['tzid']);
$db=db::conn('bbs');
$tz=$db->query('SELECT title,bkid FROM '.DB_A.'tz WHERE id='.$tzid);
if(!$tz or !$tz=$tz->fetch(db::ass))
{
?>[html=错误！]贴子不存在，可能已被删除。[br]请按后退键返回或[url=[cid].index.[bid]]返回论坛首页[/url][br][time][/html]<?php
die;
}
$bk=$db->query('SELECT bzid,name FROM '.DB_A.'bk WHERE id='.$tz['bkid']);
$bk && $bk=$bk->fetch(db::ass);
?>
[html=删贴]
[head]
[url=[cid].bk.[bid]?bkid=<%=$tz<('bkid')>%>]返回本版[/url][hr]
<?php
if($tz['uid']!=$USER['uid'] && strpos($bk['bzid'],",$USER<(uid)>,")===false)
 die('随便删别人的贴子不是一个好习惯哦。[/html]');
if(!$_POST['go'])
{
form::start('post','[%%cid].del.[%%bid]?tzid='.$tzid);
echo '标题: ',code::html($tz['title']),'[br]版块: ',$bk['name'],'[br][tab][br]你确定要删除这篇贴子和它的所有回复吗？[br][url=[%cid].tz.[%bid]?tzid=',$tzid,']取消[/url][tab]';
form::submit('确定','go');
form::end();
}
else
{
$sql='DELETE FROM '.DB_A.'tz WHERE id='.floor($tzid);
$sql2='DELETE FROM '.DB_A.'hf WHERE tzid='.floor($tzid);
if($db->exec($sql))
{
echo '已删除主贴和',$db->exec($sql2),'回复[br]';
echo '删除成功！[br][url=[%cid].bk.[%bid]?bkid=',$bkid,']返回本版[/url]';
}
else
  echo '删除失败。[br]可能是数据库的问题，请尝试重新提交一次。也可能贴子已经被删除。';
}
?>
[hr][url=[cid].index.[bid]]论坛[/url]-[url=index.index.[bid]]首页[/url]-[url=msg.liuyan.[bid]]留言[/url][br][time][foot]
[/html]