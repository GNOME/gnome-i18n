<?php
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Foutrapport</title>
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
<h1>Een vertaalfout melden bij het vertaalteam</h1>
<p>Je kunt gebruik maken van onderstaand formulier om een foutrapport
in te sturen. Als je een foutje in een vertaling tegenkomt, of een vertaling krom
vindt (en misschien iets beters weet), geef dat dan op deze manier aan ons door.</p>
<hr>
<div style="width:	38em;">
	<form class="important" name="form" method="post" action="sturen.php">
		<input type="hidden" name="object" value="Gnome-nl foutrapport">
		<input type="hidden" name="pagina" value="foutrapport">
		<h2>Persoonlijke gegevens</h2>
		<p>Deze informatie is optioneel, maar wel zeer gewenst.
		We gebruiken deze gegevens alleen om je antwoord te geven op je foutrapport.
		Ook kunnen we extra info vragen als er dingen onduidelijk zijn.</p>
		<div class="row">
			<span class="label">Je naam:</span>
			<span class="formw"><input name="naam" type="text" id="naam" size="40"></span>
		</div>
		<div class="row">
			<span class="label">Je e-mail adres:</span>
			<span class="formw"><input name="email" type="text" id="email" value="@" size="40"></span>
		</div>
		<p>&nbsp;</p>
		<h2>Programmagegevens</h2>
		<p>Waar ben je de vertaling tegengekomen? Welk programma, en welke versie van
		 het programma (via het menu Hulp, optie "Info" kun je de versie achterhalen).</p>
		<div class="row">
			<span class="label">Programma:</span>
			<span class="formw"><input name="prog" type="text" id="prog" size="40"></span>
		</div>
		<div class="row">
			<span class="label">Versie:</span>
			<span class="formw"><input name="versie" type="text" id="versie" size="40"></span>
		</div>
		<p>&nbsp;</p>
		<h2>De vertaling</h2>
		<p>De daadwerkelijke vertaalfout. Hier kun je opgeven welke vertaling fout is, en eventueel
		een verbeterde vertaling geven. Als je geen betere weet, kun je ook vertellen waarom je de vertaling
		niet goed vindt. Eventuele extra info zoals beredenering, of stijlfouten kun je in het vakje "extra info" kwijt.</p>
		<div class="row">
			<span class="label">Foute vertaling:</span>
			<span class="formw"><input name="fout" type="text" id="fout" size="40"></span>
		</div>
		<div class="row">
			<span class="label">Betere vertaling:</span>
			<span class="formw"><input name="goed" type="text" id="goed" size="40"></span>
		</div>
		<div class="row">
			<span class="label">Evt. extra info:</span>
			<span class="formw"><textarea name="extra" cols="35" rows="4" id="extra"></textarea>&nbsp;</span>
		</div>
		<div class="row">
			<span class="label">&nbsp;</span>
			<span class="formw"><input type="reset" name="Reset" value="Wissen"><input name="Submit" type="submit" id="submit" value="Rapport versturen"></span>
		</div>
		<p style="clear: right;">&nbsp;</p>
	</form>
</div>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
