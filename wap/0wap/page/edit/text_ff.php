<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$u=$_REQUEST['u'];
$eid=str::word($_REQUEST['eid']);
$ff=$_REQUEST['ff'];
$s=new session($USER['uid'],'text',3600*5*24,array($eid),false);
if(!$e=$s[$eid])
{ ?>
[html=出错啦！]
数据库：抱歉，我找不到你的文本，可能过期了。[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,index,]首页[/read][br][time][foot]
[/html]
<?php
exit;
}
switch($ff)
{
case 'xmltotext':
 $e['value']=xml::totext($e['value']);
break;
}
$s[$eid]=$e;
headecho::location($u,true);
?>