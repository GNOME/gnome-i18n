<?php
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- De GNOME-NL community</title>
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

<h1>De GNOME-NL community</h1>
<h2>Bent u een GNOME-gebruiker?</h2>
<p>Dan bent u hier op de goede plek! Deze pagina fungeert als coördinatiecentrum voor alles wat met
GNOME in Nederland en Vlaanderen te maken heeft.</p>
<h2>De maillijst</h2>
<p>Nederlandstalige GNOME-gebruikers houden contact via de <strong>gnome-nl-list</strong>. Schrijf
uzelf in op de <a href="http://mail.gnome.org/mailman/listinfo/gnome-nl-list">gnome-nl-list
Informatiepagina</a>!</p>
<h2>Het IRC-kanaal</h2>
<p><img alt="irc-account" src="images/irc-account.png" class="floatright">
Wilt u in het Nederlands chatten met mede-GNOME-gebruikers? Dat kan! Op de IRC-server 
<a href="irc://irc.gnome.org/gnome-nl">irc.gnome.org</a>, 
kanaal #gnome-nl kunt u uw ei kwijt. Mocht uw browser het ondersteunen dan 
kunt u een chatprogramma opstarten door op de irc-verwijzing hierboven te
klikken.</p>
<P>In andere gevallen kunt u gebruik maken van programma's als Gaim en
X-chat.</P>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
