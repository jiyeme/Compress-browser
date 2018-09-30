<?php
try{
[%getuser]
if(!$USER['islogin'])
 headecho::gotologin('',true);
$fn=TEMP_DIR.'/lock/brw.lock';
$jc=file_get_contents($fn) or $jc=0;
if(!$_POST['go'])
{
?>
[html=]
[form=post,read.php?[u.b]&amp;[u.c]&amp;pid=shouhu&amp;zu=1[u.sid]]
请粘贴139博客编辑页的地址（84改80端口）：[br][input=url]<%=file_get_contents('url.txt')%>[/input]
[submit=go]立即体验[/submit]
[/form]
<?php
echo '限50人，已有',$jc,'人加入[/html]';
}
else
{
set_time_limit(7200);
$url=$_POST['url'];
file_put_contents($fn,$jc+1);
if(limit::访问('brw',7200))
 throw new exception('系统：你已经加入了，赶快去体验吧！如果你填错了地址，那么很遗憾，我无法帮你。');
if($jc>=50)
 throw new exception('内测人数已满！');
$brw=new brw_main('brw_139blog',$url);
$brw->puttext(date("内容编辑中，请管理员不要删除！\n今天是Y-m-d H:i:s"),'[内容编辑中……]');
while(true)
 {
 $brw->exec();
 //sleep(1);
 }
}
}catch(exception $e)


{
?>

[html=发生错误！]
错误信息：<?php echo $e->getmessage(); ?>
[/html]
<?php
file_put_contents($fn,file_get_contents($fn)-1);
}
?>