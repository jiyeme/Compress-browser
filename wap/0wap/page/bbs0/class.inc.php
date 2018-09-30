<?php
class bbs
{
static function substr($nr,$qi,$mei)
{
return mb_substr($nr,$qi,$mei,'utf-8');
}
static function isbz($db,$uid,$bkid)
 {
return $db->select('bk',"where id=$bkid and bzid like '%,$uid,%'",'','','name');
 }
#bbs类结束
}
?>