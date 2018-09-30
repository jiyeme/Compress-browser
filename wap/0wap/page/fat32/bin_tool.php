<?php
[%getuser]
$eid=str::word($_REQUEST['eid']);
$dir=str::word($_REQUEST['dir']);
$ur="&amp;dir=$dir&amp;eid=$eid";
$fn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/'.$eid.'.gz';
$fsize=filesize($fn);
if(!$fsize)
{
copy($tfn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/downlist.tmp.gz',$fn);
unlink($tfn);
$fsize=filesize($fn);
}
?>
[html=文件工具]
<?php
/*上传系统更新。你的说客URL必须具有以下格式：[br]http://wap.139.10086.cn:xy/……[br]其中xy为两位数字，如80、84、86、96。如果后面没有:xy，你需要自己在10086.cn后面加:80，否则不能上传。[hr]*/
?>
[url=/wap/read.php?id=bbs_tz&amp;tzid=1986&bkid=4][新手必读]免流上传为何连接超时？[/url][br]
<?php
/*[url=/wap/read.php?id=bbs_tz&amp;tzid=444&amp;bkid=4][收不到文件必读]免流上传地址提取规范[/url][br]
[url=/wap/read.php?id=bbs_tz&amp;tzid=2145&amp;bkid=4]想传4MB分卷的看过来[/url]*/
?>[hr]
『文件信息』[br]
大小：<%=fat32_f::echosize($fsize)%>[br]没下载完？[read=,fat32,bin_range<%=$ur%>]断点续传[/read][hr]
『免流中转』[br]
[read=,tool,bin_to139weibo<%=$ur%>]发微博（资源有效期约一天）[/read][br]
[read=,tool,bin_to139photo2<%=$ur%>](原图上传)图片中转[/read][br]<?php
if($fsize>950*1024)
 echo '文件超过950KB，请使用HuR3分卷中转，否则上传时会出现413错误。[br]';
else
 echo '文件小于950KB，请使用HuR1不分卷中转。[br]';
?>[read=,tool,bin_to139photo3<%=$ur%>](需分离)HuR1伪装中转[/read][br]

[read=,tool,bin_to139photo7<%=$ur%>](需分离)HuR3大文件分卷中转[/read]([read=,tool,bin_to139photo8<%=$ur%>]免排队补卷[/read])[br]
[read=,tool,bin_to139group<%=$ur%>]HuR3上传到139圈子相册[/read][br]
[read=,tool,bin_tohur3<%=$ur%>]生成HuR3并下载[/read][br]
[read=,tool,bin_tohur3png<%=$ur%>]生成HuR3-png格式图片[/read](可以用hur3cut分离，xdown必须打开强制分离)[br]
[read=,tool,bin_cut<%=$ur%>]直接分割文件[/read][br]
「用下面通道生成的免流资源只有约5分钟的有效期」[br]
[read=,tool,bin_to139photo4<%=$ur%>](免分离)txt小说中转[/read][br]
[read=,tool,bin_to139photo5<%=$ur%>](文件头替换35B)任意文件中转[/read][br]
[read=,tool,bin_to139photo6<%=$ur%>](文件头替换5B)任意文件中转[/read][hr]
返回[read=,fat32,bin_dir&amp;dir=<%=$dir%>]我的文件[/read] [read=,index,]首页[/read]
[br][time][foot]
[/html]