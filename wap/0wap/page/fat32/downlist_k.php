<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
?>
[html=我的任务队列]
[head]
[read=,fat32,text_list]文本[/read]-[read=,fat32,bin_dir]文件[/read]-队列[hr]
<?php
if(time()-file_get_contents(TEMP_DIR.'/lock/timedo.lock')>600)
 echo '错误：队列守护进程意外停止！[%read=,timedo,shouhu]重启[/read][br]';
if(time()-file_get_contents(TEMP_DIR.'/lock/timedo2.lock')>600)
 echo '错误：队列第二进程意外停止！[%read=,timedo,shouhu2]重启[/read][br]';
$db=session::conn();
$rs=$db->query("select count(*) as jc from session where zu='timedo'");
if($rs)
{
$jc=$rs->fetch(db::ass);
echo '队列中共有',$jc['jc'],'条未完成任务[br]';
if($fsize=filesize(file_get_contents(TEMP_DIR.'/lock/downlist1.lock')))
 echo '进程1下载:',fat32_f::echosize($fsize),'[br]';
if($fsize=filesize(file_get_contents(TEMP_DIR.'/lock/downlist2.lock')))
 echo '进程2下载:',fat32_f::echosize($fsize),'[br]';}
$set=new session($USER['uid'],'set',0,array('page'));
$set=$set['page'];
$hang=$set['hang'] or $hang=20;
$cnt=session::zucount($USER['uid'],'timedo');
$p=$_GET['p'] or $p=1;
$la=ceil($cnt/$hang);
if($p<0 or $p>$la)
 $p=$la;
$qi=($p-1)*$hang;
$s=new session($USER['uid'],'timedo',3600*24,"limit $qi,$hang");
$selfuu=urlencode($_SERVER['REQUEST_URI']);
if($cnt>0){
echo '你的队列中共',$cnt,'条未完成任务([%read=,fat32,del&amp;type=timedo&amp;u=',$selfuu,']清空[/read])[br][br]';
$jc=$qi;
$rs=$db->prepare("select count(*) as jc from session where name<? and name>? and zu='timedo'");
foreach($s as $eid=>$val){
if($val['type']=='downlist')
{
$head='[下载]';
$foot='(';
switch($val['ztn'])
 {
  case 1:
  $foot.='下载中';
  break;
  case 2:
   $foot.='等待续传';
  default:
   $foot.='排队中';
$rs->execute(array($eid,$eid>0 ? '0' : '-0'));
$wei=$rs->fetch(db::ass);
$foot.=' 第'.($wei['jc']+1).'位';
  break;
 }
$fname=USERFILE_DIR.'/'.$USER['uid'].'/'.$val['dir'].'/';
if($val['name'])
 $fname.=$val['name'].'.gz';
else
 $fname.='downlist.tmp.gz';if($val['ztn'])
 $foot.=fat32_f::echosize(filesize($fname));
$foot.=')';
}
$jc++;
echo $jc,'. ',$head,code::html($val['title']),$foot;
echo '([%read=,fat32,del&amp;type=timedo&amp;eid=',$eid,']删除[/read])';
echo '[br]';
}
if($p<$la)
 echo '[%read=,fat32,downlist_k&amp;p=',$p+1,']下页[/read]';
if($p>1)
 echo ' [%read=,fat32,downlist_k&amp;p=',$p-1,']上页[/read]';
echo " $p/",$la,'页';
#如果.有文件#结束
}
else
 echo '数据库说：如果你是新人，欢迎使用虎绿林队列系统，你可以去文件列表页面[%read=,fat32,bin_dir]新建一个下载任务[/read]，或者去[%read=,help,fat32_downlist]虎绿林帮助中心[/read]转转。[br]如果你是来查看任务的，那么很高兴地告诉你：你的所有任务已经全部完成！！';
?>
[hr]返回[read=,index,]首页[/read][br][time][foot]
[/html]