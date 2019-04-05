<?php
/*
 *
 *	storage
 *
 *	2012/7/23 @ jiuwap.cn
 *
 */


class cloud_storage{
	static function getInstance() {
		static $stor = false;
		if ( $stor === false ){
			global $b_set;
			switch($b_set['server_method']){
				case 'sae';
					$stor = new cloud_storage_sae();
					break;
				case 'ace';
					$stor = new cloud_storage_ace();
					break;
				default:
					$stor = new cloud_storage_php();
			}
		}
		return $stor;
	}


	static function size($filename){
		return self::getInstance()->size($filename);
	}
	static function url($filename){
		return self::getInstance()->url($filename);
	}

	static function delete($filename){
		return self::getInstance()->delete($filename);
	}

	static function exists($filename){
		return self::getInstance()->exists($filename);
	}

	static function read($filename){
		return self::getInstance()->read($filename);

	}

	static function write($filename,$contents){
		return self::getInstance()->write($filename,$contents);
	}

	static function upload($filename,$filename2){
		return self::getInstance()->upload($filename,$filename2);
	}

	static function upload_temp($filename,$filename2){
		return self::getInstance()->upload_temp($filename,$filename2);
	}

	static function rename($filename,$filename2){
		return self::getInstance()->rename($filename,$filename2);
	}

	static function download($filename,$localfile){
		return self::getInstance()->download($filename,$localfile);
	}

	static function download_tmp($filename,$filename2){
		return self::getInstance()->download_tmp($filename,$filename2);
	}

	static function copy($filename,$filename2){
		return self::getInstance()->copy($filename,$filename2);
	}
	static function localname($filename){
		return self::getInstance()->localname($filename);
	}
}

class cloud_storage_ace{
	private $stor = false;
	private $_cache_file = array();
	function __construct(){
		$this->stor = new CEStorage();
		if ( $this->stor === false ){
			throw new Exception('CEStorage初始化失败',E_USER_ERROR);
		}
	}

	public function __destory(){
		foreach($this->_cache_file as $file){
			@unlink($file);
		}
		$this->_cache_file = array();

	}

	function size($filename){
		return strlen($this->read($filename));
	}

	function url($filename){
		return $this->stor->getUrl($this->domain , $filename );
	}
	function delete($filename){
		return $this->stor->delete($filename);
	}

	function exists($filename){
		//var_dump($this->stor->getList());
		//echo ($filename);
		return $this->stor->fileExists($filename);
	}

	function read($filename){
		return $this->stor->read($filename);
	}

	function write($filename,$contents){
		$tmp = time() . '_ace_storage.tmp';
		file_put_contents($tmp,$contents);
		$result = $this->upload($tmp,$filename);
		unlink($tmp);
		return $result;
	}

	//将本地文件上传至storage服务。
	//$filename 本地文件路径(绝对)
	//$filename2 存储到的storage文件路径
	function upload($filename,$filename2){
		//($this->stor->getList());
		return $this->stor->upload($filename,$filename2);
	}

	//重命名
	function rename($filename,$filename2){
		$this->write($filename2,$this->read($filename));
		$this->delete($filename);
	}

	//storage文件复制
	function copy($filename,$filename2){
		$this->write($filename2,$this->read($filename));
	}

	//将本地文件上传至storage
	//运行完毕时删除本地文件
	function upload_temp($filename,$filename2){
		$content = file_get_contents($filename);
		if ( $content === false ){
			return $content;
		}
		$this->_cache_file[] = $filename;
		return $this->write($filename2,$content);
	}

	//将storage下载至本地
	function download($filename,$localfile){
		$content = $this->read($filename);
		if ( $content === false ){
			return $content;
		}
		file_put_contents($localfile,$content);
		return $localfile;
	}

	//将storage下载到本地
	//待执行完毕删除本地文件
	function download_tmp($filename,$localfile){
		$localfile = $this->download($filename,$localfile);
		$this->_cache_file[] = $localfile;
		return $localfile;
	}

	function localname($filename) {
		return ROOT_DIR  . $filename;
	}

}

class cloud_storage_sae{
	private $stor = false;
	private $domain = false;
	function __construct(){
		global $b_set;
		$this->domain = $b_set['server_sae_storage'];
		$this->stor = new SaeStorage();
	}

	public function __destory(){
	}

	function size($filename){
		return strlen($this->read($filename));
	}

	function delete($filename){
		return $this->stor->delete($this->domain , $filename );
	}

	function url($filename){
		return $this->stor->getUrl($this->domain , $filename );
	}

	function exists($filename){
		return $this->stor->fileExists($this->domain , $filename);
	}

	function read($filename){
		return $this->stor->read($this->domain , $filename );
	}

	function write($filename,$contents){
		return $this->stor->write($this->domain , $filename,$contents );
	}

	function upload($filename,$filename2){
		return $this->stor->upload($this->domain ,$filename2, $filename);
	}

	function rename($filename,$filename2){
		$this->copy($filename,$filename2);
		$this->delete($filename);
	}

	function copy($filename,$filename2){
		$this->write($filename2,$this->read($filename));
	}

	function upload_temp($filename,$filename2){
		$result = $this->upload($filename,$filename2);
		$this->_cache_file[] = $filename;
		return $result;
	}

	function download($filename,$localfile){
		$content = $this->read($filename);
		if ( $content === false ){
			return $content;
		}
		file_put_contents($localfile,$content);
		return $localfile;
	}

	function download_tmp($filename,$localfile){
		$localfile = $this->download($filename,$localfile);
		$this->_cache_file[] = $localfile;
		return $localfile;
	}
	function localname($filename) {
		return SAE_TMP_PATH . $filename;
	}

}

class cloud_storage_php{
	private $_cache_file = array();
	private $dir = '';

	function __construct(){
		$this->dir = ROOT_DIR . 'temp/storage/';
	}

	public function __destory(){
		foreach($this->_cache_file as $file){
			@unlink($file);
		}
		$this->_cache_file = array();
	}

	function size($filename){
		return filesize($this->dir.$filename);
	}

	function delete($filename){
		return unlink($this->dir.$filename );
	}

	//返回文件当前URL
	function url($filename){
		return 'http://'.$_SERVER['HTTP_HOST'].'/'. substr($this->dir,strlen(ROOT_DIR)) . $filename;
	}

	function exists($filename){
		return file_Exists($this->dir.$filename);
	}

	function read($filename){
		return file_get_contents($this->dir.$filename);
	}

	function write($filename,$contents){
		return file_put_contents($this->dir.$filename,$contents );
	}

	function upload($filename,$filename2){
		return copy($filename,$this->dir.$filename2);
	}

	function rename($filename,$filename2){
		return rename($this->dir.$filename,$this->dir.$filename2);
	}

	function copy($filename,$filename2){
		return copy($this->dir.$filename,$this->dir.$filename2);
	}

	function upload_temp($filename,$filename2){
		$result = $this->upload($filename,$filename2);
		$this->_cache_file[] = $filename;
		return $result;
	}

	function download($filename,$localfile){
		return $this->dir.$filename;
	}

	function download_tmp($filename,$localfile){
		return $this->dir.$filename;
	}

	function localname($filename) {
		return ROOT_DIR . 'temp/disk_temp/' . $filename;
	}

}