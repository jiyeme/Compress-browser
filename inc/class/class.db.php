<?php
/*
 *
 *	mysqli数据库类
 *
 *	2011-1-14 @ jiuwap.cn
 *
 */

class db{
	private $curlink = null;

	function __construct($dbhost, $dbuser, $dbpw, $dbname,  $dbcharset='utf8') {
		$link = null;
		if( !$link = @mysqli_connect($dbhost, $dbuser, $dbpw, $dbname) ) {
			throw new Exception('mysqli:数据库连接失败',E_USER_ERROR);
		} else {
			$this->curlink = $link;
			$dbcharset && mysqli_query($link, 'SET sql_mode="",NAMES "'.$dbcharset.'",CHARACTER SET '.$dbcharset.',CHARACTER_SET_RESULTS='.$dbcharset.',COLLATION_CONNECTION="'.$dbcharset.'_general_ci"');
			if ( !@$this->select_db($dbname) ){
				throw new Exception('mysqli:打开数据表失败',E_USER_ERROR);
			}
		}
		$this->querynum = 0;
	}

	function delete($table,$where='', $limit = 0, $unbuffered = true){
		$sql = 'DELETE FROM '.$table.($where ? " WHERE $where" : '').($limit ? " LIMIT $limit" : '');
		//exit($sql);
		return $this->query($sql, ($unbuffered ? 'UNBUFFERED' : ''));
	}

	function replace($table,$data,$where,$unbuffered=true){
		$d = $this->fetch_first("SELECT count(*) as is_exists FROM {$table} WHERE {$where}");
		if ( $d['is_exists'] ){
			$this->update($table, $data, $where, $unbuffered);
		}else{
			$this->insert($table, $data, $unbuffered);
		}
	}

	function update($table, $data,$where='', $unbuffered = false){
		//$data=array('key'=>'value',...);
		$sql = '';
		foreach ($data as $k => $v) {
			$sql && $sql .= ',';
			$sql .= "`$k`='$v'";
		}
		$where && $sql .= " WHERE $where";
		return $this->query("UPDATE {$table} SET {$sql}",$unbuffered ? 'UNBUFFERED' : '');
	}

	function insert($table, $data, $return_insert_id = false){
		//$data=array('key'=>'value',...);
		$sql1 = $sql2 = '';
		foreach ($data as $k => $v) {
			if ( $sql1 ){
				$sql1 .= ',';
				$sql2 .= ',';
			}
			$sql1 .= "`$k`";
			$sql2 .= "'$v'";
		}
		$return = $this->query("INSERT INTO {$table} ({$sql1}) VALUES ({$sql2})");
		$return_insert_id && $return = $this->insert_id();
		return $return;
	}

	static function connect($dbhost, $dbuser, $dbpw, $dbname,  $dbcharset='utf8') {
		return new db($dbhost, $dbuser, $dbpw, $dbname,$dbcharset);
	}

	function select_db($dbname) {
		return mysqli_select_db($this->curlink, $dbname);
	}

	function fetch_array($query, $result_type = MYSQLI_ASSOC) {
		return mysqli_fetch_array($query, $result_type);
	}

	function fetch_first($sql) {
	    //exit($sql);
		return $this->fetch_array($this->query($sql));
	}

	function result_first($sql) {
		return $this->result($this->query($sql), 0);
	}

	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && function_exists('mysqli_unbuffered_query') ? 'mysqli_unbuffered_query' : 'mysqli_query';
		if(!($query = $func($this->curlink, $sql))) {
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->connect();
				return $this->query($sql, 'RETRY'.$type);
			}
			if($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('语句错误', $sql);
			}
		}

		$this->querynum++;
		return $query;
	}

	function affected_rows() {
		return mysqli_affected_rows($this->curlink);
	}

	function error() {
		return isset($this->curlink) ? mysqli_error($this->curlink) : mysqli_error();
	}

	function errno() {
		return isset($this->curlink) ? mysqli_errno($this->curlink) : mysqli_errno();
	}

	function result($query, $row = 0) {
		//return @mysqli_result($query, $row);
		
	    return mysqli_select_db($query, $row);
	}

	function num_rows($query) {
		return mysqli_num_rows($query);
	}

	function num_fields($query) {
		return mysqli_num_fields($query);
	}

	function free_result($query) {
		return mysqli_free_result($query);
	}

	function insert_id() {
		return ($id = mysqli_insert_id($this->curlink)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysqli_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysqli_fetch_field($query);
	}

	function version() {
		if(empty($this->version)) {
			$this->version = mysqli_get_server_info($this->curlink);
		}
		return $this->version;
	}

	function close() {
		return mysqli_close($this->curlink);
	}

	function halt($message='',$sql='') {
        if ( $sql ){
            $sql = "\r\n".$sql;
        }
		$str = $message."\r\n".$this->errno().$sql."\r\n".$this->error();
		throw new Exception($str,E_USER_ERROR);
        //write_log(__FILE__,__line__,$str);

	}

	function __destruct(){
		$this->close();
	}

	public function escape_string($str){
		$str = mysqli_escape_string($this->curlink,$str);
		return $str;
	}

}
