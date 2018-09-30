<?php
$uid=floor($_REQUEST['uid']);
$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
$path='/'.$uid.'/'.$dir.'/'.$eid;
$rpath=USERFILE_RDIR.$path.'.gz';
$zpath='down.php'.$path.'/';
if($fname=$_REQUEST['name'])
{
$zpath.=strtolower(urlencode($fname));
if($mime=$_REQUEST['mime'])
 $zpath.='?mime='.urlencode($mime);
headecho::location($zpath,true);
}
$path=$rpath;
$fsize=filesize($path);
if(!$fsize)
{ ?>
[html=出错啦！]
文件系统：抱歉，你想要下载的文件不存在或者大小为0B，不能下载。[br]出现这种情况的原因可能是：[br] 1.文件被所有者或管理员删除[br] 2.文件太久没被使用，被系统自动删除[br] 3.这个文件下载链接是错误的[br]你可以后退并继续访问，或者去[read=,index,]首页[/read]逛逛。祝你娱快，并真切地希望我们不要再见面。（呃，看到我准没好事，哈哈--）
[hr][time][foot][/html]
<?php
exit;
}
$s=new fat32_f($uid,'dir.'.$dir,0,array($eid),false);
$s=$s[$eid];
$fname=$s['title'] or $fname=substr($eid,0,8);
$pinyin=$fname;//str::word(str::pinyin($fname),false);
if($s['type'])
{
$fname.='.'.$s['type'];
$pinyin.='.'.str::word($s['type']);
}
$fname=code::html($fname);
$zpath.=strtolower(urlencode($pinyin));
?>
[html=<%=$fname%>-下载]
你正在下载文件“<%=$fname%>”（<%=fat32_f::echosize($fsize)%>）[br]<?php /*浏览器可能不支持中文文件名，自动改名为<%=$pinyin%>[br]*/ ?>[url=<%=$zpath%>]立即下载[/url][br]
[read=,fat32,fetion&amp;<%=$ur="uid=$uid&amp;dir=$dir&amp;eid=$eid"%>]url.fetion.wml[/read][or][read=,fat32,fetion&amp;<%=$ur%>&amp;go=xdown]xdown.fetion.wml[/read][br]
如果下载时连接超时，请尝试把接入点改为CMNET。[br]这里是文件的[url=<%=$rpath%>]真实下载地址[/url]，如果上面不能下载，可以试一下这个。文件名会混乱，你需要自己修改。[hr]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;uid=<%=$uid%>&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>]
改名:[input=name]<%=$pinyin%>[/input][br]
伪装成:[sel=mime][op=application/octet-stream]二进制数据[/op][op=audio/mpeg]mp3音乐文件[/op][op=text/plain]txt文本文件[/op][op=application/mr]mrp程序[/op][op=text/html]html网页[/op][op=text/vnd.wap.wml]wml网页[/op][/sel][br]
[submit]下载[/submit][anchor=post,下载,read.php?[u.b]&amp;[u.c]&amp;[u.p]&amp;uid=<%=$uid%>&amp;dir=<%=$dir%>&amp;eid=<%=$eid%>][pst=name][pst=mime][/anchor]
[br]如果你的浏览器限制了允许下载的文件类型，伪装成浏览器支持的类型后就可以下载了。
[/form]
[hr]返回[read=,index,]首页[/read][br][time][foot]
[/html]