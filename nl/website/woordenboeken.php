<?
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Gnome-nl -- Woordenboeken</title>
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
<h1>Beschikbare woordenboeken.</h1>
<? 
//<p><b><a href="http://vertaling.nl.linux.org/woord.php">Woordenboek
//van vertaling.nl.linux.org:</a></b> Op deze pagina kun je zoeken in de
//database van nl.linux.org. Dit is de aanbevolen database voor zover
//deze toereikend is.</p>
?><p>Er is op internet een aantal woordenboeken beschikbaar
die je zouden kunnen helpen bij het vertalen van Programma's
naar het Nederlands. Hier staan ze allemaal op een rijtje.</p>
<p>Het eerste rijtje bevat zogenaamde slimme bladwijzers. Gebruik de rechter 
muisknop om ze aan je bladwijzers toe te voegen en je kunt ze op een 
eenvoudige manier gebruiken om snel te zoeken.</p>
<ul>
<li><a type="text/smartbookmark" href="http://www.interglot.com/" rel="http://www.interglot.com/interglotresult.php?SrcLang=2&DstLang=1&word=%s" title="Interglot">Interglot</a></li>
<li><a type="text/smartbookmark" href="http://www.euroglotonline.nl/" rel="http://www.euroglotonline.nl/display.php?srcLang=Engels&dstLang=Nederlands&srcInput=%s&MorphReg=true&ScreenLanguage=Dutch" title="Euroglot">Euroglot Online!</a></li>
</ul>
<p>Het volgende rijtje is er een van gewone bladwijzers met nog meer woordenboeken 
als de bovenstaande niet toereikend blijken.</p>
<ul>
<li><a href="http://dict.tu-chemnitz.de/">EN&lt;-&gt;DE woordenboek. Als je het niet zeker weet en wat inspiratie van onze oosterburen nodig hebt</a></li>
<li><a href="http://www.internetwoordenboek.com/">Kennisnet Online woordenboek</a></li>
<li><a href="http://www.computerwoorden.nl/">Computerwoorden.nl</a></li>
<li><a href="http://majstro.com/Web/Majstro/dict.php?bronTaal=eng&doelTaal=dut&prec=0&teVertalen=&gebrTaal=dut">Majstro</a></li>
<li><a href="http://dictionaries.travlang.com/EnglishDutch">Travlang</a></li>
<li><a href="http://www.freedict.com/onldict/dut.html">Freedict</a></li>
<li><a href="http://www.langdy.com/dacc_g.htm">WinDi</a></li>
<li><a href="http://europa.eu.int/eurodicautom/Controller">Eurodicautom</a></li>
<li><a href="http://www.kde.nl/nl/woordenlijst.html">De woordenlijst van onze collega's van KDE</a></li>
<li><a href="http://wiktionary.org/">De Wiktionary</a>: Een Wiki Woordenboek.</li>
<li><a href="http://just.letterror.com/ltrwiki/TypeDictionary">TypeDictionary</a>: Een wikiwoordenboek toegespitst op typografische woorden.</li>
</ul>
<p>Je kunt ook zoeken naar betekenissen van Nederlandse woorden via
<a href="http://www.vandale.nl/opzoeken/woordenboek/">VanDale.nl</a>.</p>
Er zijn verder 2 handige programma's die ik even wil noemen:
<ul>
<li><a href="http://www.djcbsoftware.nl/projecten/gnuvd/">gnuvd</a>, een handig opdrachtregelprogrammaatje waarmee je kunt zoeken in de van Dale.</li>
<li><a href="http://www.dict.org/">dict</a>, waar je betekenissen van engelse woorden mee kunt opzoeken.</li>
</ul>
</div>

<? gnome_foot() ?>

</div>
</body>
</html>
