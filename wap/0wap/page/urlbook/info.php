<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
$dirinfo=new session($USER['uid'],'urlbook',0,array($dir),false);
$dirinfo=$dirinfo[$dir];
$info=new session($USER['uid'],'urlbook.'.$dir,0,array($eid),false);
$info=$info[$eid];
?>
[html=名片信息-<%=$name=code::html($info<('title')>)%>]
[head]
<?php
echo '[%read=,urlbook,index]',code::html($dirinfo['title']),'[/read]&lt;',$name;
if($info['extra'])
 echo '[br]',code::html($info['extra']);
echo '[hr]';
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$cnt=$info['count'];
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$zhi=$qi+$hang;
/*$s=new session($USER['uid'],'urlbook.'.$dir,0,"limit $qi,$hang",false);*/
if($cnt>0){
echo '共',$cnt,'条信息([%read=,urlbook,del&amp;type=info&amp;dir=',$dir,'&amp;eid=',$eid,']清空[/read][or][%read=,urlbook,newinfo&amp;dir=',$dir,'&amp;eid=',$eid,']插入[/read])[br][br]';
for($jc=$qi;$jc<$zhi&&$jc<$cnt;$jc++)
{
$nr=code::html($info[$jc]['value']);
$nm=code::html($info[$jc]['title']);
$tp=$info[$jc]['type'];
switch($tp)
 {
case 'url':
$url='<a href="'.$nr.'">'.$nm.'</a>';
break;
case 'tel':
 $url=$nm.': <a href="wtai://wp/mc;'.$nr.'">'.$nr.'</a>(<a href="sms:'.$nr.'">短信</a>)';
break;
default:
$url=$nm.': '.$nr;
 }
echo $jc+1,'.',$url;
echo '([%read=,urlbook,info_tool&amp;dir=',$dir,'&amp;eid=',$eid,'&amp;infoid=',$jc,']+[/read])';
echo '[br]';
}
if($p<$la)
 echo '[%read=,urlbook,info&amp;p=',$p+1,'&amp;dir=',$dir,'&amp;eid=',$eid,']下页[/read]';
if($p>1)
 echo ' [%read=,urlbook,index&amp;p=',$p-1,'&amp;dir=',$dir,'&amp;eid=',$eid,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '欢迎使用虎绿林地址簿。现在你已经有一张名片，你可以往里面[%read=,urlbook,newinfo&amp;dir=',$dir,'&amp;eid=',$eid,']插入信息[/read]，或者去[%read=,help,urlbook_info]虎绿林帮助中心[/read]转转。[br]你有两种方法使用名片信息：1.为每一个联系人建立一张名片，然后插入邮箱、电话、QQ等。2.把名片当成网址的分类目录，然后把相同的网址存在同一张名片里。';
?>
[hr]返回[read=,urlbook,card&amp;dir=<%=$dir%>]我的名片[/read]-[read=,index,]首页[/read][br][time][foot]
[/html]