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
<center><h3>Leden</h3></center>
<ul>
<li><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a> <a href="http://www.livejournal.com/users/inverted_tree/data/rss">(rss)</a></li>
<li><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a> <a href="http://www.moerman.org/~uwog/blog/wp-rss2.php">(rss)</a></li>
<li><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a> <a href="http://geeklog.eyesopened.nl/index.rdf">(rss)</a></li>
<li><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a> <a href="http://www.advogato.org/person/rbultje/rss.xml">(rss)</a></li>
<li><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a> <a href="http://www.advogato.org/person/adrighem/rss.xml">(rss)</a></li>
</ul>
</div>
<h1>Planet GNOME-NL</h1>


















<h2>April 05, 2005</h2>




<h3><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></h3>


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





<h2>April 04, 2005</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


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









<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


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





<h2>March 26, 2005</h2>




<h3><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></h3>


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





<h2>March 23, 2005</h2>




<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=97">23 Mar 2005</a></h4>
<p>
<b>Totem Mozilla Embedding</b><br>
Bastien already said that we had basic javascript functionality people working in the Totem-Mozilla plugin. Today, I finished the second part of the job: a two-way communication protocol. Should ideally use DBUS for that, but we're using something of ourselves right now.
<p>
The result of that is that the plugin and the child application (which is spawned on its own for security reasons) interact using some basic IPC command-set. This, in combination with the javascript-functionality, means that I can click images on a <a href="http://ronald.bitfreak.net/priv/pimp.png">HTML page</a> and the player will react as it should. The javascript-calls are compatible with Quicktime Player. Woohoo, finally a kick-ass mozilla media player. :-). Get your daily dose of Totem-CVS today!</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=97">March 23, 2005 06:22 PM</a></em>
</p>





<h2>March 14, 2005</h2>




<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


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





<h2>March 13, 2005</h2>




<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


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





<h2>March 12, 2005</h2>




<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


<h4><a href="http://uwog.net/blog/?p=38">How to spend your spare time</a></h4>
<p>
<p><em>Note: this entry is not intended to just bash Eugina, nor is it directed *only* at her. Her article was merely the cause that made me write this entry to address a more general problem. A problem that I see happening all the time: users often have the wrong expectations about OSS and the development process involved. We wrote a short <a href="http://www.abisource.com/support/expectations.phtml">essay</a> about this as well a while ago.</em></p>
	<p>@Eugenia: If you (read: if users&#8230;) want to be able to <a href="http://www.osnews.com/story.php?news_id=9933">dictate</a> what developers should or should not implement, then by all means, go ask commercial developers to implement it for you. They like to be told what to do, as customers are their only hope for survival (some cash might help speed up the process). Unbound OSS developers (ie. not working for any company) can do with <em>their spare time</em> whatever they want. No really. Their spare time. Try to remember that. Write it 1000 times on the wall: &#8220;Noone can dictate how people should spend <em>their spare time</em>&#8220;. Some developers love talking to users. Some don&#8217;t. There is nothing you can change about that. If you don&#8217;t like that, then you don&#8217;t have any other option then to start your own software movement where users can dictate what developers should do. I think such a movement would flourish!</p>
	<p>Oh, and this may come as a surprise to some: OSS is not about killing CompanyA or being more competitive than ProductX. Often developers just enjoy writing good code or being part of a community. Heck, all too often have I been told that I should quit AbiWord development and join the OpenOffice.org team. Because clearly, OpenOffice.org is the only viable competitor against MS Word. Well, *I don&#8217;t care*!  I have fun hacking and maintaining AbiWord and I greatly enjoy the community surrounding AbiWord. I don&#8217;t enjoy working on OpenOffice.org. Too bad if people don&#8217;t like the way I spend my spare time.</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=38">by uwog at March 12, 2005 12:48 AM</a></em>
</p>





<h2>March 11, 2005</h2>




<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


<h4><a href="http://uwog.net/blog/?p=37">How not to organize release note translations</a></h4>
<p>
<p>Being part of GNOME-NL, I myself together with the whole team spent a large amout of time translating the GNOME 2.10 release notes into Dutch, including some nice Dutch screenshots. We figured it would be really nice for novice (potential) users to be able to read all the goodness in their own languages.<br>
Now, this is where the mess begins. Not only were the official release notes continuously changed until the very last moment (note: bad), the process of creating the latest and the greatest .po file was a mess as well. Murray was as helpful as he could be, suggesting we needed gnome-doc-utils CVS HEAD (note: bad). When the final 2.10 release was made, we generated the HTML ourselves to host them on <a href="http://nl.gnome.org">http://nl.gnome.org</a>, which looked fine. However, the Dutch release notes linked on <a href="http://gnome.org">http://gnome.org/</a> were still partially in English! Whuh? Murray told us to follow the instructions in the &#8220;releng&#8221; module to get a proper .po file, which we did, but to no avail. There seems no way to get a .po file that <em>is</em> correct, so we are left in the dark (note: bad) now. Additionally, we noticed today that the (partial) Dutch translation was <em>dropped altogether</em> from gnome.org, without any notice (note: really really bad)!<br>
We&#8217;re very keen on giving a perfect experience to (potential) Dutch GNOME users, hence our <a href="http://l10n-status.gnome.org/gnome-2.10/top.html">high ranking</a> in the overall GNOME translation list with just 3 fuzzy strings left. However, this whole release notes translation experience is just so demotivating, that I doubt I&#8217;ll spend my time on it next time.</p>
	<p><em>Note: I do love the effort a lot of people have put into making localized release notes possible, but currently, it is just not working</em></p></p>
<p>
<em><a href="http://uwog.net/blog/?p=37">by uwog at March 11, 2005 06:15 PM</a></em>
</p>









<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=95">11 Mar 2005</a></h4>
<p>
<b>Release day</b><br>
It's kinda evil, but hey, why not? All poor packagers are busy already, so let's give them some more work. I've released <a href="http://ronald.bitfreak.net/me/cupid.php">Cupid 0.0.2</a>, which is a GStreamer/GNOME video-capture and -display tool, and <a href="http://gstreamer.freedesktop.org/releases/gst-ffmpeg/0.8.4.html">GStreamer-FFmpeg 0.8.4</a>, which is a set of GStreamer plugins based on the popular and high-quality ffmpeg codecs.

<p> <b>Other people release, too</b><br>
Also, one of our new GStreamer supporters, Jon Lech Johansen, released <a href="http://fuware.nanocrew.net/pymusique/">pyMusique</a> today, which is a Gtk-application that lets you buy music from the iTunes Music Store from your GNOME desktop. I'm already a happy user of this piece of software. Great work, Jon!</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=95">March 11, 2005 10:19 AM</a></em>
</p>





<h2>March 10, 2005</h2>




<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=94">10 Mar 2005</a></h4>
<p>
<b>GNOME-nl release party</b><br>
After the release of GNOME 2.10 and the <a href="http://mail.gnome.org/archives/gnome-nl-list/2005-March/msg00014.html">congratulations from our KDE-nl colleagues</a>, it was time to celebrate the release locally. A total of 10 people had a great evening in the Stairway to Heaven in Utrecht, the Netherlands. Of course made the obligatory <a href="http://ronald.bitfreak.net/priv/tuxbier.jpg">Tux-with-beer picture</a>, but most importantly, we all celebrated the continued livelihood of our beloved GNOME. <a href="http://ronald.bitfreak.net/priv/wijbier.jpg">Here</a>'s another picture showing (clock-wise) Kris (with blue hair!), Wouter, Jeroen, Hette, Tino, Vincent and Reinout. Not shown but definately there were Laurens, Marcel and Tim. Great release-party, let's hope we can soon do this again!</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=94">March 10, 2005 10:52 AM</a></em>
</p>





<h2>March 08, 2005</h2>




<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


<h4><a href="http://uwog.net/blog/?p=36">AbiWord Won&#8217;t Fall for Feature Bloat</a></h4>
<p>
<p><a href="http://tieguy.org/blog/index.cgi/326">Luis</a>: First of all, that awful toolbar is for some reason how Martin likes his AbiWord. Beats me as well why anyone would like that, and I agree it could use a lot of cleanup.<br>
Then on to any possible feature bloat. This can hardly be jugded just by looking at that toolbar, as it only shows the really basic functionality users expect from every WordProcessor. That said, we are trying to be very, very careful about the features we add to AbiWord. For example, all the new stuff you&#8217;ve seen floating by the last couple of weeks (Equation/MathML/LaTeX support, Grammar checker support, etc) is all <em>completely</em> implemented in plugins. We&#8217;ve been refactoring AbiWord for this very purpose. In fact, the featureset of AbiWord 2.4 will be (without the plugins) exactly the same as the featureset of AbiWord 2.2 (ignoring the usual tweaks all over the place).<br>
As long I&#8217;m involved in AbiWord&#8217;s development, I&#8217;ll battle against any feature bloat in AbiWord&#8217;s core.</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=36">by uwog at March 08, 2005 09:17 PM</a></em>
</p>









<h3><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></h3>


<h4><a href="http://www.advogato.org/person/adrighem/diary.html?start=3">8 Mar 2005</a></h4>
<p>
<p>The impossible has happened. We have 100% interface translation for GNOME 2.10. This in spite of some 6000-7000 new strings alongside the normal string updates... I'd like to thank the Dutch translation team for their effort.<p>
This is a first for us, a Dutch press release. I'm very excited about this. It means we're finally coming of age as a GNOME community. Can't wait 'till tomorrow when 2.10 will be a fact!</p>
<p>
<em><a href="http://www.advogato.org/person/adrighem/diary.html?start=3">March 08, 2005 02:34 PM</a></em>
</p>





<h2>March 07, 2005</h2>




<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=93">7 Mar 2005</a></h4>
<p>
<b>European software patents</b><br>
I just heard multiple such rumours... After the EP (EU parliament) proposed to restart the directive decision-makery, after MPs (national parliaments) of many countries opposed the directive, after many citizens openly opposed the directive, after Denmark announced they would oppose the A-item, at least for the meeting this morning, after all our tries ...
<p>
... the EC (EU council) just passed the patent directive. Long live democracy. :-(.</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=93">March 07, 2005 10:19 AM</a></em>
</p>





<h2>March 06, 2005</h2>




<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=92">6 Mar 2005</a></h4>
<p>
<b>GNOME 2.10 release party</b><br>
At the day of the release (Wednesday, March 9th), GNOME-nl will be organizing one of the GNOME release parties to celebrate the release of our all-beloved GNOME 2.10. See <a href="http://live.gnome.org/Gnome210Party">this page</a> for a complete list. Our party will be held in the Stairway to Heaven, which is a popular cafe close to the train station of Utrecht. All interested people are invited to come and join us from 20.00 on.
<p>
All dutch GNOME developers, translaters, contributors and enthusiasts are hereby invited to come and join us in this celebration. Don't bring your laptop, however, it's not a LAN/install party. See you wednesday!</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=92">March 06, 2005 09:33 PM</a></em>
</p>









<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


<h4><a href="http://uwog.net/blog/?p=35">03-06-2005</a></h4>
<p>
<p>Finally got around syncing my personal AbiWord-pet-branch with CVS HEAD again. You probably all know the irritating situation of having written a patch that wasn&#8217;t immediately committed (for whatever reason) or was put away in a branch, and you look at it again after 6 months. It&#8217;ll never apply in the current state, so you have to update and rework it. You always end up ignoring the problem until it&#8217;s too late. This becomes especially frustrating when the <a href="http://www.abisource.com/mailinglists/abiword-dev/2005/Mar/0082.html">patch is this big</a>. Glad I finally got around doing it.</p>
	<p>Can&#8217;t close this blog entry without a last reply to <a href="http://log.ometer.com/2005-03.html#3">Havoc</a>: first of all, it would be insanely nice if the cvs access procedures that will apply to FC Extra&#8217;s would also apply to Fedora Core!<br>
Now, on to the Gnumeric exclusion problem. I noticed desktop-backgrounds-extra is not in a seperate SRPM. There goes my additional saved 6MB. Let&#8217;s see. How about ditching the openoffice.org-* packages? That would make enough room for Abi to fit in, and Gnumeric as well! I ofcourse realize that his will never happen without a nice GNOMEy presentation application. So if anyone has written one, but just never gotten around to committing it in GNOME&#8217;s cvs, now is the time!</p>
	<p>BTW, p0rn on <a href="http://planet.gnome.org/">planet.gnome.org</a>? Not that I have any particular problem with it, being Dutch and all, but I just wouldn&#8217;t file it under &#8220;Tips&#8217;n'Hacks&#8221;.</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=35">by uwog at March 06, 2005 02:18 AM</a></em>
</p>





<h2>March 04, 2005</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<p>
Jakub, we went to 'Dolni Dvur' and went skiing in 'Spindleruv Mlyn' (if I spelled it correctly). If I'm right, those places are not far from Liberec. Anyway, what makes .cz attractive is that it's cheap. The beer is cheap (and good), restaurants are cheap (~EUR 60 for a good dinner for 5 persons, in the Netherlands that would cost about ~EUR 200), public transit is cheap and skiing too (equipment rent and ski passes). Also, we were told that .cz had good slopes for beginners. For us (5 students), it was a perfect place to go on holiday for a week.<br><br>[oh, i do have comments switched on and they work fine ...]</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/38997.html">March 04, 2005 11:30 PM</a></em>
</p>









<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=91">4 Mar 2005</a></h4>
<p>
<b>iTunes Music Store #2</b><br>
So, apparently, if you use iTMS on your Linux/GNOME machine, you can <a href="http://ronald.bitfreak.net/priv/bugsong.png">buy</a> songs and <a href="http://ronald.bitfreak.net/priv/playsong.png">play</a> them back. Against all expectations, these songs are:<br>
<ul>
<li>not tagged (shame on you, Apple!)
<li>not encrypted (woohoo! love ya)
</ul>
Hey, it's only $0.99/song, what are we waiting for? :-). Longe live Apple!</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=91">March 04, 2005 07:26 PM</a></em>
</p>





<h2>March 03, 2005</h2>




<h3><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></h3>


<h4><a href="http://www.advogato.org/person/adrighem/diary.html?start=2">3 Mar 2005</a></h4>
<p>
<p>Oh yeah, this feels soooooo goooood.<p>
I've been having troubles with spam for ages now. And the sa-exim package I've installed has changed that completely. Only about two spam mails per day now.<p>
On top of that: when I look at my daily logfiles for exim4, I get this result:<p>
grep teergrubed mainlog.1 | wc -l<p>
104<p>
104 connections teergrubed by my server in one day. I just love teergrubing. That'll teach them. I'm really enjoying this. I'm actually thinking about setting up some extension to phpsysinfo so I can keep an eye on the active teergrubing, as well as a cumulative counting thingy:<p>
I've teergrubed "506" spammers since 2005-02-24. Thanks for spamming me.</p>
<p>
<em><a href="http://www.advogato.org/person/adrighem/diary.html?start=2">March 03, 2005 11:13 PM</a></em>
</p>









<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


<h4><a href="http://uwog.net/blog/?p=34">I tried not to rant this time, really!</a></h4>
<p>
<p><b>AbiWord</b><br>
Just released v2.2.5. This release is the first 2.2.x release where *I* am really happy about as well. Try it, you&#8217;ll love it. [ <a href="http://www.abisource.com/release-notes/2.2.5.phtml">Release Notes</a> ], [ <a href="http://www.abisource.com/changelogs/2.2.5.phtml">Changelog</a> ], [ <a href="http://www.abisource.com/download/">Download</a> ]</p>
	<p><b>AbiWord and FC</b><br>
Quite dissapointed to see AbiWord had to go to the dumping ground. Yes dumping ground, because for the &#8216;normal&#8217; user if it is not in the default installation, it simply does not exist (but hey, it&#8217;s in the same CVS!). However, we weren&#8217;t in the default install for FC3 either, we don&#8217;t loose much there. The fact that the &#8217;simple&#8217; <em>yum install abiword</em> command will install AbiWord is nice for us geeks, but basically of no use to normal users (our target group!): I&#8217;ve seen a lot of people using FC for over 6 months, and almost noone knew that something like <em>yum</em> even existed at all&#8230;<br>
Oh, btw, the size argument is just lame. AbiWord is below 5 MB, including plugins&#8230; :-X The Fedora background rpms are almost 3 times larger in size for crying out loud! Ditch some bitmaps and include a program quite a lot of people actually care about! So there Havoc, you asked which package we would shove into FC Extra&#8217;s instead of AbiWord: desktop-backgrounds-extras. It will even save you an additional 6 MB! If the developers of that package have anything personal against me now, then let&#8217;s ask the community (wasn&#8217;t Fedora a community oriented project, supported by RH?) which package they want to stay in Fedora Core. AbiWord or fedora-backgrounds-extras?<br>
The only good thing I see about the move to FC Extra&#8217;s, is that we will be able to update AbiWord regularly, unlike the ancient AbiWord 2.0.12 in FC3 for example. For this, I&#8217;ve offered RH to maintain AbiWord myself in FC Extra&#8217;s. For Abi&#8217;s sake.<br>
<em>Sorry about this one in advance. No doubt that when I wake up, I think this was too harsh. Focus on &#8217;short-term situation&#8217; like Havoc said. Focus uwog!</em></p>
	<p><b>About my OOo rant</b><br>
Got a nice mail from Michael about that. While I got the general funny setup of that spec file, it still was not a clever move (to link it in the release notes). The general <em>perception</em> of the OOo WordPerfect filter now is that it is of rather low quality. That&#8217;s a shame for OOo as well. Even AbiWord manages its releases more carefully :-P</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=34">by uwog at March 03, 2005 04:44 AM</a></em>
</p>





<h2>March 02, 2005</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/38723.html">snow.</a></h4>
<p>
I eventually got back from .cz a week or three ago. It was a fun holiday, a good break away from the usual stress. We dealed with the car being stuck in the snow, drank a lot of beer, occasionally attempted to do some skiing and talked a lot about various things. Also I figured that I don't like skiing, so maybe I should give snowboarding a try next time.<br><br><center><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/cz_roomview_small.jpg"><br><h5>the view from my room was awesome</h5><br></center><br><br>Now it is March, and it is SNOWING IN THE NETHERLANDS. Now there is snow there are all kinds of problems with the train and the bus, it is sort of annoying when you have to commute 3 hours a day. Now (yes this is the third sentence starting with now, sorry) people would ask, why don't you find a room in Leiden (which is the place where I go to university and work a bit currently)? This is the other thing bothering me currently, I have no clue what I am going to do after my CS Bachelor. I am not sure if I want to do a master in CS, if I do I am not sure whether to do the CS master in Leiden or some other city (say Utrecht or Enschede or something). Maybe I should do some other Bachelor, say architecture, or something. Anyway, the snow is insane.<br><br>In other news, I also ordered a mac mini. And since this thing is not going to cost me as much as a powermac, I can prolly afford a new guiter amp too. Wooo.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/38723.html">March 02, 2005 10:55 PM</a></em>
</p>









<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


<h4><a href="http://uwog.net/blog/?p=33">OOo2 Rant</a></h4>
<p>
<p>WARNING: This might be offensive, so don&#8217;t read it if you can&#8217;t handle it.</p>
	<p>A preview release of OOo2 has just been released. As the AbiWord 2.2 maintainer, there is no need to state that I don&#8217;t think that OOo is the best piece of software in the world. On the other hand, there is nothing wrong with a good bit of competition, so kudos to them.<br>
However, since I&#8217;m also one of the main libwpd developers, and OOo2 uses our WordPerfect import filter based on libwpd, I&#8217;d thought I scan the release notes for a reference of it. I found, and I quote (even sighted on SlashDot already):</p>
	<blockquote><p>The [WordPerfect] filter needs continuous development to arrest bit-rot, and to improve it&#8217;s capabilities.</p></blockquote>
	<p>WTF?<br>
1&#8230;2&#8230;3&#8230;4&#8230;5&#8230;@*&amp;#*$@<br>
So that is how we thank developers who wrote the f*cking best WordPerfect import library in existance today? And even gone all the way to provide an OOo2 filter for it? That alone was an hell, since for example OOo2 has to be a smartass to have written STLPort, a redundant effort to duplicate the C++ STL, and ofcourse making sure the two are incompatible in the process. Since libwpd uses the STL extensively, we had to abstract all STL parts out of our public headers. For OOo2 alone!<br>
To add, libwpd has been (regression) tested to death, so if they know any errors (which they seem to do, as it contains bit-rot), please report them. Good luck searching.<br>
Thanks guys. You Suck.</p>
	<p>PS. It seems WordPress 1.5 thinks it is funny to add old entries to the RSS feed as well. Sorry about that.</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=33">by uwog at March 02, 2005 01:08 AM</a></em>
</p>





<h2>March 01, 2005</h2>




<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=90">1 Mar 2005</a></h4>
<p>
<b>iTunes Music Store</b><br>
Today (that's a lie; it was yesterday), the tooth fairy knocked on my door again. I said "heya tooth fairy, what've you been up to lately?" and the tooth fairy just stood there, smiling, happy, glimming even. Silent. Then, I knew something was gonna happen. I'd been here <a href="http://www.advogato.org/person/rbultje/diary.html?start=84">before</a>.
<p>
She said: "Ronald, I've been doing something different lately. Do you want me to take you to this new world and show you the code that will rule your hard disk tomorrow?" and I said "sure, why not" and off we were.
<p>
And there we went off, to a computer, she showed me a dark terminal with a GNOME mountains background through the transparent background (yes, that's slow). And she typed, magically, but it worked as if it was a P-4 2 GHz: "<a href="http://ronald.bitfreak.net/priv/xtc1.png">search Korn</a>", and output appeared. <a href="http://ronald.bitfreak.net/priv/xtc2.png">Free preview (~15sec), played back through GStreamer</a>, $0.99 for the full song. This is not just a price. Those knowledgeable know what this means.
<p>
<a href="http://www.apple.com/itunes/">Apple iTunes Music Store</a> accessible on your Linux box (with a shiny GNOME UI, of course). Coming soon.</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=90">March 01, 2005 09:33 PM</a></em>
</p>









<h3><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></h3>


<h4><a href="http://www.advogato.org/person/adrighem/diary.html?start=1">1 Mar 2005</a></h4>
<p>
<p>Finally got AMaVIS  up and running on my own server. Now my Linux box won't have to worry about windows visuses accidentally being executed with wine or something.<p>
I already had spamassassin running, but now that has been upgraded to sa-exim based execution. So spam will be rejected before it reaches my mailspool. Also teergrubing is a nice bonus.<p>
On a downside: SPAM seems to get through my new setup again. This despite the BAYES_99 keyword... I hope it's temporary<p></p>
<p>
<em><a href="http://www.advogato.org/person/adrighem/diary.html?start=1">March 01, 2005 07:42 PM</a></em>
</p>









<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=89">1 Mar 2005</a></h4>
<p>
<b>FOSDEM</b><br>
So this weekend, we had FOSDEM. There were some nice talks from various people, there was no working wireless network and I managed to have good chats with various great people. Same as always, great stuff. I met up with the dutch GNOME people and had some pleasant night-talks in the bars with them (long live gnome-nl!). I also caught a cold, which made the whole somewhat painful, because I've been caughing and sneezing all weekend (and still am). Fortuntely, I wasn't the only sneezing person in there (or, well, fortunately? ...).
<p>
Special were the talks I had with Oyvind (GIMP/GGGL/Oxide), Charles (MLT/Kino) and Edward (Pitivi) on how to do more advanced stuff with our respective video solutions w.r.t. video editing (plus lots of cool demos) plus the obligatory amounts of lunch and beer. It's good to have talks with people that know their stuff. It gave me some pretty good ideas on things I can try to do in the future, and some starting points for basic interoperation. More on this is yet to come. Here's some cool stuff: Oyvind can do pretty wacky advanced motion vector and interactivity stuff in his framework that we cannot do yet. Oyvind has some really wacky examples (like live pong using a webcam), people really should look at those. Charles has some very well-working video editing things that are being used in the industry. It's very well-thought-out ideas that we can adapt and use ourselves, too. Long live free software!
<p>
<b>GStreamer releases</b><br>
We're currently preparing for a new gst-plugins release, which should be out within a few days.</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=89">March 01, 2005 10:55 AM</a></em>
</p>





<h2>February 23, 2005</h2>




<h3><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></h3>


<h4><a href="http://geeklog.eyesopened.nl/archives/2005/02/23/loving-abiword/">Loving AbiWord</a></h4>
<p>
<p><p>I think I am beginning to love Abiword. After trying LaTeX and OpenOffice.org(2) I am now trying to fit Abiword to my needs. Love it:</p></p>

	<p><p><a href="http://geeklog.eyesopened.nl/wp-content/images/Screenshot-abiword2.png"><img src="http://geeklog.eyesopened.nl/wp-content/images/Screenshot-abiword2-thumb.png" alt="I love Abiword"></a></p></p>

	<p><p>I have removed some toolbars so I get a nice and clean interface. (I only need the stylist anyways). Now I&#8217;m trying to learn all those wonderful features and work on a nice default template for my documents.</p></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/02/23/loving-abiword/">by admin at February 23, 2005 01:11 AM</a></em>
</p>





<h2>February 22, 2005</h2>




<h3><a href="http://uwog.net/blog" title="Marc 'uwog' Maurer's Blog">Marc Maurer</a></h3>


<h4><a href="http://uwog.net/blog/?p=32">AbiWord 2.2.4, libwpd 0.8.0</a></h4>
<p>
<p><b>AbiWord</b><br>
Just released v2.2.4, go get it! [ <a href="http://www.abisource.com/release-notes/2.2.4.phtml">Release Notes</a> ], [ <a href="http://www.abisource.com/changelogs/2.2.4.phtml">Changelog</a> ], [ <a href="http://www.abisource.com/download/">Download</a> ]</p>
	<p><b>libwpd</b><br>
We released libwpd 0.8.0 last week as well. AbiWord 2.2.4 already uses it, and OpenOffice.org 2.0 will use it as well. [ <a href="http://sourceforge.net/project/shownotes.php?group_id=62662&release_id=303971">Changelog</a> ], [ <a href="http://sourceforge.net/project/showfiles.php?group_id=62662&package_id=59383&release_id=303971">Download</a> ]
</p>
	<p>You heard it here first.</p></p>
<p>
<em><a href="http://uwog.net/blog/?p=32">by uwog at February 22, 2005 01:21 AM</a></em>
</p>





<h2>February 21, 2005</h2>




<h3><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></h3>


<h4><a href="http://geeklog.eyesopened.nl/archives/2005/02/21/organizing/">Organizing</a></h4>
<p>
<p><p>If you&#8217;re not interested in the ramblings about my life, move along.. :) This post is mostly a note to myself.</p></p>

	<p><p>Today I had the strange feeling I should organize my life more. So here are the first steps to a long road. I think organizing my life can mean success at school and work, and a lot of happyness overall. I&#8217;m going to make a list of what I did, am doing and am planning to do, this way I get a good view of what I am (too much) time on, and what I should give a bit more attention. Here&#8217;s the list:</p></p>

	<p><p><strong>Current projects</strong></p></p>

	<p><ul></p>
	<p><li>Generic Project Page<br>
</li><li>Trash Applet<br>
</li><li>Aribigi<br>
</li><li>Update Manager<br>
</li><li>Update Notifier<br>
</li><li><span class="caps">GNOME </span>Dutch translation project<br>
</li></ul></p>

	<p><p><strong>Planning to do</strong></p></p>

	<p><ul></p>
	<p><li>Spend more time on bugfixing rather than implementing new features.<br>
</li><li>Write a &#8216;statistics&#8217; Evolution plugin. Which is going to show e.g. how much spam you receive, how much mail from mailinglists and how much is addressed to you personally. All this in a nice chart.<br>
</li><li><span class="caps">GNOME </span>Sync. I&#8217;ve always wanted something like this. A simple easy tool which lets the user check which settings it wants to save from your <span class="caps">GNOME</span> desktop (themes, Evolution settings, GConf keys etc.) and send it to another user, remote machine or backup. A bit like Apple .Mac Sync.<br>
</li></ul></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/02/21/organizing/">by admin at February 21, 2005 11:52 PM</a></em>
</p>











<h4><a href="http://geeklog.eyesopened.nl/archives/2005/02/21/update-manager-xcompmgr/">Update Manager, xcompmgr</a></h4>
<p>
<p><p>I just tought it looked nice to show you :-)</p></p>

	<p><p><a href="http://geeklog.eyesopened.nl/wp-content/images/updmgr.jpg"><img src="http://geeklog.eyesopened.nl/wp-content/images/updmgr-thumb.jpg" alt="Update Manager, xcompmgr"></a></p></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/02/21/update-manager-xcompmgr/">by admin at February 21, 2005 09:02 PM</a></em>
</p>





<h2>February 18, 2005</h2>




<h3><a href="http://www.advogato.org/person/rbultje/" title="Advogato diary for rbultje">Ronald S. Bultje</a></h3>


<h4><a href="http://www.advogato.org/person/rbultje/diary.html?start=88">18 Feb 2005</a></h4>
<p>
<b>GStreamer Developer Summit</b><br>
From wednesday on, most GStreamer core developers and following (Andy, Benjamin, Christian, Dave, Julien, me, Thomas, Wim) got together to discuss future development and direction for the 0.9 branch . This will then become the final design for GStreamer 1.0; 1.0 would, in the end, be proposed for inclusion in both KDE and GNOME developer platforms. Big words, time for action.
<p>
<i>Problems:</i><br>
Current GStreamer-0.8 design has various generally agreed-on problems that cannot be fixed easily without breaking ABI/API. Worse, for a long time we did not know how to fix it and did not agree on a common direction towards fixing it.
<ul>
<li>thread-safety issues (refcount-, signal-, state-change- related stuff and a lot more)
<li>clocking/synchronization issues
<li>state handling on eos/error is wrong
<li>negotiation protocol issues
<li>a lot more
</ul>
Wim was leading the discussion to identify and agree on the mistakes in our current 0.8 tree, particularly the ones that we cannot fix in the 0.8 timeframe. This is mostly stuff that we agree on and know how to fix. It&#180;s just something we need on paper for later.
<p>
<i>Generally agreed-on solutions:</i><br>
Here is what came out of this discussion:
<ul>
<li>eos/error should not change state. More generally, state and actual processing ("scheduling") should be separated from each other.
<li>refcounting should be threadsafe (glib)
<li>signals should be marshalled to the correct thread, either through a message bus or through cross-thread signal marshalling.
<li>Clocking should be improved to be implicitely synchronization (which we don&#180;t have right now; *shame*) across streams.
</ul>
Most of thursday was spent on discussing and agreeing on the above. We mostly agree on all this. Friday was spent talking about scheduling of pipelines ("processing of the media").
<p>
<i>Solution #1: -threaded:</i><br>
-threaded is an experimental branch that aims to add locking in the right places, adds protocols on locking and variable usage and make it all just work. It also adds some nice extra features such as media processing on events (e.g. "preroll", which means that the media is preloaded while a user executes an action).
<p>
<i>Solution #2: -nonblock/async:</i><br>
-async/noblock is another experimental branch that aims to remove the requirement for threads by using an event-based scheduling model. Elements are only scheduled when all preconditions for a non-blocking execution of their function is met (e.g. a file descriptor, a clock, etc.). Interesting features are seamless mainloop integration, which allows out-of-dataflow pipeline processing (e.g. expose an X window on expose events).
<p>
<i>What's next:</i><br>
The rest of friday was spent on discussing both approaches, fetching problems, setting a short-term path and some more. Wim and Dave believe that the async/noblock approach can be merged in the otherwise nice -threaded approach (which already implements the other stuff that we agreed on).
<p>
Next few days, we'll be experimenting with the design and implement all of the above. We will also discuss various directions for gst-plugins that are not directly related to GStreamer core. For the near future, we will work on preparing core to become the 0.9 branch, port the various subsystems from -threaded and -noblock/async over to this 0.9 branch (and have various people understand and review this), write a porting guide, remove deprecated code, write reference plugin implementations, updating documentation and porting plugins. From then on, we hopefully rock.
<p>
Time for beer. :).</p>
<p>
<em><a href="http://www.advogato.org/person/rbultje/diary.html?start=88">February 18, 2005 04:22 PM</a></em>
</p>





<h2>February 17, 2005</h2>




<h3><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></h3>


<h4><a href="http://geeklog.eyesopened.nl/archives/2005/02/17/update-manager-0371-released/">Update Manager 0.37.1 released</a></h4>
<p>
<p><p>The first real official upstream version of Update Manager has been released. It has been in Ubuntu Hoary for quite some time now but from now on, the releases will also be easily available to others :-) Grab the tarball and check the screenshots on the <a href="http://geeklog.eyesopened.nl/?page_id=3">Update Manager page</a>.</p></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/02/17/update-manager-0371-released/">by admin at February 17, 2005 07:35 PM</a></em>
</p>





<h2>February 16, 2005</h2>




<h3><a href="http://geeklog.eyesopened.nl" title="my geeklog">Michiel Sikkes</a></h3>


<h4><a href="http://geeklog.eyesopened.nl/archives/2005/02/16/the-regular-update/">The regular update</a></h4>
<p>
<p><p>Ok, first of all, I was very busy the last two months with non-tech stuff so I haven&#8217;t had much time to do cool stuff (I&#8217;ve even managed to get myself into local politics), like hacking on some kind of application which is so mind-boggingly good that it will upset the whole Free Software community (ok, dream on Michiel&#8230;). So here is a dull, regular update, as always.</p></p>

	<p><p>Already a while ago, I started learning C# with Mono. I&#8217;ve never been in touch with it before so I really didn&#8217;t know what to expect. It went quite well actually. After a few hours a little cute application appeared on my desktop.</p></p>

	<p><p><a href="http://geeklog.eyesopened.nl/wp-content/images/Screenshot.png"><img src="http://geeklog.eyesopened.nl/wp-content/images/Screenshot-thumb.png" alt="Cute application written with Mono"></a></p></p>

	<p><p>I was quite happy with it. I wonder wether I&#8217;m ever going to write a serious usable application with Mono. Oh well, the future will tell.</p></p>

	<p><p>Oh right. Beagle. After seeing Nat&#8217;s demos &#8211; which really rock (especially the one with the coding examples) &#8211; I decided to give Beagle a spin. Since I run Ubuntu Hoary and Beagle was not in the repository at the time I had to compile it. Luckily the <a href="http://www.ubuntulinux.org/wiki/BeagleInstallHowto">Ubuntu Wiki</a> has a very informative page about how to install Beagle. Since the inotify kernel patch is already compiled into the Ubuntu stock kernel I didn&#8217;t have to do much else than just get the latest source of Beagle and all its dependencies from <span class="caps">CVS</span>/SVN, and compile them. After about an hour I ran beagled and <span class="caps">BEST</span>.</p></p>

	<p><p><a href="http://geeklog.eyesopened.nl/wp-content/images/Screenshot1.png"><img src="http://geeklog.eyesopened.nl/wp-content/images/Screenshot1-thumb.png" alt="Bleeding-Edge Search Tool"></a></p></p>

	<p><p>Now the most productive thing I&#8217;ve contribed with is the Upgrade Notifier and Update Manager I am mainly writing with Michael Vogt from Canonical. We&#8217;ve been experimenting a lot with the UI and have done a great deal of work. More on this later.</p></p>

	<p><p>Oh right, and I used Wordpress 1.5 for my <a href="http://geeklog.eyesopened.nl/archives/2005/02/16/new-weblog/" rel="bookmark" title="New weblog">New weblog</a> :-)</p></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/02/16/the-regular-update/">by admin at February 16, 2005 11:54 PM</a></em>
</p>











<h4><a href="http://geeklog.eyesopened.nl/archives/2005/02/16/new-weblog/">New weblog</a></h4>
<p>
<p><p>Well, needed to put an article here, or it&#8217;ll look ugly ;-) More updates coming soon!</p></p></p>
<p>
<em><a href="http://geeklog.eyesopened.nl/archives/2005/02/16/new-weblog/">by admin at February 16, 2005 07:05 PM</a></em>
</p>





<h2>January 28, 2005</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/38443.html">holiday</a></h4>
<p>
Left for holidays in .cz. Will be back around Feb 6 2005.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/38443.html">January 28, 2005 09:02 PM</a></em>
</p>





<h2>January 16, 2005</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/38221.html">bah.</a></h4>
<p>
ARGHGHGHGH. So I bought a new phone 1 month ago, since I destroyed the display on the old one. And today, I noticed that the display of my new phone is broken! Bah. I hope I can get it repaired for free, since I don't really want to spend another 150 EUR on a phone.<br><br>tberman told me to get 'come on die young' by Mogwai. It is amazing (especially track 11 ['christmas steps']).<br><br>I wrote a GStreamer element for my current project. But I am not seeing any easy ways to generate .NET bindings for it. Bleh. Prolly means I get to code that bit in C. Other than that GStreamer is working very nice these days, gst# too.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/38221.html">January 16, 2005 09:26 PM</a></em>
</p>





<h2>January 12, 2005</h2>




<h3><a href="http://www.advogato.org/person/adrighem/" title="Advogato diary for adrighem">Vincent van Adrighem</a></h3>


<h4><a href="http://www.advogato.org/person/adrighem/diary.html?start=0">12 Jan 2005</a></h4>
<p>
<p>Hi,<p>
First post!<p>
After reading planet.gnome.org, I decided that it would be nice to use gnome-blog for keeping my blog up-to-date. I've had this thing for ever and never used it. But no more.<p>
Well, that's about enough for a first post.</p>
<p>
<em><a href="http://www.advogato.org/person/adrighem/diary.html?start=0">January 12, 2005 11:58 PM</a></em>
</p>









<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/38055.html">an update.</a></h4>
<p>
Hello and happy new year (yes i am late). So I didn't update for over a month again, and right now I should be studying for exams, which means it's a perfect time to write an update (right?).<br><br>Early December I went to see <a href="http://www.heideroosjes.com/">de Heideroosjes</a> in <a href="http://www.tivoli.nl/">Tivoli de Helling</a> together with my girlfriend. I saw de heideroosjes earlier last year at the Lowlands festival in the middle of a tent with &gt; 5000 other people. This time we were in the in middle of about 200 people; quite a difference. Anyway, they played a great show.<br><br>December is a weird month. It's a busy month and usually you are already tired from the months between the summer and December. I was glad when it was Christmas, finally a week no university, no work. Spent time with my family and girlfriend. Slept a lot (really a lot). Bought and got addicted to Sim City 4 (deluxe edition, rush hour is amazing). [<a href="http://www.babi-pangang.org/ik-wil-niks.html">this (in Dutch ...)</a> mostly summarizes how I was feeling that week (and basically all of December).] It sucks that I didn't have time to play more sim city the last two weeks. Also had some time to think and make decisions for the second semester of this year.<br><br>Then we entered 2005. And everything started again. This year is not even 2 weeks old, yet I already want another holiday (which is fortunately coming at the end of January. wooo!).<br><br>This year;<br>- I intend to attend <a href="http://www.fosdem.org/">Fosdem 2005</a><br>- I hope I will have time for some serious hacking again (I am itching to write loads of code, but I don't have the time).<br>- I might attend GUADEC.<br>- it would be fun to find people to start a dumb new band.<br>- I hope to get close to getting my CS Bachelor and figure out what to do next.<br>- blaat.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/38055.html">January 12, 2005 11:46 PM</a></em>
</p>





<h2>December 09, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/37807.html">dingen kloppen niet</a></h4>
<p>
[dutch braindump please move along]<br><br><br>ik heb me al weken, of zelfs maanden, niet zo relaxed/rustig/uitgeslapen/goed gevoeld als deze week (en ik zit pas op de helft). ik geloof dat mn inspiratie ook weer terug is; terwijl ik vandaag half in de trein lag te slapen kreeg ik ideeen voor een logo (voor wat laat ik gezellig in het midden) en ideeen voor het plan van aanpak voor het netwerk van mijn studievereniging (bijna een complete oplossing; het moet alleen nog op papier komen). kon ik maar vaker een week pauze nemen van mn werk/baan; ik heb het gevoel dat het me goed doet. gelukkig over twee weken kerstvakantie.<br><br><br>morgen naar de heideroosjes in tivoli de helling. wordt vast tof.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/37807.html">December 09, 2004 12:08 AM</a></em>
</p>





<h2>November 14, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/37471.html">real update.</a></h4>
<p>
Have been really busy with work, real life, university, etc. Only had time to do programming for university, which involves writing a pascal compiler right now (which is interesting and fun, though there are deadlines and I hate that). Now we managed to deliver part two of that assignment (a week late, but whatever), I have some breathing time. Today I finally had time to get flumotion working. Flumotion is looking really nice, and it basically just works. Which is amazing. I have plans to set up a little audio stream (and maybe an audio/video stream too), and I guess I am going to use Flumotion.<br><br>In other news, on October 7 (yes, more than a month ago), I went to see the band <a href="http://www.spartamusic.com/">Sparta</a> perform at the <a href="http://www.melkweg.nl/">Melkweg</a> in Amsterdam. I saw them earlier, at the <a href="http://www.lowlands.nl/">Lowlands 2003 festival</a>, where they were great. Also this time, I was certainly not disappointed, Sparta is an incredibly good live band. It was cool to see them again, and they played a lot from their new (and great) album Porcelain. Oh, that night the band 'Oil' (which is a Dutch band IIRC) opened, I was not really amazed by them.<br><br>For some reason I suddenly bought a video camera (the Canon mv700). Didn't have time yet to do something useful with it. Have a bunch of weird video editing plans (hopefully I will find time around Christmas). I also have plans with gstreamer's video effect plugins, and maybe writing some weird effect plugins myself. Have also been thinking of writing gstreamer audio plugins which simulate effect pedals (distortion, overdrive, chorus, compression, etc), dunno whether that is easily possible.<br><br>My iBook died again somewhere in september. Took it to machouse for repairs and got it back within two weeks (it was a dead mainboard again, which got replaced for free). I am happy to have a working laptop again.<br><br>At work I've been busy with nfs, ldap, kerberos, pam_nss, samba and tls. Finally I have it all working, all unix and windows accounts and passwords are stored in ldap/kerberos. On UNIX clients you can logon via pam_nss/kerberos, on Windows clients you can logon via a samba domain controller, which gets all account info from ldap. Pretty nifty, though a big pain to get it all sorted out and working. Biggest problem was finding out why the samba daemon and tls didnt like eachother (this was caused by <a href="https://bugzilla.redhat.com/bugzilla/show_bug.cgi?id=138631">a 'bug'(?)</a> in fedora's /sbin/service). Anyway, I am glad I figured it out (after 4 or 5 full days of work. bleh.).<br><br>We are also having router/network throughput problems. Blamed linux router hardware at first, but apparently the problem is somewhere else. Fortunately we do have some sort of clue again where to look, but problems like this are just annoying to no end.<br><br>I found a Sun Ultra1 at the CS dept and now I need to install NetBSD or OpenBSD on it.<br><br>I need a bigger iPod, since my 10gb iPod is now 100% full. Though maybe 185 albums is enough to have with me every day.<br><br><br>Ok, I think it's time to play more burnout 3.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/37471.html">November 14, 2004 10:43 PM</a></em>
</p>





<h2>November 01, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<p>
long time no update. this is not going to be a serious update; that one is going to happen later on.<br><br><br>i just hope that bush won't win the elections tomorrow.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/37350.html">November 01, 2004 09:48 PM</a></em>
</p>





<h2>October 02, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/36870.html">birthday</a></h4>
<p>
I am now 20, yay.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/36870.html">October 02, 2004 05:45 PM</a></em>
</p>





<h2>September 27, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/36684.html">busy busy busy busy</a></h4>
<p>
[this is not an entry about coding; since I don't have any time for coding anymore]<br><br><br>Yes, yes, I am still alive.<br><br>Helping running a student union means a lot of talking, meeting people, drinking beer and getting home late. Especially since I live 1.5 hours from Leiden (still need to find a room, but I am pretty lazy). Sitting in the train at night, after a long day, results into pictures like this<br><br><br><br><center><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/train_by_night.jpg"><br><font size="-4">huh</font></center><br><br><br><br>Didn't buy a powermac yet. Already started spending my money differently, next thing I want is a Marshall amp.<br><br><br><br><center><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/strat.jpg"><br><font size="-4">my special edition strat with super slinkies is cool</font></center><br><br><br><br>Hairdye is fun. I scared my parents; though now they are used to it and like it. People have no real problems finding me, which is sometimes useful. I think next time it should be green.<br><br><br><br><center><img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/blue.jpg"><br><font size="-4">eeek</font></center><br><br><br><br>I am still sick and should go to bed now. I hope I will be able to update this more often from now on.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/36684.html">September 27, 2004 08:30 PM</a></em>
</p>





<h2>August 24, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<p>
Thanks to the person (who didn't leave a name) who commented on my previous entry, I figured that I actually saw 'Monsieur Dodo' and not RM&amp;JHB. I couldn't find the name of the replacement myself and the Lowlands site was still mentioning RM&amp;JHB. Anyway, the music was very nice to dance to.<br><br>I also bought CDs:<br>Transphormer -- Alter Ego (elektro-ish/techno-ish/acid)<br>Agnostic Front -- Dead Yuppies<br>Rancid -- ...and out come the wolves<br><br><br>En Theo is <a href="http://frontpage.fok.nl/nieuws/45554">gevonden</a>. Yaaay.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/36434.html">August 24, 2004 12:32 PM</a></em>
</p>





<h2>August 23, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/36346.html">THEEEEOOOOOOO</a></h4>
<p>
Just got back from the <a href="http://www.lowlands.nl/">the Lowlands festival</a>, where I had a very good time. Bands seen:<br><br><b>Day 1:</b> Novastar (not really my choice of music), The Hives (with a singer who asks for applause too much), Everlast, The Offspring (who fortunately played a lot of old stuff), Heideroosjes, Ralph Myerz &amp; the Jack Herren Band (not sure if this was their name, but it rocked, they play jazzy downtempoish lounge stuff live).<br><br><b>Day 2:</b> Blood hound Gang (for only 10 minutes or so, they are annoying), Velvet Revolver (for 20 mins or so, not really my choice of music), Rude Rich &amp; the High Notes (live ska, big party), Dropkick Murphys (more party), The Darkness (which was okay. Sick people though), Within Temptation (was fun to see live once).<br><br><b>Day 3:</b> Tokyo Ska Paradise Orchestra (hooray for the crazy japanese), Ocean Size (only for a few mins, since it overlapped with Flogging Molly), Flogging Molly (more party (:, with the crowdsurfing Irish flag), Epica (ugh), The Dillinger Escape Plan (insanely fast noise), The Distillers (they weren't in a very good mood apparently, which was too bad), Papa Roach (for fun since I used to listen to their music a few years back. (apparently i wasn't the only one to go there with this reason)), Goldfinger (only the last 10 minutes :(... though bad I couldn't see their complete show), Jeff Mills (DJ).<br><br>Other stuff I wanted to see, but failed to do so (for various reasons): Oil, Gem, I am kloot, van katoen and the full shows of ocean size and goldfinger.<br><br><br>I got told that lowlands is cooler than pukkelpop. Not sure if thomas agrees with that P:. Anyway, it was a very good and cool festival and I guess I will go back there next year.<br><br><br><font size="-6">Oh ja, en THEO is dood. Zie kunstwerk op camping 2</font>.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/36346.html">August 23, 2004 09:17 PM</a></em>
</p>





<h2>August 19, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/35916.html">yay lowlands</a></h4>
<p>
I hacked on whirl2il somewhere last week, but forgot what I was working on. When I get time again I will finish it and get another 'release' out. Code is a little cleaner now.<br><br>This week was really busy. Also I am suddenly blue.<br><br>Tomorrow I leave for <a href="http://www.lowlands.nl/">lowlands</a>. Will be back on Monday.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/35916.html">August 19, 2004 09:30 PM</a></em>
</p>





<h2>August 10, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<p>
hello. so we made it back to the netherlands safely even though the plane (further referred to as the machine, as the pilots like to do) had problems with the fuel(ing) system. When they were still busy pumping fuel into the machine the door of the machine stayed open and we were asked to not fasten the seatbelts. That was sorta scary. On the way back we had quite a bit of turbulence, since they re-routed us through the turbulence area (great).<br><br>i miss the airconditioning.<br><br>tomorrow i have an exam since i failed in december. it is about concepts of programming languages and the book keeps talking about how great ada is. which sort of ANNOYS ME TO NO END. the book is boring. i fell asleep on my couch while trying to read the book while listening to the new sparta album (which rocks).<br><br>maybe i should try to port cairo/gdk/gtk+ to osx/coregraphics once i have a g5.<br><br>i also got told that i need to finish whirl2il. exams first.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/35717.html">August 10, 2004 06:22 PM</a></em>
</p>





<h2>August 01, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<p>
People are asking me what my top 3 of problems are with desktop Linux. I will try to write something up in the next few days when I won't have net access.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/35269.html">August 01, 2004 06:16 PM</a></em>
</p>





<h2>July 31, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/34948.html">the personal entertainment system. Cocoa. and Apple.</a></h4>
<p>
So last Friday we flew to the states. Our plane was a brand new 777, which is a pretty nice plane. We fortunately had an uneventful flight, however it took 10 hours.

<p><center>
<img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/our-777.jpg"><br>
<font size="small"><i>The shiny 777 after landing.</i></font>
</center>
<p>

On board everybody had a personal LCD screen (usually only business class had this), which the flight crew calls the 'personal entertainment system'. You are presented with a bunch of menus, where you can pick a movie or TV series to watch on demand. And there's a lot of stuff to watch on demand (also LOTR 1, 2 *and* 3). You go through the menus with some buttons on the handset (which is also a phone). This completely system sort of impressed me. Unfortunately we won't have it on the plane back to the Netherlands.

<p>
<center>
<img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/777-entertainment.jpg"><br>
<font size="small"><i>The main menu of the 'personal entertainment system'.</i></font>
</center>
<p>

However the system is not 100% stable yet, since my 'personal entertainment system' crashed during the flight. Hopefully this doesn't happen with the more important computer systems on the plane. Cool thing is that the 'personal entertainment system' apparently runs Linux, since I saw Tux the penguin (the framebuffer logo) above a bunch of bootup messages when my 'personal entertainment system' rebooted.

<p>
<center>
<img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/777-crash.jpg"><br>
<font size="small"><i>What apparently didn't happen to the autopilot.</i></font>
</center>
<p>

I preferred to work on my iBook while having the flight tracking up on the FUCKING LCD screen (previously the 'personal entertainment system'). And when I drained my iBooks battery (yes I only have a single battery, though it lasts for 5 hours which is enough for me), I played games on the FUCKING LCD SCREEN. You can even play multiplayer games against the other people on the plane (now isn't that cool?).

<p>
<center>
<img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/almost-there.jpg"><br>
<font size="small"><i>Almost there.</i></font>
</center>
<p>

On the plane I started to play with Cocoa again (since I have to program on OSX since I don't have Linux installed on my iBook anymore). I got the hang of Cocoa, and I am totally starting to like it. I succeeded in teaching myself to learn expose too. So I am busy writing my first big Cocoa application and it seems to be going fine. I might post screenshots soon.

<p>
<center>
<img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/osx-expose.jpg"><br>
<font size="small"><i>I like expose so much.</i></font>
</center>
<p>

One of my brothers is using my iBook to mail with friends. And he said to me that he is really liking 'what is running on my laptop'. He has a Windows XP box at home (and he is tired of all problems with that machine). I do know that my next desktop will be an Apple (probably the Powermac), and when my iBook dies, my next laptop will be a Powerbook. When my iPod dies, I want one of the new iPods. They rock.
<p>
Linux for the Desktop is just not ready, has a lot of problems and I am just too tired of it to help fixing them. I will continue to run Linux on servers, since it is perfect for that. But I am going to spend a little more money to get a solid, good-working desktop machine now. And when I am bored, I guess I get to write OSX software. Or hack on my old Linux box (still need to finish whirl2il). We will see.
<p>
I guess I am converted (well, am going to be soonish). Sorry to all GNOME people.

<p>
<center>
<img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/sfo-outside.jpg"><br>
<font size="small"><i>Waiting on the hotel shuttle.</i></font>
</center>
<p>

I will enjoy the Sun here for another week. Then we head back. I just like the Netherlands better (probably because I have friends there, can drink alcohol legally there, and can go out to concerts and bars etc.). However, on vacation you can shoot a lot of nice photos. And panoramas. Though I should have thought of making panoramas earlier.

<p>
<center>
<a href="http://www.math.leidenuniv.nl/~kris/blaat/panaroma-sf-skyline.jpg">
<img src="http://www.math.leidenuniv.nl/~kris/.oops/lj/panorama.jpg"></a><br>
<font size="small"><i>SF from Pier 7.</i></font>
</center>
<p>

Oh, I also bought CDs:<br>
Turbonegro -- Ass Corba<br>
Lars Frederiksen and The Bastards -- Viking<br>
Anti-Flag -- The Terror State<br>
Sparta -- Porcelain (for only $13.99, it is 21 euros in .nl).<br></p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/34948.html">July 31, 2004 10:34 PM</a></em>
</p>





<h2>July 20, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/34618.html">whirl2il.</a></h4>
<p>
Okay, lots of peer pressuring got me into releasing a little whirl2il tarball. Note that it is a snapshot of a proof-of-concept prototype (are you scared yet?). You can find it here: <a href="http://www.math.leidenuniv.nl/~kris/whirl2il/">http://www.math.leidenuniv.nl/~kris/whirl2il/</a>.<br><br>Please do not mail me if you have any problems with it. I know that it doesn't work.<br><br><br>In other news, I've been busy slacking, working and shopping (basically visiting Dutch cities. it was okay.).<br><br>Oh, and I want to buy <a href="http://feedback.nl/pixcash/ZmVuZGVyLXNwbGF0dGVyLXN0cmF0NTAwNTAwMTAwZmZmYWNk.jpg">this guitar</a>.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/34618.html">July 20, 2004 09:33 PM</a></em>
</p>





<h2>July 16, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/34384.html">.nl and its public transit.</a></h4>
<p>
So last night I arrived at the train station in my home town around 00:20 am. There usually goes a last bus to my home around 00:25. Yesterday I found out that that last bus does not drive in the summer schedule. Ended up spending 12 euros on a taxi (bleh), at least I got home safely.<br><br>Last Wednesday I was in Amsterdam and in the afternoon it started raining. I got really wet. The last few weeks I have been working. Also went to this years <a href="http://www.parkpop.nl/">ParkPop</a>, it was okay. Looking forward to <a href="http://www.lowlands.nl/">Lowlands</a>, bought my ticket last week.<br><br>I have been playing with <a href="http://gstreamer.freedesktop.org/">GStreamer</a> and it is looking really promising. Will probably play some more with it in the next few weeks.<br><br>CDs bought in the last few week: Poison Idea -- Feel the Darkness, Rancid -- Rancid, Rancid -- Let's Go, Thursday -- War All the Time, Beatsteaks -- Smacksmash, Face Tomorrow -- For Who You Are, Beasty Boys -- Through the 5 Boroughs, Tool -- Opiate, Punk-O-Rama 9.<br><br>Apparently I am going to be in an airplane next Friday.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/34384.html">July 16, 2004 09:27 PM</a></em>
</p>





<h2>July 13, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/34280.html">iPod.</a></h4>
<p>
After my linux desktop decided to freeze while transferring songs to the iPod and later gtkpod cleared my iPod database when I pressed sync (ok, that's my fault, but I would have expected some kind of warning). I decided to transfer my windows iPod into a Mac iPod.<br><br>I can update my iPod from iTunes now, without being afraid that the firewire drivers will screw up (on Linux I sometimes got scary sbp2 out of packets errors). I am really happy now.<br><br><br>[a usual update will follow later this week. yes i am too late and this thing is outdated].</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/34280.html">July 13, 2004 12:22 AM</a></em>
</p>





<h2>June 21, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<p>
Ok, so I upgraded to mono beta 3 and made whirl2il work with the GAC. I can also get it to generate .config files (which it actually does right now), but I am not sure if I want it to do that. When I feel like it, I should focus on getting structs working.<br><br>and today i broke a B and didnt have a spare and that made me pissed.<br><br><br>Decided not to go to GUADEC, since it is just too expensive. I can't really justify spending 450 euro on a 3 day conference. I mean, you can almost buy a <a href="http://www.fender.com/">Fender</a> for that amount of money. But I need to buy some other things which are on my wishlist first.<br><br>So last week I made my last exam (on Tue), which fortunately went well (I came home drunk on Monday evening since the beer was ridiculously cheap. oops.). On Tue we did some bbq in Leiden and watched the Dutch play a draw against Germany. And on Thu I saw an old friend again and we had, euh, beer. Finally played some pool again on Friday (didn't do that for months), very weird things happened and I never managed to win.<br><br>Oh oh oh and we had our totally fun and crazy bbq party last Saturday. The weather was okayish at first, but we ended up eating with a lot of RAIN. After that we watched the soccer match, and I got really angry at the dumb and blind referee. We also ended up "jamming" and hanging out in the rain outside.<br><br>Today I had to ditch the Pioneer DVJ X1 (a DVD turntable, yes really) from my wishlist, since it apparently costs 3500 euro. Oops.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/33797.html">June 21, 2004 07:57 PM</a></em>
</p>





<h2>June 13, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/33721.html">.</a></h4>
<p>
Busy week, but did manage to get some whirl2il hacking done. Since for pinvoke methods you need to know in which dll/solib the symbol is, I wrote code which scans the dlls for symbols. So most pinvoke methods/calls show up correctly now, without any manual tweaks needed. Other things which are working now are loading method pointers on the stack (so now you it can translate programs using 'g_signal_connect' for example), and tweaks so we can compile/translate C programs consisting of multiple C files.<br><br>Progress can be seen <a href="http://www.math.leidenuniv.nl/~kris/blaat/whirl2il-Jun12.txt">here</a>. I hope to have a more exciting example up soon. I also hope to get a preview out within two weeks.<br><br><br>--- [boring personal part of the blog follows].<br><br>As for parties/concerts and such, last Saturday (not yesterday) I went to another party with techhouse music. They even had this band who played this kind of music live, which was pretty cool to see. On Tuesday I had an exam, which sucked, so I left after 15 minutes. And I wasn't the only one (:. Had beer and lots of random food at a friend's place, after that I went to <a href="http://www.tivoli.nl/">Tivoli</a> (yes, their site totally sucks on Linux) in Utrecht to see <a href="http://www.thedistillers.com/">The Distillers</a> (who totally rocked). The support act were 'The Bips', a Dutch band, which were cool too. Last Thursday I saw three local bands in a small student place, it ended up being a big, fun party. We also had a good amount of beer, since it was cheap ;). On Friday I went for food and drinking afterwards with friends. And on Saturday I decided to stay home, a week like this (I also worked on Wed and Thu) tires you.<br><br>Today I slept even more and made a start with sorting out the two meters of paperwork here. It is really needed.<br><br>Apple announced the new G5s. I think I am going to get one of the new dual 1.8s, with more RAM, bigger disk and fatter graphicscard. Good thing students get a 5% discount.<br><br>Oh, and I found the new album of <a href="http://www.spartamusic.com/">Sparta</a> somewhere on the web. It's an amazing album. Apparently it wasn't even officially released when I found it. But I should totally buy it once I find it in the stores.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/33721.html">June 13, 2004 07:21 PM</a></em>
</p>





<h2>June 04, 2004</h2>




<h3><a href="http://www.livejournal.com/users/inverted_tree/" title="Kris' journal">Kristian Rietveld</a></h3>


<h4><a href="http://www.livejournal.com/users/inverted_tree/33396.html">not enough time part III</a></h4>
<p>
Apparently this is becoming some kind of problem. But it's all my fault. Yay.<br><br>Last Saturday I went to some party at <a href="http://www.waterfront.nl/">Waterfront</a>. They were playing loads of relaxing techhouse stuff, which rocked. I slept on Sunday and studied a little bit. Continued doing that on Monday. Made the exam on Tuesday, mixed feelings. Apparently I passed. I passed for all exams I've made since April (but I ignored and didn't attend one exam, which is a good way to up your stats).<br><br>On Wednesday I went to this music store called <a href="http://www.feedback.nl/">Feedback</a>. If you are close to the one in Rotterdam, you should really go check it out, since it's a cool store. I bought fx for my guitar (Boss DS-1 and SD-1 for those interested), a bunch of cables and a new guitar strap (since my guitar kept falling out of my old one. which sucked). After that I hung out with friends.<br><br>On Thurdays I went to work and went drinking with a friend for a bit afterwards. On Friday I went to work too. At work I frequently become more frustrated with fedora. Don't know why. Glad that I am running debian at home. Now I am tired.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/33396.html">June 04, 2004 05:53 PM</a></em>
</p>











<h4><a href="http://www.livejournal.com/users/inverted_tree/33127.html">good bye advogato.</a></h4>
<p>
Ok, so I guess advogato is not going to return anytime soon. So I figured I would continue blogging about code here.<br><br>whirl2il is progressing, somewhere this week I decided to preprocess and compile this into WHIRL:<br><br><pre>
#include &lt;gtk/gtk.h&gt;

int main (int argc, char **argv)
{
        GtkWidget *window;

        gtk_init (&argc, &argv);

        window = gtk_window_new (GTK_WINDOW_TOPLEVEL);
        gtk_widget_set_usize (window, 320, 240);
        gtk_widget_show_all (window);

        gtk_main ();

        return 0;
}
</pre><br><br>Then I ran it through whirl2il, and did some manual tweaks (the main change was replacing "libc" with "libgtk-win32-2.0-0.dll" where appropriate); which resulted into <a href="http://www.math.leidenuniv.nl/~kris/blaat/ilcode-Jun01.txt">this</a>. If you assemble it, it will actually run without any problems. (Try it, if you don't believe it).<br><br>Things which I need to fix up next:<br><ul><br>  <li>Type conversion. I didn't really implement this yet.</li><br>  <li>Clean up the code. It is sort of a mess right now.</li><br>  <li>Implement ability to reference assemblies (so we can get the P/Invokes right).</li><br>  <li>Support programs existing of multiple source files.</li><br>  <li>Try to get bigger programs working ;)</li><br></ul><br>That will keep me busy for now; will probably have time to continue hacking somewhere after Tuesday's exam.</p>
<p>
<em><a href="http://www.livejournal.com/users/inverted_tree/33127.html">June 04, 2004 05:40 PM</a></em>
</p>
</div>

<? gnome_foot() ?>
</body>
</html>
