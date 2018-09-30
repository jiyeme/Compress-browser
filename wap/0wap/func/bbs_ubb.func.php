<?php
function bbs_ubb($t)
{
global $PAGE;
$exz='['.chr(94).'《》]';
$or=chr(124);
$bds=array(
'7xy.in','8-1xy.in',
'xexe.mobi','xexexexe.mobi',
'lare.znw.cc','wk.baidu.com',
'hhk4\\.tk','cnnic.cn',
'hj8\\.in','*',
'ksad\\.cn','**',
'sesey\\.info','#',
'51roro.com','51.com',
' ','&nbsp;',
'＞＞＞','<br/>',
'＜＜＜','<hr/>',
'///','<br/>',
"\\[span=(.*)\\]",'<span style="\\1">',
'\\[div=(.*)\\]','<div style="\\1">',
'\\[/(span'.$or.'div)\\]','</\\1>',
'《图片：('.$exz.'*)，('.$exz.'*)》','<img src="\\1" alt="\\2"/>',
'《图片：(.*)》','<img src="\\1"/>',
'《锚：([a-zA-Z0-9_]+)》','<a id="\\1"></a>',

'\[url=(.*)\](.*)\[/url\]','<a href="\\1">\\2</a>',
'《外链：('.$exz.'*)，('.$exz.'*)》','<a href="http://\\1">\\2</a>',
'《外链：(.*)》','<a href="http://\\1">\\1</a>',
'《链接：('.$exz.'*)，('.$exz.'*)》','<a href="\\1">\\2</a>',
'《链接：(.*)》','<a href="\\1">\\1</a>',
'\[((br)|(hr))\]','<\\1/>',
'\[tab\]','&nbsp;&nbsp;&nbsp;&nbsp;',
'\[url\](.*)\[/url\]','<a href="\\1">\\1</a>',
'\[img\](.*)\[/img\]','<img src="\\1"/>',
'\[img=(.*)\](.*)\[/img\]','<img src="\\1" alt="\\2"/>',
'\[([biu]|(center)|(left)|(right))\]','<\\1>',
'\[/([biu]|(center)|(left)|(right))\]','</\\1>',
'\[read=(.*)\](.*)\[/read\]','<a href="read.php?id=\\1">\\2</a>',
'\[time\]',date('Y-m-d H:i:s'),
">(\\s*)下(页{$or}章{$or}节{$or}段{$or}卷{$or}张{$or}篇{$or}一)",'">\\1#下#\\2',
"\r\n",'<br/>',
"[\r\n]",'<br/>',
'<hr/>','<br/>------<br/>',
);
if($PAGE['bid']==wml)
 {
$bds[]='</?div['.chr(94).'>]*>';$bds[]='<hr/>';
$bds[]='</?span['.chr(94).'>]*>';$bds[]='';
$bds[]='<hr/>';$bds[]='<br/>--------<br/>';
 }
$jc=count($bds);
for($a=0;$a<$jc;$a=$a+2)
{
$b=$a+1;
$t=preg_replace('!'.$bds[$a].'!uisU',$bds[$b],$t);
}
if($PAGE['bid']!='wml')
 $t=preg_replace('!\\[code\\](.*)\\[/code\\]!euisU',"highlight_string(str_replace(array('&nbsp;','<br/>','&lt;','&gt;','&quot;','&#039;','&amp;'),array(' ','\n','<','>','\"','\\'','&'),'\\1'),true)",$t);
else
 $t=str_replace(array('[code]','[/code]'),'',$t);
$t=preg_replace("!(@|＠)([\\x{4e00}-\\x{9fa5}A-Za-z0-9_\\-]{1,})(\x20|&nbsp;|<|\xC2\xA0|\r|\n|\x03|\t|,|\\?|\\!|:|;|，|。|？|！|：|；|、|…|$)!ue","'\\1<a href=\"/wap/0wap/?cid=msg&amp;pid=send&amp;toname='.urlencode('\\2').'\">\\2</a>\\3'",$t);$t=preg_replace("!(@|＠)(#|＃)([0-9]{1,})(\x20|&nbsp;|<|\xC2\xA0|\r|\n|\x03|\t|,|\\?|\\!|:|;|，|。|？|！|：|；|、|…|$)!u","\\1<a href=\"/wap/0wap/?cid=msg&amp;pid=send&amp;touid=\\3\">\\2\\3</a>\\4",$t);
$t=preg_replace('!《(?:表情)?(?:：|\\:)([a-zA-Z0-9\x{4e00}-\x{9fa5}]+)》!uUe',"'<img src=\"/wap/user/face.php?d='.base64_encode(\\1).'\" alt=\"\\1\"/>'",$t);
$t=preg_replace("!(?:[\\/＼/]|&#92;)([\x{4e00}-\x{9fa5}]{1,2})(\x20|&nbsp;|<|\xC2\xA0|\r|\n|\x03|\t|\.|,|\\?|\\!|:|;|，|。|？|！|：|；|、|…|$)!uUe","'<img src=\"/wap/user/face.php?d='.urlencode(base64_encode(\\1)).'\" alt=\"\\1\"/>\\2'",$t);
return $t;
}
?>