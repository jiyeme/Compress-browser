<?php
$version = '0';
@include 'set_config/version.php';
if ( $version == 0 ){
   header('LOCATION: /install/index.php');
    exit;
}

define('DEFINED_JIUWAP','jiuwap.cn');
include "/home/jysafec1/public_html/yl/".'/inc/common.php';
if ( isset($_GET['yes']) ){
    if ( $browser->user_login($_GET['name'],$_GET['pass']) ){
      load_template('login_success',false,'index.php?r='.$browser->rand,false,'utf-8',5);
    }else{
      load_template('login_fail',false);
    }
}else{
   load_template('login_form');
}