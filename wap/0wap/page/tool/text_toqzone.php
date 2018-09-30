<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$u=$_REQUEST['u'];
$eid=str::word($_REQUEST['eid']);
$s=new session($USER['uid'],'text',3600*5*24,array($eid),false);
$input=new session($USER['uid'],'input',3600*24*5,array('qzone_cat'));
if(!$e=$s[$eid])
{ ?>
[html=出错啦！]
数据库：抱歉，我找不到你的文本，可能过期了。[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,index,]首页[/read][br][time][foot]
[/html]
<?php
exit;
}
if(!$_POST['go'])
{
?>
[html=<%=$title=code::html($e<('title')>)%>-发到QQ空间]
[head]
你正在把文章“<%=$title%>”发布到你的QQ空间。如果你第一次使用它，请先[read=,user,ssh&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]远程登陆QQ空间[/read][hr]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;eid=<%=$eid%>]
分类:[input=cat]<%=code::html($input['qzone_cat'])%>[/input][br]
标题:[input=title]<%=$title%>[/input][br]
[submit=go]发表[/submit][read=,edit,edit&amp;eid=<%=$eid%>]我再改改[/read]
[/form]
<?php
}
else
{
?>
[html=发到Q空间-状态]
已经把文章提交到了QQ空间，至于是否成功……请看下文：[hr]
<?php
$qq=new session($USER['uid'],'cookies',0,array('3g_qq_com'),false);
$qq=$qq['3g_qq_com'];
$url="http://blog.z.qq.com/infocenter/add_blog_action.jsp?sid=$qq[sid]";
$h=new httplib;
$h->open($url,10,5);
$h->post('cat',xml::空格转换($cat=$_POST['cat']));
$input['qzone_cat']=$cat;
$h->post('title',xml::空格转换($_POST['title']));
$h->post('content',xml::空格转换($e['value']));
$h->post('g_f',time());
$h->send();
$xml=$h->response();
$xml=substr($xml,strpos($xml,'-->')+3);
$xml=substr($xml,0,strpos($xml,'<!--'));
echo $xml ? $xml : '呃，似乎是连接失败，什么都没有。这种情况可能经常发生（特别是网络很卡时），请尝试重新提交。不过还是先[url=http://blog.z.qq.com/blog.jsp?B_UID='.$qq<('qq')>.'&amp;sid='.$qq<('sid')>.']去QQ空间看看吧[/url]，也许已经发表成功了。';
}
?>
[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,fat32,text_list]我的文本[/read] [read=,index,]首页[/read][br]
[time][foot]
[/html]