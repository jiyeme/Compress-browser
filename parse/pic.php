<?php
/*
 *
 *	浏览器->图片
 *
 *	2011-4-3 @ jiuwap.cn
 *
 */


!defined('m') && header('location: /?r='.rand(0,999));

$_GET['p'] = trim($_GET['p']);
$arr = $browser->cache_get('pic',$_GET['p']);


if ( !isset($arr['url']) || empty($arr['url']) ){
	header('HTTP/1.0 404 Not Found');
	exit;
}else{
	$url = $arr['url'];
}

$referer = $arr['referer'];
$url_A = parse_url($url);
if ( !isset($url_A['path']) ){
	$url_A['path'] = '/';
}

//读取数据库COOKIE
$cookies = $browser->cookieGet($url_A['host'] ,$url_A['path']);

$filename = 'tpic_' . sha1($url);
if ( $referer ){
    $filename .= substr(md5($referer),5,10);
}

if ( $browser->tempfile_exists($filename) ){
	$filecontent = @$browser->tempfile_read($filename);
	@file_put_contents($filename,$filecontent);
	$arr = @GetImageSize($filename);
	@unlink($filename);
	if ( $arr === false ){
		header('HTTP/1.0 404 Not Found');
		exit;
	}
	Header('Content-type: '.$arr['mime']);
	echo $filecontent;
	exit;
}

if ( $browser->pic_wap == 0 && in_array($arr['mime'],array('text/vnd.wap.wml','application/vnd.wap.xhtml+xml','application/vnd.wap.wmlc','text/vnd.wap.wmlscript','application/vnd.wap.wmlscriptc')) ){
	$zip = false;
}else{
	$zip = true;
}
unset($arr);

//检测是否嵌套浏览
include ROOT_DIR.'set_config/set_forbidhost.php';
if ( in_array(strtolower($url_A['host']),$b_set['forbid']) && !stripos($url,$b_set['host'].'/self') ){
	header('HTTP/1.0 404 Not Found');
    exit;
}

$http = new httplib();
$http->set_dns($browser->dns_getAll());
$http->set_referer($referer);
$http->set_timeout(30);
$http->set_location(3);
if ( $http->open($url) == false ){
	header('HTTP/1.0 404 Not Found');
    exit;
}
$url_A = $http->get_urls();

//发送COOKIE
foreach($cookies as $cookie_key=>$cookie_value){
	$http->put_cookie($cookie_key,$cookie_value);
}
unset($cookies,$cookie_key,$cookie_value);

$browser->selectBrowserUA();

//开始
$http->send();

//获取返回头信息
$header = $http->get_headers();
$mime = array('image/gif'=>'gif','image/jpeg'=>'jpg','image/bmp'=>'bmp','image/jpg'=>'jpg','image/png'=>'png');
if( !isset($header['CONTENT-TYPE']) ){
	header('HTTP/1.1 404 Not Found');
    exit;
}

$i = strpos($header['CONTENT-TYPE'],';');
if ( $i !== false ){
	$header['CONTENT-TYPE'] = substr($header['CONTENT-TYPE'],0,$i);
}
unset($i);

$header['CONTENT-TYPE'] = strtolower($header['CONTENT-TYPE']);
if ( !isset($mime[$header['CONTENT-TYPE']]) || isset($header['CONTENT-LENGTH']) && $header['CONTENT-LENGTH'] > 2000000){
	header('HTTP/1.1 404 Not Found');
    exit;
}


//保存返回的COOKIE
foreach ( $header['COOKIE'] as $key => $value){
	$browser->cookieSave($url_A['host'],$value['domain'],$key,$value['value'],$value['path'],$value['expires']);
}


$mime = $mime[$header['CONTENT-TYPE']];

//保存文件
$pic_file_content = $http->get_body();



$browser->tempfile_write($filename, $pic_file_content);


file_put_contents($filename, $pic_file_content);

unset($http);
$arr = GetImageSize($filename);

if ( $arr === false ){
	exit;
}

$width = $arr[0];
$height = $arr[1];

//开始处理图片咯！
switch ($arr[2]) {
	case 1://gif
		$im = ImageCreateFromGIF($filename);
		break;
	case 2://jpg
		$im = imagecreatefromJPEG($filename);
		break;
	case 3://png
		$im = ImageCreateFromPNG($filename);
		break;
	case 6://bmp
		$im = ImageCreateFromBMP($filename);
		break;
	default://
		$zip = true;
		break;
}

@unlink($filename);

if ( !$zip || $browser->pic == 6){
	Header('Content-type: '.$arr['mime']);
	echo $pic_file_content;
	exit;
}

//图片质量
switch ($browser->pic) {
case 8:
	$deep = 80;
	$N_height = 500;
	$N_width = 500;
	break;
case 3:
	$deep = 70;
	$N_height = 400;
	$N_width = 400;
	break;
case 7:
	$deep = 50;
	$N_height = 320;
	$N_width = 320;
	break;
case 2:
	$deep = 20;
	$N_height = 320;
	$N_width = 240;
	break;
default:
	$deep = 70;
	$N_height = 320;
	$N_width = 320;
	break;
}

if ( $height > $N_height ){
	$i = $height / $N_height;
	$width = $width / $i;
	$height = $N_height;
}
if ( $width > $N_width ){
	$i = $width / $N_width;
	$height = $height / $i;
	$width = $N_width;
}

$srcW = ImageSX($im);
$srcH = ImageSY($im);
$ni = imagecreatetruecolor($width,$height);
if ($arr[2]!=6){
	imagealphablending($ni,false);
}
if ($arr[2]==1){
	$black = ImageColorAllocate($ni, 0,0,0);
	imagecolortransparent($ni,$black);
}elseif ($arr[2]==3){
	imagesavealpha($ni,true);
}
ImageCopyResized($ni,$im,0,0,0,0,$width,$height,$srcW,$srcH);

switch ($arr[2]) {
	case 1://gif
		Header('Content-type: image/gif');
		ImageGif($ni,$filename, $deep);
		break;
	case 2://jpg
		Header('Content-type: image/jpeg');
		ImageJpeg($ni,$filename, $deep);
		break;
	case 3://png
		Header('Content-type: image/png');
		$deep = (int)($deep/10);
		if ( $deep == 10 ){
			$deep = 9;
		}
		ImagePng($ni,$filename,$deep);
		break;
	case 6://bmp
		Header('Content-type: image/jpeg');
		ImageJpeg($ni,$filename, $deep);
		break;
	default://
		Header('Content-type: image/jpeg');
		ImageJpeg($ni,$filename, $deep);
		break;
}
ImageDestroy($im);

$pic_file_content2 = @file_get_contents($filename);
@unlink($filename);

$old = strlen($pic_file_content);
$new = strlen($pic_file_content2);
if ( $new >= $old  ){
	$browser->tempfile_write($filename, $pic_file_content);
	unset($pic_file_content2);
	echo $pic_file_content;
}else{
	$new = $old - $new;
	$browser->num_add(0,$new);
	unset($pic_file_content);
	echo $pic_file_content2;
}
exit;