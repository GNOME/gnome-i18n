<?php
include "functions.php";
must_be_logged_in();
$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);
if($_POST["item"] == "0") {
	$sql = "INSERT INTO `".$_POST["object"]."` ( `id` , `title` , `author` , `summary` , `content` , `posted` ) VALUES ( '', '"
	.$_POST["title"]."', '".$GLOBALS["PHP_AUTH_USER"]."', '"
	.$_POST["summary"]."', '".$_POST["content"]."', NOW( ) )";
	$res = mysql_query($sql);
} else {
$sql = "select * from news where id = '".$_POST["item"]."'";
// run the query and set a result identifier to the recordset
$res = mysql_query($sql);
$newsitem = mysql_fetch_assoc($res);
if ( ! ( $_POST["title"] == $newsitem['title'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `title` = '".$_POST["title"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["summary"] == $newsitem['summary'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `summary` = '".$_POST["summary"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["content"] == $newsitem['content'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `content` = '".$_POST["content"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
}
header("Location: nieuws.php");
?>
