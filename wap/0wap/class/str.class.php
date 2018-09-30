<?php
#str类,字符串处理类#
class str
{
static function 全是中文吗($str,$extra='')
{
$preg='/'.'^[\x{4e00}-\x{9fa5}'.$extra.']+$/u';
return preg_match($preg,$str);
}
static function npos($str,$substr,$times,$code='utf-8')
{
if($times<1)
 return false;
$len=mb_strlen($substr,$code);
for($off=-$len;$times>0;$times--)
 {
$off+=$len;
$off=mb_strpos($str,$substr,$off,$code);
 }
return $off;
}
static function word($f,$tolower=false)
{
$f=preg_replace('![^a-zA-Z0-9_\\-]!','',$f);
if($tolower)
 $f=strtolower($f);
return $f;
}
static function geturlquery($query,$and=false,$space=false)
{
$co=count($query);
$jc=0;
if($and)
 $and='&amp;';
else
 $and='&';
$url='';
foreach($query as $key=>$value)
 {
$jc++;
$url.=urlencode($key).'='.urlencode($value);
if($jc<$co)
 $url.=$and;
 }
if($space)
  $url=str_replace('+','%20',$url);
return $url;
}
static function getcode($str,$list=array('base64','UTF-7','GBK','UTF-8','UTF-16BE','UTF-16LE','BIG5')){
foreach($list as $code)
 {
if($str===mb_convert_encoding(mb_convert_encoding($str,'UTF-16LE',$code),$code,'UTF-16LE'))
 return $code;
 }
}
function pinyin($str,$ucwords=true,$fg='')
{
#参数：字符串、首字母大写、分隔符
static $py=false;
$str=mb_convert_encoding($str,'gbk','utf-8');
if(!$py)
 $py=new chinesespell;
$str=$py->getfullspell($str,' ');
if($ucwords)
 $str=ucwords($str);
if($fg!==' ') $str=str_replace(' ',$fg,$str);
return $str;
}
#str类结束#
}
?>