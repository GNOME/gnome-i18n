<?
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Technische tips bij het vertalen</title>
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
<h1>Technische tips</h1>

<p>In dit document vind je handige tips voor bij het vertalen, zoals <a href="#software">programma's</a>,
<a href="#anatomie">hoe een .po bestand in elkaar zit</a> en hoe je <a href="#cvs">via CVS de broncode
kan downloaden</a>.</p>

<a name="software"></a><h1>Programma's voor vertalers</h1>
<p>Je kunt de po bestanden vertalen met je favoriete editor. Dit werkt altijd
en sommigen zweren erbij. Er zijn echter ook programma's verkrijgbaar die ervoor
gemaakt zijn om het vertalen makkelijker en sneller te maken. Je kunt ze zoals
altijd vinden via freshmeat.net, maar ik heb hier een klein lijstje voor de
ongeduldigen onder ons:
</p><ul>
<li><a href="http://gtranslator.sourceforge.net">Gtranslator</a>: De GNOME 2 gebaseerde
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


<a name="anatomie"></a><h1>Anatomie van een PO bestand</h1>

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
Als je dit tegenkomt moet je ook iets aan de kop toevoegen:
<blockquote>Plural-Forms: nplurals=2; plural=(n != 1);</blockquote>
Op dat moment zal de juiste vertaling worden aangeroepen voor meervoud en enkelvoud.
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


<a name="cvs"></a><h1>CVS-toegang</h1>
<h2>Wat is CVS?</h2>
<p>Voor het software-beheer van gnome wordt gebruik gemaakt van CVS (Concurrent Versions System). Dat is een soort van Network Filesystem waar iedereen een eigen kopie van de broncode op zijn eigen computer heeft. De server houdt geschiedenis van veranderingen bij per bestand en de gebruikers kunnen de veranderingen per bestand via een soort van rsync-protocol (gedeeltelijk bijwerken m.b.v. patches) van de server halen of naar de server opsturen. Dit alles gaat natuurlijk veelal automagisch ;-)</p>
<p>Vertalers kunnen de nieuwste "CVS-versie" van software-broncode downloaden om de vertaling te testen, of gewoon om de nieuwste versie van een software-pakket te gebruiken. Het enige wat je nodig hebt om dit te kunnen doen is het programma "cvs".</p>
<h2>Anonieme CVS-toegang</h2>
<p>Om de software te kunnen downloaden kun je gebruik maken van de anonieme CVS-toegang. Dit vereist 3 stappen.</p>
<ul>
<li><b>Stap 1</b>
Eerst moet je een omgevingsvariabele opzetten zodat de server gevonden kan worden:
<blockquote>
<p>$ export CVSROOT=':pserver:anonymous@anoncvs.gnome.org:/cvs/gnome'</p>
</blockquote></li>
<li><b>Stap 2</b>
Vervolgens moet je je aanmelden op de server:
<p>(Je kunt gewoon op "enter" drukken aangezien er geen wachtwoord is voor anonieme</p> toegang.)
<blockquote>
<p>$ cvs login</p>
</blockquote>
<p>Dit hoef je slechts eenmalig te doen omdat cvs de aanmeldprocedure onthoudt.</p>
</li>
<li><b>Stap 3</b>
Nu kun je de software downloaden met:
<blockquote>
<p>$ cvs -z3 checkout $module</p>
</blockquote>
<p>Die -z3 geeft de compressiefactor aan. Laat deze onveranderd om de server niet te zwaar te belasten.</p></li>
<li><b>Stap 3a</b>
Als je later de software wilt bijwerken ga je in de map $module staan en geef je deze opdracht:
<blockquote>
<p>$ cvs -z3 update -Pd</p>
</blockquote>
<p>-Pd zijn wat extra opties om ervoor te zorgen dat werkelijk alles wordt bijgewerkt.</p></li>
</ul>

<h2>CVS-toegang voor mensen met een account</h2>
<p>Voor schrijftoegang heb je een officieel account nodig. Dit is voor de meesten niet nodig. Maar voor de volledigheid:</p>
<ul><li><b>Stap 1</b>
De omgevingsvariabele wordt net iets anders:
<blockquote>
<p>$ export CVSROOT=':pserver:je-cvs-naam@cvs.gnome.org:/cvs/gnome'</p>
</blockquote></li>
<li><b>Stap 2</b>
Vervolgens moet je je aanmelden op de server:
<p>(Nu moet je natuurlijk gewoon je wachtwoord invullen.)
<blockquote>
<p>$ cvs login</p>
</blockquote>
<p>Dit hoef je slechts eenmalig te doen omdat cvs de aanmeldprocedure onthoudt.</p>
</li>
</ul>
<p>De rest is volledig hetzelfde als bij anonieme toegang. Plus dat je een extra opdracht erbij hebt. De opdracht om iets op de server te zetten. Daarvoor gebruik je "cvs commit" vanuit de module-map.</p>
<h2>Po-bestand bijwerken na update</h2>
<p>Na een sync met de nieuwste CVS is je po-bestand nog niet bijgewerkt. Dat doe je door naar de map $module/po te gaan en daar de opdracht "intltool-update nl" uit te voeren. Dan wordt het po-bestand bijgewerkt en kun je de vertaling weer helemaal bijwerken.</p>
<p>Als je op deze manier de vertalingen gaat maken moet je er wel bijzeggen wat voor CVS-versie je hebt gebruikt zodat degene met schrijftoegang weet naar welke "branch" hij de vertaling weg moet schrijven. Anders komen er vertalingen op de verkeerde plek terecht.</p>
<p>Branches moet je zien als zijsporen van een treinrails. Je hebt de hoofdweg (HEAD) en je kunt een wissel ($branch) aangeven waarna er een zijspoor ontstaat Dat zijn over het algemeen de stabiele releases die later afsterven terwijl de HEAD gewoon doorgaat.</p>
<p>Voor meer informatie hierover kun je kijken op de <a href="http://developer.gnome.org/tools/cvs.html">pagina's op gnome.org.</a></p>

</div>

<? gnome_foot() ?>

</div>
</body>
</html>
