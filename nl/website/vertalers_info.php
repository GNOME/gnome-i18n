<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- Korte uitleg</title>
<? html_head() ?>
</head>
<body>
<div class="body">

<?
meter();
gnome_head();
gnome_menu();
?>
<div class="content">
<h1>Korte uitleg voor vertalers!</h1>
<p>Er moeten in Linux-programma's ruwweg 2 dingen vertaald worden:
De teksten in het programma (bv: File->Save => Bestand->Opslaan), en de
handleidingen van de programma's. De nadruk ligt vooral op de teksten in de
programma's. Die teksten worden (live) vertaald via het programma gettext.
Dit programma kijkt in een lijst wat het vertaalde woord/zin zou moeten zijn.
Die lijsten heten po-bestanden (omdat ze eindigen op .po).</p>
<p>Die po-bestanden kun je hier downloaden via de statuspagina's. Ook kun
je ze maken door de broncode van een programma te downloaden en intltool te
gebruiken. Voor de nieuwste versie van de broncode is meestal het
programma cvs nodig. De statuspagina's worden regelmatig (meerdere malen per dag)
bijgewerkt en dus is het zelf downloaden van de broncode in de meeste
gevallen niet nodig.</p>
<h2>Enkele tips voor de beginnende vertaler:</h2>
<ul>
<li>Vertaal niet te snel in de gebiedende wijs / enkelvoud. De gebiedende wijs geeft aan dat de
gebruiker iets moet doen. Meervoud geeft aan dat de computer iets moet doen.(b.v. Save File -> Bestand opslaan)</li>
<li>Schrijf woorden die aan elkaar horen aan elkaar (Print preview -> Afdrukvoorbeeld, en geen Afdruk&nbsp;voorbeeld).</li>
<li>Schrijf alleen het eerste woord met een hoofdletter. Dus Geen Zinnen Als Dit. In het engels is dit wel de conventie (Save File), maar in het Nederlands is dat niet zo (dus: Bestand opslaan).</li>
</ul>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
