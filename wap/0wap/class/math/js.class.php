<?php
#math_js类，科学计算器#
class math_js
{
static function th($ss)
{
if($ss=='')
 return null;
$o=chr(124);
$x=chr(94);
$ss=preg_replace("/[$x\\+\\-\\*\\/%0-9a-z\\(\\)\\[\\]\\{\\}<>,\\.\\$x]/i",'',$ss);
$split="/[\\+\\-\\*\\/%\\(\\)\\[\\]\\{\\}<>,\\$x]/i";
$ssk=preg_split($split,$ss);
$ck="/$x((0x[0-9a-f]+(\\.[0-9a-f]+)?e?)$o([0-9]+(\\.[0-9]+)?e?)$o(dfm)$o(mod)$o(j?(a?sin)$o(a?cos)$o(a?tan))$o(j2h)$o(h2j)$o(gen)$o(mi)$o(pi)$o(jxian)$o(dechex))?\$/i";
foreach($ssk as $i)
{
if(!preg_match($ck,$i))
 return false;
}
static $bds;
if(!$bds)
 $bds=array(
'/[\\[\\{<]/','(',
'/[\\]\\}>]/',')',
'/j2h\(/i','math_j::j2h(',
'/h2j\(/i','math_j::h2j(',
"/j((sin)$o(cos)$o(tan))\((.*)\)/",'\\1(math_j::j2h(math_j::getjlimit(\\5)))',
'/mod/i','%',
'/gen\\((.*),([0-9]+)\\)/i','pow(\\1,1/\\2)',
'/mi\(/i','pow(',
'/pi/i','M_PI',
'/jxian\(/i','math_j::getjlimit(',
);
$j=count($bds);
for($i=0;$i<$j;$i+=2)
 {$ss=preg_replace($bds[$i],$bds[$i+1],$ss);}
return $ss;
}
static function go($ss)
{
$ss=self::th($ss);
if(!$ss)
 return $ss;
$eval='return '.$ss.';';
return eval($eval);
}
#end#
}
?>