<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

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
<div class="rightbox">
<?transstatus($important_branch); ?>
</div>
<div class="content">
<h1>Welkom bij GNOME Nederlands</h1>

<p>Het GNOME-NL project is opgezet om GNOME naar het Nederlands te
vertalen. GNOME is een vrije (Libre), gebruikersvriendelijke, krachtige
en toegankelijke grafische werkomgeving voor de besturingssystemen die
tot de Unix-familie behoren, zoals Linux en BSD. GNOME is een acroniem
dat staat voor GNU Network Object Model Environment. GNOME omvat zowel
een werkomgeving (desktop environment) als een ontwikkelplatform
(developer platform).</p>
<p>Op deze website is informatie te vinden over de voortgang van het project,
alsmede informatie voor nieuwe vertalers. Kijk daarvoor in het menu onder het kopje "Vertalers".</p>
<p>Ook voor gebruikers hebben we een stukje van deze site ingedeeld om
uit te leggen hoe ze gebruik kunnen maken van deze vertalingen.</p>
<p>Heb je een vertaalfout gevonden? Laat dat dan direct weten
<a href="foutrapport.php">via dit formulier.</a></p>
</div>
<? gnome_foot() ?>

</div>
</body>
</html>
