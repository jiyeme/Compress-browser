<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
?>
[html=我的地址簿]
[head]
<?php
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$cnt=session::zucount($USER['uid'],'urlbook');
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new session($USER['uid'],'urlbook',0,"limit $qi,$hang",false);
if($cnt>0)
{
echo '共',$cnt,'个地址簿([%read=,urlbook,del&amp;type=dir]清空[/read][or][%read=,urlbook,newdir]新建[/read])[br][br]';
$jc=$qi;
foreach($s as $eid=>$val)
{
$jc++;
echo $jc,'. ','[%%read=,urlbook,card&amp;dir=',$eid,']',code::html($val['title']),'[/read]';
echo '([%read=,urlbook,dir_tool&amp;dir=',$eid,']+[/read])';
echo '[br]';
}
if($p<$la)
 echo '[%read=,urlbook,index&amp;p=',$p+1,']下页[/read]';
if($p>1)
 echo ' [%read=,urlbook,index&amp;p=',$p-1,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '欢迎使用虎绿林地址簿。在这里，你可以保存网址、电话号码、QQ号或者一段普通的文字……而且你还可以给它们自由分类。在这里，你可以直接访问地址簿里的网站、一键查看源码和复制网页内容。你还可以看到好友的QQ在线状态，并直接与Ta聊3GQQ！如果你是自带浏览器，还可以直接给对方发短信、打电话！最后，你还可以把地址簿文件下载到本地直接访问，更方便快捷。[br]赶快[%read=,urlbook,newdir]新建一个地址簿[/read]体验一下吧，或者去[%read=,help,urlbook_index]虎绿林帮助中心[/read]转转。';
?>
[hr]返回[read=,index,]首页[/read][br][time][foot]
[/html]