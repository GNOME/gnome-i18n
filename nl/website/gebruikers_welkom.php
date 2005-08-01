<?php
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Welkom bij G.N.O.M.E.</title>
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
<a href="images/schermafdruk.png" target="gnome-nl_screenshot">
<img class="floatright" src="images/schermafdruk_mini.png" alt="GNOME-NL voorbeeld"></a>
<h1>Welkom bij G.N.O.M.E.</h1>
<p><strong>GNOME</strong> is een vrije (Libre), gebruiksvriendelijke, krachtige en toegankelijke grafische werkomgeving
voor de besturingssystemen die tot de Unix-familie behoren, zoals Linux en BSD.
<strong>GNOME</strong> is een acroniem dat staat voor 
<strong>G</strong>NU <strong>N</strong>etwork <strong>O</strong>bject 
<strong>M</strong>odel <strong>E</strong>nvironment.</p>
<p><strong>GNOME</strong> omvat zowel een werkomgeving (desktop environment) 
als een ontwikkelplatform (developer platform).
Een vergelijkbaar project is <strong>KDE</strong>.</p>

<h2>Het doel van GNOME</h2>
<p>GNOME heeft de begrippen gebruiksvriendelijkheid, eenvoud en toegankelijkheid hoog in het vaandel staan. 
Regelmatig worden verbeterde versies uitgebracht. GNOME krijgt krachtige ondersteuning vanuit het bedrijfsleven, 
onder meer van Sun Microsystems, Red Hat en Novell.
</p>

<h2>Werkomgeving</h2>
<p>Een werkomgeving is hetgeen waarmee u werkt en wat op uw computer zichtbaar is, 
waaronder het bestandsbeheer, de menu's, en diverse toepassingen zoals een web-browser. 
Zie ook de onderwerpen over de grafische gebruikersinterface en de desktop.</p>

<h2>Ontwikkelplatform</h2>
<p>Een ontwikkelplatform bestaat uit een aantal gereedschappen en kant-en-klare onderdelen 
("Lego-blokjes") in software. De meerwaarde hiervan zit hem in het feit dat niet iedere 
programmeur het wiel opnieuw uit hoeft te vinden, maar dat veel voorwerk al verricht is.
Een belangrijk onderdeel van het GNOME-ontwikkelplatform is de toolkit GTK.
</p>

<h2>Meedoen</h2>
<p>De grootste kracht van GNOME is de hechte gemeenschap. Bijna iedereen kan bijdragen aan het verbeteren 
van GNOME, ook degenen die niet kunnen programmeren.</p>
<p>Sinds de start van GNOME in 1997 hebben al honderden mensen programmacode voor GNOME 
geschreven. Nog meer mensen hebben bijgedragen op een andere belangrijke manier door 
bijvoorbeeld te vertalen, te documenteren, en de kwaliteit te bewaken.</p>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
