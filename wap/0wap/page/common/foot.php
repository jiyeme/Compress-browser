<?php
global $PAGE;
if($PAGE['bid']=='xhtml')
 $_GET['bid']='wml';
else
 $_GET['bid']='xhtml';
$f_url='read.php?'.str::geturlquery($_GET,true);
 echo '[hr][tp]';
[iswml]echo '[[url=',$f_url,']WAP2.0版[/url]][[url=#main]返回顶部[/url]]';[/iswml]
[isxhtml]if(($f_trd=mt_rand(1,3))==1)
 $f_t='按3回顶';
elseif($f_trd==2)
 $f_t='按6到底';
else
 $f_t='按8刷新';
echo '[[url=',$f_url,']WAP1.0版[/url]][[url=#top]',$f_t,'[/url]]';[/isxhtml]
echo '[br]';
$STOP_TIME=microtime(true);
$SPENT_TIME=$STOP_TIME-$START_TIME;
echo '执行: ',round($SPENT_TIME,4),'秒 (压缩:';
if($PAGE['gzip'])
 echo '开)';
else
 echo '关)';
$AD['site']='bottom';
include ubb::page($PAGE['bid'],'common','ad');
echo '<br/>'.str_replace('"read.php?','"/wap/read.php?',file_get_contents('../temp/new.htm')).'<br/>';
?>[/tp]