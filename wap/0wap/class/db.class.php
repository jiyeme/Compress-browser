<?php
if(!defined('S_DB_TYPE'))
 include dirname(__FILE__).'/../s_db.inc.php';
/*db类，用于快速操作数据库，减少打字。*/
class db
{
const ass=PDO::FETCH_ASSOC; #记录集返回模式（关联数组）
const num=PDO::FETCH_NUM; #（普通数组）
const both=PDO::FETCH_BOTH; #（两者皆有）
static $db;
#返回PDO连接对象#
static function conn($dbname)
{
if(self::$db) return self::$db;
self::$db=new PDO(S_DB_TYPE.':dbname='.S_DB_NAME.';host='.S_DB_HOST,S_DB_USER,S_DB_PASS);
self::$db->exec("set names 'utf8'");
return self::$db;
}
#db类结束#
}
?>
