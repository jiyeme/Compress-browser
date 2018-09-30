<?php
#imgpit（图片指针）类，用于imgtxt（小说图片阅读器）程序。
class imgpit
{
var $hgao,$hkuan, #分割条高宽
  $tgao,$tkuan, #原图高宽
  $mgao,$mkuan, #目标高宽
  $tx,$hx,$mx, #游标（原图总次数、原图目前行数、当前行次数、目标图当前行数）
  $tjc,$hjc,$mjc, #换行次数：原图上游标移动次数、原图每行分开数、目标图行数
  $thubu; #原图分行互补（防止半个字现象）
static function 随机取色($im)
{return imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));}
static function 图片字符串($str,$path)
{
$p=imagecreate($w=mt_rand(320,640),$h=mt_rand(240,480));
imagecolorallocate($p,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
for($i=0;$i<50;$i++)
{
imagefilledrectangle($p,mt_rand(0,$w),mt_rand(0,$h),mt_rand(10,60),mt_rand(10,60),self::随机取色($p));
imageline($p,mt_rand(0,$w),mt_rand(0,$h),mt_rand(0,$w),mt_rand(0,$h),self::随机取色($p));
imagechar($p,mt_rand(5,30),mt_rand(0,$w),mt_rand(0,$h),mt_rand(0,9),self::随机取色($p));
imagesetpixel($p,mt_rand(0,$w),mt_rand(0,$h),self::随机取色($p));
}
$color=imagecolorallocate($p,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
$ttf=DB_DIR.'/qi.ttf';
imagettftext($p,mt_rand(50,100),mt_rand(-10,10),mt_rand(0,80),mt_rand(120,280),$color,$ttf,$str);
if(substr($path,-4,4)=='.png')
 imagepng($p,$path);
else
 imagejpeg($p,$path);
imagedestroy($p);
}
static function 单像素图片($path)
{
$im=imagecreate(1,1);
imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
imagegif($im,$path);
imagedestroy($im);
}
function __construct($tkuan,$tgao,$mkuan,$mgao)
{
$this->tgao=$tgao;
$this->tkuan=$tkuan;
$this->mgao=$mgao;
$this->mkuan=$mkuan;
$this->hgao=26;
$this->thubu=0;
$this->mqu=2;
$this->tiaohkuan();
$this->tjc=floor($this->tgao/$this->hgao);
/*if($this->tgao%$this->hgao>$this->thubu)
 $this->tjc++;*/
$this->mjc=floor($this->mgao/$this->hgao);
if($this->mgao%$this->hgao<=$this->mqu)
 $this->mjc++;
}
function tiaohkuan()
{
$hjc=ceil($this->tkuan/$this->mkuan);
$this->hjc=$hjc;
$realtkuan=$this->tkuan+$hjc*$this->thubu;
$this->hkuan=ceil($realtkuan/$hjc);
}
function next()
{
if($this->tx>=$this->tjc)
 return false;
if($this->mx>=$this->mjc)
{$this->mx=0;
/*if(($this->hx+1)<=$this->hjc)
{$this->hx=$this->hjc-1;
$this->tx--;}
else
 $this->hx--;*/
return null;}
if(($this->hx+1)>=$this->hjc)
{$this->hx=-1;$this->tx++;}
$this->mx++;
$this->hx++;
return true;
}
function getinfo()
{
$info=array();
$info['mx']=0;
$info['my']=$this->mx*$this->hgao;
$info['tx']=$this->hx*($this->hkuan-$this->thubu);
$info['ty']=$this->tx*$this->hgao;
$info['kuan']=$this->hkuan;
if(($yu=$this->tkuan-$info['kuan']-$info['tx'])<0)
 $info['kuan']+=$yu;
$info['gao']=$this->hgao;
return $info;
}
function past()
{
$this->mx--;
}
function touming(&$im)
{
$this->alpha($im);
imagefilter($im,IMG_FILTER_GRAYSCALE)&&$this->alpha($im);
$po=$_REQUEST;
if($po['brig'])
  imagefilter($im,IMG_FILTER_BRIGHTNESS,$po['brig'])&&$this->alpha($im);
if($po['cont'])
  imagefilter($im,IMG_FILTER_CONTRAST,$po['cont'])&&$this->alpha($im);
if($po['smoo'])
  imagefilter($im,IMG_FILTER_SMOOTH,$po['smoo'])&&$this->alpha($im);
}
function alpha($im)
{
return imagealphablending($im,true);
}
static function hex2rgb($hex,&$r,&$g,&$b)
{
#十六进制色转RGB
if(strlen($hex)==3)
 $j=1;
else
 $j=2;
$p=0;
$r=hexdec(substr($hex,$p,$j));
$p+=$j;
$g=hexdec(substr($hex,$p,$j));
$p+=$j;
$b=hexdec(substr($hex,$p,$j));
}
#imgpit类结束
}
?>