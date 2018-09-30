<?php
$d=str::word($_REQUEST['d']);
$z=floor($_REQUEST['z']) or $z=1;
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
exit;}
$rd=$db->prepare('select tit,uvm from '.DB_A.'book where zbm=? and zip=? order by uvm desc');
$rd->execute(array($d,$z));
$rd=$rd->fetchall(db::ass);
?>
[html=换版本看-<%=$bkname=code::html($bk<('book')>)%>]
[head]
<?php
if($cnt=count($rd))
{
 echo '《',$bkname,'》第',$z,'章 共有',count($rd),'个版本：[br]';
foreach($rd as $i=>$n)
 {
switch($n['uvm'])
{
default: echo '默认';
}
echo '. [%read=,book,read&amp;d=',$d,'&amp;z=',$z,'&amp;v=',$i+1,']',$n['tit'] ? code::html($n['tit']) : '无题','[/read]';
echo '[br]';
 }
}
?>[hr]
[read=,book,set&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]阅读设置[/read]
[read=,book,shuping&amp;d=<%=$d%>]书评[/read]
[read=,book,bookmark&amp;d=<%=$d%>]我的书签[/read][br]
[read=,book,list&amp;d=<%=$d%>&amp;p=<%=ceil($z/($set<('hang')>?$set<('hang')>:20))%>]目录[/read]-[read=,book,shuye&amp;d=<%=$d%>]封面[/read]-[read=,book,]小说阅读[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]