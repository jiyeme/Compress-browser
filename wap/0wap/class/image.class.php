<?php
class image
{
#将16位565颜色值转为24位RGB#
static function getRGBfor565($rgb,&$r,&$g,&$b)
{
$r=($rgb>>11)<<3;
$g=(($rgb>>5)&63)<<2;
$b=($rgb&31)<<3;
}
#取像素RGB值#
static function getRGB($im,$rgb,&$r,&$g,&$b)
{
$rgb=imagecolorsforindex($im,$rgb);
$r=$rgb['red'];
$g=$rgb['green'];
$b=$rgb['blue'];
}
#从bmp565字节流中创建图片#
static function createfrombmp565($fn,$w,$h=null)
{
$size=filesize($fn)/2;
if($w>0 && $h<=0)
 {$h=$size/$w;}
elseif($h>0 && $w<=0)
 {$w=$size/$h;}
elseif($w<=0 && $h<=0)
 {return false;}
$fp=fopen($fn,'r');
if(!$fp or !$size){return false;}
$im=imagecreatetruecolor($w,$h);
for($y=0;$y<$h;$y++){
for($x=0;$x<$w;$x++){
$rgb=unpack('v',fread($fp,2));
self::getrgbfor565($rgb[1],$r,$g,$b);
$color=imagecolorallocate($im,$r,$g,$b);
imagesetpixel($im,$x,$y,$color);
 }}
return $im;
}
#转24位颜色值为16位565颜色值#
static function rgb565($im,$rgb)
{
self::getRGB($im,$rgb,$r,$g,$b);
$r=$r>>3;$g=$g>>2;
$b=$b>>3;
return ($r<<11)+($g<<5)+$b;
}
#生成16位bmp颜色字节流，如果没有指定文件名，则直接返回#
static function bmp565($im,$fname=null)
{
$w=imagesx($im);
$h=imagesy($im);
/*$addjc=($w*2)%4;
$addr=str_repeat(chr(0),$addjc);*/
$data='';
for($y=0;$y<$h;$y++)
{
 for($x=0;$x<$w;$x++)
 {
 $rgb=self::rgb565($im,$index=imageColorAt($im,$x,$y));
 $data.=pack('v',$rgb);
/*$y=imagecolorsforindex($im,$index);
echo "y:$y[red] . $y[green] . $y[blue]<br>",($rgb&31)*8,' . ',(($rgb>>5)&63)*4,' . ',($rgb>>11)*8,"<br>";*/
 }
#$data.=$addr;
}
if($fname==null)
 return $data;
else
 file_put_contents($fname,$data);
}
#class end#
}
?>