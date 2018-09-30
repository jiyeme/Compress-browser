<?php
define('DEFINED_JIUWAP','jiuwap.cn');
include "E:/wamp64/www/".'/inc/common.php';
$browser->user_login_check();

if ( isset($_GET['yes']) ){
   $browser->user_logout();
   load_template('logout_success',false);
}else{
   load_template('logout_check',false);
}