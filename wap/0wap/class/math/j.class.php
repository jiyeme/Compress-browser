<?php
class math_j
{
/*数学计算类之角度计算*/
static function h2j($hu)
{
#弧度转角度
return $hu*180/M_PI;
}
static function j2h($jiao)
{
#角度转弧度
return $jiao*M_PI/180;
}
static function getjlimit($a,$qi=0,$zhi=360)
{
#取任意角在特定角度范围内的同终边角，返回结果数组
if($a<$zhi&&$a>=$qi)
 return $a;
$a=math_s::limit($a,$qi,360);
$b=array();
for($a+=360;$a<$zhi;$a+=360)
 {$b[]=$a;}
if(count($b)===1)
 return $b[0];
else
 return $b;
}
static function getxiangxian($a)
{
if($a>=360)
{
$a=self::getjlimit($a,0,360);
if(is_array($a))
 $a=$a[0];
}
$b=$a%90;
$c=floor($a/90);
if($b==0)
{
if($c%2==0)
{
 $v="在x轴";
if($c==0)
 $v.="正半轴上";
else
 $v.="负半轴上";
}
else
 {
 $v="在y轴";
if($c==1)
 $v.="正半轴上";
else
 $v.="负半轴上";
 }
}
else
 {
$d=floor($a/90)+1;
$v="第".$d."象限";
 }}
#math-j类结束
}
?>