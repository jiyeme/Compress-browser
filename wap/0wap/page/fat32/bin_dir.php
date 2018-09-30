<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$xxdir=$_REQUEST['xx'];
?>
[html=<%=$xxdir ? '选择文件夹' : '我的文件夹'%>]
[head]
<?php if(!$xxdir) { ?>
[read=,fat32,text_list]文本[/read]-文件[hr]
<?php
}
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$cnt=session::zucount($USER['uid'],'dir');
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new session($USER['uid'],'dir',3600*24*30,"limit $qi,$hang");
$selfuu=urlencode($_SERVER['REQUEST_URI']);
if($cnt>0)
{
echo '共',$cnt,'个文件夹([%read=,fat32,del&amp;type=dir&amp;u=',$selfuu,']清空[/read][or][%read=,fat32,newdir&amp;u=',$selfuu,']新建[/read])[br][br]';
if($xxdir)
 echo $_REQUEST['dirtitle'] ? code::html(url::b64d($_REQUEST['dirtitle'])) : '请选择一个文件夹','[br]';
$u=$_REQUEST['u'];
$jc=$qi;
if($xxdir=='bin')
 $binuu='&amp;xx=bin&amp;u='.urlencode($u).'&amp;bintitle='.urlencode($_REQUEST['bintitle']);
else
 $binuu='';
foreach($s as $eid=>$val)
{
$jc++;
echo $jc,'. ',$xxdir=='dir' ? '[url='.code::html($u).'&amp;dir='.$eid.']'.code::html($val['title']).'[/url]' : '[%%read=,fat32,bin_list&amp;dir='.$eid.$binuu.']'.code::html($val['title']).'[/read]';
if(!$xxdir)
echo '([%read=,fat32,bin_dir_tool&amp;dir=',$eid,']工具[/read][or][%read=,fat32,del&amp;type=dir&amp;dir=',$eid,']删除[/read])';
echo '[br]';
}
if($xxdir)
 $uu='&amp;xx='.$xxdir.'&amp;u='.urlencode($u).'&amp;dirtitle='.urlencode($_REQUEST['dirtitle']).'&amp;bintitle='.urlencode($_REQUEST['bintitle']);
else
 $uu='';
if($p<$la)
 echo '[%read=,fat32,bin_dir&amp;p=',$p+1,$uu,']下页[/read]';
if($p>1)
 echo ' [%read=,fat32,bin_dir&amp;p=',$p-1,$uu,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '数据库说：如果你是新人，欢迎使用虎绿林文件系统，你可以去[%read=,fat32,newdir&amp;u=',$selfuu,']新建一个文件夹[/read]用来保存文件，或者去[%read=,help,fat32_bin_dir]虎绿林帮助中心[/read]转转。[br]如果你是来找文件的，那么很遗憾的告诉你：我没有发现任何属于你的文件夹，这意味着你的所有文件都丢失了。或者，你可以到[%read=,fat32,text_list]文本文件区[/read]看看，或许会有所发现。';
?>
[hr]返回[read=,index,]首页[/read][br][time][foot]
[/html]