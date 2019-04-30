<?php
/*
 *
 *	时间校正
 *
 *	2011/7/10 @ jiuwap.cn
 *
 */
//exit(ROOT_DIR);
if ( !defined('JIUWAP_TIME_CHA') ){
    
	date_default_timezone_set('PRC');
	function NTP_time(){
        require ROOT_DIR .'set_config/set_config.php';
		$local = $net = 0;
		
		$cha1 = false;
		    $cha1 = cloud_memcache::get('ntp_time');

		if ( !$cha1 ){
			if ( $fp = @pfsockopen('time.nist.gov',13,$errno,$errstr,3) ){
				stream_set_timeout($fp, 3);
				$net = fread($fp,2096);
				if ( empty($net) ){
					$cha1 = 0;
				}else{
					$net = strtotime($net);
					$local = time();
					$cha1 = $net - $local;
				}
				if ( $cha1 == 0 ){
					$cha1 = 1;
				}
			}else{
				$cha1 = 1;
			}
			    cloud_memcache::set('ntp_time',$cha1);
		}
		$net = $local + $cha1;
		define('JIUWAP_TIME_CHA',$cha1);
	}
	NTP_time();


	function time_(){
		return time() + JIUWAP_TIME_CHA;
	}

	function microtime_(){
		list($usec, $sec) = explode(' ', microtime());
		$sec = $sec + JIUWAP_TIME_CHA;
		return "$usec $sec";
	}

	function strtotime_($time,$timestamp=false){
		if ( $timestamp == false ){
			$timestamp = time_();
		}
		return strtotime($time,$timestamp);
	}

	function date_($format,$timestamp=false){
		if ( $timestamp == false ){
			$timestamp = time_();
		}
		return date($format,$timestamp);
	}

	function getdate_($timestamp=false){
		if ( $timestamp == false ){
			$timestamp = time_();
		}
		return getdate($timestamp);
	}

	function gmdate_($format,$timestamp=false){
		if ( $timestamp == false ){
			$timestamp = time_();
		}
		return gmdate($format,$timestamp);
	}

	function strftime_($format,$timestamp=false){
		if ( $timestamp == false ){
			$timestamp = time_();
		}
		return strftime($format,$timestamp);
	}

	function localtime_($timestamp=false,$is_associative=false){
		if ( $timestamp == false ){
			$timestamp = time_();
		}
		return localtime($timestamp,$is_associative);
	}
}