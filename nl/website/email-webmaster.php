<?php
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- e-mail de webmaster</title>
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
<h1>E-mail de webmaster</h1>
<p>Om misbruik te voorkomen, staat er tegenwoordig geen e-mailadres meer op de website.
In plaats daarvan kunt u onderstaand formulier gebruiken om met mij in contact te komen.
Vergeet niet uw eigen e-mailadres in te vullen zodat ik een bericht terug kan sturen.</p>
<div style="width:	38em;">
	<form class="important" name="form" method="post" action="sturen.php">
		<input type="hidden" name="object" value="Gnome-nl webform">
		<input type="hidden" name="pagina" value="email-webmaster">
		<h2>Persoonlijke gegevens</h2>
		<div class="row">
			<span class="label">Naam:</span>
			<span class="formw"><input name="naam" type="text" id="naam" size="40"></span>
		</div>
		<div class="row">
			<span class="label">E-mailadres:</span>
			<span class="formw"><input name="email" type="text" id="email" value="@" size="40"></span>
		</div>
		<p style="clear: right;">&nbsp;</p>
		<div class="row">
			<span class="label">Bericht:</span>
			<span class="formw"><textarea name="extra" cols="35" rows="8" id="extra"></textarea>&nbsp;</span>
		</div>
		<div class="row">
			<span class="label">&nbsp;</span>
			<span class="formw"><input type="reset" name="Reset" value="Wissen"><input name="Submit" type="submit" id="submit" value="Bericht versturen"></span>
		</div>
		<p style="clear: right;">&nbsp;</p>
	</form>
</div>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
