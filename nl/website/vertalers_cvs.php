<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
include "functions.php";
?>

<head>
  <title>Gnome-nl -- CVS-toegang</title>
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
<h1>CVS-toegang</h1>
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
