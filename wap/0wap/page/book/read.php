<?php
$p=floor($_REQUEST['p']) or $p=1;
$d=str::word($_REQUEST['d']);
$z=floor($_REQUEST['z']) or $z=1;
$v=floor($_REQUEST['v']) or $v=1;
$v--;
$db=db::conn('book');
$bk=$db->prepare('select book,zjs from '.DB_A.'title where zbm=?');
$bk->execute(array($d));
if(!$bk=$bk->fetch(db::ass))
{ ?>
[html=嗨，我们又见面了]
数据库说：“其实，我并不想总是看到你愁眉苦脸的样子，但是我还是要说：抱歉，我没有找到这本小说，可能它被其他人抢走了吧，对于管理不善我很抱歉。[br]或许，你可以[read=,book,title_j]添加这本小说[/read]，或者去[read=,book,]小说阅读首页[/read]重新搜索一下，也许它只是改名了呢。[br]祝阅读娱快[xor]_[xor]”[hr]
返回[read=,book,]小说阅读[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]
<?php
exit;
}
[%getuser]
if($USER['islogin'])
{
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
if(isset($set['size']))
 $size=$set['size'];
else
 $size=1000;
$dntxt=$set['dntxt'] or $dntxt=true;
}
else
{
$size=1000;
$dntxt=true;
}
$rd=$db->prepare('select tit,tnr from '.DB_A.'book where zbm=? and zip=? order by uvm desc');
$rd->execute(array($d,$z));
$rd=$rd->fetchall(db::ass);
?>
[html=<%=$tit=code::html($rd<($v)><('tit')> ? $rd<($v)><('tit')> : '无题'),'-',code::html($bk<('book')>)%>]
[head]
<%=$tit,'(',$z,'/',$bk['zjs'],'章)'%>[hr]
[read=,book,set&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]阅读设置[/read]
[read=,book,shuping&amp;d=<%=$d%>]书评[/read]
[read=,book,bookmark_j&amp;d=<%=$d%>&amp;z=<%=$z%>]存书签[/read]
[read=,book,uvm&amp;d=<%=$d%>&amp;z=<%=$z%>]版本(<%=count($rd)%>)[/read][hr]
<?php
$rd=$rd[$v];
if($dntxt)
 $rd['tnr']=html_entity_decode($rd['tnr'],ENT_QUOTES,'utf-8');
$len=mb_strlen($rd['tnr'],'utf-8');
if($len>0)
{
if(!$size)
 $la=1;
else
 $la=ceil($len/$size);
if($la==1)
 echo code::html($rd['tnr'],true),'[hr]';
else
 {
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$size;
echo code::html(mb_substr($rd['tnr'],$qi,$size,'utf-8'),true),'[hr]';
if($p<$la)
 echo '[%read=,book,read&amp;d=',$d,'&amp;z=',$z,'&amp;p=',$p+1,']下页[/read] ';
if($p>1)
 echo '[%read=,book,read&amp;d=',$d,'&amp;z=',$z,'&amp;p=',$p-1,']上页[/read] ';
echo $p,'/',$la,'页';
echo '[br]';
 }
}
else
 echo '抱歉，内容不存在[hr]';
if($z<$bk['zjs'])
 echo '[%read=,book,read&amp;d=',$d,'&amp;z=',$z+1,']下章[/read] ';
if($z>1)
 echo '[%read=,book,read&amp;d=',$d,'&amp;z=',$z-1,']上章[/read] ';
echo $z,'/',$bk['zjs'],'章';
if($bk['zjs']>2)
{ ?>
[br][form=get,read.php][h.b][h.c][h.p][h.sid][h=d]<%=$d%>[/h][input=z,2]<%=$z==$bk['zjs']?1:$bk['zjs']%>[/input][submit]跳章[/submit][/form]
<?php
}
?>
[hr][read=,book,list&amp;d=<%=$d%>&amp;p=<%=ceil($z/($set<('hang')>?$set<('hang')>:20))%>]目录[/read]-[read=,book,shuye&amp;d=<%=$d%>]封面[/read]-[read=,book,]小说阅读[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]