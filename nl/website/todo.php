<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- TODO</title>
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

<h1>Website TODO lijst</h1>

<p>Gnome-nl wil deze website graag uitbreiden en verbeteren. Daarvoor zijn de volgende idee&euml;n:</p>

<table border="1" cellpadding="10" cellspacing="0">
<tr>
<th valign="top">ToDo item
<th valign="top">Geschatte tijd/moeite

<tr style="background-color: orange;">
<td valign="top"><strong>Website veranderen</strong> om vertalen en 
vertalers minder prominent te maken. Subprojecten:
<td valign="top">Voor totaal veel tijd en moeite vanwege veel 
subprojecten. 

<tr style="background-color: red;">
<td valign="top"><strong>Gebruikersgedeelte</strong> is verouderd en kan 
uitgebreider. Denk aan een nl-versie van <a 
href='http://gnome-hacks.jodrell.net/'>http://gnome-hacks.jodrell.net/</a>. 
Gebruikers geven elkaar tips. Liefst zo makkelijk mogelijk door 
gebruikers bij te werken (Wiki?)
<td valign="top">Als bezoekers zelf tips kunnen toevoegen, makkelijk qua 
onderhoud. Opzetten kost wel even tijd, maar relatief weinig. Proberen 
snel uit te werken.

<tr style="background-color: lightgreen;">
<td valign="top"><strong>Paginahoeveelheid onder het kopje 
&quot;Vertalers&quot;</strong>. Dat is ook iets wat we zouden moeten 
verminderen. Er kan hier en daar wat bij elkaar gemikt worden. Voorstel: 
deel &quot;Beginnen met vertalen&quot; en deel &quot;Technische 
tips&quot;. 
<td valign="top">Gedaan door Michel Klijmij.

<tr style="background-color: lightgreen;">
<td valign="top">De <strong>verwijzingen naar andere websites</strong> 
loopt hier en daar wat achter. Vooral de woordenboeken omdat die niet zo 
vaak gebruikt worden (en wel vaak veranderen). Maar ook de andere 
vertaalprojecten en andere GNOME-pagina's zou verbeterd kunnen worden.
<td valign="top">Gedaan door Vincent van Adrighem.

<tr style="background-color: lightgrey;">
<td valign="top"><strong>Vertalen FootNotes</strong>, eventueel alleen 
headlines
<td valign="top">Kost heel veel tijd. Alleen headlines, verspreid over 
meerdere mensen, is nog wel te doen. Prioriteit is laag.

<tr>
<td valign="top">Overige zaken:
<td valign="top">&nbsp;

<tr style="background-color: red;">
<td valign="top">Opzetten van <strong>vertaalinfra voor documentatie</strong> om ook dat in de toekomst eenvoudig vertaald te kunnen hebben.<br>
Idee: Zo opzettendat een deel van de code zo naar l10n-stats kan (iedere taal heeft het
uiteindelijk nodig). Misschien met xml2po op de server en in
process-translation, zodat de vertalers en het statistiekenscript gewoon
met PO-bestanden kunnen werken?
<td valign="top">Lastig. Prioriteit niet zo hoog. Wel iets om in de 
nabije toekomst over na te denken.

<tr style="background-color: lightgrey;">
<td valign="top">Het <strong>verenigen van de vertalingen</strong> van 
GNOME en andere projecten in een enkele infrastructuur. Dit geeft een 
beter overzicht van het geheel. Ook zal het de samenwerking tussen de 
projecten bevorderen. <em>Is dat voor nl.gnome.org of ergens anders, 
zoals Vrijschrift?</em>
<td valign="top">Er is al contact geweest met het KDE team. Meer 
contacten leggen hiervoor. Een betere plaats hiervoor is <a href="http://vertaling.vrijschrift.org">vertaling.vrijschrift.org</a>

<tr style="background-color: lightgrey;">
<td valign="top">Vroeg of laat zal om een <strong>webforum</strong> 
worden gevraagd. 
Mooiste oplossing is een ge&iuml;ntegreerde voorziening met NNTP, milinglist en webforum. Zoiets als <a href='http://www.gmane.org/'>Gmane</a>. Zo ontstaan niet allerlei subgroepjes.
<td valign="top">Kost weinig tijd als het eenmaal loopt, zou snel 
gerealiseerd moeten kunnen worden.

</table>

</div>

<? gnome_foot() ?>

</div>
</body>
</html>
