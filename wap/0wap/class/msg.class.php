<?php
/*站内信操作类*/
class msg
{
static $msg;

static function getlist($uid,$offset=0,$limit=0,$read=null)
{
user::conn();
$sql='select id,byuid,title,`read`,time from '.DB_A.'msg where uid='.floor($uid).($read===null ? '' : ($read ? ' and `read`=1' : ' and `read`=0')).' order by time desc';
if($limit)
 $sql.=' limit '.floor($offset).','.floor($limit);
$rs=user::$db->query($sql);
while($rs&&$nr=$rs->fetch(db::ass))
{
$uinfo=user::getinfobyuid($nr['byuid']);
self::$msg[$nr['id']]=array('name'=>$uinfo['name'],'uid'=>$nr['byuid'],'title'=>$nr['title'],'time'=>$nr['time'],'read'=>$nr['read']);
}
return self::$msg;
}

static function view($uid,$id)
{
user::conn();
$sql='select * from '.DB_A.'msg where id='.floor($id).' and uid='.floor($uid);
$rs=user::$db->query($sql);
$nr=$rs->fetch(db::ass);
if(!$nr)
 return false;
$uinfo=user::getinfobyuid($nr['byuid']);
return self::$msg[$id]=array('name'=>$uinfo['name'],'uid'=>$nr['byuid'],'title'=>$nr['title'],'nr'=>$nr['nr'],'time'=>$nr['time'],'read'=>$nr['read']);
}
static function getlist2($uid,$offset=0,$limit=0,$read=null)
{
user::conn();
$sql='select id,uid,title,`read`,time from '.DB_A.'msg where byuid='.floor($uid).($read===null ? '' : ($read ? ' and `read`=1' : ' and `read`=0')).' order by time desc';
if($limit)
 $sql.=' limit '.floor($offset).','.floor($limit);
$rs=user::$db->query($sql);
while($nr=$rs->fetch(db::ass))
{
$uinfo=user::getinfobyuid($nr['uid']);
self::$msg[$nr['id']]=array('name'=>$uinfo['name'],'uid'=>$nr['uid'],'title'=>$nr['title'],'time'=>$nr['time'],'read'=>$nr['read']);
}
return self::$msg;
}
static function view2($uid,$id)
{
user::conn();
$sql='select * from '.DB_A.'msg where id='.floor($id).' and byuid='.floor($uid);
$rs=user::$db->query($sql);
$nr=$rs->fetch(db::ass);
if(!$nr)
 return false;
$uinfo=user::getinfobyuid($nr['uid']);
return self::$msg[$id]=array('name'=>$uinfo['name'],'uid'=>$nr['uid'],'title'=>$nr['title'],'nr'=>$nr['nr'],'time'=>$nr['time'],'read'=>$nr['read']);
}
static function send($byuid,$touid,$title,$nr)
{
if(trim($nr)=='')
 return false;
if(trim($title)=='')
 $title=mb_substr($nr,0,15,'utf-8').(mb_strlen($nr,'utf-8')>15 ? '…' : '');
user::conn();
$sql='insert into '.DB_A.'msg(uid,byuid,title,nr,`read`,time) values(?,?,?,?,?,?)';
$rs=user::$db->prepare($sql);
return $rs->execute(array($touid,$byuid,$title,$nr,0,time()));
}
static function read($uid,$id=null)
{
if(self::$msg[$id]['read'])
 return true;
user::conn();
$sql='update '.DB_A.'msg set `read`=1 where'.($id===null ? '' : ' id='.floor($id).' and').' uid='.floor($uid);
return user::$db->exec($sql);
}
static function count($uid,$read=null,$by=false)
{
$sql='select count(*) from '.DB_A.'msg where '.($by ? 'by' : '').'uid='.floor($uid).($read===null ? '' : ($read ? ' and `read`=1' : ' and `read`=0'));
user::conn();

$rs=user::$db->query($sql);
$rs=$rs->fetch(db::num);
return $rs[0];
}
static function chat($uid,$uid2,$limit,$page,&$all)
{
user::conn();
$uid=floor($uid);
$uid2=floor($uid2);
$limit=floor($limit);
$page=floor($page);
$qi=($page-1)*$limit;
$sql=' from '.DB_A.'msg where (uid='.$uid.' AND byuid='.$uid2.') OR (uid='.$uid2.' AND byuid='.$uid.')';
$rs=user::$db->query('select count(*) as cnt'.$sql);
$rs=$rs->fetch(db::ass);
$all=$rs['cnt'];
$rs=user::$db->query('select *'.$sql.' ORDER BY time DESC LIMIT '.$qi.','.$limit);
return $rs->fetchall(db::ass);
}
//msg类结束
}
?>