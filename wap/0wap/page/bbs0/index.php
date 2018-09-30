[html=绿虎论坛]
[head]
[form=post,[cid].sou.[bid]]
关键字:[input=w][/input][br]
用户名:[input=souta][/input][br]
[submit]贴子搜索[/submit]
[/form]
[hr]
版块列表：[br]
<?php
$db=db::conn('bbs');
$qu=$db->query('select name from '.DB_A.'qu order by youxian');
if(!$qu or !$qu=$qu->fetchall(db::ass))
  echo '『没有任何分区！』';
else
{
foreach($qu as $qui) {
echo '『',$qui['name'],'』[br]';
$bk=$db->query("select id,name from ".DB_A."bk where name like '{$qui['name']}.%' order by youxian");
if(!$bk or !$bk=$bk->fetchall(db::ass))
  echo '没有任何版块！[br]';
else
{
$x=0;
$cnt=count($bk);
foreach($bk as $i=>$bki) {
$x++;
$name=explode('.',$bki['name']);
echo '<a href="[%cid].bk.[%pid]?bkid=',$bki['id'],'">',$name[1],'</a>';
if($i%2 or $x>=$cnt)
 echo '[br]';
else
 echo ' [or] ';
   }
 }
}
}
?>[hr]<a href="[cid].tz.[bid]?tzid=14335">论坛发贴版块选择指南</a>[br]<a href="cmmm.index.[bid]">免流资源发布专区</a>[br]
友链:[url=http://sz.tzhwap.com/]华智刷钻[/url]
[hr]
『<a href="[cid].bk.[bid]?bkid=0">最新帖子</a>[or]<a href="[cid].bk.[bid]?type=my">我的贴子</a>[or]<a href="msg.list.[bid]">我的内信</a>』[br]
<?php
          $tz=$db->query('select id,title from '.DB_A.'tz where bkid not in (select id from bk where notshow=1) order by fttime desc limit 15');
if(!$tz or !$tz=$tz->fetchall(db::ass))
 echo '论坛里还没有任何贴子，谁去发一个呢？[br]';
else
 foreach($tz as $i=>$tzi)
{echo $i+1,'. <a href="[%cid].tz.[%bid]?tzid=',$tzi['id'],'">',code::html(mb_substr($tzi['title'],0,20,'utf-8')),'</a>[br]';}
?>
[hr]<a href="index.index.[bid]">首页</a>-<a href="msg.liuyan.[bid]">留言</a>[br]
[time][foot]
[/html]