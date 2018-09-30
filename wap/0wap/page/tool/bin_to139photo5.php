<?php
set_time_limit(600);
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
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
{
?>
[html=<%=$title=code::html($e<('title')>)%>-转到139说客相册]
[head]
你正在把文件“<%=$title%>”粘贴到139社区。[br]<?php
$fp=fopen(USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/'.$eid.'.gz','r');
$nr=strtoupper(bin2hex(fread($fp,35)));
fclose($fp);
echo '文件的前35字节：[br][url=#]';
for($i=0;$i<70;$i+=2)
{
echo substr($nr,$i,2),' ';
}
?>[/url][br]你需要粘贴你的相片上传页地址。<?php /*如果你第一次使用它，请先[read=,user,ssh&amp;u=<%=urlencode($_SERVER<('REQUEST_URI')>)%>]登记相册信息[/read]*/ ?>[hr]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>[u.sid]]
说客URL:[input=url]<%=code::html($input['139photo_url2'])%>[/input][br]
文件名:[input=fname]<%=str::word(str::pinyin($title))%>.gif[/input][br]
图片描述:[input=content]<%=code::html($title)%>[/input][br]
[submit=go]发送[/submit]
[/form]
<?php
}
else
{
?>
[html=发到说客相册-状态]
<?php
if(limit::访问('to139photo',120))
{ ?>
两分钟内只能传一次哦，好好珍惜吧。[/html]
<?php
exit;
}
?>已经把图片提交到了说客相册（资源可能很快过期，请抓紧时间下载），至于是否成功……请看下文：[hr]
<?php
/*$sk=new session($USER['uid'],'cookies',0,array('wap_139_10086_cn'),false);
$sk=$sk['wap_139_10086_cn'];*/
$input['139photo_url2']=$lur=$_REQUEST['url'];
$gour=substr($lur,26);
$lur=explode(';jsessionid=',$lur);
$lur=explode('?',$lur[1]);
$sk['jsessionid']=$lur[0];
$lur=url::getqueryarray($lur[1]);
$sk['src']=$lur['src'];
$sk['cid']=$lur['cid'];
$sk['m']=$lur['m'];
$url=/*"http://wap.139.10086.cn:84*/substr($_REQUEST['url'],0,26)."/photo.do;jsessionid={$sk[jsessionid]}?1=&m={$sk[m]}&src={$sk[src]}&do=Text.addPost&go=".urlencode($gour);
$h=new httplib;
$h->open($url,10,5);
$fn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/'.$eid.'.gz';
echo '文件大小:',fat32_f::echosize($fz=filesize($fn)),'[br]';
#$md5=md5_file($fn,true);
$tp=TEMP_DIR.'/rc/139photo2.gif';
imgpit::单像素图片($tp);
$tplen=filesize($tp);
$h->file('upfile',$_POST['fname'],file_get_contents($tp).substr($nr=file_get_contents($fn),$tplen)/*.pack('L',$fz).$md5.'HuR1'*/,'image/gif');
$h->post('content',$_POST['content']);
$h->post('submit','上传');
$h->post('cid',$sk['cid']);
$h->send();
$xml=$h->response();
echo '文件的前',$tplen,'个字节：[br][url=#]';
echo strtoupper(bin2hex(substr($nr,0,$tplen)));
/*$tplen=$tplen*2;
for($i=0;$i<$tplen;$i+=2)
{
echo substr($hex,$i,2);
if($i%8==7)
 echo '[tab]';
else
 echo ' ';
}*/
echo '[/url][hr]';
/*$xml=substr($xml,strpos($xml,'-->')+3);
$xml=substr($xml,0,strpos($xml,'<!--'));*/
echo $xml ? xml::totext($xml) : '呃，似乎是连接失败，什么都没有。这种情况可能经常发生（特别是网络很卡时），请尝试重新提交。也许已经成功发布了，还是先';
echo '[url=http://wap.139.10086.cn:84/photo.do;jsessionid='.$sk<('jsessionid')>.'?1=&amp;m='.$sk<('m')>.'&amp;src='.$sk<('src')>.'&amp;do=Photo.my'/*&amp;cid=',$sk<('cid')>*/,']去说客相册看看吧[/url]';
}
?>
[hr]返回[url=<%=code::html($u)%>]来源页[/url] [read=,fat32,bin_list&amp;dir=<%=$dir%>]我的文件[/read] [read=,index,]首页[/read][br]
[time][foot]
[/html]