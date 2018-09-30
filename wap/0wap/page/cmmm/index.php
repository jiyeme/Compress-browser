[html=免流资源发布专区]
[head]
『特别说明：用XDown下载需要分离的免流资源（特别是视频）时，请打开菜单中的“强制分离”，否则资源不能用』[br]
<?php
$AD['site']='top';
include ubb::page('xhtml','common','ad');echo '[br]';
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$cnt=session::zucount(0,'cmmm');
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new session(0,'cmmm',3600*24*30,"limit $qi,$hang");
$selfuu=urlencode($_SERVER['REQUEST_URI']);
if($cnt>0)
{
echo '共',$cnt,'个分类([%read=,cmmm,listnew]查看全部[/read][or][%read=,cmmm,sou]搜索[/read][or][%read=,cmmm,newdir&amp;u=',$selfuu,']新建[/read][or][%read=,cmmm,clean]清理[/read])[br][br]';
$jc=$qi;
foreach($s as $eid=>$val)
{
$jc++;
echo $jc,'. [%%read=,cmmm,list&amp;dir='.$eid.']'.code::html($val['title']).'[/read][br]';

}
if($p<$la)
 echo '[%read=,cmmm,index&amp;p=',$p+1,']下页[/read]';if($p>1)
 echo ' [%read=,cmmm,index&amp;p=',$p-1,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '数据库说：欢迎使用虎绿林文件系统，你可以去[%read=,cmmm,newdir&amp;u=',$selfuu,']新建一个分类[/read]用来发布免流资源。祝你的资源大受欢迎。因为你的贡献，爪机更精彩！';
?>
[hr]返回[read=,index,]首页[/read][br][time][foot]
[/html]