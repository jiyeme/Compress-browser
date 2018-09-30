<?php
set_time_limit(0);
for($i=0;true;$i++)
{
$fp=fopen('0.txt','a+');
fwrite($fp,$i.". ".date("m-d H:i")."\n");
fclose($fp);
sleep(3600);
}
?>