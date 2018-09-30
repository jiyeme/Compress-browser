<?php
$u=$_REQUEST['u'] or $u="read.php?bid={$PAGE['bid']}&cid=index&pid=index";
if(!$_REQUEST['go'])
{
?>
[html=用户登陆]
[tp]<?php
if($_REQUEST['must'])
 echo '你需要登陆后才能使用该功能';
else
 echo '嗨！朋友，好久不见，你在哪里？';
?>[/tp][hr]
[form=post,read.php?[u.b]&amp;cid=user&amp;pid=login]
[h=u]<?php echo code::html($u); ?>[/h]
用户名:[input=name][/input][br]
密码:[input=pass][/input][br]
[submit=go]登陆[/submit][anchor=post,登陆,read.php?[u.b]&amp;cid=user&amp;pid=login][post=name]$(name)[/post][post=pass]$(pass)[/post][post=go]true[/post][post=u]<%=code::html($u)%>[/post][/anchor][br]
如果没有用户名，你可以[url=/wap/?id=reg&amp;u=<%=urlencode($u)%>]注册[/url]一个
[/form]
<?php
}
else{
$u=code::html($u.'&sid=');
if(user::login($name,$pass,$uid,$sid,$err))
{
headecho::refresh(3,$u.$sid);
?>
[html=登陆成功]
<?php
echo "$name 欢迎回来！[br][xor]_[xor][br]3秒后<a href=\"$u$sid\">返回来源页</a>[br]『保存自动登陆书签』[br]请<a href=\"read.php?bid={$PAGE['bid']}&amp;sid=$sid\">点击此处</a>并存为书签，下次从书签进入即可自动登陆。";
}
else
{
?>
[html=登陆失败]
数据库说：“哎，出问题了：<?php
echo $err->msg;
echo '[br]请尝试返回重新登陆。[br]如果你不幸忘了密码，[%read=,user,lostpass]这里[/read]可以帮你找回它。”';
}
}
?>
[hr][tp][url=<?php echo code::html($u); ?>]返回[/url]-[read=,index,index]首页[/read][br]
[time][/tp][foot]
[/html]