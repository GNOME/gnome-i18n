<?php
include "functions.php";
must_be_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Agenda bewerken</title>
<? html_head() ?>
</head>
<body>
<div class="body">

<?
translate();
gnome_head();
gnome_menu();
?>
<div class="content">

<h2>Welkom <? echo $GLOBALS["PHP_AUTH_USER"] ?>.</h2>
<?
$db = mysql_pconnect($GLOBALS['mysqlhost'],"gnome_nl",$GLOBALS['mysql_password']);
mysql_select_db("gnome_nl",$db);

if(isset($_GET["item"]) && $_GET["item"] != "0") {
	$sql = "select * from events where id = '".$_GET["item"]."'";
	// run the query and set a result identifier to the recordset
	$res = mysql_query($sql);
	$event = mysql_fetch_assoc($res);
	}; ?>
<form class="important" name="form" method="post" action="agenda_submit.php">
	<input type="hidden" name="object" value="events">
<?	echo "<input type=\"hidden\" name=\"item\" value=\"".$_GET["item"]."\">"; ?>
	<h2>Evenement:</h2>
	<div class="row">
		<span class="label">Titel:</span>
		<span class="formw"><input name="title" type="text" id="title" size="60" <?
	if (isset($event['title']))
		echo "value=\"".$event['title']."\"";
?>></span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Locatie:</span>
		<span class="formw"><input name="location" type="text" id="location" size="60" <?
	if (isset($event['location']))
		echo "value=\"".$event['location']."\"";
?>></span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Samenvatting:</span>
		<span class="formw"><textarea name="summary" cols="60" rows="10" id="summary"><?
	if (isset($event['summary']))
		echo $event['summary'];
?></textarea>&nbsp;</span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Van:<br><i>Opmaak moet <datum> <tijd> blijven.</i></span>
		<span class="formw"><input name="from" type="text" id="from" size="60" <?
	if (isset($event['from']))
		echo "value=\"".$event['from']."\"";
	else
		echo "value=\"2005-02-19 00:00:00\"";
?>></span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Tot:<br><i>Opmaak moet <datum> <tijd> blijven.</i></span>
		<span class="formw"><input name="until" type="text" id="until" size="60" <?
	if (isset($event['until']))
		echo "value=\"".$event['until']."\"";
	else
		echo "value=\"2005-02-19 23:44:00\"";
?>></span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Personen:</span>
		<span class="formw"><textarea name="personen" cols="60" rows="16" id="personen"><?
	if (isset($event['personen']))
		echo $event['personen'];
?></textarea>&nbsp;</span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Meer info:<br><i>(optioneel)</i></span>
		<span class="formw"><textarea name="content" cols="60" rows="16" id="content"><?
	if (isset($event['content']))
		echo $event['content'];
?></textarea>&nbsp;</span>
	</div>
	<div class="row">
		<span class="label">&nbsp;</span>
		<span class="formw"><input type="reset" name="Reset" value="Reset"><input name="Submit" type="submit" id="submit" value="Veranderingen opslaan"></span>
	</div>
	<p style="clear: right;">&nbsp;</p>
</form>



</div>

<? gnome_foot() ?>

</div>
</body>
</html>
