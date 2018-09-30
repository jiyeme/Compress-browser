<?php
$p=floor($_REQUEST['p']) or $p=1;
$d=str::word($_REQUEST['d']);
$db=db::conn('book');
$bk=$db->prepare('select book,zzn,tyn,zjs from '.DB_A.'title where zbm=?');
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
$hang=$set['hang'] or $hang=20;
}
else
 $hang=20;
$la=ceil($bk['zjs']/$hang);
$qi=($p-1)*$hang+1;
$zhi=$qi+$hang;
if($p<0 or $p>$la) $p=$la;
?>
[html=<%=$book=code::html($bk<('book')>)%>-目录]
[head]
<%=$book,'-',code::html($bk['zzn']),'(共',$bk['zjs'],'章)'%>[hr]
[read=,book,set&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]阅读设置[/read]
[read=,book,shuping&amp;d=<%=$d%>]书评[/read]
[read=,book,bookmark&amp;d=<%=$d%>]书签[/read]
[read=,book,gen&amp;d=<%=$d%>]更新[/read][hr]
<?php
if($la>0)
{
$sql='select tit from '.DB_A.'book where zbm=? and zip=? order by uvm desc';
$rs=$db->prepare($sql);
for($i=$qi;$i<$zhi&&$i<=$bk['zjs'];$i++)
{
$rs->execute(array($d,$i));
$title=$rs->fetchall(db::ass);
$uvm=count($title);
$title=$title[0]['tit'];
echo $i,'. [%read=,book,read&amp;d=',$d,'&amp;z=',$i,']',code::html($title),'[/read]';
if($uvm!==1)
 echo '([%read=,book,uvm&amp;d=',$d,'&amp;z=',$i,']',$uvm,'[/read])';
echo '[br]';
}
echo '[hr]';
if($p<$la)
 echo '[%read=,book,list&amp;d=',$d,'&amp;p=',$p+1,']下页[/read] ';
if($p>1)
 echo '[%read=,book,list&amp;d=',$d,'&amp;p=',$p-1,']上页[/read] ';
echo $p,'/',$la,'页';
if($la>2)
{ ?>
[br][form=get,read.php][h.b][h.c][h.p][h.sid][h=d]<%=$d%>[/h][input=p,2]<%=$p==$la?1:$la%>[/input][submit]跳页[/submit][/form]
<?php }
#if($la>0)结束
}
?>
[hr][read=,book,shuye&amp;d=<%=$d%>]封面[/read]-[read=,book,]小说阅读[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]