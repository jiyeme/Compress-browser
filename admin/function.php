<?php
function html_size($size) {
if($size>1024&$szie<(1024*1024)){
return round($size/1024,2)."KB";
}elseif($size>(1024*1024)&&$hsize<(1024*1024*1024)) {
return round($hsize/(1024*1024),2)."MB";
}else{
return "0KB";
}
}
?>