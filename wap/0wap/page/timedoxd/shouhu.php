<?php
$TIMEDO['id']=1;
#计划任务守护进程#
$lockfile=TEMP_DIR.'/lock/timedo.lock';
$myurl="http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$lasttm=file_get_contents($lockfile);
if(time()-$lasttm<600)
 exit('队列已启动');
else
 file_put_contents($lockfile,time());
set_time_limit(0);
ini_set('memory_limit','64M');
$db=session::conn();
$rs=$db->query("select id,sid,nr,timeout from session where zu='timedo' and name>'0' order by name asc limit 10");
if(!$rs)
{
file_put_contents($lockfile,time()-300);
exit;
}
while($nr=$rs->fetch(db::ass))
{
$info=unserialize($nr['nr']);
timedo($db,$nr['id'],$nr['sid'],$info,$nr['timeout']);
file_put_contents($lockfile,time());
sleep(1);
}
unset($rs);
file_put_contents($lockfile,0);
sleep(1);
$h=new httplib();
$h->open($myurl,30,5);$h->send();
exit;
#计划任务调用函数#
function timedo($db,$id,$sid,$info,$timeout)
{
global $TIMEDO;
$path=PAGE_CDIR.'/sub/'.$info['type'].'.sub.php';
 //file_put_contents('0.txt',serialize($info));
include $path;
}
#守护程序结束#
?>