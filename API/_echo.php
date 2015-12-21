<?php
header('Content-type:text/html;charset=UTF8');

?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="../includes/jquery.js"></script>
<style type="text/css">
	* {
		font-family:Tahoma;
		font-size:13px;
	}
</style>	

<?
print '<PRE>';
print_r($_GET);
print_r($_POST);


?>