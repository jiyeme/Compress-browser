<?php
/*math_s类，处理纯数字问题*/
class math_s
{
static function limit($x,$k,$b)
{
/*取出符合函数
y=$x+n*$b(n属于整数, y<$k)
的第一个值*/
if($x<$k)
 {
for(;$x<$k;$x+=$b);
return $x-$b;
 }
else
 {
for(;$x>=$k;$x-=$b);
return $x;
 }
}
static function maxYue($a,$b)
{
#求最大公约数
$a=abs($a);
$b=abs($b);
$min=min($a,$b);
$max=max($a,$b);
for($i=$min;$i>0;$i--)
 {
if($max%$i==0)
 return $i;
 }
return 1;
}
static function minBei($a,$b)
{
#求最小公倍数
$a=abs($a);
$b=abs($b);
$min=min($a,$b);
$max=max($a,$b);
if($min==0)
 return $max;
for($i=$max;true;$i+=$max)
 {
if($i%$min==0)
 return $i;
 }
}
static function jianFen($a,$b,&$ya,&$yb)
{
#取得最简分数(a为分子,b为分母)，$ya和$yb是接收参数
#成功返回true，失败返回false
if($b==0)
 return false;
if(!is_int($a) or !is_int($b))
{
for($i=2;true;$i++)
  {
$ya=$a*$i;
$yb=$b*$i;
if(is_int($ya)&&is_int($yb))
{$a=$ya;$b=$yb;break;}
  }
 }
$i=self::maxyue($a,$b);
$ya=$a/$i;
$yb=$b/$i;
return true;
}
static function getFenShi($a,$b,$jian=false)
{
#返回分数表达式，失败返回false. $jian=true时将化简分式
if($b==0)
 return false;
if($a==0)
 return '0';
$k=0;
if(($a&&!$b)or($b&&!$a))
 {$k++;$sa='-';}
$a=abs($a);
$b=abs($b);
if($jian)
 self::jianfen($a,$b,$a,$b);
$sb="$a";
if($b!=1)
 {$sc="/$b";$k++;}
$ss="$sa$sb$sc";
if($k)
 $ss="($ss)";
return $ss;
}
#math-s类结束#
}
?>