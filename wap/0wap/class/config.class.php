<?php
/*配置文件类，读取和写入配置用。
配置采用serialize()进行串行化后写入文件，用unsesialize()读取。
注意在不用本雷读取时忽略开头的< ? p h p  …  ? >。*/
class config
{
const headstr='<?php die; ?>'; /*配置文件头，防止非正常读取。请不要随便修改！*/
static $dir=TEMP_DIR; /*配置文件夹*/
/*设置新的配置文件夹*/
static function setdir($dir)
{
self::$dir=$dir;
}
/*保存配置
参数：
 配置名，要是合法的文件名。
 配置值，可以是任何PHP变量（除了资源类型）*/
static function put($name,$value)
{
return file_put_contents(self::$dir.'/'.$name.'.inc.php', self::headstr.serialize($value));
}
/*获取配置
 参数：配置名
 返回配置值*/
static function get($name)
{
return unserialize(file_get_contents(self::$dir.'/'.$name.'.inc.php',false,null,strlen(self::headstr)));
}
/*类结束*/
}
?>