<?php
try
{
/*global $lockfile;
unlink($lockfile);
$fp=fopen(ROOT_DIR.'/downlist.log.txt','a+');*/
$ztn=$info['ztn'];
$info['ztn']=1;
$rs=$db->prepare('update session set nr=? where id=?');
$rs->execute(array(serialize($info),$id));
unset($rs);
$h=new http(false);
$h->open($info['url']);
$dir=USERFILE_DIR.'/'.$sid.'/'.$info['dir'].'/';
if(!is_dir($dir))
 fat32_f::mkdir($dir,0777);
if($ztn==2)
{
$fname=$dir.$info['name'].'.gz';
$size=filesize($fname);
$h->range($size);
$mode='a';
}
else
{
$fname=$dir.'downlist.tmp.gz';
$mode='w';
}
if(!$h->send())
{
$name=md5('');
throw new exception('＞下载失败。'.$h->errmsg);
}
stream_set_timeout($h->fp,540);
if($ztn==2 && !$h->isrange())
{
$name=md5('');
throw new exception('＞续传失败：该资源不支持断点续传。');
}
file_put_contents(TEMP_DIR.'/lock/downlist'.$TIMEDO['id'].'.lock',$fname);
$tmye=date('H',time());
$tmye=($tmye<10 or $tmye>21);
/* //悲剧的数据库防阻塞处理，反而阻塞了数据库
$db=null;
global $db;
$db=null;
session::$db=null;*/
$h->tofile($fname,$tmye ? 0 : 50*1024*1024,$mode);
/*$db=session::conn();*/
if($ztn==2)
 $name=$info['name'];
else
 $name=md5_file($fname);
/*$meta=stream_get_meta_data($h->fp);
if($istimeout=$meta['wrapper_data']['timed_out'])
{
$info['ztn']=2;
$info['name']=$name;
$rs=$db->prepare('update session set name=?,nr=? where id=?');
$rs->execute(array(time(),serialize($info),$id));
unset($rs);rename($fname,$dir.$name.'.tmp.gz');
return;
}
else
{*/
rename($fname,$dir.$name.'.gz');
throw new exception('');
}
/*}*/
catch(exception $ex){
$nr=serialize(array('title'=>$info['title'].$ex->getmessage(),));
$rs=$db->prepare('delete from session where sid=? and zu=? and name=?');
$rs->execute(array($sid,'dir.'.$info['dir'],$name));
unset($rs);$rs=$db->prepare('insert into session(sid,name,zu,nr,timeout) values(?,?,?,?,?)');
$rs->execute(array($sid,$name,'dir.'.$info['dir'],$nr,time()+7200));
unset($rs);
$rs=$db->prepare('delete from session where id=?');
$rs->execute(array($id));
unset($rs);
rename($fname,$dir.$name.'.gz');
return;
}
?>