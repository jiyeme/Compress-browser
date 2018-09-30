<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$dir=str::word($_REQUEST['dir']);
$dirinfo=new session($USER['uid'],'urlbook',0,array($dir),false);
$dirinfo=$dirinfo[$dir];
?>
[html=我的名片-<%=$name=code::html($dirinfo<('title')>)%>]
[head]
<?php
echo '[%read=,urlbook,index]地址簿[/read]&lt;',$name;
if($dirinfo['extra'])
 echo '[br]',code::html($dirinfo['extra']);
echo '[hr]';
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
echo $cnt=session::zucount($USER['uid'],'urlbook.'.$dir);
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new session($USER['uid'],'urlbook.'.$dir,0,"limit $qi,$hang",false);
if($cnt>0)
{
echo '共',$cnt,'张名片([%read=,urlbook,del&amp;type=card&amp;dir=',$dir,']清空[/read][or][%read=,urlbook,newcard&amp;dir=',$dir,']插入[/read])[br][br]';
$jc=$qi;
foreach($s as $eid=>$val)
{
$jc++;
echo $jc,'. ','[%%read=,urlbook,info&amp;dir=',$dir,'&amp;eid=',$eid,']',code::html($val['title']),'[/read]';
echo '([%read=,urlbook,card_tool&amp;dir=',$dir,'&amp;eid=',$eid,']+[/read])';
echo '[br]';
}
if($p<$la)
 echo '[%read=,urlbook,card&amp;p=',$p+1,'&amp;dir=',$dir,']下页[/read]';
if($p>1)
 echo ' [%read=,urlbook,index&amp;p=',$p-1,'&amp;dir=',$dir,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '欢迎使用虎绿林地址簿。现在你已经有一个地址簿了，你可以往里面[%read=,urlbook,newcard&amp;dir=',$dir,']插入名片[/read]，或者去[%read=,help,urlbook_card]虎绿林帮助中心[/read]转转。[br]你有两种方法使用名片：1.为每一个联系人建立一张名片。2.把名片当成网址的分类目录，然后把相同的网址存在同一张名片里。';
?>
[hr]返回[read=,urlbook,index]我的地址簿[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]