[html=文件断点续传]
[head]
<?php
if(!$USER['islogin'])
 headecho::gotologin('',true);
$dir=$_REQUEST['dir'];
$eid=$_REQUEST['eid'];
$s=new session($USER['uid'],'dir.'.$dir,3600,array($eid));
$e=$s[$eid];
$fname=USERFILE_DIR."/{$USER['uid']}/$dir/$eid.gz";
if(!$_REQUEST['go'])
{ ?>
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>[u.sid]]
你正在断点续传文件“<%=$e['title']%>”（<%=fat32_f::echosize(filesize($fname))%>）。注意：有些资源是不支持断点续传的。[br]
续传URL（同下载URL。你也可以选择相同文件的其他下载地址续传）:[br][input=url]http://[/input][br]
[submit=go]提交[/submit]
[/form]
<?php }
else
{
$s=new session($USER['uid'],'timedo',3600*2,'');
if(count($s)>=3)
 die('你的任务队列已满！最多只支持3条任务。[/html]');
$s[time()]=array(
'type'=>'downlist',
'title'=>$e['title'],
'url'=>str_replace('&amp;','&',$_REQUEST['url']),
'dir'=>$dir,
'ztn'=>2,
'name'=>$eid
);
echo '续传任务已添加。[%read=,fat32,downlist_k]查看[/read]';
}
?>
[hr][read=,fat32,bin_tool&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>]返回[/read]-[read=,index,]首页[/read][br][time][foot][/html]