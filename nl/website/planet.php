<?php
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>




 
<head>
<title>Planet GNOME-NL</title>
<? html_head() ?>
<meta name="generator" content="Planet 0.2 http://www.planetplanet.org/">
</head>

<body>
<div class="body">

<?
translate();
gnome_head();
gnome_menu();
?>
<div class="content">
<div class="rightbox">
<p>Je kunt deze planet ook aan je eigen RSS-verzameling toevoegen.
Gebruik daarvoor deze pagina: <a href="data/rss20.xml">RSS 2.0</a>.
</p>
<center><h3>Leden</h3></center>
<ul>
<li><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a> <a href="http://www.livejournal.com/users/inverted_tree/data/rss">(rss)</a></li>
<li><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a> <a href="http://www.moerman.org/~uwog/blog/wp-rss2.php">(rss)</a></li>
<li><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a> <a href="http://www.klijmij.net/~michel/index.php/feed/">(rss)</a></li>
<li><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a> <a href="http://geeklog.eyesopened.nl/index.rdf">(rss)</a></li>
<li><a href="http://vanschouwen.system-x.org" title="Reinouts' nerdy notes">Reinout van Schouwen</a> <a href="http://vanschouwen.system-x.org/wp-rss2.php">(rss)</a></li>
<li><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a> <a href="http://www.advogato.org/person/rbultje/rss.xml">(rss)</a></li>
<li><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a> <a href="http://www.advogato.org/person/adrighem/rss.xml">(rss)</a></li>
</ul>
</div>
<h1>Planet GNOME-NL</h1>


















<div class="blosxomDate>April 11, 2005</div>




<p class="blosxomTitle"><a href="http://vanschouwen.system-x.org" title="Reinouts' nerdy notes">Reinout van Schouwen</a></p>


<h4><a href="http://vanschouwen.system-x.org/?p=16">GNOME-nl PR (2)</a></h4>
<p>
<p>Had a nice gettogether with <a href="http://geeklog.eyesopened.nl/">Michiel</a> and <a href="http://www.advogato.org/person/rbultje/">Ronald</a> today, to discuss a magazine article about GNOME we&#8217;re going to write. I baked a quiche with broccoli, cashew nuts and blue cheese for dinner, didn&#8217;t even get one complaint about the <a href="http://vegatopia.com/">absence of meat</a> <img src="http://vanschouwen.system-x.org/wp-images/smilies/icon_smile.gif" alt=":-)" class="wp-smiley"> </p>
	<p>Last Friday I did an introductory presentation about GNOME for small entrepreneurs on the isle of <a href="http://www.texel.nl/">Texel</a>, on invitation of Alex de Haan from <a href="http://www.stichtingsoftwareconsulent.nl/html/agenda.html">Stichting De Softwareconsulent</a>. Pieter Valentijn (Dutch <a href="http://www.turbocash.nl/">TurboCASH</a> developer) was kind enough to give me a ride. Picture I made of the audience:<br>
<img src="http://vanschouwen.system-x.org/pictures/mkb publiek" alt="The audience"></p>
	<p>I found out that there&#8217;s a couple of GNOME modules that aren&#8217;t listed on the <a href="http://l10n-status.gnome.org/">status pages</a> but require translation nevertheless; this was the case with the gnome-2.10 branch of <a href="http://www.gnome.org/projects/epiphany/extensions">epiphany-extensions</a>. Can someone explain to me why just the HEAD branch is listed on the status page?
</p></p>
<p>
<em><a href="http://vanschouwen.system-x.org/?p=16">by Reinout at April 11, 2005 09:27 PM</a></em>
</p>









<p class="blosxomTitle"><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></p>


<h4><a href="http://www.advogato.org/person/adrighem/diary.html?start=5">11 Apr 2005</a></h4>
<p>
<p><b>GNOME-NL</b><p>
We gaan de goede kant op. Er zijn enkele goede ontwepen voor naamkaartjes. Gisteren hebben Reinout, Ronald en Michiel gewerkt aan een stuk voor in Linux Magazine. Er is een rekening besteld om zo de financi&#195;&#171;le kant wat beter te organiseren. De toekomst ziet er zonnig uit.<p>
<b>Andere activiteiten</b><p>
Afgelopen zaterdag een feestje gehad. Veel bier (Grolsch en Palm) en een gezellige sfeer. Met de nachttrein naar huis, maaaaaaar voordta het zo ver was, eerst nog even een tussenstop in een lokale kroeg. Je moet wel het maximale uit zo'n avond halen. Ik hoef je niet te vertellen dat er zondag weinig gebeurde. Altijd lekker, zo'n weekendje.</p>
<p>
<em><a href="http://www.advogato.org/person/adrighem/diary.html?start=5">April 11, 2005 11:47 AM</a></em>
</p>





<div class="blosxomDate>April 09, 2005</div>




<p class="blosxomTitle"><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></p>


<p>
didn't do that much this week. slowly getting more time to do some programming<br>again (finally). got to open my mac mini for the third time (ugh), now it will<br>remain opened until i finally have working memory.<br><br>today i installed daapd (and howl/mDNSResponder) on my fileserver (where all my music is on). it's very easy to setup and works great. now when i launch iTunes on any machine on my network, I get an item "Kris' music", which is a remote library with all of my music which is on the fileserver. very nifty. i hope this also works over VPNs, so the next step is to setup a VPN, and try if iTunes finds this daap server when i am at the university for example. if you are interested in setting this up, here's a link:<br><a href="http://the.taoofmac.com/space/HOWTO/Set%20Up%20daapd%20on%20Fedora%20Linux">http://the.taoofmac.com/space/HOWTO/Set%20Up%20daapd%20on%20Fedora%20Linux</a>. I wonder whether rhythmbox or some other linux app has support for this.<br><br><hr width="50%"><br><br>nu ik door het verliezen van m'n OV (gelukkig al een nieuwe aangevraagd) veel betaal voor het reizen per trein denk ik dat ik het recht heb om wat te zeggen over het materieel. zo zat (nou ja, stond) ik afgelopen week voor het eerst in een nieuwe sprinter. wat een klotedingen zijn dat geworden, veel luidruchtiger dan eerst. ze zien er wel mooi uit. het piepje van de deuren is wel grappig. het is wel jammer om te zien dat het erop lijkt dat het treinmaterieel achteruit gaat. zo vind ik de nieuwe dubbeldekkers (die intercity dingen) ook een stuk minder prettig dan de oude (die gele bakken). op de een of andere manier zit ik lekkerder in de oude en slapen ze vooral prettig (je kan tenminste met je benen bij de stoel tegenover je). ook zijn die nieuwe intercities erg koud in de winter! op het gebied van verwarming winnen die oude barrels uit 1960 of zo nog steeds. nog steeds vind ik de koplopers en die beneluxtrein de prettigste treinen om een tijd in te zitten, ook oude bakken dus. zal wel aan mij liggen.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/39880.html">April 09, 2005 07:13 PM</a></em>
</p>









<p class="blosxomTitle"><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a></p>


<h4><a href="http://www.klijmij.net/~michel/index.php/archief/2005/03/31/waarom/">Waarom&#8230;</a></h4>
<p>
<p>Waarom maakt heel de USA zich druk om de euthanasie van Terry Schiavo en roepen alle conservatieven en gelovigen om het hardst over het "right to live", maar hoor je niemand zo over de barbaarse doodstraf praten, of Guantanamo Bay?</p></p>
<p>
<em><a href="http://www.klijmij.net/~michel/index.php/archief/2005/03/31/waarom/">by Michel at April 09, 2005 10:00 AM</a></em>
</p>





<div class="blosxomDate>April 08, 2005</div>




<p class="blosxomTitle"><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a></p>


<h4><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/08/ubuntu-hoary-hedgehog/">Ubuntu &#8220;Hoary Hedgehog&#8221;</a></h4>
<p>
<p>De nieuwste versie van <a href="http://www.ubuntulinux.org/">Ubuntu Linux</a> is uit! Nadat de eerste versie, <em>Warty Warthog</em>, de nieuwe distributie omhoog deed schieten in de ranglijst van <a href="http://www.distrowatch.com/">Distrowatch</a> is dit de tweede release.</p>

<p>Ubuntu is gebaseerd op de nieuwste <a href="http://nl.gnome.org/">GNOME</a> werkomgeving en beschikbaar voor PC (zowel 32-bit als 64-bit) en Mac. Downloaden is niet nodig, want je kan <em>gratis</em> CD&#8217;s bestellen in net kartonnen hoesje via <a href="http://shipit.ubuntulinux.org/">http://shipit.ubuntulinux.org</a>. Met deze release zal de Ubuntu-hype nog wel even voortduren&#8230;</p></p>
<p>
<em><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/08/ubuntu-hoary-hedgehog/">by Michel at April 08, 2005 09:45 PM</a></em>
</p>











<h4><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/08/iets-vrolijkers/">Iets vrolijkers&#8230;</a></h4>
<p>
<p>De nieuwe paus is bekend:</p>

<p><img src="http://michel.klijmij.net/wp-content/nieuwepaus.jpg" alt="Paus Rohannus Bonus I"></p></p>
<p>
<em><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/08/iets-vrolijkers/">by Michel at April 08, 2005 02:45 PM</a></em>
</p>





<div class="blosxomDate>April 07, 2005</div>




<p class="blosxomTitle"><a href="http://vanschouwen.system-x.org" title="Reinouts' nerdy notes">Reinout van Schouwen</a></p>


<h4><a href="http://vanschouwen.system-x.org/?p=15">GNOME-nl PR</a></h4>
<p>
<p>Past Saturday I did a talk for the <a href="http://62.58.32.90/bijeenkomsten_2005">Dutch Linux User Group</a> about translating free software (slides <a href="http://www.cs.vu.nl/~reinout/presentatie%202%20april%2005.pdf">here</a>).<br>
In the process I did a little PR for the GNOME-nl initiative as well. As a proof of our existance we now even have our own <a href="http://nl.gnome.org/planet.php">Planet<br>
</a> - unfortunately it barfs on the nice unicode characters elsewhere in my blog, so I&#8217;m not listed yet&#8230; (arrrgh!)</p>
	<p><strong><a href="http://www.mandrivalinux.com/">Mandrakesoft</a> announces name change after the merger with Conectiva:</strong></p>
	<p><img src="http://images.mandrakesoft.com/img/mandrivalinux-logo-en-anim.gif" alt="Mandriva">
</p></p>
<p>
<em><a href="http://vanschouwen.system-x.org/?p=15">by Reinout at April 07, 2005 02:43 PM</a></em>
</p>





<div class="blosxomDate>April 06, 2005</div>




<p class="blosxomTitle"><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></p>


<h4><a href="http://www.advogato.org/person/adrighem/diary.html?start=4">6 Apr 2005</a></h4>
<p>
<p>Last saturday we thought about setting up some kind of planet GNOME-NL. And now, (drumroll please), here it is:<p>
<a href="http://nl.gnome.org/planet.php">http://nl.gnome.org/planet.php</a></p>
<p>
<em><a href="http://www.advogato.org/person/adrighem/diary.html?start=4">April 06, 2005 03:59 PM</a></em>
</p>









<p class="blosxomTitle"><a href="http://vanschouwen.system-x.org" title="Reinouts' nerdy notes">Reinout van Schouwen</a></p>


<h4><a href="http://vanschouwen.system-x.org/?p=14">Bloedbank</a></h4>
<p>
<p><a href="http://www.hannawillemijn.nl/archives/000076.html">Hanna</a>, het heeft even geduurd, maar vandaag ben ik dan ook een keer naar de bloedbank geweest. Nu weet ik eindelijk dat ik bloedgroep O positief heb. Grappig!
</p></p>
<p>
<em><a href="http://vanschouwen.system-x.org/?p=14">by Reinout at April 06, 2005 02:14 PM</a></em>
</p>









<p class="blosxomTitle"><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a></p>


<h4><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/06/oma/">Oma</a></h4>
<p>
<div>
<p align="right"><em>Sterven doe je niet ineens,<br>
maar af en toe &#8216;n beetje<br>
en alle beetjes die je stierf,<br>
&#8216;t is vreemd, maar die vergeet je,<br>
het is je dikwijls zelfs ontgaan,<br>
je zegt ik ben wat moe,<br>
maar op &#8216;n keer dan ben je aan<br>
je laatste beetje toe.</em></p>


<p>Na een moeilijke tijd van afnemende gezondheid is in alle rust van ons heengegaan onze moeder, schoonmoeder, oma, zuster en schoonzuster</p>


<p align="center"><strong>Johanna Maria de Winter<br>
Hanny</strong></p>


<p align="center">* 18 juni 1920                                 † 30 maart 2005</p>


<p align="right">Ingrid Klijmij - Woerlee<br>
John Klijmij<br>
Michel en Astrid<br>
Laurens<br>
<br>
Paul Woerlee<br>
Cor Witbraad<br>

Frans de Winter<br>
Lies de Winter - van der Steen</p>

<p>De crematieplechtigheid heeft op 6 april in familiekring plaatsgevonden.</p>
</div></p>
<p>
<em><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/06/oma/">by Michel at April 06, 2005 01:00 PM</a></em>
</p>





<div class="blosxomDate>April 05, 2005</div>




<p class="blosxomTitle"><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></p>


<h4><a href="http://geeklog.eyesopened.nl/archives/2005/04/05/desktop-places-nautilus/">Desktop Places, Nautilus</a></h4>
<p>
<p><p>Finally my exams week is over, good results so I look forward to the end of this college year.</p></p>

	<p><p><h4>Desktop Places</h4>I&#8217;ve created a <a href="http://live.gnome.org/DesktopPlaces">wiki page</a> on live.gnome.org after some discussion with seb128 on <span class="caps">IRC</span>. I think (and I hope more people) we need a central way of modifing, adding and fetching all the standard desktop places we have like Home, Trash etc. This is closely related to gtk-bookmarks, which also relates to <a href="http://live.gnome.org/RecentFilesAndBookmarks">this wiki page</a>.</p></p>

	<p><p><h4>File management stuff</h4>Now I&#8217;m at it, lets go on with some ideas. It would really rock if we could move some parts of libnautilus-private (like file management operations) to another public library. This way other apps can integrate with nautilus which will give a consistent feeling for the user. (Think about the same progress window in every app)</p></p>

	<p><p><h4>Halfish boredom</h4><a href="http://geeklog.eyesopened.nl/wp-content/images/gnome-mysql.png"><img src="http://geeklog.eyesopened.nl/wp-content/images/gnome-mysql_thumb.png" class="left"></a>So I was a little bored today and decided to get my mind of the usual stuff and relax a bit with some python hacking. Since I need to examine/edit and add a lot of data into a MySQL database in the near future for my job I&#8217;ve hacked together a little utility to do database management tasks.<br>
<br>
<br>
<br>
<br>
</p></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/04/05/desktop-places-nautilus/">by Michiel at April 05, 2005 08:02 PM</a></em>
</p>





<div class="blosxomDate>April 04, 2005</div>




<p class="blosxomTitle"><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></p>


<h4><a href="http://www.livejournal.com/users/inverted_tree/39587.html">gnome-nl gnome 2.10 release party photos</a></h4>
<p>
OOPS! Being the irregular blogger I am, I forgot to post some photos from the gnome-nl gnome 2.10 release party. I think most people there agreed that we should do something like that more often, and I got told that I promised to try to organise a "pub-evening" (I would use 'borrel' in Dutch, dunno what to use in English) each month. Since I am busy I am hopelessly failing, but it is going to happen someday!<br><br>Ok so here are some photos:<br><a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/tux-na-werk.jpg"><br><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_tux-na-werk.jpg" border="0"><br></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/gnome-nl-feescht.jpg"><br><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_gnome-nl-feescht.jpg" border="0"><br></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/gnome-nl-feescht-2.jpg"><br><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_gnome-nl-feescht-2.jpg" border="0"><br></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/gnome-nl-mensen-blij.jpg"><br><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_gnome-nl-mensen-blij.jpg" border="0"><br></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/nog-meer-gnome-nl-mensen-blij.jpg"><br><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_nog-meer-gnome-nl-mensen-blij.jpg" border="0"><br></a><br><br>Oh, another thing I forgot. It looks like I will be at GUADEC this year (finally); not 100% sure yet, but I am already looking forward to it.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/39587.html">April 04, 2005 09:42 PM</a></em>
</p>











<p>
So i finally got my mac mini about 2 weeks ago (after 6 weeks of waiting), but i haven't been able to use it since i:<br>a) refuse to use it with 256 mb RAM<br>b) i bought a 1 gb DIMM which was broken, got it changed for another one, which doesn't appear to be broken but my mac mini is not stable at all.<br>Which basically means i get to get some other brand (when i finally have time to do so); until then my mac will be sitting in a corner.<br><br>Besides that and paid work, I have been busy creating a yearbook. Creating a yearbook basically means kicking people until they manage to deliver their content and layouting about 205 pages. It sometimes is a fun task to do, though it is tedious when you have missed the deadline for the press and some people still need to send in their content. Anyway, it fortunately is almost over, so i can concentrate on other things.<br><br>I hate continuously reminding people to do something, etc, etc. I guess that means that i should not become a manager.<br><br><center><hr width="50"></center><br><br>En als er ook maar iets irritant is, dan is het wel je OV kaart kwijt zijn. Bleh.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/39182.html">April 04, 2005 09:07 PM</a></em>
</p>









<p class="blosxomTitle"><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></p>


<h4><a href="http://uwog.net/blog/?p=41">*Ahum*</a></h4>
<p>
<p><b>AbiWord</b><br>
Something about 2.2.7 and brown paper bags [ <a href="http://www.abisource.com/release-notes/2.2.7.phtml">Release Notes</a> ], [ <a href="http://www.abisource.com/changelogs/2.2.7.phtml">Changelog</a> ], [ <a href="http://www.abisource.com/download/">Download</a> ]</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=41">by uwog at April 04, 2005 08:59 PM</a></em>
</p>











<h4><a href="http://uwog.net/blog/?p=40">AbiWord 2.2.6 and Finland Fun!</a></h4>
<p>
<p><b>AbiWord</b><br>
Just released v2.2.6, go get it! [ <a href="http://www.abisource.com/release-notes/2.2.6.phtml">Release Notes</a> ], [ <a href="http://www.abisource.com/changelogs/2.2.6.phtml">Changelog</a> ], [ <a href="http://www.abisource.com/download/">Download</a> ]</p>
	<p><b>Finland Trip</b><br>
Went to Finland last week (near Kuusamo), as a <a href="http://www.betterbe.com">company</a> I very occasionally work for invited me out of the blue to join them; all paid for! Naturally, I couldn&#8217;t resist, and we had a fun 4 days racing over the snow using scooters and dog sledges, and drinking expensive beverages.</p>
	<p><center></p>
	<table border="0">
<tr>
<td>
<a href="http://www.moerman.org/~uwog/blog/gfx/finland-2005.jpg"><img src="http://www.moerman.org/~uwog/blog/gfx/finland-2005.thumb.jpg" alt="Finland Fun"></a></td>
	<td>
<a href="http://www.moerman.org/~uwog/blog/gfx/finland-dogs-2005.jpg"><img src="http://www.moerman.org/~uwog/blog/gfx/finland-dogs-2005.thumb.jpg" alt="Finland Fun with Dogs Sledges"></a></td>
</tr>
</table>
	<p>Proof of my Finland Trip</center>
</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=40">by uwog at April 04, 2005 01:24 AM</a></em>
</p>





<div class="blosxomDate>April 03, 2005</div>




<p class="blosxomTitle"><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a></p>


<h4><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/03/lenteehmzomer/">Lente&#8230;ehm&#8230;zomer?</a></h4>
<p>
<p>Heerlijk weer dit weekend. Dus de fiets op! Gelukkig ben je zo de bruisende metropool Gouda uit en het Groene Hart in. We hebben de Veenweideroute gereden, pas geleden uitgezet door <a href="http://www.reeuwijkseland.nl/">Het Reeuwijkse Land</a>. De kaart en beschrijving zijn van die site te downloaden, maar je kan natuurlijk ook gewoon even langs de <a href="http://www.vvvgroenehart.nl/">VVV</a>.</p>

<p><img src="http://www.klijmij.net/~michel/wp-content/veenweide_klein.jpg" alt="Veenweideroute" border="0" align="right"></p>

<p>De route ging via Reeuwijk-Brug naar Reeuwijk-Dorp, Boskoop, Tempel (een echt gehucht!), de Reeuwijkse Hout en langs de Reeuwijkse Plassen. Zeker met dit weer een prima tocht, niet te lang ook. Bij de Plassen stonden 2 ijsverkopers (druk!), dus ook dat is goed geregeld.</p></p>
<p>
<em><a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/03/lenteehmzomer/">by Michel at April 03, 2005 05:30 PM</a></em>
</p>





<div class="blosxomDate>March 26, 2005</div>




<p class="blosxomTitle"><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></p>


<h4><a href="http://geeklog.eyesopened.nl/archives/2005/03/26/progress-dialogs-screenshot-desktop-improvements/">Progress dialogs</a></h4>
<p>
<p><p>It&#8217;s been a while, so here are some updates. I&#8217;m sorry that I broke p.g.o (again). I changed some user permissions of one article in WordPress so it decided to change all articles or something.</p></p>

	<p><p>When I was fixing bug <a href="http://bugzilla.gnome.org/show_bug.cgi?id=159057">#159057</a> I needed to build a progress dialog for emptying the trash. I compared the Nautilus&#8217; ones with the guidelines in the <span class="caps">HIG</span> and came to the conclusion that they aren&#8217;t really following <span class="caps">HIG</span> guidelines so I created my own:</p></p>

	<p><p><img src="http://geeklog.eyesopened.nl/wp-content/images/emptying_trash.png" class="nothing"></p></p>

	<p><p>It would be a good idea to create a consistent dialog for trashapplet and nautilus, I&#8217;ve opened a bugreport for it <a href="http://bugzilla.gnome.org/show_bug.cgi?id=171694">here</a>.<br>
The current nautilus progress dialog looks like:</p></p>

	<p><p><img src="http://geeklog.eyesopened.nl/wp-content/images/naut_trash.png" class="nothing"></p></p>

	<p><p>Been thinking about and discussing some desktop improvements a bit in #gnome-nl. I&#8217;ve come up with <a href="http://geeklog.eyesopened.nl/index.php/ramblings/">a list</a> which I think are nice improvements for the desktop. Most items aren&#8217;t new. I&#8217;ll also try and work on some of them in the neir future.</p></p>

	<p><p>Oh, and to go with the flow:</p></p>

	<p><p><a href="http://geeklog.eyesopened.nl/wp-content/images/screenshot"><img src="http://geeklog.eyesopened.nl/wp-content/images/screenshot_thumb.png" class="center"></a></p></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/03/26/progress-dialogs-screenshot-desktop-improvements/">by Michiel at March 26, 2005 03:10 AM</a></em>
</p>





<div class="blosxomDate>March 23, 2005</div>




<p class="blosxomTitle"><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></p>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=97">23 Mar 2005</a></h4>
<p>
<b>Totem Mozilla Embedding</b><br>
Bastien already said that we had basic javascript functionality people working in the Totem-Mozilla plugin. Today, I finished the second part of the job: a two-way communication protocol. Should ideally use DBUS for that, but we're using something of ourselves right now.
<p>
The result of that is that the plugin and the child application (which is spawned on its own for security reasons) interact using some basic IPC command-set. This, in combination with the javascript-functionality, means that I can click images on a <a href="http://ronald.bitfreak.net/priv/pimp.png">HTML page</a> and the player will react as it should. The javascript-calls are compatible with Quicktime Player. Woohoo, finally a kick-ass mozilla media player. :-). Get your daily dose of Totem-CVS today!</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=97">March 23, 2005 06:22 PM</a></em>
</p>





<div class="blosxomDate>March 14, 2005</div>




<p class="blosxomTitle"><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></p>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=96">14 Mar 2005</a></h4>
<p>
<b>MP4 authoring</b><br>
After Jon's release of pyMusique, I felt the strong need to get my hands on a working MPEG-4 ISO muxer (for GStreamer). Reasons are simple:
<ul>
<li>Writing tags to music downloaded from iTMS (yes, they're untagged!).
<li>Capture MPEG-4 video/audio in a MPEG-4 container using Cupid.
<li>Transcoding my Ogg/Vorbis files to MPEG-4 so I can transfer them to my iPod.
</ul>
And so I did. GStreamer can now write full-spec MPEG-4 files. <a href="http://ronald.bitfreak.net/priv/mp4.jpg">Screenshot</a>. Tested with all of the above use cases. (3) can be done using gst-launch, tags are conserved. (2) just works. Jon is adding support for (1) right now. Get your daily dose of GStreamer CVS right now!</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=96">March 14, 2005 04:49 PM</a></em>
</p>





<div class="blosxomDate>March 13, 2005</div>




<p class="blosxomTitle"><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></p>


<h4><a href="http://uwog.net/blog/?p=39">Moderation and Helpfulness</a></h4>
<p>
<p><b>Blog Comments</b><br>
For some reason, all comments to my blog needed moderation (didn&#8217;t I turn that off? no I did not, but there is another slightly obscure &#8220;Comment author must have a previously approved comment&#8221; option); I just figured people had dumped me en masse on their /ignore list, until I looked a bit better in the WordPress Admin. Now people can happily chat with me again, sorry! I&#8217;ll approve all non-spam comments immediately.</p>
	<p><b>Fedora Extras</b><br>
In other news, I did my first steps in the World of Fedora Extras Package Building. Immediately ran into some problems due to me being new to the process and the big flux RawHide is in now. Fortunately, the guys from Red Hat and #fedora-devel were really helpful in getting me going. Quite unexpected, as I figured most people would just be too busy with their normal Red Hat job to help some n00b volunteer. Thanks!</p>
	<p><b>MS Word</b><br>
Apparently, some Word developers <a href="http://blogs.msdn.com/rick_schaut/archive/2005/03/13/394808.aspx">read planet.gnome.org as well</a>. Welcome, I&#8217;d say! Now while I&#8217;m at it: I use MS Word occasionally myself (for some customers that expect Word documents, and to look for features that AbiWord could nicely adopt as well) and I would really love to have LaTeX math support in Word. If you have some spare time the comming weeks Rick, could you add that? Of course, I&#8217;d be willing to add it myself, if Word was GPLed :-)</p>
	<p><b>Dutch GNOME 2.10 release notes</b><br>
Since we&#8217;re in a cheery mood, and someone on my blog told me to <a href="http://uwog.net/blog/?p=34#comment-2125">quit whining</a> a few days back (yay for blog comments :), we&#8217;re now working on fixing the Dutch GNOME 2.10 release notes translations. Thanks for the help Danilo and Murray!</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=39">by uwog at March 13, 2005 04:05 PM</a></em>
</p>
</div>

<? gnome_foot() ?>
</body>
</html>
