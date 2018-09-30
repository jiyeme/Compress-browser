<?php
!defined('m') && header('location: /?r='.rand(0,999));

$str = id2password($id).'/'.urlencode($dir['title']);
echo '(可以外链,链接将在十分钟后失效)<br/>';
echo '<input type="text" value="http://'.$b_set['host'].'/disk.php/'.$str.'"/><br/>';
echo '<a href="disk.php/'.$str.'">下载文件</a><br/>';
echo '<a href="http://f.10086.cn/d/dlkjava.fcc?clientId=938&commondownloadUrl=http://'.$b_set['host'].'/disk.php/'.$str.'">fl下载jsp后缀文件</a><br/>';
echo '<a href="http://mnews.i139.cn/pams/msproxy.do?L=http://'.$b_set['host'].'/disk.php/'.$str.'&M=A672AC1F36B65AE83A667707C7B6517795489106">shjb下载jsp后缀文件</a><br/>';
echo '<a href="disk.php?cmd=info&amp;id='.$id.$h.'">返回文件</a>';
