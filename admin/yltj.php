<?php
$browser_users_id=mysql_query("select count(id) from `browser_users`",$db);
while($row=mysql_fetch_row($browser_users_id)){
$num_id=$row[0];
}
$browser_users_look=mysql_query("select sum(num_look) from `browser_users`",$db);
while($row=mysql_fetch_assoc($browser_users_look)){
$num_look=$row['sum(num_look)'];
}
$browser_users_size_html=mysql_query("select sum(num_size_html) from `browser_users`",$db);
while($row=mysql_fetch_assoc($browser_users_size_html)){
$num_size=$row['sum(num_size_html)'];
}
$browser_users_size_pic=mysql_query("select sum(num_size_pic) from `browser_users`",$db);
while($row=mysql_fetch_assoc($browser_users_size_pic)) {
$num_size_pic=$row['sum(num_size_pic)'];
}
$books=mysql_query("select count(id) from `browser_books`",$db);
while($row=mysql_fetch_row($books)){
$book=$row[0];
}
$file_id=mysql_query("select count(id) from `disk_file`",$db);
while($row=mysql_fetch_row($file_id)){
$num_file=$row[0];
}
$file_size=mysql_query("select sum(size) from `disk_file`",$db);
while($row=mysql_fetch_assoc($file_size)) {
$num_file_size=$row['sum(size)'];
}
$browser_copys=mysql_query("select count(id) from `browser_copys`",$db);
while($row=mysql_fetch_row($browser_copys)){
$num_copys=$row[0];
}
$browser_caches=mysql_query("select count(keyid) from `browser_caches`",$db);
while($row=mysql_fetch_row($browser_caches)){
$num_cache=$row[0];
}
echo "<title>压流统计</title><div>统计列表</div>用户总数:{$num_id}人<br/>用户浏览次数:{$num_look}次<br/>浏览网页:".html_size($num_size)."<br/>浏览图片:".html_size($num_size_pic)."<br/>总共使用流量:".html_size($num_size+$num_size_pic)."<br/>书签数量:{$book}条<br/>网盘:{$num_file}个文件<br/>网盘占用:".html_size($num_file_size)."空间<br/>剪切板有:{$num_copys}条<br/>数据库有:{$num_cache}条缓存";

?>