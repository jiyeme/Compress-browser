<?php
include ROOT_DIR.'/s_db.inc.php';
/*session类
用户会话变量管理
实现了ArrayAccess接口，可以像使用$_SESSION数组那样使用它（支持遍历、count()。
不支持这样的操作方法：$session['mysession']['op']=2;，请改为$session['mysession']=array('op'=>2);）
操作示例：
$session=new session($USER[uid],'copy',3600); #传入会话id(用户uid)，开始会话（会话属于copy组，1小时后过期。为0永不过期）
$session['text']=array('10086','虎绿林','龙行华夏','test'); #可以给session成员赋任何类型的值print_r($session['text']); #取得会话变量
unset($session['text']); #删除不再需要的会话变量
session::rollback(); #静态方法，回滚会话，放弃当前会话的所有更改
$session->getinfo(); #重新从数据库中取得会话变量（回滚只针对数据库。如果回滚之后需要继续处理会话刚开始时的数据，请调用该方法）
session::commit(); #静态方法，保存当前所有会话的更改
『注意：由于所有的会话对象都共享同一个数据库连接，因此同一时间只能有一个ommit(); #静态方法，保存当前所有会话的更改
『注意：由于所有的会话对象都共享同一个数据库连接，因此同一时间只能有一个事务存在。所以当new一个新会话对象时，旧对象已进行的改动会被自动提交，并开始新事务。若再次更改旧对象，则当数据库回滚时只有这部分处于新事务的更改可以回滚。』
unset($session); #销毁会话对象，并自动保存之前所有会话的更改。
*/
class session implements ArrayAccess,countable,iterator
{
static $db, #数据库连接
  //$commit=true, #事务是否已提交/回滚或者尚未开始
  $ob_jc=0; #对象计数器
var $info,$sid, #会话变量
  $timeout=0, #超时
 $zu='', #组
$count,$offset; #遍历时用的变量
/*iterator接口的实现*/
function rewind()
{
reset($this->info);
$this->count=$this->count();
$this->offset=0;
}
function next()
{
next($this->info);
$this->offset++;
}
function valid()
{
if($this->offset<$this->count)
 return true;
else
 return false;
}
function key()
{
return key($this->info);
}
function current()
{
$i=current($this->info);
return $i['nr'];
}
/*iterator结束，可以用foreach遍历了*/
function count()
{
return count($this->info);
}
/*搞定countable接口，可以count($session)了*/
static function conn(){
if(!self::$db)
{
 self::$db=new pdo(S_DB_TYPE.':dbname='.S_DB_NAME.';host='.S_DB_HOST,S_DB_USER,S_DB_PASS);
 self::$db->exec("set names 'utf8'");
}
return self::$db;
}
static function zuCount($sid,$zu)
{
#统计组成员数量
self::conn();
$rs=self::$db->prepare('select count(*) as jc from '.DB_A.'session where sid=? and zu=?');
$rs->execute(array($sid,$zu));
$rs=$rs->fetch(PDO::FETCH_ASSOC);
return $rs['jc'];
}
function __construct($sid,$zu='',$timeout=0,$in='')
{
self::$ob_jc++;
self::conn();
//if(self::$ob_jc==1)
 //self::start();
$this->sid=$sid;
$this->zu=$zu;
$this->timeout=$timeout;
$this->getinfo($in);
}
static function start()
{
//self::$commit && $ok=self::$db->beginTransaction();
//$ok && self::$commit=false;
 return $ok;
}
function getinfo($in='')
{
$arr=array($this->sid,$this->zu);
if(is_array($in))
{
if($jc=count($in))
{
$jc--;
$arr=array_merge($arr,$in);
$in=' and name in('.str_repeat('?,',$jc).'?)';
}
else
 $in='';
 }
$sql='select id,name,nr from '.DB_A.'session where sid=? and zu=? '.$in;
$rs=self::$db->prepare($sql);
if(!$rs->execute($arr))
 return false;
while($nr=$rs->fetch(PDO::FETCH_ASSOC))
{
$this->info[$nr['name']]=array('nr'=>unserialize($nr['nr']),'id'=>$nr['id']);
}
return $this->info;
}
/*下面是ArrayAccess接口*/
function offsetExists($name)
{
return isset($this->info[$name]);
}
function offsetGet($name)
{
return $this->info[$name]['nr'];
}
function offsetSet($name,$nr)
{
$val=serialize($nr);
$timeout=$this->timeout ? $this->timeout+time() : 0;
if(!$ex=$this->offsetExists($name))
{
 $sql='insert into '.DB_A.'session(sid,name,zu,nr,timeout) values(?,?,?,?,?)';
$rs=self::$db->prepare($sql);
$ok=$rs->execute(array($this->sid,$name,$this->zu,$val,$timeout));
}
else
{
 $sql='update '.DB_A.'session set nr=?,timeout=? where id='.$this->info[$name]['id'];
$rs=self::$db->prepare($sql);
$ok=$rs->execute(array($val,$timeout));
}
if($ok)
{
$this->info[$name]['nr']=$nr;
if(!$ex)
 $this->info[$name]['id']=self::$db->lastinsertid();
return true;
}
else
 return false;
}
function offsetUnset($name)
{
if(!$this->offsetExists($name))
 return false;
$sql="delete from ".DB_A."session where id=".$this->info[$name]['id'];
$ok=self::$db->exec($sql);
if($ok)
{
unset($this->info[$name]);
return true;
}
else
 return false;
}
static function commit()
{
//if(!self::$commit)
 //$ok=self::$commit=self::$db->commit();
return $ok;
}
function rollback()
{
//if(!self::$commit)
 //$ok=self::$commit=self::$db->rollback();
return $ok;
}
function __destruct()
{
self::$ob_jc--;
//if(self::$ob_jc==0)
 //self::commit();
}
#session类结束
}
?>