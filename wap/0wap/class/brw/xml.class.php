<?php
class brw_xml
{
function totext($xmlnr,&$link,&$title)
{
$arr=xml::toarray(xml::去空白($xmlnr));
$str='';
$br=false;
$nbsp=false;
$ii=0;
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
 if(($att2=$att['TITLE'].$att['ALT'].$att['VALUE'])!='')
{
$str.='['.$att2.']';
if($tag=='CARD' && $type!='cdata')
 {
 $str.="\n";
 $title=$att2;
 }
}
$str.=$i['value'];
if($tag=='TITLE')
 $title=$i['value'];
elseif($tag=='A')
  {
$ii++;
$str.="(#$ii)";
$link[$ii]=$att['HREF'];
  }
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