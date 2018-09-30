<?php
class_exists('session',true);
class fat32_f extends session
{
#重载删除过期内容的方法
function deltimeout()
{
return;
$rs=parent::$db->prepare('select name,nr from '.DB_A.'session where sid=? and zu=? and timeout>0 and timeout<'.time());
$rs->execute(array($this->sid,$this->zu));
while($nr=$rs->fetch(db::ass))
{
unlink($fn=USERFILE_DIR.'/'.str::word($this->sid).'/'.str::word(substr($this->zu,4)).'/'.str::word($nr['name']).'.gz');
}
$rs=parent::$db->prepare('delete from '.DB_A.'session where sid=? and zu=? and timeout>0 and timeout<'.time());
return $rs->execute(array($this->sid,$this->zu));
}
static function tofile($sid,$dir,$name,$value)
{
$dir=USERFILE_DIR.'/'.$sid.'/'.$dir;
if(!is_dir($dir))
 self::mkdir($dir,0777);
return file_put_contents($dir.'/'.$name.'.gz',$value);
}
static function mkdir($dir,$qs=0777)
{if(is_dir($dir))
 return true;
$topdir=dirname($dir);
if(!is_dir($topdir))
 self::mkdir($topdir,$qs);
mkdir($dir,$qs);
}
static function delete($path,$clean=false,$c=-1)
{
#参数：路径、是清空还是删除、目录层数
if(is_file($path))
 return unlink($path);
elseif(is_dir($path))
 {
if(!$dr=opendir($path))
 return false;
while(($fn=readdir($dr))!='')
{
if($fn!='.' && $fn!='..')
 {
$fn=$path.'/'.$fn;
if(is_file($fn))
 unlink($fn);
elseif(($c>1 or $c<0) && is_dir($fn))
 self::delete($fn,$clean,$c-1);
 }
}
if($clean)
 return true;
else
 return rmdir($path);
 }
else
 return false;
}
static function echosize($size,$jd=3)
{
if($size<1024)
 return $size.'B';
elseif($size<1024*1024)
 return round($size/1024,$jd).'KB';
else
 return round($size/1024/1024,$jd).'MB';
}
#fat32类结束
}
?>