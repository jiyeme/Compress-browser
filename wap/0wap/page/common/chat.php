<?php
if(!$USER['islogin'])
 return;
$chat_s=new session($USER['uid'],'set',0,'chat');
$chat_set=$chat_s['set'] or $chat_set=array('open'=>true,'limit'=>3);

$s=new session(0,'chat',3600,'order by name desc limit '.$chat_set['limit']);

