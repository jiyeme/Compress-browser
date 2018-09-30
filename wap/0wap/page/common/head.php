<?php
if(!$USER['islogin'])
 $USER['islogin']=user::islogin($USER['name'],$USER['pass'],$USER['uid'],$USER['sid'],$USER['err'],$USER);
$USER['setinfo']=unserialize($USER['setinfo']);
//var_dump($USER);
if($USER['notshow'])
 {
$USER['notshow']=false;
return;
 }
echo '[tp]';
if($USER['islogin'])
{
if($USER['msgcnt']=msg::count($USER['uid'],0))
 echo '[%read=,msg,list&amp;read=0]收到',$USER['msgcnt'],'条新消息[/read][br]';
echo '[%read=,user,index]',$USER['name'],'[/read][[%read=,user,exit]退出[/read]]';
}
elseif($USER['err']->code==5)
 echo '你的身份过期了，需要[%read=,user,login&amp;u=',urlencode($_SERVER<('REQUEST_URI')>),']重新登陆[/read]';
else
 echo '游客 你好，为了正常使用本站的全部功能，建议你[%read=,user,login&amp;u=',urlencode($_SERVER<('REQUEST_URI')>),']马上登陆[/read]';
echo '[/tp][hr]';
?>