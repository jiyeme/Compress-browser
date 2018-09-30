<?php
/*ob_start('dolog');
function dolog($nr)
{file_put_contents('brw.txt',$nr);return $nr;}*/
set_time_limit(3600*5);
register_shutdown_function('brw_end');
$zu=floor($_GET['zu']);
$s=brw_shouhu::start($zu);
foreach($s as $n=>$v)
{
 $info[$n]=$v;
}
$done=false;
$tm=time();
for($i=0;true;$i++)
{
foreach($info as &$v_s)
{
 $done=true;
 $brw=new brw_main('brw_139blog',$v_s['blogurl'],$v_s);
 $brw->exec();
brw_shouhu::exists();
}
if(!$done)
 break;
$done=false;
if(time()-$tm>60)

 {
$tm=time();
$s->info=null;
$s->getinfo();
foreach($s as $n=>$v)
 {
$info[$n]['blogurl']=$v['blogurl'];
 }
foreach($info as $n=>$v)
 {
if(!isset($s[$n]))
 unset($info[$n]);
 }
 }
//unset($v_s);
}
brw_end();
function brw_end()
{
global $done,$info,$zu;
if($done)
{
$done=false;
brw_shouhu::save($info);
brw_shouhu::exists(null);
brw_shouhu::check(1,5);
}
}
?>
[html=提示]进程已结束，可能是由于任务数为空。[/html]