<?php
[%getuser]
!$USER['islogin'] && headecho::gotologin('',true);
?>
[html=修改用户名]
[head]
<?php
if(!$_POST['go'])
{ ?>
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p][u.sid]]
我要改名为[input=name][/input][br]
[submit=go]确定[/submit]
[/form]
<?php }
else
{
try
 {
$name=$_POST['name'];
if(!$name or $name=='匿名' or strlen($name)>100)
 throw new exception('抱歉，你不能没有名字，你的名字也不能叫做0，或者“匿名”。你的名字也不能太长！');
if(!user::用户名合法吗($name))
 throw new exception('抱歉，系统不支持你起这样的名字。你只能起包含中文、字母、数字、_和-的名字。');
$db=user::conn();
$sql="select uid from ${DB_A}user where name=?";
$rs=$db->prepare($sql);
$rs->execute(array($name));
if($nr=$rs->fetch(db::ass))
 throw new exception('抱歉，这个用户名已经被人用了，Ta的uid是'.$nr['uid']);
$sql="update ${DB_A}user set name=? where uid=?";
$rs=$db->prepare($sql);
if($rs->execute(array($name,$USER['uid'])))
 echo "从这一刻起，{$USER['name']} 改名为 {$name}";
else
 echo '悲剧，不知怎么了，你改名失败了。请重试。';
 }
catch(exception $e)
{
echo $e->getmessage();
}
}
?>
[hr][read=,user,]返回[/read]-[read=,index,]首页[/read]
[time][foot]
[/html]