<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- Anatomie van een po</title>
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
<h1>Anatomie van een PO bestand</h1>

<p>Een PO bestand bestaat ruwweg uit 2 delen. <a href="#kop">De kop</a>:
Daarin staan de gegevens van de vertaler, zijn team, de taal enzovoorts.
Daarna komen, in deel 2, <a href="#vertalingen">de vertalingen</a>.

<h2><a name="kop" style="color:black">De kop van het bestand</a></h2>
<hr>
<p>Je moet allereerst de informatie in de kop invullen. Dan weten we dat
jij het aan het vertalen bent en kunnen we je vinden als je fouten maakt :-)
Het is de regel dat je vorige vertalers in de kop laat staan als commentaar.
Een soort van bedankje voor het vertalen dus.
</p>
Voorbeeld: <b>Gnome-vfs</b>
<pre>
# Gnome-vfs translation.
# Copyright (C) 2002 Free Software Foundation, Inc.
# Dennis Smit &lt;e-mail&gt;, 2000.
# Mendel Mobach &lt;e-mail&gt;, 2000.
msgid ""
msgstr ""
"Project-Id-Version: Gnome-vfs\n"
"POT-Creation-Date: 2002-06-14 13:07+0200\n"
"PO-Revision-Date: 2002-05-28 20:49+02:00\n"
"Last-Translator: Mendel Mobach &lt;e-mail&gt;\n"
"Language-Team: Dutch &lt;e-mail&gt;\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
</pre>
<p>Hier zie je dus hoe het moet. Eerst is dit bestand vertaald door Dennis
Smit. Vervolgens heeft Mendel Mobach de vertaling overgenomen. De programma's
genoemd onder het kopje programma's in het linker menu doen dit automatisch.
Daar hoef je dan dus niets aan te doen. Je kunt het beste wel even controleren
of het goed staat voordat je het doorstuurt naar de coördinator.<p>

<h2><a name="vertalingen" style="color:black">De vertalingen</a></h2>
<hr>
%s, %d, etc... Voorbeeld: <b>Gnome-vfs</b>
<pre>
#: libgnomevfs/gnome-vfs-configuration.c:269
#, c-format
msgid "%s:%d contains no module name."
msgstr "%s:%d bevat geen modulenaam."
</pre>
<p>Hier zie je een voorbeeldvertaling. De te vertalen tekst bevat rare tekens als
%s en %d. Wanneer het programma draait vervangt het deze tekens door eigen tekst.
Het zijn dus een soort van variabelen. (%s = string/tekst, %d = decimal/getal).
De ingevulde tekst zou dus iets zijn als "I/O-fout:30 bevat geen modulenaam" ofzoiets.
</p>
<hr>
Underscores... Voorbeeld: <b>Gnome-session</b>
<pre>
#: gnome-session/gsm-client-editor.c:108
msgid "_Style:"
msgstr "_Stijl:"
</pre>
<p>Hier zie je een vertaling met een underscore. De underscore geeft een sneltoets aan.
Dit is een Alt+(de toets waar de underscore voor staat) binding. Hij moet uniek zijn
zodat de gebruiker hem kan gebruiken om te navigeren met zijn toetsenbord in plaats van
met zijn muis. Andere voorbeelden zijn de alt+F (file-menu) en alt+W (bewerken).
De CTRL-toets sneltoetsen worden niet beïnvloed hierdoor (CTRL+C, CTRL+V).
</p>
<hr>
\n... Voorbeeld: <b>Gnome-session</b>
<pre>
#: gnome-session/session-properties-capplet.c:142
msgid ""
"Some changes are not saved.\n"
"Is it still OK to exit?"
msgstr ""
"Sommige wijzigingen zijn niet opgeslagen. \n"
"Wilt u toch afsluiten?"
</pre>
<p>Hier zien we een zin die een \n bevat aan het einde van de regel. Als dit in het
origineel staat, dan is het blijkbaar nodig om met een \n aan te geven dat er naar de
volgende regel gesprongen dient te worden. Zet het dus ook in je vertaling.
</p>
<hr>
Meervouden... Voorbeeld: <b>gnome-games</b>
<pre>
#: gtali/gyahtzee.c:123
#, c-format
msgid "%s wins the game with %d point"
msgid_plural "%s wins the game with %d points"
msgstr[0] "%s wint het spel met %d punt!"
msgstr[1] "%s wint het spel met %d punten!"
</pre>
<p>Je ziet hier twee vertalingen, een enkelvoud en een meervoud, in een msgid.
De msgstr[0] staat voor het enkelvoud en [1] voor het meervoud.</p>
<p>Als je dit tegenkomt moet je ook iets aan de kop toevoegen:
<blockquote>Plural-Forms: nplurals=2; plural=(n != 1);</blockquote>
Op dat moment zal de juiste vertaling worden aangeroepen voor meervoud en enkelvoud.</p>
<hr>
Gelabelde variabelen... Voorbeeld: <b>gramps</b>
<pre>
#: plugins/Verify.py:226
msgid "Married often: %(male_name)s married %(nfam)d times.\n"
msgstr "Vaak getrouwd: %(male_name)s is %(nfam)d keer getrouwd.\n"
</pre>
<p>Tussen de variabele-aanduiding en het type variabele zie je een label tussen haakjes staan.
Hieraan kan de variabele herkend worden. Dit moet <b>niet</b> vertaald worden.</p>
<p>Een voordeel van gelabelde variabelen is dat je makkelijk kunt verwisselen omdat je
makkelijk het verschil kunt zien.</p>
<hr>
<p><b>Nog een laatste tip:</b> Soms zie je de melding "fuzzy" (vaag, onduidelijk) staan
in het commentaar boven een vertaling. Als je een term hebt vertaald voor een programma,
en die maker verandert het origineel (hoofdletter weg, of zinsnede net iets anders),
dan wordt de term fuzzy erbij gezet. De tekst zal dan niet meer automatisch vertaald
worden. Het is dan jouw taak als vertaler om te controleren of de vertaling nog klopt
en deze zo nodig aan te passen. Wanneer dit is gebeurd haal je het woordje "fuzzy" weg
en wordt de tekst weer vertaald.</p>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
