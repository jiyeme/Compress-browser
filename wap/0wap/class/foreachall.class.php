<?php
/*这个类用于进行遍历操作*/
class foreachall
{
static function array_val($array,$func)
{
if(is_array($array))
 {
foreach($array as $key=>$val)
  {
$array[$key]=self::array_val($val,$func);
  }
 }
else
 {
$array=$func($array);
 }
return $array;
}
#foreachall类结束#
}
?>