<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$dir=str::word($_REQUEST['dir']);
$dirinfo=new session($USER['uid'],'urlbook',0,array($dir),false);
$dirinfo=$dirinfo[$dir];
$count=0;
$cnt=session::zucount($USER['uid'],'urlbook.'.$dir);
$info=new session($USER['uid'],'urlbook.'.$dir,'',false);
foreach($info as $infoi)
 {$count+=$infoi['count'];}
?>
[html=地址簿工具-<%=$name=code::html($dirinfo<('title')>)%>]
[head]
<?php
echo '[%read=,urlbook,index]地址簿[/read]&lt;',$name;
echo '[br]共',$cnt,'张名片,',$count,'条信息[br][%read=,urlbook,del&amp;type=dir&amp;dir=',$dir,']删除[/read][or][%read=,urlbook,card&amp;dir=',$dir,']查看[/read][or][%read=,urlbook,dir_kan&amp;dir=',$dir,']浏览[/read][or][%read=,urlbook,dir_down&amp;dir=',$dir,']下载[/read][hr]';
?>
[form=post,read.php?[u.b]&amp;[u.p]&amp;pid=dir_gai&amp;dir=<%=$dir%>]
名称:[input=name]<%=code::html($dirinfo['title'])%>[/input][br]
备注(多框稍后加):[input=extra]<%=code::html($dirinfo['extra'])%>[/input][br]
[submit=go]提交[/submit]
[/form]
[hr]返回[read=,urlbook,index]我的地址簿[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]