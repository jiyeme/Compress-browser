<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$xxdir=$_REQUEST['xx']=='bin';
$dir=str::word($_REQUEST['dir']);
$dirinfo=new session($USER['uid'],'dir',0,array($dir));
if(!$dirinfo=$dirinfo[$dir])
{ ?>
[html=文件夹不存在]
文件系统说：“抱歉，你要进入的文件夹已经不在了，可能是之前被你删除了。[br]
请[read=,fat32,bin_dir<%=$xxbin ? '&amp;xx=bin&amp;u='.urlencode($_REQUEST<('u')>) : ''%>]返回重新选择一个文件夹[/read]，愿你娱快。”
[hr]返回[read=,index,]首页[/read][br][time][/html]
<?php exit; } ?>
[html=<%=$xxdir ? '选择文件-' :'我的文件-',$dirname=code::html($dirinfo<('title')>)%>]
[head]
<?php if(!$xxdir) { ?>
[read=,fat32,text_list]文本[/read]-[read=,fat32,bin_dir]文件夹[/read]&gt;<%=$dirname%>-[read=,fat32,downlist_k]队列[/read][hr]
<?php
}
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$cnt=session::zucount($USER['uid'],'dir.'.$dir);
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new fat32_f($USER['uid'],'dir.'.$dir,3600*24*30,"limit $qi,$hang");
$selfuu=urlencode($_SERVER['REQUEST_URI']);
if($cnt>0)
{
echo '共',$cnt,'个文件([%read=,fat32,del&amp;type=bin&amp;dir='.$dir.'&amp;u=',$selfuu,']清空[/read][or][%read=,fat32,newbin&amp;dir=',$dir,'&amp;u=',$selfuu,']新建[/read])[br][br]';
if($xxdir)
 echo $_REQUEST['bintitle'] ? code::html(url::b64d($_REQUEST['bintitle'])) : '请选择一个文件','[br]';
$u=$_REQUEST['u'];
$jc=$qi;
foreach($s as $eid=>$val)
{
$fname=$val['title'];
/*if($val['type']!='')
 $fname.='.'.$val['type'];*/
$jc++;
echo $jc,'. ',$xxdir ? '[url='.code::html($u).'&amp;dir='.$dir.'&amp;eid='.$eid.']'.code::html($fname).'[/url]' : '[%%read=,fat32,download&amp;uid='.$USER<('uid')>.'&amp;dir='.$dir.'&amp;eid='.$eid.']'.code::html($fname).'[/read]';
if(!$xxdir)
echo '([%read=,fat32,bin_tool&amp;dir=',$dir,'&amp;eid=',$eid,']工具[/read]'./*[%read=,tool,bin_to139photo4&amp;dir=',$dir,'&amp;eid=',$eid,']4[/read][%read=,tool,bin_to139photo5&amp;dir=',$dir,'&amp;eid=',$eid,']5[/read][%read=,tool,bin_to139photo6&amp;dir=',$dir,'&amp;eid=',$eid,']6[/read]*/'[or][%read=,edit,bin_totext&amp;dir=',$dir,'&amp;eid=',$eid,']编辑[/read][or][%read=,fat32,del&amp;type=bin&amp;dir=',$dir,'&amp;eid=',$eid,']删除[/read])';
echo '[br]';
}
if($xxdir)
 $uu='&amp;xx=bin&amp;u='.urlencode($u).'&amp;bintitle='.urlencode($_REQUEST['bintitle']);
else
 $uu='';
if($p<$la)
 echo '[%read=,fat32,bin_list&amp;p=',$p+1,'&amp;dir=',$dir,$uu,']下页[/read]';
if($p>1)
 echo ' [%read=,fat32,bin_list&amp;p=',$p-1,'&amp;dir=',$dir,$uu,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '数据库说：如果你是新人，欢迎使用虎绿林文件系统，你可以去[%read=,fat32,newbin&amp;dir=',$dir,'&amp;u=',$selfuu,']新建一个文件[/read]用来保存文件，或者去[%read=,help,fat32_bin]虎绿林帮助中心[/read]转转。[br]如果你是来找文件的，那么很遗憾的告诉你：我没有在这个文件夹里发现任何属于你的文件。或者，你可以到[%read=,fat32,text_list]文本文件区[/read]看看，或许会有所发现。';
?>
[hr]返回[read=,index,]首页[/read][br][time][foot]
[/html]