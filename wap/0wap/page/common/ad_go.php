<?php
[%getuser]
$aid=floor($_GET['aid']);
$s=new session(0,'ad',0,array($aid));
$s=$s[$aid];
if(!$s)
 headecho::location('read.php',true);
else
 headecho::location($s['url']);
$fp=fopen('./ad.log.txt','a');
fwrite($fp,'['.date('m-d H:i')."] {$USER['name']}({$USER['uid']}) {$s['title']}\r\n");
fclose($fp);
?>