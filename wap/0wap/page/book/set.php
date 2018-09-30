<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$s=new session($USER['uid'],'set',0,array('page'));
$set=$s['page'];
$u=$_REQUEST['u'];
if(!$_POST['go'])
{
?>
[html=阅读设置]
[head]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($u)%>]
[read=,help,read_set]帮助中心_阅读设置[/read][br]
章节目录每页显示条数:[input=hang,3]<%=$set['hang']?$set['hang']:20%>[/input][br]
阅读分页字数:[input=size,3]<%=isset($set['size']) ? $set['size'] : 1000%>[/input][br]
(留空或为0则不分页)[br]
去除代码:[sel=dntxt][op=1]是[/op][op=0]否[/op][/sel][br](如果你在阅读时看到了烦人的 &amp; # quote ;  &amp; # 39 ; 之类的字符，请开启它。)[br]
[submit=go]提交[/submit][anchor=post,提交,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;u=<%=urlencode($u)%>][post=go]1[/post][pst=hang][pst=size][pst=dntxt][/anchor]
[/form]
[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,book,]小说阅读[/read] [read=,index,]首页[/read][br][time][foot]
[/html]
<?php
}
else
{
$s['page']=array(
'hang'=> (($hang=floor($_POST['hang']))>0 ? $hang : 20),
'size'=> (($size=floor($_POST['size']))>=0 ? $size : 'all'),
'dntxt'=>floor($_POST['dntxt']),
);
if(!$u)
 $u='read.php?[%%u.b]&cid=book&pid=index[%%u.sid]';
headecho::location($u,true);
}
?>