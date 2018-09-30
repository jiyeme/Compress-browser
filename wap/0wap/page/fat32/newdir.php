<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
if(!$_POST['go'])
{
?>
[html=新建文件夹]
[head]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($_REQUEST<('u')>);%>[u.sid]]
给你的文件夹取一个合适的名字吧:[br][input=name][/input][br]
[submit=go]新建[/submit][anchor=post,新建,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($_REQUEST<('u')>)%>[u.sid]][pst=name][/anchor]
[/form]
<?php
}
else
{
$eid=url::b64e(pack('L',time()));
$title=$_POST['name'] or $title='新建文件夹';
$s=new session($USER['uid'],'dir',0,array($eid));
$s[$eid]=array(
'title'=>$title,
);
fat32_f::mkdir(USERFILE_DIR.'/'.$USER['uid'].'/'.$eid,0777);
headecho::location($_REQUEST['u'] ? $_REQUEST['u'].'&dir='.$eid : 'read.php?[%%u.b]&cid=fat32&pid=bin_dir[%%u.sid]',true);
}
?>[hr]返回[read=,fat32,bin_dir]我的文件夹[/read]-[read=,index,]首页[/read]
[br][time][foot]
[/html]