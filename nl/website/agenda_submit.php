<?php
include "functions.php";
must_be_logged_in();
$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);
if($_POST["item"] == "0") {
	$sql = "INSERT INTO `".$_POST["object"]."` ( `id` , `title` , `from` , `until` , `location` , `summary` , `personen` , `content` ) VALUES ( '', '"
	.$_POST["title"]."', '".$_POST["from"]."', '"
	.$_POST["until"]."', '".$_POST["location"]."', '"
	.$_POST["summary"]."', '"
	.$_POST["personen"]."', '".$_POST["content"]."' )";
	$res = mysql_query($sql);
} else {
$sql = "select * from events where id = '".$_POST["item"]."'";
// run the query and set a result identifier to the recordset
$res = mysql_query($sql);
$event = mysql_fetch_assoc($res);
if ( ! ( $_POST["title"] == $event['title'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `title` = '".$_POST["title"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["location"] == $event['location'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `location` = '".$_POST["location"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["summary"] == $event['summary'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `summary` = '".$_POST["summary"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["from"] == $event['from'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `from` = '".$_POST["from"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["until"] == $event['until'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `until` = '".$_POST["until"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["personen"] == $event['personen'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `personen` = '".$_POST["personen"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
if ( ! ( $_POST["content"] == $event['content'] )) {
	$sql = "UPDATE `".$_POST["object"]."` SET `content` = '".$_POST["content"]."' WHERE `id` = '".$_POST["item"]."' LIMIT 1 ;";
	$res = mysql_query($sql);
	}
}
header("Location: agenda.php");
?>
