<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- Programma's</title>
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
<h1>Programma's voor vertalers</h1>
<p>Je kunt de po bestanden vertalen met je favoriete editor. Dit werkt altijd
en sommigen zweren erbij. Er zijn echter ook programma's verkrijgbaar die ervoor
gemaakt zijn om het vertalen makkelijker en sneller te maken. Je kunt ze zoals
altijd vinden via freshmeat.net, maar ik heb hier een klein lijstje voor de
ongeduldigen onder ons:
</p><ul>
<li><a href="http://www.gtranslator.org">Gtranslator</a>: De GNOME 2 gebaseerde
translation-editor. Features zijn o.a. deelvensters om de lijst met vertalingen 
tegelijk te zien met je huidige vertaling, functies om fouten ongedaan te maken, 
een leerbuffer om veel voorkomende teksten niet iedere keer opnieuw te hoeven
vertalen, compatibel met de nieuwe meervoudsvormen van gettext en natuurlijk
een gelikte gnome-interface.</li>
<li><a href="http://i18n.kde.org/tools/kbabel/">KBabel</a>: Op KDE gebaseerd
programma met ongeveer dezelfde features. Volledig gericht op
het snel, efficiënt en comfortabel vertalen van een programma.</li>
<li><a href="http://poedit.sourceforge.net/">PoEdit</a>: Een uitgebreide
translation-editor die zowel
in Linux als in Windows werkt. Wel zo makkelijk.</li>
</ul><p>
Verder zijn er natuurlijk vele kleine programma's die je kunnen helpen. Als je
een vertaling zelf wilt testen/uitproberen, dan kun je dat doen op deze manier:
</p>
<ul>
<li>Installeer het programma wat je vertaalt (logisch, hoe wil je het anders testen)</li>
<li>Tik in (in een terminal):</li>
</ul>
<pre>msgfmt jouwvertaling.po -o /usr/share/locale/nl/LC_MESSAGES/projectnaam.mo</pre>
projectnaam staat dus voor de naam van het programma wat je vertaalt.
<p>Testen kun je ook doen met de opdracht "msgfmt -cvv jouwvertaling.po".
Met "msgfmt --statistics" kijk je of je alles vertaald hebt.
</p>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
