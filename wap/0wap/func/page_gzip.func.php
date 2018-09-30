<?php
function page_gzip($f)
{
global $PAGE;
if($PAGE['gzip']&&PAGE_GZIP&&function_exists('gzencode'))
{
$f=gzencode($f,PAGE_GZIP);
header('Content-Encoding: gzip');
header('Vary: Accept-Encoding');
}
header('Content-Length: '.strlen($f));
return $f;
}
?>