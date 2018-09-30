<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$xxdir=$_REQUEST['xx']=='text';
?>
[html=<%=$xxdir ? '选择文本' : '我的文本'%>]
[head]
<?php if(!$xxdir) { ?>
文本-[read=,fat32,bin_dir]文件[/read][hr]
<?php
}
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$cnt=session::zucount($USER['uid'],'text');
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new session($USER['uid'],'text',3600*24*30,"limit $qi,$hang");
$selfuu=urlencode($_SERVER['REQUEST_URI']);
if($cnt>0)
{
echo '共',$cnt,'段文本([%read=,fat32,del&amp;type=text&amp;u=',$selfuu,']清空[/read][or][%read=,fat32,newtext&amp;u=',$selfuu,']新建[/read])[br][br]';
if($xxdir)
 echo $_REQUEST['texttitle'] ? code::html(url::b64d($_REQUEST['title'])) : '请选择一段文本','[br]';
$u=$_REQUEST['u'];
$jc=$qi;
foreach($s as $eid=>$val)
{
$jc++;
echo $jc,'. ',$xxdir ? '[url='.code::html($u).'&amp;eid='.$eid.']'.code::html($val['title']).'[/url]' : '[%%read=,fat32,bin_dir&amp;xx=dir&amp;dirtitle='.url::b64e('请选择保存文件夹').'&amp;u='.urlencode('read.php?[%%u.b]&cid=edit&pid=text_tobin&eid='.$eid).']'.code::html($val['title']).'[/read]';
if(!$xxdir)
echo '([%read=,edit,edit&amp;eid=',$eid,']编辑[/read][or][%read=,fat32,text_tool&amp;eid=',$eid,']工具[/read][or][%read=,fat32,del&amp;type=text&amp;eid=',$eid,']删除[/read])';
echo '[br]';
}
if($xxdir)
 $uu='&amp;xx=text&amp;u='.urlencode($u).'&amp;texttitle='.urlencode($_REQUEST['texttitle']);
else
 $uu='';
if($p<$la)
 echo '[%read=,fat32,text_list&amp;p=',$p+1,$uu,']下页[/read]';
if($p>1)
 echo ' [%read=,fat32,text_list&amp;p=',$p-1,$uu,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '数据库说：如果你是新人，欢迎使用虎绿林文件系统，你可以去[%read=,fat32,newtext&amp;u=',$selfuu,']新建一段文本[/read]，或者去[%read=,help,fat32_bin_dir]虎绿林帮助中心[/read]转转。[br]如果你是来找文本的，那么很遗憾的告诉你：你的所有文本都丢失了。或者，你可以到[%read=,fat32,dir_list]文件区[/read]看看，或许会有所发现。';
?>
[hr]返回[read=,index,]首页[/read][br][time][foot]
[/html]