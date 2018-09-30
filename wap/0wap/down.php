<?php
$myname=$_SERVER['SCRIPT_NAME'];
include "./config.inc.php";
//include SUB_DIR.'/ddos.sub.php';
$url=substr($_SERVER['REQUEST_URI'],strlen($myname)+1);
$op=explode('/',$url);
$down=new httpdownload();
if($down->set_byfile(USERFILE_DIR.'/'.str::word($op[0]).'/'.str::word($op[1]).'/'.str::word($op[2]).'.gz'))
 {
//$fname=explode('?',$op[3]);
//$down->filename=$fname[0];

if($_GET['mime'])  $down->mime=$_GET['mime'];
$down->download();
}
?>