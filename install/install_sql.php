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
		if ( !$sql = @file_get_contents('jiuwap.sql') ){
			throw new Exception('读取jiuwap.sql时发生错误');
		}
		$sql = str_replace(array("\r","\n","\t"),'',$sql);
		$sql = explode(';',$sql);
		$n = count($sql);
		for($i=0;$i<$n ;$i++){
			!empty($sql[$i]) && @mysql_query($sql[$i]);
		}

		top('导入SQL - 安装玖玩浏览器');
		echo '<b>安装玖玩浏览器<br/>导入SQL完成</b>';
		echo hr;
		echo '恭喜，导入SQL成功！！<br/>';
		echo '当前版本：'.$install_version.'<br/>';
		foot();
		exit;
	}catch(Exception $e){
		echo $e->getMessage();
		exit;
	}
