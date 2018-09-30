<?php
#静态类ubb，对文本进行正则替换。
class ubb
{
#替换字符串
static function str($t,$ubb_type=DEFAULT_PAGE_UBB)
{
if(!$ubb_type)
 $ubb_type=DEFAULT_PAGE_UBB;
include UBB_DIR.'/'.$ubb_type.'.inc.php';
foreach($list as $goubb)
{
include UBB_DIR.'/option/'.$goubb.'.inc.php';
$jc=count($bds);
for($i=0;$i<$jc;$i+=2)
 {
$bds_cz[]='!'.$bds[$i].'!isU';
$bds_th[]=$bds[$i+1];
 }
$t=preg_replace($bds_cz,$bds_th,$t);
}
return $t;
}
/*替换文件
参数解释：
$f 原文件名
$newf 保存文件名，留空('')的话就覆盖原文件
$ubb_type UBB类型，留空('')则取config里的默认值
$must 是否必须更新，默认为否，当目标文件存在时就不更新
返回：成功返回true,失败false,如果没有进行替换，返回null
*/
static function file($f,$newf='',$ubb_type=DEFAULT_UBB_TYPE,$must=false)
{
if($newf==='')
 $newf=$f;
if(!$must && is_file($newf)) return null;
$f=file_get_contents($f);
if($f===false)
  return false;
$f=self::str($f,$ubb_type);
$f_dir=dirname($newf);
if(!is_dir($f_dir))
  mkdir($f_dir,0777);
$ok=file_put_contents($newf,$f);
if($ok===false)
  return false;
else
  return true;
}
static function page($bid,$cid,$pid,$must=false)
{
global $PAGE;
$f=PAGE_DIR."/$cid/$pid.php";
$newf=PAGECACHE_DIR."/$bid/$cid.$pid.inc.php";
if(!$must && is_file($newf))
  return $newf;
else
 {
$ok=self::file($f,$newf,$bid,true);
if($ok===false)
  return false;
else
  return $newf;
 }
}
#ubb类结束#
}
?>