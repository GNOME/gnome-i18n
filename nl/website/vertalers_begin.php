<?
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- beginnen met vertalen</title>
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

<a name="top"></a><h1>Beginnen met vertalen</h1>

<h2>Beste vertaler,</h2>
<p>Dus je hebt besloten dat je wel mee wilt helpen? Dat is mooi om te horen.
We kunnen altijd wel vertalers gebruiken. Of je nu ervaring hebt als
vertaler of niet; dat maakt niet uit. Als je kennis van de Nederlandse taal
maar goed is.</p>
<p>Wat je moet doen is dit: Stuur een e-mail naar de coördinator met daarin je
vertaal-ervaring tot nu toe en je eventuele voorkeur voor een bepaald pakket
of soort programma. Sommige mensen hebben een grafische achtergrond en zullen
dus beter een programma als Gimp of SodiPodi kunnen vertalen. Anderen hebben
vanwege een Economische achtergrond een goede basiskennis om programma's als
GnuCash te vertalen.</p>
<p>Abonneer je ook op de <a href="http://mailman.vrijschrift.nl/listinfo/vertaling">discussielijst vertaling@vrijschrift.org</a>. 
Daar worden onder andere discussies gehouden over
hoe iets vertaald zou moeten worden. <a href="#lijsten">Verderop</a> staat meer informatie over relevante discussielijsten.</p>
<p>Lees ook even de <a href="#uitleg">&quot;Korte uitleg&quot;</a> door. Daar staat uitgelegd hoe we
bepaalde dingen vertalen en hoe de vertaalbestanden (po-files) werken. Kom je problemen tegen, lees dan de 
<a href="#ehbv">Eerste Hulp Bij Vertalen</a>.</p>
Groetjes,<br>
<a href="email-webmaster.php">Jullie coördinator.</a>

<a name="uitleg"></a><h1>Korte uitleg voor vertalers!</h1>
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

<a name="ehbv"></a><h1>Eerste Hulp Bij Vertalen</h1>
<p>Je gaat vertalen, het gaat erg lekker, en ineens kom je een vertaling
tegen waar je niets mee kunt. Je bent niet eerste die tegen zoiets
aanloopt. Gelukkig ook maar, want je hebt nu een hoop mogelijkheden om
je hiertegen te wapenen.</p>
<ul>
<li>Het eerste wat je kunt doen is altijd: Bekijk je vertaling in het wild. Start
het programma in kwestie. Vaak kun je probeerversies van programma's
downloaden die nieuw genoeg zijn om je vertaling te kunnen testen. Met
gnome kun je gebruik maken van de garnome-pakketten om zo zelf de
programma's te compileren. Ook kun je even spieken hoe een ander pakket
het opgelost heeft. Dat is het voordeel van open-source. ;-)</li>
<li><b><a href="http://wiki.vrijschrift.nl/SoftwareVertalers">Wiki:</a></b>
Om ruimte te geven om te discussiëren over probleemvertalingen
(zoals directory) is er een zgn. WiKi opgezet. Een WiKi is
een website waar de gebruiker tijdens zijn bezoek de tekst van de
site live kan aanpassen in zijn webbrowser. De oude discussiepagina is 
<a href="http://www.o2w.nl/~ivo/wiki.pl?HomePage">hier</a> te vinden.
De nieuwe wordt op dit moment nog opgezet en kun je
<a href="http://wiki.vrijschrift.nl/SoftwareVertalers">hier</a> vinden.</li>
<li><a href="irc://irc.gnome.org/gnome-nl">IRC op Gimpnet</a>
: In het kanaal #gnome-nl zitten altijd wel een paar vertalers die je
kunnen helpen met eventuele problemen bij het vertalen. Ook kun je op
terecht in het kanaal #i18n (dit is wel een engelstalig kanaal) voor hulp.</li>
<li>Zoek eens in de <a href="woordenboeken.php">woordenboeken</a>
die beschikbaar zijn op het internet. Vaak staan daar de woorden in die je zoekt.</li>
<li>Als alles tevergeefs is, kun je je probleem/vraag altijd via de
<a href="#lijsten">discussielijst</a> bespreken.</li>
</ul>

<a name="lijsten"></a><h2>Discussielijsten</h2>
<p>Er zijn 3 discussielijsten waar je lid van kunt worden.
</p><ol>
<li><b>gnome-nl-list@gnome.org:</b> Nederlandstalige GNOME-gebruikers houden contact via de <strong>gnome-nl-list</strong>. Schrijf
jezelf in op de <a href="http://mail.gnome.org/mailman/listinfo/gnome-nl-list">gnome-nl-list
Informatiepagina</a>!</li>
<li><b>vertaling@vrijschrift.org:</b> Nederlandstalige discussielijst waar de Nederlandse
vertalingen van de hele open source gemeenschap worden gecoordineerd.
Geautomatiseerde berichten van nieuwe programma ( voor het TP ) worden hier gepost door
robotten. Ook kun je hier vragen stellen aan je Nederlandse medevertalers.
Hier zijn niet alleen gnome-vertalers lid van, maar ook KDE, en algemene GNU
vertalers. Hier zouden alle vertalers lid van moeten worden. De lijst is zeker
niet druk te noemen met gemiddeld slechts enkele e-mails per week. Abonneren op
de lijst doe je door te kijken op
<a href="http://mailman.vrijschrift.nl/listinfo/vertaling">de infopagina</a>.</li>
<li><b>gnome-i18n@gnome.org:</b> Engelse discussielijst waar de vertaling van GNOME naar
alle talen wordt gecoördineerd. Hier worden aankondigingen gepost van nieuwe
programma's die uit gaan komen. Ook wordt hier gediscussieerd over problemen met
vertalen. Alle coördinatoren en redelijk actieve vertalers zouden zich hierop
moeten abonneren. Het is een lijst met middelmatig verkeer van een a twee
e-mails per dag. Lid worden van de discussielist doe je op de
<a href="http://mail.gnome.org/mailman/listinfo/gnome-i18n">mailman pagina</a>
van gnome.org.</li>
</ol>


</div>

<? gnome_foot() ?>

</div>
</body>
</html>
