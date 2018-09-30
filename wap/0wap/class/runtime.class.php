<?php
/**
runtime
运行时间类
静态方法：
  gettime()  返回精确到4位小数的系统时间(秒)
普通方法：
  start()  开始计时，并返回开始时刻
  stop()  停止计时，并返回停止时刻
  spent( int 小数位数 默认为4位 )  返回所用时间
普通成员：
  $start  开始时刻
  $stop  停止时刻
**/
class runtime
{
public $start=0;
public $stop=0;
static function gettime()
{
return microtime(true);
}
function start()
{
$this->start=self::gettime();
}
function stop()
{
$this->stop=self::gettime();
}
function spent($i=4)
{
$time=$this->stop-$this->start;
return round($time,$i);
}
#runtime类结束
}
?>