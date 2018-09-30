<?php
/*�һ��Ŷӿ������� �ļ�ϵͳ��*/
class ftfs
{
static $db; //���ݿ����
static $root; //ͬ��Ŀ¼
static $uid; //�û�id

//���ݿ�����
static function conn()
{
if(!self::$db)
 self::$db=db::conn('ftdb');
}

//��ʼ����ÿ��ʹ��ǰ����
static function start($root,$uid)
{
 self::$root=$root;
 self::$uid=$uid;
 self::conn();
}

//����Ŀ¼���ļ���
static function dirbase($path,&$dir,&$base)
{
$dir=dirname($path);
$base=basename($path);
}

//��Ŀ¼
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

//�г��ļ������а汾
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

//ȡ��ָ��id���ļ���Ŀ¼����Ϣ
static function getbyid($id,$row='')
{
$sql='SELECT dir,name,uid,time,'.$row.'active,extra,isdir FROM '.DB_A.'ftfs WHERE id='.floor($id);
$rs=self::$db->query($sql);
if(!$rs)
 return false;
return $rs->fetch(db::ass);
}

//ʹָ�����Ƶ��ļ���Ϊ����
static function disable($path)
{
self::dirbase($path,$dir,$name);
$sql='UPDATE '.DB_A.'ftfs SET active=2 WHERE dir=? AND name=? AND isdir=0';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
return $rs->execute(array($dir,$name));
}

//���ǣ���ʵ��ɾ�����û��ϴεı༭
static function overwritebyuid($path)
{
self::dirbase($path,$dir,$name);
$sql='DELETE FROM '.DB_A.'ftfs WHERE id=(SELECT id FROM '.DB_A.'ftfs WHERE dir=? AND name=? AND uid=? AND isdir=0 ORDER BY time DESC LIMIT 1)';
$rs=self::$db->prepare($sql);
if(!$rs)
 return false;
return $rs->execute(array($dir,$name,self::$uid));
}

//�Ƿ�ΪĿ¼������trueΪĿ¼��falseΪ�ļ���NULLΪ�����ڣ�
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

/*/д�ļ���������
static function fileput($dir,$name,&$data)
{
$path=self::$root.$dir;
if(is_dir($path)) 
}*/

//�½��ļ�
static function create($path,$active,$extra,&$data)
{
self::dirbase($path,$dir,$name);
if(self::isdir($path))
 return '����ͬ����Ŀ¼';
if(!self::isdir($dir))
 return '��Ŀ¼������';
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

//�½�Ŀ¼�����һ��������Linux�ļ�Ȩ�ޣ�
static function mkdir($path,$active,$extra,$level='0777')
{
self::dirbase($path,$dir,$name);
$is=self::isdir($path);
if($is)
 return 'Ŀ¼�Ѵ���';
elseif($is===false)
 return '����ͬ���ļ�';
if(!self::isdir($dir))
 return '��Ŀ¼������';
$sql='INSERT INTO '.DB_A.'ftfs(dir,name,isdir,active,uid,extra,time,data) values(?,?,1,?,?,?,?,?)';
$rs=self::$db->prepare($sql);
if(!$rs) return false;
if($active==1)
 {
mkdir($path,$level);
 }
return $rs->execute($dir,$name,$active,self::$uid,$extra,time(),$level);
}

//�����ļ�
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

//�����ļ�
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