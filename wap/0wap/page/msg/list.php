[html=收件箱]
<?php [%getuser] ?>
[tp][read=,msg,list]全部[/read][or][read=,msg,list&amp;read=0]未读[/read][or][read=,msg,list&amp;read=1]已读[/read][or][read=,msg,send&amp;touid=<%=$USER<('uid')>%>]发给自己[/read][or][url=/wap/?id=atinfo&amp;read=1]@消息[/url][/tp][hr]
<?php
if(!$USER['islogin'])
 headecho::gotologin('',true);
$read=$_GET['read'];
if($read=='')
 $read=null;
echo '[div=bk2]『',$read===null ? '全部' : ($x=($read ? '已读' : '未读')),'消息』[/div][whr]';
$p=$_GET['p'] or $p=1;
$cnt=msg::count($USER['uid'],$read);
$la=ceil($cnt/20);
if($p>$la) $p=$la;
$qi=($p-1)*20;
$msg=msg::getlist($USER['uid'],$qi,20,$read);
if(!$msg)
{
 echo '收件箱里没有',$x,'消息。';
}
else
{
$i=$qi;
foreach($msg as $id=>$v)
 {
$i++;
$uinfo=user::getinfobyuid($v['uid']);
echo '[div=bk',$i%2?'1':'2',']',$i,'、',$v['read'] ? '（已读）' : '（未读）',$uinfo['name'],'：“[%read=,msg,view&amp;id=',$id,']',code::html($v['title']),'[/read]”',date('（m-d H:i）',$v['time']),'[/div][whr]';
 }
echo '[div=bk1]';
if($p<$la)
 echo '[%read=,msg,list&amp;read=',$read,'&amp;p=',$p+1,']下页[/read]';
if($p<1)
 echo ' [%read=,msg,list&amp;read=',$read,'&amp;p=',$p-1,']上页[/read]';
echo " {$p}/{$la}页";
}
?>[/div][hr][div=bk2][time][br]
[read=,msg,list2]发件箱[/read]-[url=../?id=bbs&amp;sid=<%=$PAGE<('sid')>%>]论坛[/url]-[read=,index,]首页[/read][/div]
[foot][/html]