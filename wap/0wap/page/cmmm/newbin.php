<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$dir=str::word($_REQUEST['dir']);
if(!$_POST['go'])
{
?>
[html=新建资源]
[head][form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>[u.sid]]
给你的资源取一个合适的名字吧:[br][input=name][/input][br]
写一段介绍，让别人更了解你的资源:[br][input=jies][/input][br]
你的资源有多少分卷呢:[br][input=juan]1[/input][br]
[submit=go]新建[/submit][anchor=post,新建,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>[u.sid]][pst=name][/anchor]
[/form]
<?php
}
else
{
$eid=url::b64e(pack('L',time()));
$title=trim($_POST['name']) or $title='新建资源';
$s=new session(0,'cmmm.'.$dir,0,array($eid));
$s[$eid]=array(
'title'=>$title,
'jies'=>$_POST['jies'],
'uid'=>$USER['uid'],
);
headecho::location($_REQUEST['u'] ? $_REQUEST['u'].'&dir='.$eid : 'read.php?[%%u.b]&cid=cmmm&pid=addjuan&dir='.$dir.'&eid='.$eid.'&juan='.floor($_POST['juan']).'[%%u.sid]',true);
}
?>[hr]返回[read=,cmmm,index]免流专区[/read]-[read=,index,]首页[/read]
[br][time][foot]
[/html]