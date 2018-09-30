<?php
[%getuser]
include FUNC_DIR.'/bbs_ubb.func.php';
$tzid=floor($_GET['tzid']);
$db=db::conn('bbs');
$tz=$db->query('select bkid,uid,title,nr,fttime,hfcount,rdcount,ztn,extra from '.DB_A.'tz where id='.$tzid);
if(!$tz or !$tz=$tz->fetch(db::ass))
{ ?>[html=错误！]贴子不存在，可能已被删除。[br]按后退键返回上一页或[url=[cid].index.[bid]]返回论坛首页[/url][/html]<?
 exit; }
$db->exec('update '.DB_A.'tz set rdcount=rdcount+1 where id='.$tzid);
$bkid=$tz['bkid'];
$rdcount=$tz['rdcount']+1;
$mei=1000; #每页字数
$title=code::html(mb_substr($tz['title'],0,50,'utf-8'));
$p=floor($_GET['p']);
$tp=$_GET['tp'];
$nr=$tz['nr'];
$bk=$db->query('select * from '.DB_A.'bk where id='.$bkid);
if($bk){
$bk=$bk->fetch(db::ass); $isbz=(strpos($bk['bzid'],",{$USER['uid']},")!==false);
//var_dump($bk,",{$USER['uid']},");
}else{
$bk=array('name'=>'未知版块');
$isbz=false;
}
if($jietie=(strpos($tz['ztn'],'[结]')!==false))
  $ztn="此贴已结。[br]原因：{$tz['extra']}[hr]";
if($suotie=(strpos($tz['ztn'],'[锁]')!==false))
{
if(!$isbz)
 {
  $nr="贴子被锁了，看不到。。。";
  $tp='all';
 }
 $ztn="[hr]此帖已锁。[br]原因：{$tz['extra']}[hr]";
}
$strlen=mb_strlen($nr,'utf-8');
$la=ceil($strlen/$mei);
if($p<1 or $p>$la)
 $p=1;
$qi=$tp=='all' ? 0 : ($p-1)*$mei;
$zhi=$tp ? $strlen : $mei;
$nr=mb_substr($nr,$qi,$zhi,'utf-8');
$nr=bbs_ubb(code::html($nr));
if($tz['uid']==$USER['uid'] or $isbz)
 $gai='[hr][[url=[%%cid].gai.[%%bid]?tzid='.$tzid.']改[/url][or][url=[%%cid].xu.[%%bid]?tzid='.$tzid.']续[/url][or][url=[%%cid].del.[%%bid]?tzid='.$tzid.']删[/url][or][url=[%%cid].floor.[%%bid]?tzid='.$tzid.']沉[/url]';
if($isbz)
 $gai.='[or][url=[%%cid].move.[%%bid]?tzid='.$tzid.']移[/url][or][url=[%%cid].settop.[%%bid]?tzid='.$tzid.']设[/url]]';
?>
[html=<?php echo $title; ?>]
[head]
[url=[cid].ft.[bid]?bkid=<%=$bkid%>]发贴[/url]-[url=[cid].bk.[bid]?bkid=<%=$bkid%>]<%=$bk['name']%>[/url][br]
<?php
$info=user::getinfobyuid($tz['uid']);
echo '标题:',$tz['ztn'],' ',$title,'[br]作者: <a href="msg.send.[%bid]?touid=',$tz['uid'],'">',$info['name'],'</a>[br]时间: ',date('y-m-d H:i:s',$tz['fttime']),'[br]点击: ',$rdcount,'[hr]',$nr,$ztn;
if($la>1 && $tp!='all')
 echo '[hr]';
$jiz='<a href="[%%cid].[%%pid].[%%bid]?tzid='.$tzid.'&amp;';
if(!$tp && $p<$la)
 echo $jiz,'p=',$p+1,'">下页</a> ';
if($tp!='all' && $p>1)
 echo $jiz,'p=',$p-1,'">上页</a> ';
if($p<$la&&!$tp)
 echo $jiz,'p=',$p+1,'&amp;tp=other">余下</a> ';
if($la>1 && $tp!='all')
 echo $jiz,'tp=all">全部</a> ';
//echo '[br]共',$strlen,'字';
if($la>1 && !$tp)
 echo $p,'/',$la,'页';
echo $gai,'[hr]『<a href="[%cid].hf.[%bid]?tzid=',$tzid,'">回复列表(',$tz['hfcount'],')</a>』[br]';
if($tz['hfcount']<1)
 echo '没有回复，赶快抢沙发吧！[hr]';
else
 {
$hf=$db->query('select id,nr,hftime,uid from '.DB_A.'hf where tzid='.$tzid.' order by hftime desc limit 3');
if($hf && $hf=$hf->fetchall(db::ass)){
 foreach($hf as $i=>$hfi){
$info=user::getinfobyuid($hfi['uid']);
echo $tz['hfcount']-$i,'. ',bbs_ubb(code::html(mb_substr($hfi['nr'],0,200,'utf-8')));
if(mb_strlen($hfi['nr'],'utf-8')>200)
 echo '……[url=[%cid].hf.[%bid]?hfid=',$hfi<('id')>,']展开[/url]';
echo '[br](<a href="msg.send.[%bid]?touid=',$hfi['uid'],'">',$info['name'].'</a>','/',date('y-m-d H:i',$hfi['hftime']),')[hr]';
}
 }
else
 echo '抱歉，可能数据出错了，没有找到回复内容。[hr]';
}
if(!$jietie && !$suotie)
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
[hr]
[url=[cid].index.[bid]]论坛[/url]-[url=index.index.[bid]]首页[/url]-[url=msg.liuyan.[bid]]留言[/url][br][time][foot]
[/html]