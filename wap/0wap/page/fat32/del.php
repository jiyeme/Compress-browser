<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$type=$_REQUEST['type'];
$dir=str::word($_REQUEST['dir']);
$eid=str::word($_REQUEST['eid']);
if($type=='text')
{
$cur='text_list';
if($eid=='')
 $hep='所有文本';
else
 $hep='文本'.$eid;
}
elseif($type=='dir')
{
$cur='bin_dir';
if($dir=='')
 $hep='所有文件夹和文件';
else
 $hep='文件夹'.$dir.'以及里面所有的文件';
}
elseif($type=='bin'&&$dir!='')
{
$cur='bin_list';
if($eid=='')
 $hep='文件夹'.$dir.'里面所有的文件';
else
 $hep='文件夹'.$dir.'里面的文件'.$eid;
}
elseif($type=='timedo')
{
$cur='downlist_k';
if($eid=='')
 $hep='队列中的所有任务';
else
 $hep='队列中的任务'.$eid;
$hep.='（注意：如果你的任务已经被任务执行器读取，即使你在这里删除了任务，它还是会被执行。任务执行器每次读取10条任务。）';
}
else
 headecho::location('read.php?[%%u.b]&cid=error&pid=query',true);
if(!$_POST['go'])
{
?>
[html=文本/文件数据清理]
[form=post,read.php?[u.b]&amp;[u.c]&amp;[u.p][u.sid]]
[h=type]<%=$type%>[/h]
[h=dir]<%=$dir%>[/h]
[h=eid]<%=$eid%>[/h]
你确定要删除<%=$hep%>吗？删除后是不能再恢复的，到时可别后悔哦。[br][read=,fat32,<%=$cur,'&amp;dir=',$dir,'&amp;eid=',$eid%>]我不删啦，回去看看先[/read]
[br][submit=go]删吧[/submit]，神马都是浮云
[/form]
[time][foot]
[/html]
<?php }
else
{
$db=session::conn();
$db->begintransaction();
if($type=='timedo')
{
$sql='delete from session where sid=? and zu=?';
$arr[]=$USER['uid'];
$arr[]='timedo';
if($eid!='')
 {
$sql.=' and name=?';
$arr[]=$eid;
 }
}
elseif($type=='text')
 {
$sql='delete from session where sid=? and zu=?';
$arr[]=$USER['uid'];
$arr[]='text';
if($eid!='')
  {
$sql.=' and name=?';
$arr[]=$eid;
  }
 }
elseif($type=='dir')
 {
$sql='delete from session where sid=?';
$arr[]=$USER['uid'];
if($dir=='')
{
$sql.=' and zu>=? and zu<?';
$arr[]='dir';
$arr[]='dis';
$drn=USERFILE_DIR.'/'.$USER['uid'];
}
else
{
$sql.=' and zu=?';
$arr[]='dir.'.$dir;
 #$db=session::conn();
$rs=$db->prepare('delete from session where sid=? and zu=? and name=?');
$rs->execute(array($USER['uid'],'dir',$dir));
$drn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir;
}
fat32_f::delete($drn,false,2);
 }
elseif($type=='bin')
 {
$sql='delete from session where sid=? and zu=?';
$arr[]=$USER['uid'];
$arr[]='dir.'.$dir;
if($eid=='')
{
$drn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir;
fat32_f::delete($drn,false,2);
fat32_f::mkdir($drn,0777);
}
else
{
$sql.=' and name=?';
$arr[]=$eid;
$drn=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir.'/'.$eid.'.gz';
unlink($drn);
}
 }
 #echo $sql;
 #var_dump($arr);
 #$db=session::conn();
$rs=$db->prepare($sql);
$rs->execute($arr);
$db->commit();
/*headecho::location(*/
?>[html=已删除]<?php
echo '目标已删除，<a href="read.php?[%%u.b]&amp;cid=fat32&amp;pid='.$cur.'&amp;dir='.$dir.'&amp;eid='.$eid.'">返回</a>[/html]';
}
?>