<?php
[%getuser]
$dir=str::word($_REQUEST['dir']);
$dirinfo=new session(0,'cmmm',0,array($dir));
if(!$dirinfo=$dirinfo[$dir])
{ ?>
[html=分类不存在]
数据库说：“抱歉，你要进入的分类已经不在了，可能是被原主人删除了。[br]
请[read=,cmmm,index]返回重新选择一个分类[/read]，愿你娱快。”
[hr]返回[read=,index,]首页[/read][br][time][/html]
<?php exit; } ?>
[html=<%=$dirname=code::html($dirinfo<('title')>)%>-免流资源]
[head]
<?php if(!$xxdir) { ?>
[read=,cmmm,index]免流资源[/read]&gt;<%=$dirname%>[br]
介绍：<%=code::html($dirinfo<('jies')>)%>[hr]
<?php
}
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];$hang=$set['hang'] or $hang=20;
$cnt=session::zucount(0,'cmmm.'.$dir);
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new fat32_f(0,'cmmm.'.$dir,3600*24*30,"order by id desc limit $qi,$hang");
$selfuu=urlencode($_SERVER['REQUEST_URI']);
if($cnt>0)
{
echo '共',$cnt,'个资源([%read=,cmmm,newbin&amp;dir=',$dir,']新建[/read])[br][br]';
$u=$_REQUEST['u'];
$jc=$qi;
foreach($s as $eid=>$val)
{
$fname=$val['title'];
$jc++;
echo $jc,'. [%%read=,cmmm,info&amp;dir=',$dir,'&amp;eid=',$eid,']',code::html($fname),'[/read][br]';
}
if($p<$la)
 echo '[%read=,cmmm,list&amp;p=',$p+1,'&amp;dir=',$dir,']下页[/read]';
if($p>1)
 echo ' [%read=,cmmm,list&amp;p=',$p-1,'&amp;dir=',$dir,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '数据库说：欢迎使用虎绿林免流资源发布系统，你可以去[%read=,cmmm,newbin&amp;dir=',$dir,']新建一免流个资源[/read]。爪机有你更精彩！';
?>
[hr]返回[read=,cmmm,]分类[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]