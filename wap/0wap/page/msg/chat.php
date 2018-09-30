[html=消息查看-聊天模式]<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$touid=$_GET['touid'];
if($_POST['goxx'])
 msg::send($USER['uid'],$touid,'',$_POST['nrxx']);
$p=$_GET['p'];
$p<1 && $p=1;
$uinfo=user::getinfobyuid($touid);
$uinfo=array($USER['uid']=>$USER['name'],$touid=>$uinfo['name']);
$msgall=msg::chat($USER['uid'],$touid,5,$p,$cnt);
$la=ceil($cnt/5);
echo '[%read=,msg,send&amp;touid=',$touid,']回复[/read][or]';
if($p>1)

 echo '[%read=,msg,chat&amp;touid=',$touid,'&amp;p=',$p-1,']上页[/read] ';
echo "共{$cnt}条,{$p}/{$la}页";
echo '[br]';
form::start('post',"read.php?bid={$PAGE['bid']}&cid={$PAGE['cid']}&pid={$PAGE['pid']}&touid=".$touid);
form::input('nrxx');
form::submit('快速回复','goxx');
form::submit('刷新');
form::end();
//var_dump($uinfo,$la,$msgall);
include FUNC_DIR.'/bbs_ubb.func.php';
foreach($msgall as $msg)
{
echo '[hr]';
if(!$msg['read'])
{
if($msg['uid']==$USER['uid'])
 {
msg::read($USER['uid'],$msg['id']);
echo '[新]';
 }
else
 echo '[对方未读]';
}
echo '来自：',$uinfo[$msg['byuid']],'[br]时间：',date('Y-m-d H:i:s',$msg['time']),'[br]'/*标题：',code::html($msg['title']),'[br]内容：'*/;
echo bbs_ubb(code::html($msg['nr']));
}
echo '[hr][%read=,msg,send&amp;touid=',$touid,']回复[/read][or]';
if($p<$la)
 echo '[%read=,msg,chat&amp;touid=',$touid,'&amp;p=',$p+1,']下页[/read] ';
echo "共{$cnt}条,{$p}/{$la}页";
?>[hr][read=,msg,list]收件箱[/read]-[read=,index,]首页[/read][br][time]
[foot][/html]