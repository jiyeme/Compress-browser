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
else
 die('[html=错误]抱歉，文件的大小为0，可能过期了。[/html]');
$lockfile=USERFILE_DIR.$urf.'/bin_to139photo.lock';
if(time()-filemtime($lockfile)<600)
{
?>[html=上次的任务未完成]以下是上次任务的执行情况[hr]<?php
echo file_get_contents($lockfile);
?>[hr]请等待这个任务完成，或者[read=,tool,bin_to139photo7&amp;break=true]取消任务[/read]（任务不属于你时慎用，后果自负）。[br]<?php
if($isonly)
 echo '（这是你自己的任务）';
else
 echo '（请等待你的上一个任务完成再补卷。）';
?>[/html]<?php
exit;
}
$u=$_REQUEST['u'];
$eid=str::word($_REQUEST['eid']);
$dir=str::word($_REQUEST['dir']);
$s=new session($USER['uid'],'dir.'.$dir,3600*5*24,array($eid),false);
$input=new session($USER['uid'],'input',3600*24*5,array('139photo_url2'));
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
[html=<%=$title=code::html($e<('title')>)%>-转到139说客相册]
[head]
<?php
if(is_file($breakfile))
{
unlink($breakfile);
echo '中转系统：弱弱地问一句：取消别人的任务很好玩吧？[hr]';
}
?>
你正在把文件“<%=$title%>”（<%=fat32_f::echosize($fsize), '，大文件补卷专用通道'%>）分卷发布到你的139社区相册。你需要粘贴你的相片上传页地址。<?php /*如果你第一次使用它，请先[read=,user,ssh&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]登记相册信息[/read]*/ ?>[hr]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>]说客URL:[input=url]<%=code::html($input['139photo_url2'])%>[/input][br]
文件名:[input=fname]<%=str::word(str::pinyin($title))%>[/input].jpg[br]
图片描述:[input=content]<%=code::html($title)%>[/input][br]
分卷大小:[input=packsize]950[/input]KB[br]
指定分卷上传：[br]第[input=packi,2]1[/input]卷到第[input=packend,2]1[/input]卷（最多只能传3卷）[br]
[submit=go]发送[/submit]
[/form]
<?php
}
else
{
?>
[html=发到说客相册-状态]
已经把图片提交到了说客相册，至于是否成功……[br]
<?php
/*$sk=new session($USER['uid'],'cookies',0,array('wap_139_10086_cn'),false);
$sk=$sk['wap_139_10086_cn'];*/
function strtolog($str)
{
global $lockfile;
static $str2='';
echo $str;
$str2.=$str;
file_put_contents($lockfile,$str2);
}
$input['139photo_url2']=$lur=$_REQUEST['url'];
$gour=substr($lur,26);
$lur=explode(';jsessionid=',$lur);
$lur=explode('?',$lur[1]);$sk['jsessionid']=$lur[0];
$lur=url::getqueryarray($lur[1]);
$sk['src']=$lur['src'];
$sk['cid']=$lur['cid'];
$sk['m']=$lur['m'];
$packsize=floor($_POST['packsize'])*1024;
$fn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/'.$eid.'.gz';
echo '文件大小:',fat32_f::echosize($fz=filesize($fn)),'[br]';
$md5=md5_file($fn,true);
$tp=TEMP_DIR.'/rc/139photo.jpg';
imgpit::图片字符串($_POST['content'],$tp);
$tpnr=file_get_contents($tp)."\r\n";
$packcount=ceil($fz/$packsize);
strtolog("{$USER['name']}(uid {$USER['uid']})的任务[br]{$_POST['content']}(共{$packcount}个分卷)[br]");
unset($s,$input);
$i=floor($_REQUEST['packi']) or $i=1;
$end=floor($_REQUEST['packend']) or $end=$packcount;
if($end>$i+3)
 $end=$i+3;
for(;$i<=$end;$i++)
{
strtolog(date('H:i:s')." 分卷{$i}开始发送[br]");
$url=/*"http://wap.139.10086.cn:84*/substr($_REQUEST['url'],0,26)."/photo.do;jsessionid={$sk[jsessionid]}?1=&m={$sk[m]}&src={$sk[src]}&do=Text.addPost&go=".urlencode($gour);
$h=new httplib;
$h->open($url,10,5);
/*HuR3（4B）+ 本段数据MD5（16B）+ 本段数据长度（长整4B）+ 本段数据 + 分卷号（短整2B，从1起）+ 分卷总数（短整2B）+ 整个文件的MD5（合并分卷之后完整文件的MD5）+ 整个文件的长度（合并分卷之后的）+ 图片数据*/
$seek=($i-1)*$packsize;
$nr=$tpnr.pack('V',$fz).$md5.pack('v2',$packcount,$i);
$nr.=file_get_contents($fn,false,null,$seek,$packsize);
if($packcount==$i)
 $packsize=$fz-$packsize*($i-1);
$nr.=pack('V',$packsize);
$nr.=md5($fn,true);
$nr.='HuR3';
$h->file('upfile',$_POST['fname'].$i.'.jpg',$nr,'image/jpeg');
$h->post('content',$_POST['content'].'.'.$i);
$h->post('submit','上传');
$h->post('cid',$sk['cid']);
$h->send();
unset($h);
if(is_file($breakfile))
 {
  unlink($breakfile);
  break;
 }
}
unlink($lockfile);
echo '[url=http://wap.139.10086.cn:84/photo.do;jsessionid='.$sk<('jsessionid')>.'?1=&amp;m='.$sk<('m')>.'&amp;src='.$sk<('src')>.'&amp;do=Photo.my'/*&amp;cid=',$sk<('cid')>*/,']自己去说客相册看看吧[/url]';
}
?>
[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,fat32,bin_list&amp;dir=<%=$dir%>]我的文件[/read] [read=,index,]首页[/read][br]
[time][foot]
[/html]