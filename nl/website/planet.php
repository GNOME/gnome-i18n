<?php
include "functions.php";
is_logged_in();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>




 
<head>
<title>Planet GNOME-NL</title>
<? html_head() ?>
<meta name="generator" content="Planet/1.0~pre1 +http://www.planetplanet.org">
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
<li><a href="http://www.stratos-online.nl" title="StratoS Online">Erik Snoeijs</a> <a href="">(rss)</a></li>
<li><a href="http://www.jabber.ee/~jorn/blog" title="Jorn's blog">Jorn Baayen</a> <a href="">(rss)</a></li>
<li><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a> <a href="">(rss)</a></li>
<li><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a> <a href="">(rss)</a></li>
<li><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a> <a href="">(rss)</a></li>
<li><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a> <a href="">(rss)</a></li>
<li><a href="http://pvanhoof.be/blog/index.php/" title="Diary of Philip Van Hoof">Philip van Hoof</a> <a href="">(rss)</a></li>
<li><a href="http://vanschouwen.system-x.org" title="Reinouts' nerdy notes">Reinout van Schouwen</a> <a href="">(rss)</a></li>
<li><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a> <a href="">(rss)</a></li>
<li><a href="http://weblog.lambda1.be" title="Ruben's Weblog">Ruben Vermeersch</a> <a href="">(rss)</a></li>
<li><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a> <a href="">(rss)</a></li>
</ul>
</div>
<h1>Planet GNOME-NL</h1>








<p>GNOME-NL is niet puur een organisatie, met regels en structuur. Het is
een project, opgezet en onderhouden door mensen. Het zijn de mensen achter de
naam, die het project tot een succes maken.</p>
<p>Zoals bij meeste OSS-projecten, is ook GNOME-NL zeer open. Deze mensen
delen via dit venster hun dagenlijkse beslommeringen, zij het GNOME-gerelateerd,
of niet. U kunt hier bijblijven met de laatste nieuwtjes.</p>









<div class="blogdate">April 17, 2005</div>




<p class="blogname"><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></p>


<div class="blogtitle">
<img class="floatright" src="images/ronald.png" alt="Ronald S. Bultje face">
<a href="http://www.advogato.org/person/rbultje/diary.html?start=99">17 Apr 2005</a></div>
<div class="blogstory">
<b>Totem DVD &amp; the like</b><br />
Added some missing features (w.r.t. the Xine backend) in Totem/GStreamer today. One being manual aspect-ratio selection, partly with thanks to Laurens Buhler. The other being language codes, which I implemented in DVD playback. The result is that a media source with multiple audio tracks and language code support will now <a href="http://ronald.bitfreak.net/priv/dvd-lang.png">display proper language names</a> in the audio/language menu. Same code is in place for subtitles yet, but then again, DVD subtitles don't work yet, so it's kinda pointless in a way. Jan (he's working on that) promised me he'd make it working within a few days now. Then menus, and we're done, GStreamer will finally have good DVD support.
<p>
As for the language code detection, I should implement it for mkv/ogm too, I guess.</div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.advogato.org/person/rbultje/diary.html?start=99">April 17, 2005 10:26 PM</a></em>





<div class="blogdate">April 16, 2005</div>




<p class="blogname"><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></p>


<div class="blogtitle">
<a href="http://www.advogato.org/person/adrighem/diary.html?start=7">16 Apr 2005</a></div>
<div class="blogstory">
<p>Soms bijt traffic-shaping terug. Vandaag was het dus zover. Ik heb reeds enige maanden een downstream van 400K en een upstream van 100K (Chello Classic).<p>
Helaas stond mijn traffic shaper nog steeds op 300K down en 35K up. Dit had ik ooit geÃ¯nstalleerd om interactief verkeer en ACKs voorrang te geven zodat ik geen last heb van aso-uploads van anderen. Het werkte ideaal, maar zorgde dus de afgelopen maanden netjes voor een up- en downloadbeperking.<p>
Zojuist dus het script aangepast (was een kwestie van wijzigen van 2 getallen) et voila. Ik ben ook trots lid van het high-upstream team. WHOOHOO! O ja, en nog steeds zit de traffic-shaper erin. Hij werkt nu weer zoals ik hem bedoeld had.</div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.advogato.org/person/adrighem/diary.html?start=7">April 16, 2005 11:11 PM</a></em>











<div class="blogtitle">
<a href="http://www.advogato.org/person/adrighem/diary.html?start=6">16 Apr 2005</a></div>
<div class="blogstory">
Your Score (long test)
<p>Your scored -0.5 on the Moral Order axis and 4 on the Moral Rules axis.
The following items best match your score:
<ol>
<li>System: Socialism
<li>Variation: Moderate Socialism, Economic Socialism
<li>Ideologies: Social Democratism
<li>US Parties: No match.
<li>Presidents: Jimmy Carter (76.51%)
<li>2004 Election Candidates: Ralph Nader (72.31%), John Kerry (71.27%), George W. Bush (53.39%)
</ol>
Of the 70908 people who took the test:
<ol>
<li>0.1% had the same score as you.
<li>5.5% were above you on the chart.
<li>91.9% were below you on the chart.
<li>27.5% were to your right on the chart.
<li>60.6% were to your left on the chart.
</ol>
<p>On another note: Ubuntu Breezy seems to have started..so all you people who want an adventure. Start using breezy and help the developers find bugs!</div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.advogato.org/person/adrighem/diary.html?start=6">April 16, 2005 09:23 PM</a></em>









<p class="blogname"><a href="http://www.jabber.ee/~jorn/blog" title="Jorn's blog">Jorn Baayen</a></p>


<div class="blogstory">
<p><img src="http://www.jabber.ee/~jorn/blog/wp-content/luise.png" class="alignright" /><br />
Vrijdag kreeg ik bezoek. Tegen de middag kwamen mijn twee voormalige huisgenoten uit Tartu op bezoek. Op dat moment belde ook Thomas, een mede-Nederlander hier in Estland, dat hij in Tallinn was, en dat we iets zouden kunnen gaan drinken. Zoals de gemiddelde zuigeling al had kunnen zien aankomen, had ik uiteindelijk drie gasten. We dronken wat, en praatten wat bij. Helaas was er voor mij nog werk aan de winkel, en na een uur verwijderde het bezoek zich weer en verspreidde zich over de stad. Rond etenstijd kwam iedereen weer terug, om zich te goed te doen aan de taco&#8217;s die ik net tevoren tevoren bereid had. Na dit festijn zwalkten wij dieper het centrum in, om in <a href="http://www.kloostriait.ee/"><em>Kloostri Ait</em></a> nog een dessert te nuttigen. Thomas&#8217; vriendin, Liina, had zich inmiddels ook bij ons gevoegd. Thomas vertelde een aardig verhaal over twee Finnen waarmij hij ooit een kamer had gedeeld. Deze kwamen een keer per maand met al hun vuile vas en kapotte schoenen naar Estland, om die hier goedkoop de laten wassen en te laten repareren. En om, niet te vergeten, een buitensporige hoeveelheid goedkope alkohol aan te schaffen. We vroegen ons af, of er ook Finnen zouden zijn die in de winter naar Estland zouden gaan om hier goedkoop tientallen diepvriespizza&#8217;s in te slaan (en die vervolgens gewoon in de tuin zouden opstapelen, omdat het toch altijd vriest). Wie weet?</p>
	<p>Zaterdag is nog niet afgelopen.
</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.jabber.ee/~jorn/blog/?p=6">April 16, 2005 06:10 PM</a></em>









<p class="blogname"><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a></p>


<div class="blogtitle">
<a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/16/waar-sta-jij/">Waar sta jij?</a></div>
<div class="blogstory">
<p>Wel een aardige test: http://www.moral-politics.com/xpolitics.aspx?menu=Home</p>

<blockquote>Your Score<br />
<br />
Your scored -2 on the Moral Order axis and 6 on the Moral Rules axis.<br />
<br />
Matches<br />
<br />
The following items best match your score:<br />
<br />
   1. System: Socialism<br />
   2. Variation: Economic Socialism<br />
   3. Ideologies: International Socialism<br />
   4. US Parties: No match.<br />
   5. Presidents: Jimmy Carter (72.05%)<br />
   6. 2004 Election Candidates: Ralph Nader (71.70%), John Kerry (64.37%), George W. Bush (42.38%) <br />

Statistics<br />
<br />
Of the 70808 people who took the test:<br />
<br />
   1. 1.9% had the same score as you.<br />
   2. 0.2% were above you on the chart.<br />
   3. 95.4% were below you on the chart.<br />
   4. 47% were to your right on the chart.<br />
   5. 29.6% were to your left on the chart.
</blockquote>

<p>Dat was na de 2-vragentest, binnenkort ga ik eens zitten voor de uitgebreide test.</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.klijmij.net/~michel/index.php/archief/2005/04/16/waar-sta-jij/">April 16, 2005 01:00 PM</a></em>





<div class="blogdate">April 15, 2005</div>




<p class="blogname"><a href="http://vanschouwen.system-x.org" title="Reinouts' nerdy notes">Reinout van Schouwen</a></p>


<div class="blogtitle">
<img class="floatright" src="images/reinout.png" alt="Reinout van Schouwen face">
<a href="http://vanschouwen.system-x.org/?p=18">Goede daad voor vandaag</a></div>
<div class="blogstory">
<p>Onderteken ook de petitie voor eerlijke handel:<br />
<a href="http://www.maketradefair.com/en/index.php?file=dumped.htm&#038;cat=1&#038;subcat=7&#038;select=2"><img src="http://www.novib.nl/media/maketradefair/downloads/fullban_468x60_3A_lips.gif" alt="Join the big noise" /></a></p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://vanschouwen.system-x.org/?p=18">April 15, 2005 11:31 PM</a></em>











<div class="blogtitle">
<img class="floatright" src="images/reinout.png" alt="Reinout van Schouwen face">
<a href="http://vanschouwen.system-x.org/?p=17">Packaging foo</a></div>
<div class="blogstory">
<p>Now listed on <a href="http://nl.gnome.org/planet.php">Planet GNOME-nl</a>, now with extra cool layout, woot! No hackergotchi&#8217;s yet, though, but I think <a href="http://www.stratos-online.nl/">Erik</a>  deserves the credits for creating one for me:<br />
<img src="http://vanschouwen.system-x.org/pictures/hackergotchi-reinout.png" alt="Reinouts' head" /></p>
	<p>Furthermore I&#8217;m proud to announce the first in the line of &#8216;rvs&#8217; RPMs for <a href="http://www.mandriva.com/products/limited-edition">Mandriva Linux 2005</a>: I&#8217;ve packaged <a href="http://www.dsl.uow.edu.au/~harvey/code_epiphany.shtml">Epiphany 1.6.2 with dynamic hierarchical bookmarks menu</a>. If you want to try it, you&#8217;ll need to set up the <a href="http://gpwgnome.osknowledge.org/">gpwgnome</a> repository first. Find the RPM <a href="http://www.cs.vu.nl/~reinout/RPMS/">here</a>, SRPM <a href="http://www.cs.vu.nl/~reinout/SRPMS/">here</a>.
</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://vanschouwen.system-x.org/?p=17">April 15, 2005 11:31 PM</a></em>





<div class="blogdate">April 14, 2005</div>




<p class="blogname"><a href="http://pvanhoof.be/blog/index.php/" title="Diary of Philip Van Hoof">Philip van Hoof</a></p>


<div class="blogtitle">
<img class="floatright" src="images/pvanhoof.png" alt="Philip van Hoof face">
<a href="http://pvanhoof.be/blog/index.php/2005/04/14/20-a-generic-desktop-application-configuration-management-system">A generic desktop application configuration management system</a></div>
<div class="blogstory">
<p>
Since people started blogging about this[1], I feel I have to respond in blog-fashion. I'm probably wrong about that, but oh well. It won't hurt much. So far I never used my personal blog-space for this type of discussions so bear with me.
</p><p>
I'll introduce myself first: Yes, I'm one of the people who are actively discussing the requirements and design of a possible generic desktop application configuration management system dubbed D-Conf. In fact, I'm the person who started collecting some of the requirements using <a href="http://freax.be/wiki/index.php/Temporary_location_for_D-Conf_specs">this wiki</a>. This doesn't mean I'm the only person. I'm not.
</p><p>
So whats the status? So far have a few people added and/or corrected both views and real requirements to this wiki-page. Some <a href="http://lists.freedesktop.org/archives/xdg/">xdg-list</a> quotes from developers of important applications have been used to form some requirements. Other requirements listed on the wiki-page originate from the many many discussions and threads that popped up on the <a href="http://lists.freedesktop.org/archives/xdg/">xdg-list</a> about this subject. So you'd say the project has most of the requirements, why isn't development starting? It's a good question.
</p><p>
And it has a simple answer:
</p><p>
First does the wiki-page need some Guassian filtering on the collected requirements. And a project leader would need to select which requirements will become the features of the first releases.
</p><p>And second so far nobody in the group of interested people is qualified to actually lead this project. Such a qualified person would have to carry a very heavy burden with this project and he (or she) would need the trust from most important desktop environment communities and desktop application developers. A bad project leader would condemn the project. Thats why I, personally, decided not to lead this project. I <i>just</i> started the wiki-page with requirements because <a href="http://lists.freedesktop.org/archives/xdg/2005-March/006145.html">Alexander Larsson</a> suggested me to start collecting them. That doesn't mean I want to lead the project. I bet thats a relief to a lot people. As a project leader I would probably get the implementation done. However, I wouldn't manage to get acceptance from all desktop environment communities. I know that. Therefor I understand that I, as a project leader, would condemn the project long before it's started.
</p><p>
I'm also confident that without a project leader, nor the implementation nor acceptance would happen successfully. I am, however, highly interested in helping with the implementation of a new such system. So my role would (or will) be a developer (a coder). Not really a decision-making or leading role.
</p><p>
I understand many people are thrilled about this and want to see it happen. I even got a few personal mails from people who've been following the discussions and who have read that I wasn't interested in leading this project. They tried encouraging me to continue leading it (while I never played a leading-role, but .. okay). I guess these people will have to be patient. There's still many things to discuss first. It's not easy to convince certain developers and people. And its not easy to agree on which technologies to use and depend on. And IMHO does the project still need a project leader. We can't start without. Or IMHO it would be a foolish attempt.
</p><hr /><hr />
<p>
[1] I'll list the blogs in chronological sequence</p><p>
<a href="http://aseigo.blogspot.com/2005/04/stupidity-of-dconf.html">Stupidity of dconf</a> by Aaron J. Seigo, 
<a href="http://www.kdedevelopers.org/node/view/961">On the virtues of a common configuration system</a> by Waldo Bastian, 
<a href="http://aseigo.blogspot.com/2005/04/its-not-virtues-stupid-or-more-d-conf.html">It's not the virtues, stupid ; or, more D-Conf fun</a> by Aaron J. Seigo,
<a href="http://log.ometer.com/2005-04.html#11">freedesktop.org</a> by Havoc Pennington.
</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://pvanhoof.be/blog/index.php/2005/04/14/20-a-generic-desktop-application-configuration-management-system">April 14, 2005 08:45 PM</a></em>





<div class="blogdate">April 13, 2005</div>




<p class="blogname"><a href="http://www.jabber.ee/~jorn/blog" title="Jorn's blog">Jorn Baayen</a></p>


<div class="blogstory">
<p><img src="http://www.jabber.ee/~jorn/blog/wp-content/tehnika.png" class="alignright" /><br />
<em>Oef</em>. <em>Tien-en-een-half-uur</em> achter elkaar bugfixen. Na acht uur vond ik wel mooi geweest, maar toen werd ik opeens nog met een bug opgezadeld die vandaag nog gefixed <em>moest</em> worden. Tsja. </p>
	<p>Heel veel tijd voor wat andere dingen was er vandaag dus niet. Wel heb ik wat foto&#8217;s genomen van de directe omgeving van dit huis. Hierboven zo eentje. In dat gebouw daar rechts woon ik.</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.jabber.ee/~jorn/blog/?p=5">April 13, 2005 10:44 PM</a></em>









<p class="blogname"><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a></p>


<div class="blogtitle">
<a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/13/crashen-uithuilen-of-uithalen/">Crashen? Uithuilen of uithalen…</a></div>
<div class="blogstory">
<blockquote>De meest agressieve reacties op dataverlies werden gevonden in Engeland, waar 33 procent van de geïnterviewde werknemers tegen hun computer schreeuwt wanneer de computer crasht. In Nederland is dat 31 procent, terwijl 23 procent in paniek raakt of in huilen uitbarst. Amerikanen accepteren dataverlies wat gemakkelijker - 38 procent van de Amerikaanse computergebruikers zucht slechts diep wanneer hun computer storingen begint te vertonen.</blockquote>

<p><a href="http://www.webwereld.nl/nav/trillian?21253">Aldus WebWereld</a>, dat citeert uit recent wereldwijd onderzoek van Ontrack Data Recovery. Verder bleek dat men liever zelf probeert een crash op te lossen dan de IT-ers erbij te halen.</p>

<p>Toevallig komt dat net na een leuke middag waarbij in de lerarenkamer algemene verwarring heerste over het programma waarmee de cijfers worden bijgehouden. De meesten toonden een combinatie van lichte paniek tot berusting en verontwaardiging&#8230;</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.klijmij.net/~michel/index.php/archief/2005/04/13/crashen-uithuilen-of-uithalen/">April 13, 2005 05:17 PM</a></em>









<p class="blogname"><a href="http://www.jabber.ee/~jorn/blog" title="Jorn's blog">Jorn Baayen</a></p>


<div class="blogstory">
<p>Vandaag was productief. Direct uit bed schoof ik met een bak <a href="http://www.meieri.ee/?structure=001&#038;content=11&#038;pstructure=001008&#038;prod_id=18&#038;rnd=29554">yoghurt</a> achter de pcs, en begon met het bouwen van een reeks <a href="http://www.oreillynet.com/pub/a/wireless/2001/02/23/wep.html"><em>SSH-tunnels</em></a> richting Ruoholahti. Enkele uren later kwamen de eerste paketten aan. Ongeveer op dat moment kwamen Piret en haar vriendin Kadri op bezoek, die terstond begonnen met het bakken van pannenkoeken. Samen met wat echte Gilze suikerstroop propten we deze in een rap tempo naar binnen. Terwijl de dames onderwerpen als <em>spikerdamine</em> (spieken) voor hun aankomende examens besproken, hervatte ik met een volle buik de voorbereidingen voor mijn aandeel in de <a href="http://www.djcbsoftware.nl/ChangeLog/2005_04_01_changelog.html#111333959610432232">bug fixing jihad</a>. Rond een uur of 9 &#8217;s avonds was het doel bereikt, en dientengevolge zal ik morgenochtend in staat zijn met een scherp zwaard ten strijde te strekken.</p>
	<p>Voordat mijn vermoeide lichaam werd toegestaan het bed op te zoeken, eiste mijn inmiddels wederom lege maag dat er een bezoek gebracht zou worden aan de supermarkt. Gehoorzamend ging ik naar buiten, en zag een <a href="http://www.hot.ee/railfan/emu/er.htm#electric">trein</a>. Verwarring maakte zich enige seconden van mij meester. In de nevelige staat die optreed na het lang verblijven in de <a href="http://catb.org/~esr/jargon/html/H/hack-mode.html"><em>hack mode</em></a> waar <a href="http://www.djcbsoftware.nl/ChangeLog/">Dirk-Jan</a> altijd over schrijft, was ik glad vergeten dat ik niet meer in Tartu woon. De wandeling naar de supermarkt verliep verder zonder enige noemenswaardige gebeurtenissen. Weer thuis wokte ik snelletjes een restje courgette op samen met wat andere dingetjes die ik in de keuken vond. Het resultaat at ik op, vergezeld met een kaasschnitzel en een (overigens belachelijk slechte) aardappelsalade. Ik heb wel eens lekkerder gegeten.
</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.jabber.ee/~jorn/blog/?p=4">April 13, 2005 01:32 AM</a></em>









<p class="blogname"><a href="http://pvanhoof.be/blog/index.php/" title="Diary of Philip Van Hoof">Philip van Hoof</a></p>


<div class="blogtitle">
<img class="floatright" src="images/pvanhoof.png" alt="Philip van Hoof face">
<a href="http://pvanhoof.be/blog/index.php/2005/04/13/19-matthew-thomas-first-48-hours-enduring-ubuntu-hoary">Matthew Thomas first 48 hours enduring Ubuntu Hoary</a></div>
<div class="blogstory">
<p>Make sure you read <a href="http://mpt.net.nz/archive/2005/04/11/ubuntu" hreflang="en">this</a>.</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://pvanhoof.be/blog/index.php/2005/04/13/19-matthew-thomas-first-48-hours-enduring-ubuntu-hoary">April 13, 2005 01:04 AM</a></em>





<div class="blogdate">April 12, 2005</div>




<p class="blogname"><a href="http://www.klijmij.net/~michel" title="Publiekelijk tijdverdrijf">Michel Klijmij</a></p>


<div class="blogtitle">
<a href="http://www.klijmij.net/~michel/index.php/archief/2005/04/12/ubuntu-in-69-verbeterpunten/">Ubuntu in 69 verbeterpunten</a></div>
<div class="blogstory">
<p>Aangezien ik tegenwoordig ook op <a href="http://nl.gnome.org/planet.php">Planet GNOME-NL</a> sta voel ik me gelijk verplicht wat meer over computers te schrijven (ook al komen de meesten hier door te zoeken op "<a href="http://www.klijmij.net/~michel/stats/#searchq">naakte vrouwen</a>"&#8230;hallo viezeriken!). Dus bij deze <img src="http://www.klijmij.net/~michel/wp-images/smilies/icon_smile.gif" alt=":)" class="wp-smiley" /> </p>

<p>Zoals geschreven is <a href="http://www.ubuntulinux.org/">Ubuntu</a> pas geleden met een nieuwe versie gekomen. Vanuit het perspectief van interface designer <a href="http://mpt.net.nz/archive/2005/04/11/ubuntu">valt er nogal wat op te merken</a>. De meeste punten slaan meer op <a href="http://www.gnome.org/">GNOME</a> in het algemeen dan Ubuntu, en een aantal fouten zitten ook in MS Windows en MaxOS X. De schrijver vindt maar liefst 69 punten om wat op aan te merken, waarvan de laatste is "it&#8217;s <em>brown</em>". Dezelfde auteur (interface designer voor <a href="http://www.canonical.com/">Canonical</a>, makers van Ubuntu!) vond trouwens ook 48 designfouten na 48 uur <a href="http://www.apple.nl/macosx/">MacOS X</a>.</p>

<p>Ondanks dat GNOME een hele fijne werkomgeving is valt er nog genoeg aan te verbeteren, lijkt het.</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.klijmij.net/~michel/index.php/archief/2005/04/12/ubuntu-in-69-verbeterpunten/">April 12, 2005 10:56 PM</a></em>









<p class="blogname"><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></p>


<div class="blogtitle">
<img class="floatright" src="images/ronald.png" alt="Ronald S. Bultje face">
<a href="http://www.advogato.org/person/rbultje/diary.html?start=98">12 Apr 2005</a></div>
<div class="blogstory">
<b>Dutch Gnomeys</b><br />
The people ate GNOME-NL have set up a private, unique and special <a href="http://nl.gnome.org/planet.php">Planet Gnome-NL</a> which features your all-beloved GNOME hackers, but then only the special dutch flavour. Also, we're currently working on an article in a national news magazine, which looks promising. The best of all this is that we're doing cool stuff and are having fun at that. Yay!
<p>
<b>Totem</b>
So lately, I've been doing stability and finetuning work for Totem. It's nice to see that it "just works" most of the time, I've wanted this kind of a media player for years. Looks good, works well and hey, it's GStreamer-based. Other people are also helping, such as Christoph Burghardt, who's working on a zoom feature using the GStreamer backend. I'm trying to get a friend into fixing the unimplemented aspect-ratio menu item, it's his first C code, so it'll require some guidance, but that's good. In other parts, the nautilus properties page, thumbnailer and mozilla plugin all also received a lot of finetuning love. All in all, I think I can be a bit proud of the whole thing - even though it is still mostly Bastien's work. ;-).</div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.advogato.org/person/rbultje/diary.html?start=98">April 12, 2005 10:02 PM</a></em>





<div class="blogdate">April 11, 2005</div>




<p class="blogname"><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></p>


<div class="blogtitle">
<a href="http://www.advogato.org/person/adrighem/diary.html?start=5">11 Apr 2005</a></div>
<div class="blogstory">
<p><b>GNOME-NL</b><p>
We gaan de goede kant op. Er zijn enkele goede ontwepen voor naamkaartjes. Gisteren hebben Reinout, Ronald en Michiel gewerkt aan een stuk voor in Linux Magazine. Er is een rekening besteld om zo de financiÃ«le kant wat beter te organiseren. De toekomst ziet er zonnig uit.<p>
<b>Andere activiteiten</b><p>
Afgelopen zaterdag een feestje gehad. Veel bier (Grolsch en Palm) en een gezellige sfeer. Met de nachttrein naar huis, maaaaaaar voordta het zo ver was, eerst nog even een tussenstop in een lokale kroeg. Je moet wel het maximale uit zo'n avond halen. Ik hoef je niet te vertellen dat er zondag weinig gebeurde. Altijd lekker, zo'n weekendje.</div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.advogato.org/person/adrighem/diary.html?start=5">April 11, 2005 11:47 AM</a></em>





<div class="blogdate">April 09, 2005</div>




<p class="blogname"><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></p>


<div class="blogstory">
didn't do that much this week. slowly getting more time to do some programming<br />again (finally). got to open my mac mini for the third time (ugh), now it will<br />remain opened until i finally have working memory.<br /><br />today i installed daapd (and howl/mDNSResponder) on my fileserver (where all my music is on). it's very easy to setup and works great. now when i launch iTunes on any machine on my network, I get an item "Kris' music", which is a remote library with all of my music which is on the fileserver. very nifty. i hope this also works over VPNs, so the next step is to setup a VPN, and try if iTunes finds this daap server when i am at the university for example. if you are interested in setting this up, here's a link:<br /><a href="http://the.taoofmac.com/space/HOWTO/Set%20Up%20daapd%20on%20Fedora%20Linux">http://the.taoofmac.com/space/HOWTO/Set%20Up%20daapd%20on%20Fedora%20Linux</a>. I wonder whether rhythmbox or some other linux app has support for this.<br /><br /><hr width="50%" /><br /><br />nu ik door het verliezen van m'n OV (gelukkig al een nieuwe aangevraagd) veel betaal voor het reizen per trein denk ik dat ik het recht heb om wat te zeggen over het materieel. zo zat (nou ja, stond) ik afgelopen week voor het eerst in een nieuwe sprinter. wat een klotedingen zijn dat geworden, veel luidruchtiger dan eerst. ze zien er wel mooi uit. het piepje van de deuren is wel grappig. het is wel jammer om te zien dat het erop lijkt dat het treinmaterieel achteruit gaat. zo vind ik de nieuwe dubbeldekkers (die intercity dingen) ook een stuk minder prettig dan de oude (die gele bakken). op de een of andere manier zit ik lekkerder in de oude en slapen ze vooral prettig (je kan tenminste met je benen bij de stoel tegenover je). ook zijn die nieuwe intercities erg koud in de winter! op het gebied van verwarming winnen die oude barrels uit 1960 of zo nog steeds. nog steeds vind ik de koplopers en die beneluxtrein de prettigste treinen om een tijd in te zitten, ook oude bakken dus. zal wel aan mij liggen.</div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.livejournal.com/users/inverted_tree/39880.html">April 09, 2005 07:13 PM</a></em>





<div class="blogdate">April 06, 2005</div>




<p class="blogname"><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></p>


<div class="blogtitle">
<a href="http://www.advogato.org/person/adrighem/diary.html?start=4">6 Apr 2005</a></div>
<div class="blogstory">
<p>Last saturday we thought about setting up some kind of planet GNOME-NL. And now, (drumroll please), here it is:<p>
<a href="http://nl.gnome.org/planet.php">http://nl.gnome.org/planet.php</a></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.advogato.org/person/adrighem/diary.html?start=4">April 06, 2005 03:59 PM</a></em>





<div class="blogdate">April 05, 2005</div>




<p class="blogname"><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></p>


<div class="blogtitle">
<a href="http://geeklog.eyesopened.nl/archives/2005/04/05/desktop-places-nautilus/">Desktop Places, Nautilus</a></div>
<div class="blogstory">
<p><p>Finally my exams week is over, good results so I look forward to the end of this college year.</p></p>

	<p><p><h4>Desktop Places</h4>I&#8217;ve created a <a href="http://live.gnome.org/DesktopPlaces">wiki page</a> on live.gnome.org after some discussion with seb128 on <span class="caps">IRC</span>. I think (and I hope more people) we need a central way of modifing, adding and fetching all the standard desktop places we have like Home, Trash etc. This is closely related to gtk-bookmarks, which also relates to <a href="http://live.gnome.org/RecentFilesAndBookmarks">this wiki page</a>.</p></p>

	<p><p><h4>File management stuff</h4>Now I&#8217;m at it, lets go on with some ideas. It would really rock if we could move some parts of libnautilus-private (like file management operations) to another public library. This way other apps can integrate with nautilus which will give a consistent feeling for the user. (Think about the same progress window in every app)</p></p>

	<p><p><h4>Halfish boredom</h4><a href="http://geeklog.eyesopened.nl/wp-content/images/gnome-mysql.png"><img src="http://geeklog.eyesopened.nl/wp-content/images/gnome-mysql_thumb.png" class="left" /></a>So I was a little bored today and decided to get my mind of the usual stuff and relax a bit with some python hacking. Since I need to examine/edit and add a lot of data into a MySQL database in the near future for my job I&#8217;ve hacked together a little utility to do database management tasks.<br />
<br />
<br />
<br />
<br />
</p></p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://geeklog.eyesopened.nl/archives/2005/04/05/desktop-places-nautilus/">April 05, 2005 08:02 PM</a></em>





<div class="blogdate">April 04, 2005</div>




<p class="blogname"><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></p>


<div class="blogtitle">
<a href="http://www.livejournal.com/users/inverted_tree/39587.html">gnome-nl gnome 2.10 release party photos</a></div>
<div class="blogstory">
OOPS! Being the irregular blogger I am, I forgot to post some photos from the gnome-nl gnome 2.10 release party. I think most people there agreed that we should do something like that more often, and I got told that I promised to try to organise a "pub-evening" (I would use 'borrel' in Dutch, dunno what to use in English) each month. Since I am busy I am hopelessly failing, but it is going to happen someday!<br /><br />Ok so here are some photos:<br /><a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/tux-na-werk.jpg"><br /><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_tux-na-werk.jpg" border="0" /><br /></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/gnome-nl-feescht.jpg"><br /><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_gnome-nl-feescht.jpg" border="0" /><br /></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/gnome-nl-feescht-2.jpg"><br /><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_gnome-nl-feescht-2.jpg" border="0" /><br /></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/gnome-nl-mensen-blij.jpg"><br /><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_gnome-nl-mensen-blij.jpg" border="0" /><br /></a> <a href="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/nog-meer-gnome-nl-mensen-blij.jpg"><br /><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/gnome-nl-2.10/small_nog-meer-gnome-nl-mensen-blij.jpg" border="0" /><br /></a><br /><br />Oh, another thing I forgot. It looks like I will be at GUADEC this year (finally); not 100% sure yet, but I am already looking forward to it.</div>
<em><a style="font-style: italic; font-size: x-small;" href="http://www.livejournal.com/users/inverted_tree/39587.html">April 04, 2005 09:42 PM</a></em>









<p class="blogname"><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></p>


<div class="blogtitle">
<img class="floatright" src="images/uwog.png" alt="Marc Maurer face">
<a href="http://uwog.net/blog/?p=41">*Ahum*</a></div>
<div class="blogstory">
<p><b>AbiWord</b><br />
Something about 2.2.7 and brown paper bags [ <a href="http://www.abisource.com/release-notes/2.2.7.phtml">Release Notes</a> ], [ <a href="http://www.abisource.com/changelogs/2.2.7.phtml">Changelog</a> ], [ <a href="http://www.abisource.com/download/">Download</a> ]</p></div>
<em><a style="font-style: italic; font-size: x-small;" href="http://uwog.net/blog/?p=41">April 04, 2005 08:59 PM</a></em>
</div>

<? gnome_foot() ?>
</body>
</html>
