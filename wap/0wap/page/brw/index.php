[html=网页中转-进程]
[head]
<?php
if(!$USER['islogin'])
 headecho::gotologin('',true);
for($i=1;$i<=5;$i++)
{
echo '进程',$i,'(',session::zucount(0,'brw.'.$i),'人)';
if(brw_shouhu::exists(true,$i))
 echo '正常';
else
 echo '[%read=,brw,shouhu&amp;zu=',$i,']重启[/read]';
echo '[br]';
}
?>
[form=post,read.php?[u.b]&amp;[u.c]&amp;pid=add]
组：[input=zu,2][/input](1-5)[br]
博客URL：[input=url][/input][br]
[submit=go]加入[/submit][submit=go]移除我的任务[/submit]
[/form]
[hr][time]
[/html]