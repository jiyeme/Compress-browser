<?php
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$dir=str::word($_REQUEST['dir']);
$path=USERFILE_DIR.'/'.$USER['uid'].'/'.$dir;
$s=new session($USER['uid'],'dir',0,array($dir));
$s=$s[$dir];
if(!$s)
{ ?>
[html=错误]文件夹不存在，请[read=,fat32,bin_dir]返回重选[/read]！[/html]
<?php exit;
}
if(!$_POST['go'])
{
?>
[html=添加到下载队列]
文件将被保存到 <%=$s['title']%>[br]<?php
}
?>