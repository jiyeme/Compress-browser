[html=远程登陆器]
<?php
$u=$_GET['u'];
$type=str::word($_REQUEST['type']);
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
[%head]
if(!$_POST['go'])
{
if(!$type)
{
$u='&amp;u='.urlencode($u).'&amp;type=';
?>
选择登陆位置：[br]
[read=,user,ssh<%=$u%>3g_qq_com]手机腾讯网(3g.qq.com)[/read][br]
[read=,user,ssh<%=$u%>qidian_cn]手机起点网(qidian.cn)[/read][br]
[read=,user,ssh<%=$u%>wap_139_10086_cn]139说客相册(wap.139.10086.cn)[/read]
<?php
}
else
{
?>
[form=post,read.php?[u.b]&amp;cid=user&amp;pid=ssh&amp;type=<%=$type%>&amp;u=<%=urlencode($u)%>]
<?php
$ssh['page']='form';
include ubb::page($PAGE['bid'],'user/ssh',$type.'.sub');
?>
[/form]
<?php
}
}
else
{
$ssh['page']='do';
$s=new session($USER['uid'],'cookies',3600*24*30,array($type),true);
;
$ssh['cookies']=$s[$type];
include ubb::page($PAGE['bid'],'user/ssh',$type.'.sub');
if($ssh['ok'])
{
$s[$type]=$ssh['cookies'];
echo '[hr]登陆成功，[url=',code::html($u),']点击返回来源页[/url]';
}
else
  echo '[hr]登陆失败，请返回重新登录，或[url=',code::html($u),']点击返回来源页[/url]';
}
?>
[hr]返回[url=<%=code::html($u)%>]来源页[/url]-[read=,index,]首页[/read][br][time][foot]
[/html]