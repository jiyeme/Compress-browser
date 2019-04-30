<?php
$fp = fsockopen("ssl://wodemo.com", 443, $errno, $errstr, 10);
if(!$fp) {
        echo "$errstr ($errno)<br>\n";
} else {
    $str1 = "GET / HTTP/1.0\nHost: wodemo.com\n\n";
    $str2 = "GET / HTTP/1.0\nHost: wodemo.com\n\n";
        fputs($fp,$str1);
        while(!feof($fp)) {
                echo fgets($fp,128);
        }
        fclose($fp);
}