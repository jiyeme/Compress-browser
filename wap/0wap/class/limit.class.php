<?php
class limit
{
static function 访问($zu,$time)
{
#限制访问速度。参数：分组、秒数。
#被限制返回真，否则返回假
global $USER;
$s=new session($USER['uid'],'limit',0,array($zu),false);
$tm=time();
$n=$s[$zu];
if($n&&$tm-$n<$time)
 return true;
else
{
$s[$zu]=$tm;
return false;
}
}
#class end
}
?>