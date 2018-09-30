<?php
$df=new session(0,'downlist_file',3600*24,'order by name asc limit 21');
$jb=$df[0];
$tm=time();
if($jb['jc']>0 && $tm-$jb['tm']<3600)
 exit;
else
{
$jb['jc']++;
$jb['tm']=$tm;
$s[0]=$jb;
}
