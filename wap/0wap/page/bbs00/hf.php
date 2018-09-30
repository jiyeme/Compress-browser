<?php
$tzid=floor($_GET['tzid']);
$db=db::conn('bbs');
$tz=$db->query('SELECT hfcount,ztn,title,bkid FROM '.DB_A.'tz WHERE id='.$tzid);
if(!$tz or !$tz=$tz->fetch(db::ass)) { ?>
[html=错误！]贴子不存在，可能已经被删除！[br]请按后退键返回或[url=[cid].index.[bid]]返回论坛首页[/url][/html]
<?php
exit;}
$title=$tz['title'];
$bkid=$tz['bkid'];
?>
[html=回复列表]
[head]
[url=[cid].tz.[bid]?tzid=<%=$tzid%>]返回贴子[/url]-[url=[cid].bk.[bid]?bkid=<%=$bkid%>]返回本版[/url][hr]
<%=code::html($title)%>
[br]『回复列表』[hr]
<?php
$hfcount=$tz['hfcount'];
if(!$hfcount)
  echo '没有回复。快来抢沙发吧！[hr]';
else
{
$mei=10; #每页回复数
$la=floor($hfcount/$mei);

if($hfcount%$mei) $la++;
$p=floor($_GET['p']);
if($p<1 or $p>$la)
  $p=1;
$qi=($p-1)*$mei;
$hf=$db->query('SELECT*FROM '.DB_A.'hf WHERE tzid='.$tzid.' ORDER BY youxian DESC,hftime DESC LIMIT '.$qi.','.$mei);
$hf=$hf->fetchall(db::ass);
include FUNC_DIR.'/bbs_ubb.func.php';
foreach($hf as $i=>$hfi)
 {
$info=user::getinfobyuid($hfi['uid']);
echo $hfcount-$i-$qi,'. ',$hfi['ztn'],bbs_ubb(code::html(mb_substr($hfi['nr'],0,1000,'utf-8')));
if(mb_strlen($hfi['nr'],'utf-8')>1000)
 echo '……<a href="[%cid].hfall.[%bid]?hfid=',$hfi['id'],'">展开</a>';
echo '[br](<a href="msg.send.[%bid]?touid=',$hfi['uid'],'">',$info['name'],'</a>/',date('Y-m-d H:i',$hfi['hftime']),')[hr]';
 }
$jiz='<a href="[%%cid].[%%pid].[%%bid]?tzid='.$tzid.'&amp;p=';
if($p<$la)
 echo $jiz,$p+1,'">下页</a>';
if($p>1)
 echo ' ',$jiz,$p-1,'">上页</a>';
echo "[br]$p/$la","页,共$hfcount","条[hr]";
if($la>1)
  {
form::start('get','[%%cid].[%%pid].[%%bid]');
form::hidden('tzid',$tzid);
form::input('p',2,$la);
form::submit('跳页');
form::end();
echo '[hr]';
  }
}
if(!preg_match('/\[锁\]/u',$tz[1]['ztn']) && !preg_match('/\[结\]/u',$tz[1]['ztn']))
{
form::start('post','[%%cid].xiehf.[%%bid]?tzid='.$tzid);
form::input('nr');
echo '[br]';
form::submit('快速回复','go');
form::submit('多框回复');
form::end();
}
else
 echo '结贴或锁贴后不能回复。';
?>
[hr][url=[cid].index.[bid]]论坛[/url]-[url=index.index.[bid]]首页[/url]-[url=msg.liuyan.[bid]]留言[/url][br][time][foot]
[/html]