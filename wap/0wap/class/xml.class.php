<?php
class xml
{
static function 空格转换($str)
{
return str_replace(chr(0xc2).chr(0xa0),' ',$str);
}
static function 去空白($xmlnr)
{
return preg_replace(
 array('!>\s+<!', '!\s+!', '!> !','!<br ?/?>!i'),
 array('><', ' ', '>',"\n"),
$xmlnr);
}
static function totext($xmlnr)
{
$arr=self::toarray(self::去空白($xmlnr));
$str='';
$br=false;
$nbsp=false;
foreach($arr as $i)
{
 #print_r($i);continue;
$tag=$i['tag'];
if(($tag=='TD' or $tag=='Q') && !$nbsp)
{
$str.=' ';
$nbsp=true;
}
else
 $nbsp=false;
if($tag=='BR' or (($tag=='P' or $tag=='DIV' or $tag=='FORM' or $tag=='HR' or $tag=='BODY' or $tag=='CARD' or $tag=='BLOCKQUOTE' or $tag=='TR' or $tag=='UI' or $tag=='LI') && $i['type']!='cdata' && !$br))
{
$str.="\n";
$br=true;
}
else
 $br=false;
if($tag!='STYLE' && $tag!='SCRIPT' && $tag!='POSTFIELD')
 {
 #print_r($i);continue;
if(($att=$i['attributes']) && $att['TYPE']!='hidden' /* && $att['TYPE']!='postfield'*/)
 if(($att=$att['TITLE'].$att['ALT'].$att['VALUE'])!='')
{
$str.='['.$att.']';
if($tag=='CARD' && $type!='cdata')
 $str.="\n";
}
$str.=$i['value'];
 }
}
return $str ? $str : $xmlnr;
}
static function toarray($str)
{
$xml=xml_parser_create('utf-8');
xml_parse_into_struct($xml,$str,$arr);
return $arr;
}
#classend
}
?>