<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
if(!$_POST['go'])
{
?>
[html=新建分类]
[head]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($_REQUEST<('u')>);%>[u.sid]]
给你的分类取一个合适的名字吧:[br][input=name][/input][br]
写一段介绍，让别人更了解你的分类:[br][input=jies][/input][br]
[submit=go]新建[/submit][anchor=post,新建,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($_REQUEST<('u')>)%>[u.sid]][pst=name][/anchor]
[/form]
<?php}
else
{
$eid=url::b64e(pack('L',time()));
$title=trim($_POST['name']) or $title='新建分类';
$s=new session(0,'cmmm',0,array($eid));
$s[$eid]=array(
'title'=>$title,
'jies'=>$_POST['jies'],
'uid'=>$USER['uid'],
);
headecho::location($_REQUEST['u'] ? $_REQUEST['u'].'&dir='.$eid : 'read.php?[%%u.b]&cid=fat32&pid=bin_dir[%%u.sid]',true);
}
?>[hr]返回[read=,cmmm,index]免流专区[/read]-[read=,index,]首页[/read]
[br][time][foot]
[/html]