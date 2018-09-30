<?php
global $PAGE;
$pagehead='<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>'.$PAGE['meta'].'
<meta http-equiv="Content-Type" content="'.$PAGE['mime'].'; charset='.$PAGE['charset'].'"/>
<style type="text/css">'.headecho::getpagecss($PAGE['bid']).$PAGE['css'].'</style>';
?>