<?php
include "functions.php";
must_be_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Nieuws bewerken</title>
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
	$sql = "select * from news where id = '".$_GET["item"]."'";
	// run the query and set a result identifier to the recordset
	$res = mysql_query($sql);
	$newsitem = mysql_fetch_assoc($res);
	}; ?>
<form class="important" name="form" method="post" action="submit.php">
	<input type="hidden" name="object" value="news">
<?	echo "<input type=\"hidden\" name=\"item\" value=\"".$_GET["item"]."\">"; ?>
	<h2>Nieuwsbericht:</h2>
	<div class="row">
		<span class="label">Titel:</span>
		<span class="formw"><input name="title" type="text" id="title" size="60" <?
	if (isset($newsitem['title']))
		echo "value=\"".$newsitem['title']."\"";
?>></span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Samenvatting:</span>
		<span class="formw"><textarea name="summary" cols="60" rows="16" id="summary"><?
	if (isset($newsitem['summary']))
		echo $newsitem['summary'];
?></textarea>&nbsp;</span>
	</div>
	<p style="clear: right;">&nbsp;</p>
	<div class="row">
		<span class="label">Bericht:<br><i>(optioneel)</i></span>
		<span class="formw"><textarea name="content" cols="60" rows="16" id="content"><?
	if (isset($newsitem['content']))
		echo $newsitem['content'];
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
