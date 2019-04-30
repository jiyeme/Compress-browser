<?php

$install_version = '20190419';
require 'inc.php';

$version = 0;
if(file_exists('../set_config/version.php'))
    include '../set_config/version.php';

$do = isset($_GET['do']) ? $_GET['do'] : '';

if ( $do == 'clear' ) {
	require 'clear.php';
}elseif ( $do == 'unstall' ) {
	require 'unstall.php';
}elseif ( $do == 'sql' ) {
	require 'install_sql.php';
}elseif ( $do == 'update' ) {
	require 'update_check.php';
}elseif ( $version < $install_version || $do == 'install_full' ){
	//require 'install_sql.php';
	require 'install_full.php';
}else{
	require 'main.php';
}

