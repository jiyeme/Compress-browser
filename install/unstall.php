<?php

include('../set_config/set_config.php');
try{
	if ( !$db = @mysql_connect($b_set['db']['server'],$b_set['db']['user'],$b_set['db']['pass'])){
		throw new Exception('连接数据库失败,'.mysql_error());
	}
	@mysql_query('CREATE DATABASE `'.$b_set['db']['table'].'` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');
	if( !$conn2 = @mysql_select_db($b_set['db']['table'],$db)){
		throw new Exception('打开数据库失败,'.mysql_error());
	}

	@mysql_query('DROP TABLE `browser_books`');
	@mysql_query('DROP TABLE `browser_caches`');
	@mysql_query('DROP TABLE `browser_cookies`');
	@mysql_query('DROP TABLE `browser_copys`');
	@mysql_query('DROP TABLE `browser_dns`');
	@mysql_query('DROP TABLE `browser_temps`');
	@mysql_query('DROP TABLE `browser_temps_file`');
	@mysql_query('DROP TABLE `browser_users`');
	@mysql_query('DROP TABLE `browser_users_quicklogin`');
	@mysql_query('DROP TABLE `disk_dir`');
	@mysql_query('DROP TABLE `disk_file`');


	top('删除数据库 - 卸载玖玩浏览器');
	echo '<b>卸载玖玩浏览器<br/>删除数据库</b>';
	echo hr;
	echo '恭喜，删除数据库成功！！<br/>';
	echo '当前版本：'.$install_version.'<br/>';
	foot();
	exit;
}catch(Exception $e){
	echo $e->getMessage();
	exit;
}
