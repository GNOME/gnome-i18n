<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- Gnome in het Nederlands</title>
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
<h1>GNOME in het Nederlands: Hoe?</h1>
<p>
Een hoop mensen hebben GNOME nu op hun systeem staan en gebruiken het naar
volle tevredenheid, maar vragen zich af hoe het in het Nederlands kan.
Daar zijn een paar manieren voor.
</p><ul>
<li><b>/etc/environment</b><p>Dit is <b>de aanbevolen manier</b> van aangeven.
Hierin staan alle dingen die belangrijk zijn voor je omgeving.
Hier voeg je dus ook je voorkeurstaal aan toe:</p>
<pre>LANG=nl_NL.UTF-8
LANGUAGE=nl_NL.UTF-8</pre></li>
<li><b>/etc/profile</b><p>Ook in /etc/profile kun je aangeven dat je op je
hele systeem gebruik wilt maken van de Nederlandse taal. Dat doe je door:</p>
<pre>export LANG=nl_NL.UTF-8
export LANGUAGE=nl_NL.UTF-8</pre>
erin te zetten. Dit werkt alleen wanneer er een <b>bash</b> compatibele shell
op je systeem staat. Dit is tegenwoordig zo op alle moderne
Linux-distributies.</li>
<li><b>~/.bashrc</b><p>In plaats van globaal (hele systeem), kun je dit
natuurlijk ook instellen per gebruiker.
Om dit te doen zet je in het bestandje .bashrc (in je homedir) de inmiddels
bekende regel:</p>
<pre>export LANG=nl_NL.UTF-8
export LANGUAGE=nl_NL.UTF-8</pre></li>
</ul>
<p>Wanneer je dit veranderd hebt, zul je GNOME even moeten herstarten (afmelden en weer aanmelden) om de veranderingen te activeren.
</p><p><b>Opmerking:</b>De iets oudere distributies bieden geen volledige ondersteuning voor nl_NL.UTF-8.
Als het niet juist werkt kun je de code proberen te veranderen in:</p>
<ul><li>nl_NL@Euro</li>
<li>nl_NL</li>
<li>nl</li></ul>
<p><b>Opmerking 2:</b>Het gebruik van sommige taalinstellingen kan leiden tot
zeer traag opstartende of afsluitende programma's (pauzes lopen soms op tot 30
seconden). Dit wordt waarschijnlijk veroorzaakt door incompatibiliteiten tussen
de al wat oudere bibliotheken en de nieuwe fontconfig + UTF-8 mogelijkheden van
GNOME. Probeer in dat geval dus een van de andere mogelijkheden.</p>
<h2>GDM</h2>
<p><b>GDM</b> kun je ook in het Nederlands gebruiken. Om dat te doen zul je GDM moeten configureren.
Dat kan vanuit het inlogscherm als het "systeem"-menu aan staat. Anders kun je de configuratie met de 
hand bewerken. De bestanden zijn: /etc/gdm/gdm.conf of /etc/X11/gdm/gdm.conf</p>
<p>Daarin moet je de regel "DefaultLocale=" opzoeken en veranderen in:
<pre>DefaultLocale=nl_NL.UTF-8</pre>
<p>En als deze nog niet in de configuratie staat, deze zelf toevoegen onder het kopje "[greeter]".</p>

<p>Er zit een klein bugje in het startscript waardoor GDM niet
100% Nederlands wordt na deze aanpassing (en herstarten van GDM natuurlijk).
Om GDM 100% Nederlands te krijgen moeten er enkele regels aan het
start-script worden toegevoegd (/etc/init.d/gdm).</p>
<p>Open dat bestand met je favoriete editor en voeg aan het begin
(Na de declaratie van variabelen en voor de functies) dit stukje toe:</p>
<pre># Setup the default LANG environment variable if there is such
LANG_RE='^LANG=[a-z][-a-zA-Z0-9_.@]*$'
if test -f /etc/environment && grep -q "$LANG_RE" /etc/environment; then
	export `grep "$LANG_RE" /etc/environment`
fi</pre>
<p>Hiervoor moet je wel je taal hebben ingesteld in '/etc/environment'.
Na een herstart van GDM zal deze 100% Nederlands zijn.</p>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
