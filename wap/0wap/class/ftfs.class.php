<?php
/*烈虎团队开发工具 文件系统类*/
class ftfs
{
static $db; //数据库对象
static $root; //同步目录
static $uid; //用户id

//数据库连接
static function conn()
{
if(!self::$db)
 self::$db=db::conn('ftdb');
}

//初始化，每次使用前调用
static function start($root,$uid)
{
 self::$root=$root;
 self::$uid=$uid;
 self::conn();
}

//分离目录和文件名
static function dirbase($path,&$dir,&$base)
{
$dir=dirname($path);
$base=basename($path);
}

//列目录
static function list($dir,$active,$offset,$size,$row='')
{
$sql='SELECT id,name,uid,time,'.$row.'isdir FROM '.DB_A.'ftfs WHERE active=? AND dir=? ORDER BY name LIMIT ?,?';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
$rs->execute(array($active,$dir,$offset,$size));
$rs=$rs->fetchALL(db::ass);
$x=null;
foreach($rs as $i)
 {
if($i['isdir'])
 $x['dir'][]=$i;
else
 $x['file'][]=$i;
 }
return $x;
}

//列出文件的所有版本
static function getbyname($path,$offset=0,$size=1,$row='')
{
self::dirbase($path,$dir,$name);
$sql='SELECT id,uid,time,'.$row.'active,extra,isdir FROM '.DB_A.'ftfs WHERE dir=? AND name=? ORDER BY time DESC LIMIT ?,?';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
$rs->execute(array($dir,$name,$offset,$size));
return $rs->fetchALL(db::ass);
}

//取得指定id的文件或目录的信息
static function getbyid($id,$row='')
{
$sql='SELECT dir,name,uid,time,'.$row.'active,extra,isdir FROM '.DB_A.'ftfs WHERE id='.floor($id);
$rs=self::$db->query($sql);
if(!$rs)
 return false;
return $rs->fetch(db::ass);
}

//使指定名称的文件成为备份
static function disable($path)
{
self::dirbase($path,$dir,$name);
$sql='UPDATE '.DB_A.'ftfs SET active=2 WHERE dir=? AND name=? AND isdir=0';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
return $rs->execute(array($dir,$name));
}

//覆盖（其实是删除）用户上次的编辑
static function overwritebyuid($path)
{
self::dirbase($path,$dir,$name);
$sql='DELETE FROM '.DB_A.'ftfs WHERE id=(SELECT id FROM '.DB_A.'ftfs WHERE dir=? AND name=? AND uid=? AND isdir=0 ORDER BY time DESC LIMIT 1)';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
return $rs->execute(array($dir,$name,self::$uid));
}

//是否为目录（返回true为目录，false为文件，NULL为不存在）
static function isdir($path)
{
self::dirbase($path,$dir,$name);
$rs=self::$db->prepare('SELECT id FROM '.DB_A.'ftfs WHERE dir=? AND name=? AND active=1 LIMIT 1');
if(!$rs) return false;
$rs->execute($dir,$name);
$rs=$rs->fetch(db::ass);
if(!isset($rs['isdir']))
 return null;
else
 return $rs['isdir'] ? true : false;
}

/*/写文件（废弃）
static function fileput($dir,$name,&$data)
{
$path=self::$root.$dir;
if(is_dir($path)) 
}*/

//新建文件
static function create($path,$active,$extra,&$data)
{
self::dirbase($path,$dir,$name);
if(self::isdir($path))
 return '存在同名的目录';
if(!self::isdir($dir))
 return '父目录不存在';
$sql='INSERT INTO '.DB_A.'ftfs(dir,name,isdir,active,uid,extra,time,data) values(?,?,0,?,?,?,?,?)';
$rs=self::$db->prepare($sql);
if(!$rs) return false;
if($active==1)
 {
self::disable($path);
file_put_contents(self::$root.$path,$data);
 }
return $rs->execute($dir,$name,$active,self::$uid,$extra,time(),$data);
}

$name,&$data)
{
$path=self::$root.$dir;
if(is_dir($path)) 
}*/

//新建目录（最后一个参数是Linux文件权限）
static function mkdir($path,$active,$extra,$level='0777')
{
self::dirbase($path,$dir,$name);
$is=self::isdir($path);
if($is)
 return '目录已存在';
elseif($is===false)
 return '存在同名文件';
if(!self::isdir($dir))
 return '父目录不存在';
$sql='INSERT INTO '.DB_A.'ftfs(dir,name,isdir,active,uid,extra,time,data) values(?,?,1,?,?,?,?,?)';
$rs=self::$db->prepare($sql);
if(!$rs) return false;
if($active==1)
 {
mkdir($path,$level);
 }
return $rs->execute($dir,$name,$active,self::$uid,$extra,time(),$level);
}

//激活文件
static function enable($id)
{
$info=self::getbyid($id);
if(!$info)
 return false;
$dir=$info['dir'];
$name=$info['name'];
if(!$info['isdir'])
 {
$sql='UPDATE '.DB_A.'ftfs SET active=2 WHERE dir=? AND name=? AND id<>?';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
if(!$rs->execute(array($dir,$name,$id)))
 return false;
 }
return self::$db->exec('UPDATE '.DB_A.'ftfs SET active=1 WHERE id='.floor($id));
}

//废弃文件
static function delete($id)
{
$info=self::getbyid($id);
if(!$info)
 return false;
$dir=$info['dir'];
$name=$info['name'];
if(!$info['isdir'] && $info['active']==1)
 {
$sql='UPDATE '.DB_A.'ftfs SET active=1 WHERE id=(SELECT id FROM '.DB_A.'ftfs WHERE dir=? AND name=? AND id<>? ORDER BY time DESC LIMIT 1)';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
if(!$rs->execute(array($dir,$name,$id)))
 return false;
 }
return self::$db->exec('UPDATE '.DB_A.'ftfs SET active=0 WHERE id='.floor($id));
}

//class end
}
?>