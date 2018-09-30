<?php
#计划任务测试
$logfile=fopen(ROOT_DIR.'/timedo.log.txt','a+');
fwrite($logfile,date('H:i:s')."，{$info[name]}到此一游！\n");
if(time()-filemtime($dbbak=DB_DIR.'/bbs.db3.bak')>3600*3)
{
fclose($logfile);
unlink($logfile=ROOT_DIR.'/timedo.log.txt');
$logfile=fopen($logfile,'a+');
copy(DB_DIR.'/bbs.db3',$dbbak);
copy(DB_DIR.'/user.db3',DB_DIR.'/user.db3.bak');
fwrite($logfile,"论坛和用户数据库已备份\n");
$db->exec('OPTIMIZE TABLE session');
$db->exec('ANALYZE TABLE session');
fwrite($logfile,"表已优化\n");
if(date('H')<6)
 {
fat32_f::delete('../mrptmp');
fat32_f::delete('../fctmp',true);
fat32_f::delete('../qbtmp',true);
fat32_f::delete('../soutmp',true);
fat32_f::delete('../mailtmp',true);
fat32_f::delete($_SERVER['DOCUMENT_ROOT'].'/temp',true);
 }
}
else
 sleep(5);
#mkdir('../qbtmp',0777);
#mkdir('../fctmp',0777);
mkdir('../mrptmp',0777);
#mkdir('../soutmp',0777);
$rs=$db->query("select sid,zu,name from session where timeout<".(time()+3600)." and timeout>0 and zu>'dir' and zu<'dis'");
if(!$rs)
 return;
while($nr=$rs->fetch(db::ass))
{
$fnm=$nr['sid'].'/'.substr($nr['zu'],4).'/'.$nr['name'].'.gz';
if(unlink(USERFILE_DIR.'/'.$fnm))
 fwrite($logfile,"删除{$fnm}\n");
}
$db->exec("delete from session where timeout<".(time()+3600)." and timeout>0");
fclose($logfile);
?>