<?php
try{
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
if($touid=$_GET['touid'])
$uinfo=user::getinfobyuid($touid);
else{
$uinfo=user::getinfobyname($_GET['toname']);
$touid=$uinfo['uid'];
}
if(!$uinfo)
 throw new exception('抱歉，你不能给一个不存在的用户发信息。');
form::someinput_set(array('new'=>3));
if(!$_POST['go'])
{ ?>[html=给<%=$name=code::html($uinfo<('name')>)%>发信息]
<?php
form::start('post','read.php?[%%u.b]&[%%u.c]&[%%u.p]&touid='.$touid.($PAGE['u_sid'] ? '&sid='.$PAGE['sid'] : ''));
echo '正在给 ',$name,' 发信息......[br]';
/*echo '标题(可空):[br]';
form::input('title');echo '[br]';*/
echo '内容(多框输入):[br]';
form::someinput_put('nr');
form::submit('发送','go');
form::end();
echo '[hr]『用户信息』[br]用户ID：'.$touid.'[br]<a href="/wap/?id=bbs_bk_mt&amp;uid='.$touid.'">查看Ta的贴子</a>[br]个性签名：',code::html($uinfo['qianm']),'[br]联系方式：',code::html($uinfo['lianx']);
//var_dump($uinfo);
}else{
$title=trim($_POST['title']);
$nr=form::someinput_get('nr');
if(trim($nr)=='')
 throw new exception('抱歉，不能把空白的内容发给对方。');
if(msg::send($USER['uid'],$touid,$title,$nr))
{ ?>[html=发送成功]发送成功，信息已到达<%=code::html($uinfo['name'])%>的收件箱。<?php }
else
 throw new exception('抱歉，不知怎么了，你的信息无法正确送达。请稍后再试……');
}
}catch(exception $e){
?>[html=发生错误！]<?php
echo $e->getmessage();
}
?>[hr][read=,msg,chat&amp;touid=<%=$touid%>]聊天模式[/read]-[read=,msg,list]收件箱[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]