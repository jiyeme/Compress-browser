<?php
/*
 *
 *	Memcache
 *
 *	2012/7/23 @ jiuwap.cn
 *
 */

class cloud_memcache{

	private static function getInstance() {
		static $mem = false;
		if ( $mem === false ){
			global $b_set;
			switch($b_set['server_method']){
				case 'sae';
					$mem = new cloud_memcache_sae();
					break;
				case 'ace';
					$mem = new cloud_memcache_ace();
					break;
				default:
					$mem = new cloud_memcache_php();
			}
		}
		return $mem;
	}

	static function set($key,$value=''){
		return self::getInstance()->set($key,$value);
	}

	static function get($key){
		return self::getInstance()->get($key);
	}
}


class cloud_memcache_sae{
	private $mmc = false;
	function __construct(){
		$this->mmc = memcache_init();
		if ( $this->mmc === false ){
			throw new Exception('memcache_init初始化失败',E_USER_ERROR);
		}
	}

	function set($key,$value=''){
		return memcache_set($this->mmc,$key,$value);
	}

	function get($key){
		return memcache_get($this->mmc,$key);
	}
}


class cloud_memcache_ace{
	private $mmc = false;
	function __construct(){
		$this->mmc = new Memcache;
		$this->mmc->init();
	}

	function set($key,$value=''){
		return memcache_set($this->mmc,$key,$value);
	}

	function get($key){
		return memcache_get($this->mmc,$key);
	}
}


class cloud_memcache_php{
	function __construct(){
	}

	function set($key,$value=''){
		return file_put_contents(ROOT_DIR . 'temp/memcache/' .$key,$value);
	}

	function get($key){
		return file_get_contents(ROOT_DIR . 'temp/memcache/' .$key);
	}
}