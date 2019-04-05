<?php
//define('SYSTEM_ERROR',1991);



/*

function function_error_handler($errno, $errstr, $errfile, $errline ) {
	header('Content-Type: text/html; charset=utf-8');
	switch( $errno ){
	case E_USER_WARNING:
		die($errstr);
		break;
	default:
        echo "Unknown error type: [$errno] $errstr<br />$errfile<br />$errline<br />\n";
		break;
	}

}
set_error_handler('function_error_handler');
*/

function function_exception_handler($e) {
	$errno = $e->getCode();
	$errstr = $e->getMessage();
	$errline = $e->getLine();
	$errfile = $e->getFile();
	$trace = $e->getTrace();
	$errfile = substr($errfile,strlen(substr(__FILE__,0,-strlen('\inc\class\error.exception.php'))));
	header('Content-Type: text/html; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="application/vnd.wap.xhtml+xml; charset=utf-8"/>
<meta http-equiv="Cache-Control" content="must-revalidate,no-cache"/>
<title>系统繁忙</title>
<style>body{font-size:15px;color:#000;font-family:Arial,Helvetica,sans-serif;}a{color:#039;text-decoration:none;}</style>
</head><body>';
	echo '<strong>玖玩浏览器</strong>(<a href="/?">首页</a>)<hr/>';
    switch ($errno) {
    case E_USER_ERROR:
        echo "异常：$errstr<br />\n";
        echo "文件：$errfile<br />\n";
        echo "行号：$errline<br />\n";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
		echo $e->getTraceAsString();
        break;

    case E_USER_WARNING:
        echo "警告：$errstr";
        break;

    case E_USER_NOTICE:
        echo "提示：$errstr";
        break;

    default:
        echo "错误：$errstr";
        break;
    }
	echo '<hr/>';
	echo 'Powered By <a href="http://jiuwap.cn">jiuwap.cn</a>';
	echo '</body></html>';
	exit;

	//	echo $message;
	//	echo
}

set_exception_handler('function_exception_handler');

	//throw new Exception('错误的属性',E_USER_NOTICE);
