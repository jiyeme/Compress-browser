[html=发件箱]
<?php [%getuser] ?>
[read=,msg,list2]全部[/read][or][read=,msg,list2&amp;read=0]对方未读[/read][or][read=,msg,list2&amp;read=1]对方已读[/read][or][read=,msg,send&amp;touid=<%=$USER<('uid')>%>]发给自己[/read][hr]
<?php
if(!$USER['islogin'])
 headecho::gotologin('',true);
$read=$_GET['read'];
if($read=='')
 $read=null;
echo '『',$read===null ? '全部' : ($x=($read ? '对方已读' : '对方未读')),'消息』[br]';
$p=$_GET['p'] or $p=1;
$cnt=msg::count($USER['uid'],$read,true);
$la=ceil($cnt/20);
if($p>$la) $p=$la;
$qi=($p-1)*20;
$msg=msg::getlist2($USER['uid'],$qi,20,$read);
if(!$msg)
{
 echo '发件箱里没有',$x,'消息。';
}
else
{
$i=$qi;
foreach($msg as $id=>$v)
 {
$i++;
$uinfo=user::getinfobyuid($v['uid']);
echo $i,'、',$v['read'] ? '（对方已读）' : '（对方未读）',$uinfo['name'],'：“[%read=,msg,view2&amp;id=',$id,']',code::html($v['title']),'[/read]”',date('（m-d H:i）',$v['time']),'[br]';
 }
if($p<$la)
 echo '[%read=,msg,list2&amp;read=',$read,'&amp;p=',$p+1,']下页[/read]';
if($p<1)
 echo ' [%read=,msg,list2&amp;read=',$read,'&amp;p=',$p-1,']上页[/read]';
echo " {$p}/{$la}页";
}
?>[hr][time][br]
[read=,msg,list]收件箱[/read]-[url=../?id=bbs&amp;sid=<%=$PAGE<('sid')>%>]论坛[/url]-[read=,index,]首页[/read]
[foot][/html]