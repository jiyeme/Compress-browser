<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$s=new session($USER['uid'],'set',3600*24*5,array('ad'));
$u=$_REQUEST['u'];
if(!$_POST['go'])
{
?>
[html=广告设置]
[head]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($u)%>]
用户登陆后，广告默认开启。如果你不想看到它们，请在这里选择关闭。[br]
顶部广告:[sel=top][op=0]关[/op][op=1]开[/op][/sel][br]
底部广告:[sel=bottom][op=0]关[/op][op=1]开[/op][/sel][br]
其他位置广告:[sel=other][op=0]关[/op][op=1]开[/op][/sel][br]
[submit=go]提交[/submit][anchor=post,提交,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($u)%>][post=go]1[/post][pst=top][pst=bottom][pst=other][/anchor][/form]
[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,index,]首页[/read][br][time][foot]
[/html]
<?php
}
else
{
$s['ad']=array(
'top'=> $_POST['top'] ? 1 : 0,
'bottom'=> $_POST['bottom'] ? 1 : 0,
'other'=>$_POST['other'] ? 1 : 0,
);
if(!$u)
 $u='read.php?[%%u.b]&cid=index&pid=index[%%u.sid]';
headecho::location($u,true);
}
?>