<?php
$db=db::conn('book');
$d=str::word($_REQUEST['d']);
$rs=$db->query("select book,zjs,zzn from ".DB_A."title where zbm='$d' limit 1");
if(!$rs or !$rs=$rs->fetch(db::ass))
{
?>
[html=错误！]
数据库说：“嗯……不好意思，我找了半天都没有发现你要新增章节的那本书。你真的确定本站有那本书吗？[br]或许，你可以先[read=,book,title_j]添加一本空白的书[/read]，再来新增章节。如果你要写书，请去[read=,book,xie]作者专区[/read]。”[hr]
[read=,index,]返回首页[/read][br][time]
[/html]
<?php
exit;
}
?>
[html=<%=code::html($rs<('book')>)%>-章节采集器]
<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
[%head]
if(!$_POST['go'])
{
?>
[form=post,read.php?[u.b]&amp;cid=book&amp;pid=cai_j&amp;d=<%=$d%>]
采集位置：[br]
[sel=type][op=cmread_com]中国移动手机阅读[/op][op=qidian_cn]手机起点网[/op][op=auto]自动分析[/op][/sel][br]
起始地址：[br][input=url]http://[/input][br]
章号：起:[input=qi,2]<%=$rs['zjs']+1%>[/input]/共[input=gong,2]10[/input]章[br]
[h.sid]
[submit=go]开始[/submit][anchor=post,开始,read.php?[u.b]&amp;cid=book&amp;pid=cai_j&amp;d=<%=$d%>[u.sid]][post=type]$(post)[/post][post=url]$(url)[/post][post=qi]$(qi)[/post][post=gong]$(gong)[/post][post=go]1[/post][/anchor]
[/form]
[hr]『采集说明』[br]1.如果采集需要登录，请先点这里[read=,user,ssh&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]登录采集网站[/read]。[br]2.请提交一个阅读页地址，不能是章节目录。[br]3.请设为整章阅读，或确保每页都是整章（中国移动手机阅读的采集不用）。系统暂不能处理分页。[br]4.如果“共*章”留空，系统将从起始地址开始自动采集所有章节（采集付费章节的注意）！
<?php
}
else
{
$s=new session($USER['uid'],'book_cai',6400);
;
$s[$tm=time()]=array(
'd'=>str::word($_GET['d']),
'type'=>str::word($_POST['type']),
'url'=>$_POST['url'],
'qi'=>floor($_POST['qi']),
'gong'=>floor($_POST['gong']),
'uid'=>$USER['uid']
);
unset($s);
$h=new httplib;
$h->open($u="http://$_SERVER[HTTP_HOST]:$_SERVER[SERVER_PORT]".dirname($_SERVER['REQUEST_URI']).'/read.php?bid=xhtml&cid=book&pid=cai_do&doid='.$tm.'&uid='.$USER['uid'],10,0);
if($h->send())
  echo '程序正在努力采集中，采集结果自动添加到章节列表中，请返回[%read=,book,list&amp;d=',$d,']目录[/read]查看采集情况。[br]为了节省资源，希望你不要反复提交同一任务，以免导致重复采集。';
else
  echo '采集任务打开失败，请返回重新提交，[br]或者[url=',code::html($u),']去这里[/url]手动开始任务（页面会连接超时，但会成功的，希望你不要反复刷新，以免导致重复采集）。';
echo '[br]采集任务10分钟后会过期，这时如还没有任何进展，请重新提交任务。';
}
?>
[hr]返回[read=,book,list&amp;d=<%=$d%>]目录[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]