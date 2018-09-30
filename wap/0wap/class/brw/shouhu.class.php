<?php
class brw_shouhu
{
static $s;
static $zu;
static function start($zu)
{
if(self::exists(true))
 throw new exception('进程已启动',1);
self::$zu=$zu;
return self::$s=new session(0,'brw.'.$zu,3600*5,'');
}
static function exists($look=false,$zu=null)
{
if($zu===null)
 $zu=self::$zu;
$eid='brw.'.$zu;
$s=new session(0,'limit',0,array($eid));
if($look)
 {
$tm=$s[$eid];
if(time()<$tm+30)
 return true;
else
 return false;
 }
elseif($look===null)
 {
  $s[$eid]=0;
 }
else
 {
  $s[$eid]=time();
 }
}
static function save(&$info)
{
self::$s->info=null;
self::$s->getinfo();
foreach($info as $n=>$v)
 {
if(isset(self::$s[$n]))
 self::$s[$n]=$v;
 }
}
static function reopen($zu=null)
{
$url="http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?cid=brw&pid=shouhu&zu=".($zu===null ? self::$zu : $zu);
$h=new httplib;
$h->open($url,5,0);
return $h->send();
}
static function check($start,$end)
{ for($i=$start;$i<=$end;$i++)
 {
!self::exists(true,$i) && self::reopen($i);
 }
}
/*CLASS END*/
}
?>