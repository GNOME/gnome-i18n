<?php
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Welkom</title>
<? html_head() ?>
</head>
<body>
<div class="body">

<?
translate();
gnome_head();
gnome_menu();
?>
<div class="rightbox"><center><h3>Even uw aandacht...</h3></center>

<?
//transstatus($important_branch); 
include "reclame.php";
?>
</div>
<div class="content">
<h1>Welkom bij GNOME Nederlands</h1>

<p>GNOME is een vrije, gebruikersvriendelijke, krachtige
en toegankelijke grafische werkomgeving voor de besturingssystemen die
tot de Unix-familie behoren, zoals Linux en BSD. GNOME is een acroniem
dat staat voor GNU Network Object Model Environment. GNOME omvat zowel
een werkomgeving (desktop environment) als een ontwikkelplatform
(developer platform).</p>
<h1>9 maart 2005: GNOME 2.10 is uitgebracht</h1>
<p>Het GNOME Project heeft vandaag de nieuwste versie uitgebracht van
de GNOME Werkomgeving en -Ontwikkelplatform, de vooruitstrevende werkomgeving
voor Linux- en Unix- besturingssystemen. Versie 2.10 vergroot het gebruiksgemak,
de stabiliteit en de kracht van GNOME door integratie van multimedia, toevoeging
van programma -ontwikkelingsmogelijkheden en toepassing van duizenden verbeteringen
die de eenvoudigste en vriendelijkste werkomgeving nog verder verfijnen. </p>
Kijk <a href="start/html/">hier</a> voor meer info.
<p>Het GNOME-NL project is opgezet om GNOME naar het Nederlands te
vertalen. Dit omdat een werkomgeving pas echt goed is als je er gebruik van kan maken
in je eigen taal. Het doel is daarom om een 100% vertaalde "GNOME Desktop" te hebben.
Het gevolg hiervan is dat we alle software in de GNOME CVS vertalen. De officiële GNOME programma's
krijgen hierbij enige voorrang.</p>
<p>GNOME-NL is ondertussen uitgegroeid tot een heuse community. Klik op de diverse opties
in het menu om eens te kijken waar we ons mee bezig houden, of kom eens langs op de discussielijst
of op <a href="irc://irc.gnome.org/gnome-nl">IRC</a> en maak kennis met GNOME-NL.</p>
<p>Op deze website is ook informatie te vinden over de voortgang van het project,
alsmede informatie voor nieuwe vertalers. Kijk daarvoor in het menu onder het kopje "Vertalers".</p>
</div>
<? gnome_foot() ?>

</div>
</body>
</html>
