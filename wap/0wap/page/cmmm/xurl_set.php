[html=XDownload种子生成器]
[form=post,read.php?[u.b]&amp;[u.c]&amp;pid=xurl_down&amp;d=<%=time()%>[u.sid]]
[h=timeout]<%=$_POST['timeout']%>[/h]
[h=restart]<%=$_POST['restart']%>[/h]
[h=apn]<%=$apn=$_POST['apn']%>[/h]
[h=every]<%=$every=$_POST['every']%>[/h]
[h=proxy]<%=$proxy=$_POST['proxy']%>[/h]
[h=count]<%=$count=$_POST['count']%>[/h]
[h=icount]<%=$icount=$_POST['icount']%>[/h]
<?php
for($x=0;$x<$count;$x++)
{
if($every)
 echo '接入点:<input name="apn'.$x.'"/>[br]代理:<input name="proxy'.$x.'"/><br/>';
echo '保存名称:<input name="name'.$x.'" value="QQDownload/"><br/>地址:[br]';
for($y=0;$y<$icount;$y++)
 {echo '<input name="url'.$x.'_'.$y.'" value="http://"/><br/>';}
echo '[hr]';
}
?>
[submit=go]点击下载[/submit]
[/form]
[/html]