<?php
($c_bc=$_GET['bc'])!='' or ($c_bc=$_COOKIE['hu60_rd_bc'])!='' or $c_bc='FFFFFF';
($c_xc=$_GET['xc'])!='' or ($c_xc=$_COOKIE['hu60_rd_xc'])!='' or $c_xc='000000';
if($c_xc=='717372'&&$c_bc=='FFFFFF') $c_bc='111312';
if($c_bc=='111312' or $_GET['ye'])
{
if($c_xc=='000000') $c_xc='717372';
$c_hr='995555';
$c_lk='567756';
$c_tp='252525';}
else {$c_hr='BED8EA';
$c_lk='08C';
$c_tp='E6F3FF';}
$pagecss='body{margin:0px;padding:4px;background-color:#'.$c_bc.';color:#'.$c_xc.';}
p{margin:0px;padding:2px;}
div{margin:0px;padding:2px;}
.tp{margin:0px;background-color:#'.$c_tp.';border:1px solid #'.$c_xc.';}
a{text-decoration:none;color:#'.$c_lk.';}
form{margin:0px;padding:0px;}
img{border:none;}
hr{height:1px;border:1px solid #'.$c_hr.';border-left:none;border-right:none;}
.bk1{border:1px solid #'.$c_hr.';background-color:#'.$c_tp.';}
.bk2{border:1px solid #'.$c_lk.';}
';
?>