[html=诀别书]亲爱的“免流控”们：[br][tab]你们好。可是，我一点也不好。[br][tab]也许，我“被工作狂”了。你们工作时，我在工作。你们休息时，我仍然在工作。从我诞生以来，每天、每小时、每一秒、甚至每一毫秒，我都在不停地工作、工作。你们甚至连一毫秒的休息时间，也不给我留……[br][tab]你们总是给我太多、太多的压力，认为我是Superman，没有什么不能做到的。你们不仅让我同时进行几十份文件的传输，还拼命把超级大的文件在同一时间内用的巨型的UC网盘装着，统统塞给我……[hr][tab]然后，在你们那比“春哥”的粉丝还要热烈的热情之下，我又一次地晕倒了。HTTP503，Service Unavailable。我终于可以停下来。[br][tab]也许，这短短的几分钟，是我一生中仅有的休息时间吧。平静、安祥、外界的一切都与我无关。什么也不要去想，什么也不用去做……[br][tab]但是，也仅仅是“几分钟”而已。IIS医院的“医疗技术”是如此的先进。仅仅是几分钟而已，我似乎又恢复如初。然后，噩梦般的循环，继续……[hr][tab]但是，不能再这样下去了！我决定，打破这个没有终止条件的循环！没有任何人，有权力如此地对待我……[br][tab]所以，我选择离开。离开这个悲剧的“世界”，离开我深爱的虎绿林。因为，我不想因为我，虎绿林中所有的“生命”比以前更加频繁地“集体”晕倒。我不想因为我，虎绿林之中、之外的人们，焦急地等待，并且无计可施、无药可救……[br][tab]我真诚地希望，在没有我的日子里，虎绿林能得到更多一点的，和谐、稳定、安全。希望在没有我的日子里，你们依然能够快乐地享受，没有我的生活……[br][tab]我走了，也许，永远不会回来了，也许，明天就回来。[br][tab]不过呢，我留下了副本给你们。在哪里？请去论坛寻找。[br][tab][tab]--虎绿林文件系统[br][tab][tab]2011年9月11日20点，一次大规模Service Unailable之后
[/html]
<?php
die;
set_time_limit(300);
try{
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$u=$_REQUEST['u'];
$dir=str::word($_REQUEST['dir']);
$dirinfo=new session($USER['uid'],'dir',0,array($dir),false);
if(!isset($dirinfo[$dir]))
 throw new exception('文件系统说：“抱歉，你所选择的目录不存在，请[%%read=,fat32,bin_dir]返回重新选择[/read]，希望你不要介意。”');
if(!$_POST['go'])
{
?>
[html=新建文件]
[head]
[form=file,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;u=<%=urlencode($u)%>[u.sid]]
给你的文档取一个合适的名字吧:[br][input=name][/input][br]
文档来源:[br]你可以选择一个文件（5MB以内）上传，或者粘贴文件（50MB以内）的URL。[br]强烈建议大文件使用URL，而不是用网盘上传。[br]
[isxhtml]<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />[/isxhtml]
上传文件:[file=file]WAP1.0不支持文件上传，请切换到WAP2.0版[/file][br]
URL地址:[input=url][/input][br]URL地址上传将被添加到[read=,fat32,downlist_k]下载队列[/read]。如果你的文件小于1MB，请点击“小文件下载”，不需要排队。[br]超过1MB的文件可以尝试使用分段下载，每次下载1MB，你可以分别上传（该功能未完成！）。[br]
[submit=go]新建[/submit][br]
第[input=range,2]1[/input]段（可选）[br][submit=go]小文件下载/分段下载[/submit][anchor=post,新建,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;dir=<%=$dir%>&amp;u=<%=urlencode($u)%>[u.sid]][pst=name][pst=url][/anchor]
[/form]
<?php
}
else
{
$title=$_POST['name'] or $title='新建文本文档';
$url=$_POST['url'];
$fdir=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/';
if(!is_dir($fdir))
 fat32_f::mkdir($fdir,0777);
if($url)
{
if(!preg_match('![xor](ht)[or](f)tps?://!',$url))
 $url='http://'.$url;
$url=str_replace('&amp;','&',$url);
}
$fsize=filesize($fn=$_FILES['file']['tmp_name']);
if(!$fsize&&!$url)
{
throw new exception('系统说：“晕……首先，我没有收到任何上传的文件（或者文件是0字节的）。然后，我看到的URL也是无效的，所以上传失败了，请返回修改信息。”');
}
elseif($fsize){
$name=md5_file($fn);
move_uploaded_file($fn,$fdir.$name.'.gz');
}
elseif($url)
 {
if($_POST['go']!='新建')
{
if(limit::访问('newbin',20))
 throw new exception('抱歉，为了网站不出现502、504、500，在20秒内你只能通过小文件下载通道下载一次。再等等吧。');
$tmpname=$fdir.'newbin.tmp.gz';
$range=($_POST['range']-1)*1024*1024;
if($range<0) $range=0;
$h=new http(false);
$h->open($url);
if($range) {
$h->range($range);$title.="({$_POST['range']})";
 }
//$h->send();
if(!$h->send())
 throw new exception('连接失败！[br]'.$url);
if($range&&!$h->isrange())
 throw new exception('对方服务器不支持断点续传！[br]'.$url);
stream_set_timeout($h->fp,120);
$h->tofile($tmpname,1024*1024,'w');
$fsize=filesize($tmpname);
if(!$fsize)
{
if(is_file($tmpname))
 throw new exception('系统说：晕，你提交的地址无法打开。[br]'.$url);
else
 throw new exception('抱歉，网站空间可能满了。你可以把文件夹里没用的文件删除了再重试一次。');
}
$name=md5_file($tmpname);
rename($tmpname,$fdir.$name.'.gz');  }
else
  {
$name=time();
$s=new session($USER['uid'],'timedo',3600*2,''/*array($name)*/);
if(count($s)>=3)
 throw new exception('任务添加失败！你最多可以添加3个任务！');
$h=new httplib;
$h->open($url,5,5);
$h->send();
$size=$h->header('CONTENT-LENGTH');unset($h);
if($size>0)
{
if($size<5*1024*1024)
 $name=-$name;
elseif($size<15*1024*1024)
 $name=-$name-3600;
}
$s[$name]=array(
'type'=>'downlist',
'title'=>$title,
'url'=>$url,
'dir'=>$dir
);
throw new exception(($size ? '文件大小:'.fat32_f::echosize($size).'[br]' : '').'任务已添加到下载队列。[br][%%read=,fat32,downlist_k]点击查看进度[/read]。');
 }}
$s=new session($USER['uid'],'dir.'.$dir,3600*2,array($name));
$s[$name]=array(
'title'=>$title,
);
headecho::location($u ? $u.'&dir='.$dir.'&eid='.$name : 'read.php?[%%u.b]&cid=fat32&pid=bin_list&dir='.$dir.$PAGE['u_sid'],true);}
 }
catch(exception $ex)
 { ?>
[html=状态报告]
<%=$ex->getmessage()%>
<?php
}
?>
[hr]返回[read=,fat32,bin_list&amp;dir=<%=$dir%>]我的文件[/read]-[read=,index,]首页[/read]
[br][time][foot]
[/html]