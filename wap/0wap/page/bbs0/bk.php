<?php
try{
$bkid=floor($_GET['bkid']);
$uid=floor($_GET['uid']);
$tp=$_GET['tp'] or $tp='new';
$db=db::conn('bbs');
$baseurl='[%%cid].[%%pid].[%%bid]?';
$sqlcnt='select count(*) as cnt';
$sqltz='select id,title,uid,fttime,hfcount,rdcount,ztn';
$sql=' from '.DB_A.'tz';
$msgbox='[hr]';

if($bkid)
{
$baseurl.='bkid='.$bkid.'&amp;';
$sql.=' where bkid='.$bkid;
$bk=$db->query('select name,extra from '.DB_A.'bk where id='.$bkid);
if(!$bk or !$bk=$bk->fetch(db::ass))
 throw new exception('抱歉，版块不存在，可能是论坛改版了，或者是你进的链接写错了。');
$bkname=$bk['name'];
$msgbox.='版块说明：'.$bk['extra'].'[hr]';
}
elseif($uid)
{
$baseurl.='uid='.$uid.'&amp;';
$sql.=' where uid='.$uid;
$info=user::getinfobyuid($uid);
if(!$info)
 throw new exception('抱歉，你要查看的用户不存在，可能是这个用户被管理员删除了，或者你进的链接写错了。');
$bkname=$info['name'].'的贴子';
}
else
{

$sql.=' where bkid not in (select id from '.DB_A.'bk where notshow=1)';
$bkname='查看论坛全部贴子';
}
?>
[html=<?php echo $bkname; ?>]
[head]
<?php
if($bkid)
 echo '[url=[%cid].ft.[%bid]?bkid=',$bkid,']发贴[/url]-';
$sl=new selectlink($baseurl,$tp);
$sl->add(array(
 'new','最新','tp=new',
 'newhf','最新回复','tp=newhf',
 'hot','最热','tp=hot',
 'good','精华','tp=good',
 'lock','锁定','tp=lock'));
$sl->show();
echo $msgbox;
switch($tp)
{
case 'hot':
 $sql.=' order by rdcount desc,hfcount desc';
break;
case 'newhf':
 $sql.=' order by hftime desc';
break;
case 'good':
 $sql.=' and ztn like \'%[精]%\' order by fttime desc';
break;
case 'lock':
 $sql.=' and ztn like \'%[锁]%\' order by fttime desc';
break;
default:
 $sql.=' order by youxian desc, fttime desc';
break;
}
?>
『贴子列表』[br]
<?php
$tzcnt=$db->query($sqlcnt.$sql);
if(!$tzcnt or !$tzcnt=$tzcnt->fetch(db::ass) or ($tzcnt=$tzcnt['cnt'])<1)
 echo '没有任何贴子，赶紧来上一贴吧！[hr]';
else
{
$mei=20; #每页贴子数
$la=ceil($tzcnt/$mei);
$p=floor($_GET['p']) or $p=floor($_POST['p']);
if($p<1 or $p>$la)
  $p=1;
$qi=($p-1)*$mei;
$sql.=' limit '.$qi.','.$mei;
$tz=$db->query($sqltz.$sql);
if(!$tz or !$tz=$tz->fetchall(db::ass))
 echo '抱歉，好像出问题了，没有找到贴子。你可以尝试刷新一下：）';
else
 foreach($tz as $i=>$tzi)
 {
$info=user::getinfobyuid($tzi['uid']);
echo $i+$qi+1,'. ',$tzi['ztn'],'<a href="[%cid].tz.[%bid]?tzid=',$tzi['id'],'">',code::html(mb_substr($tzi['title'],0,20,'utf-8')), mb_strlen($tzi['title'],'utf-8')>20 ? '…' : '' ,'</a>[br](',$info['name'],'/<a href="[%cid].hf.[%bid]?tzid=',$tzi['id'],'">',$tzi['hfcount'],'回复</a>/',$tzi['rdcount'],'点击/',date('y-m-d H:i',$tzi['fttime']),')[br]';
 }
echo '[hr]';
$baseurl.='tp='.$tp.'&amp;';
$jiz='<a href="'.$baseurl.'p=';
if($p<$la)
 echo $jiz,$p+1,'">下页</a>';
if($p>1)
 echo ' ',$jiz,$p-1,'">上页</a>';
echo '[br]',$p,'/',$la,'页,共',$tzcnt,'条[hr]';
if($la>1)
  {
?>
[form=post,<%=$baseurl%>]
[input=p,2]<%=$la%>[/input]
[submit]跳页[/submit][anchor=post,跳页,<%=$baseurl%>][pst=p][/anchor]
[/form][hr]
<?php
  }
}
?>
[url=[cid].index.[bid]]论坛[/url]-[url=index.index.[bid]]首页[/url]-[url=msg.liuyan.[bid]]留言[/url][br][time][foot]
[/html]<?php
}catch(exception $e){
?>[html=错误！]<%=$e->getmessage()%>[br]按后退键返回或[url=[cid].index.[bid]]返回论坛首页[/url][/html]<?php
}
?>