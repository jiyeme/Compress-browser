[html=消息查看]<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$id=$_GET['id'];
if(!$msg=msg::view($USER['uid'],$id))
 echo '抱歉，消息不存在。';
else
{
echo '来自：',$msg['name'],'[br]时间：',date('Y-m-d H:i:s',$msg['time']),/*'[br]标题：',code::html($msg['title']),*/'[hr]';
include FUNC_DIR.'/bbs_ubb.func.php';
echo bbs_ubb(code::html($msg['nr']));
echo '[hr][%read=,msg,send&amp;touid=',$msg<('uid')>,']回复[/read][or][%read=,msg,chat&amp;touid=',$msg<('uid')>,']聊天模式[/read]';
msg::read($USER['uid'],$id);
}
?>[hr][read=,msg,list]收件箱[/read]-[read=,index,]首页[/read][br][time]
[foot][/html]