<?php
/**
*html代码处理类
 *方法列表
  *totext($html)
    *html/wml转换为纯文本
**/
/**function debug($h)
{
static $j=0;
echo ++$j,'<br>',code::html($h),'<hr>';
die;
}**/
class html
{
static function totext($html)
{
$html=str_replace("\r","\n",$html);
$o=chr(124);
$x=chr(94);
$ex="[$x<]";
$exq="[$x\"<]";
$html=preg_replace(array(
 "!\s*(</?((br)$o(p)$o(div)$o(blockquote)$o(tr)$o(td)$o(li)$o(ui)$o(form)$o(hr))((\s+.*)$o(/))?>)\s*!i",
 "!\s*(</?((html)$o(wml)$o(body)$o(head)$o(card)$o(style)$o(script))((\s+.*)$o(/))?>)\s*!i",
),'\\1',$html);
$html=preg_replace('!\s+!',' ',$html);
#debug($html);
$html=preg_replace(array(
 '!</?td( .'.$ex.'*)?>!i',
 '!</?q( .'.$ex.'*)?>!i',
),' ',$html);
#debug($html);
$html=preg_replace(array(
 '!</?p( .'.$ex.'*)?>!i',
 '!</?div( .'.$ex.'*)?>!i',
 '!</?blockquote( .'.$ex.'*)?>!is',
 '!</?tr( .'.$ex.'*)?>!i',
 '!</?form( .'.$ex.'*)?>!i',
 '!</?ui( .'.$ex.'*)?>!is',
),"\n",$html);
#debug($html);
$html=preg_replace(
 array('!<li( .'.$ex.'*)?>!i','! +!', "!\n+!"),
 array("\n ",' ',"\n"),
$html);
$html=preg_replace(array(
 '!<style( .'.$ex.'*)?>.*</style>!i',
 '!<script( .'.$ex.'*)?>.*</script>!i',
),'',$html);
$html=preg_replace(
 array(
  '!<.'.$ex.'* title="(.'.$exq.'+)?".'.$ex.'*/?>!i',
  $p='!<.'.$ex.'*((text)'.$o.'(submit)).'.$ex.'* value="(.'.$exq.'+)?".'.$ex.'*/?>!i',
  '!<.'.$ex.'* alt="(.'.$exq.'+)?".'.$ex.'*/?>!i',
 ),
array('[\\1]','[\\4]','[\\1]'),$html);
echo code::html($p);
$html=preg_replace(array('!<br ?/?>!i',
'!<hr ?/?>!i',
),"\n",$html);
$html=html_entity_decode(strip_tags($html),ENT_QUOTES,'utf-8');
return $html;
}
#html类结束
}
?>