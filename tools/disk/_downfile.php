<?php
!defined('m') && header('location: /?r='.rand(0,999));

$dir = $browser->db->fetch_first('SELECT title,file,mime,size FROM `disk_file` WHERE id='.$id);
if ( !$dir ){
	require ROOT_DIR.'tools/disk/_nofoundfile.php';
	exit;
}

@ob_end_clean();
@ob_start();
@set_time_limit(7200);

if ( !cloud_storage::exists('disk_' . $dir['file']) ){
	//var_dump($dir);exit;
	echo '文件丢失?';
	//header('HTTP/1.0 404 Not Found');
	exit;
}

$filecontent = @cloud_storage::read('disk_' . $dir['file']);

$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$filename = $dir['title'];
$encoded_filename = urlencode($filename);
$encoded_filename = str_replace('+', '%20', $encoded_filename);

if (preg_match("/MSIE/", $ua)) {
	header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
}elseif (preg_match("/Firefox/", $ua)) {
	header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
} else {
	header('Content-Disposition: attachment; filename="' . $filename . '"');
}
header('Content-Encoding: none');
Header('Content-Length: '.strlen($filecontent));
Header('Content-type: '.get_file_mime(get_short_file_mime($filename)));

@ob_flush();
flush();
/*if (!$file = @fopen($dir['file'],'rb')){
	exit;
}
while ( !@feof($file) ) {
	echo @fread($file,1024);
	ob_flush();
	flush();
}
fclose($file);*/
echo $filecontent;
exit;