<?php
define('DEFINED_JIUWAP','jiuwap.cn');
include "/home/jysafec1/public_html/yl/".'/inc/common.php';
$browser->user_login_check();

if ( isset($_GET['yes']) ){
   $browser->user_logout();
   load_template('logout_success',false);
}else{
   load_template('logout_check',false);
}