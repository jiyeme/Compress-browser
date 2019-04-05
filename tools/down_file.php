<?php
/*
 *
 *	浏览器->中转下载
 *
 *	2011-4-3 @ jiuwap.cn
 *
 */

!defined('m') && header('location: /?r='.rand(0,999));

require_once ROOT_DIR.'tools/disk/inc.php';

$arr = $browser->cache_get('pic',$id);
if ( !isset($arr['url']) || empty($arr['url']) ){
	error_show('文件信息丢失(11),请重新下载。('.$id.')');
}

//$filename = $browser->uid.'_'.sha1($arr['url']);
if ( false !== ($content = $browser->temp_read('return_down',sha1($arr['url'])) ) ) {
	if ( !$content = @unserialize($content) ){
		error_show('文件信息损坏(12),请重新下载。('.$id.')');
	}
}else{
	error_show('文件信息丢失(2),请重新下载。('.$id.')');
}
if ( $content['size'] > $b_set['tdown'] ){
	error_show('当前系统不允许中转下载大于'.bitsize($b_set['tdown']).'的文件。('.$id.')');
}

$filecontent = $browser->tempfile_read(sha1($arr['url']));
if ( $filecontent !== false  ){
    @ob_end_clean();
    @ob_start();
    @set_time_limit(7200);

	header('Date: '.gmdate_('D, d M Y H:i:s') . ' GMT');
	header('Last-Modified: '.gmdate_('D, d M Y H:i:s') . ' GMT');
	$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	$encoded_filename = urlencode($content['name']);
	$encoded_filename = str_replace('+', '%20', $encoded_filename);

	if (preg_match("/MSIE/", $ua)) {
		header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
	}elseif (preg_match("/Firefox/", $ua)) {
		header('Content-Disposition: attachment; filename*="utf8\'\'' . $content['name'] . '"');
	} else {
		header('Content-Disposition: attachment; filename="' . $content['name'] . '"');
	}
	header('Content-Encoding: none');
	Header('Content-Length: '.$content['size']);
	Header('Content-Type: '.$content['type']);


	@ob_flush();
	flush();
	/*if (!$fp = @fopen($b_set['rfile'].$filename,'rb')){
		exit;
	}
	while(!feof($fp)){
		echo fread($fp,1024);
		ob_flush();
		flush();
	}
	fclose($fp);*/
	echo $filecontent;
	exit;
}

$http = new httplib();
$http->set_dns($browser->dns_getAll());
//$http->set_referer($arr['referer']);
/*
if( !empty($browser->ipagent) ){
	$ip = explode(':',$browser->ipagent);
	$http->proxy(trim($ip[0]),trim($ip[1]));
	unset($ip);
}*/
$http->set_timeout(30);
$http->set_location(3);
$http->open($arr['url']);

$cookies = $content['cookie'];
foreach($cookies as $cookie_key=>$cookie_value){
	$http->put_cookie($cookie_key,$cookie_value);
}
unset($cookies,$cookie_key,$cookie_value);

$headers = $content['header'];
foreach($headers as $header_key=>$header_value){
	$http->put_header($header_key,$header_value);
}
unset($headers,$header_key,$header_value);

$http->send();
$header = $http->get_headers();

if ( !isset($header['STATUS']) || $header['STATUS'] != '200' ){
	error_show('连接目标文件失败,请重新下载。('.$id.')');
}
if ( !isset($header['CONTENT-LENGTH']) || $header['CONTENT-LENGTH'] - $content['size'] >200 || $content['size'] - $header['CONTENT-LENGTH'] >200 ){
	error_show('文件信息损坏(2),请重新下载。('.$id.')');
}
if ( isset($header['CONTENT-LENGTH']) ){
	$content['size'] = $header['CONTENT-LENGTH'];
}

@ob_end_clean();
@ob_start();
@set_time_limit(7200);

header('Date: '.gmdate_('D, d M Y H:i:s') . ' GMT');
header('Last-Modified: '.gmdate_('D, d M Y H:i:s') . ' GMT');
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$encoded_filename = urlencode($content['name']);
$encoded_filename = str_replace("+", "%20", $encoded_filename);
if (preg_match("/MSIE/", $ua)) {
	header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
}elseif (preg_match("/Firefox/", $ua)) {
	header('Content-Disposition: attachment; filename*="utf8\'\'' . $content['name'] . '"');
} else {
	header('Content-Disposition: attachment; filename="' . $content['name'] . '"');
}
header('Content-Encoding: none');
Header('Content-Length: '.$content['size']);
Header('Content-Type: '.$content['type']);
//header('Content-Transfer-Encoding: binary');
ob_flush();
flush();
$str = $http->get_body();
echo $str;
ob_flush();
flush();


$browser->tempfile_write(sha1($arr['url']),$str);
exit;