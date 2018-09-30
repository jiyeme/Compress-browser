<?php
set_time_limit(0);
ini_set('memory_limit','20M');
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$breakfile=USERFILE_DIR.'/'.$USER['uid'].'/bin_to139photo_break.lock';
if($_GET['break'])
{ 
file_put_contents($breakfile,time());
?>[html=取消中转任务]取消命令已提交。[br]如果正在进行的是你的任务，那么任务马上会被取消。[br]如果正在进行的不是你的任务，那么任务不会被取消。但是当你下次进行中转时，你将被系统批评 ～～[br]请不要尝试在别人的任务进行时使用取消功能。[/html]<?php
exit;
}
if($isonly=($fsize=filesize(USERFILE_DIR.'/'.$USER['uid'].'/'.str::word($_REQUEST['dir']).'/'.str::word($_REQUEST['eid']).'.gz')))
 $urf='/'.$USER['uid'];
else die('[html=错误]抱歉，文件的大小为0，可能过期了。[/html]');
$lockfile=USERFILE_DIR.$urf.'/bin_to139photo.lock';
if(time()-filemtime($lockfile)<600)
{
?>[html=上次的任务未完成]以下是上次任务的执行情况[hr]<?php
echo file_get_contents($lockfile);
?>[hr]请等待这个任务完成，或者[read=,tool,bin_to139photo7&amp;break=true]取消任务[/read]（任务不属于你时慎用，后果自负）。[br]<?php
if($isonly)
 echo '（这是你自己的任务）';
else
 echo '（请等待你的上一个任务完成再生成HuR3。）';
?>[/html]<?php
exit;
}
$u=$_REQUEST['u'];
$eid=str::word($_REQUEST['eid']);
$dir=str::word($_REQUEST['dir']);
$s=new session($USER['uid'],'dir.'.$dir,3600*5*24,array($eid),false);
/*$input=new session($USER['uid'],'input',3600*24*5,array('139photo_url2'));*/
if(!$e=$s[$eid])
{ ?>
[html=出错啦！]
文件系统：抱歉，我找不到你的文件，可能过期了。[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,index,]首页[/read][br][time][foot]
[/html]
<?php
exit;
}
if(!$_POST['go'])
{ ?>
[html=<%=$title=code::html($e<('title')>)%>-生成HuR3]
[head]
<?php
if(is_file($breakfile))
{
unlink($breakfile);
echo '中转系统：弱弱地问一句：取消别人的任务很好玩吧？[hr]';
}
?>
你正在用文件“<%=$title%>”（<%=fat32_f::echosize($fsize), '，大文件补卷专用通道'%>）在线分割文件。<?php /*如果你第一次使用它，请先[read=,user,ssh&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]登记相册信息[/read]*/ ?>[hr]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>]
保存目录:[input=content]<%=$e<('title')>%>[/input][br]
分卷大小:[input=packsize]950[/input][sel=bit][op=1048576]MB[/op][op=1024" selected="selected]KB[/op][op=1]B[/op][/sel][br]
生成指定分卷：[br]第[input=packi,2]1[/input]卷到第[input=packend,2]0[/input]卷[br]
[submit=go]发送[/submit]
[/form]
<?php
}
else
{
?>
[html=在线分割文件-状态]
HuR3正在生成中……[br]
<?php
function strtolog($str)
{
global $lockfile;
static $str2='';
echo $str;
$str2.=$str;
file_put_contents($lockfile,$str2);
}
$packsize=floor($_POST['packsize'])*floor($_POST['bit']);
$fn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/'.$eid.'.gz';echo '文件大小:',fat32_f::echosize($fz=filesize($fn)),'[br]';
/*$md5=md5_file($fn,true);
$tp=TEMP_DIR.'/rc/139photo.jpg';
imgpit::图片字符串(*/
$content=$_POST['content'];
/*,$tp);
$tpnr=file_get_contents($tp)."\r\n";*/
$packcount=ceil($fz/$packsize);
strtolog("{$USER['name']}(uid {$USER['uid']})的任务[br]{$_POST['content']}(共{$packcount}个分卷)[br]");
unset($s,$input);
$i=floor($_REQUEST['packi']) or $i=1;
$end=floor($_REQUEST['packend']) or $end=$packcount;
$dir=url::b64e(pack('V',time()));
$s=new session($USER['uid'],'dir',0,array($dir));
$s[$dir]=array('title'=>$content);
fat32_f::mkdir(USERFILE_DIR.'/'.$USER['uid'].'/'.$dir,0777);
$s=new session($USER['uid'],'dir.'.$dir,3600*2,'');
$fp0=fopen($fn,'r');
for(;$i<=$end;$i++)
{
strtolog(date('H:i:s')." 分卷{$i}开始生成[br]");
//exit('为了增加对大型分卷的支持，算法正在修改中，暂时停用');
$目标目录=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir;
$fp=fopen($目标目录.'/h'.$i.'.gz','w');
$seek=($i-1)*$packsize;
fseek($fp0,$seek);
for($i临时=0,$len临时=8192;$i临时<$packsize;$i临时+=$len临时)
{
if($packsize<$i临时+$len临时)
 $len临时=$packsize-$i临时;
$nr=fread($fp0,$len临时);
fwrite($fp,$nr);
}
if($packcount==$i)
 $packsize=$fz-$packsize*($i-1);
fclose($fp);
$s['h'.$i]=array('title'=>'h'.$i,'type'=>'jpg');
if(is_file($breakfile))
 {
  unlink($breakfile);
  break;
 }
}
unlink($目标目录.'/hur3.tmp.gz');
unlink($lockfile);
unlink($fn);
unset($s[$eid]);
echo '为节省空间，已删除原文件。';
echo '[%read=,fat32,bin_list&amp;dir=',$dir,']去文件夹看看吧[/read]';
}
?>
[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,fat32,bin_list&amp;dir=<%=$dir%>]我的文件[/read] [read=,index,]首页[/read][br]
[time][foot]
[/html]