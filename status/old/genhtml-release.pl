#!/usr/bin/perl  

#
#  The GNOME Statustable Generator - HTML part
#
#  Copyright (C) 2000 Free Software Foundation.
#
#  This library is free software; you can redistribute it and/or
#  modify it under the terms of the GNU General Public License as
#  published by the Free Software Foundation; either version 2 of the
#  License, or (at your option) any later version.
#
#  This script is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
#  General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this library; if not, write to the Free Software
#  Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
#
#  Authors:        Frob <frob@df.ru>
#  With help from: Kenneth Christiansen <kenneth@gnu.org>
#		   Kjartan Maraas 
#		   Dand
#		   Keld Simonsen
#		   Carlos Perelló Marín <carlos@gnome-db.org>

#use strict; #fix the file, so this can be enabled

$grey = 0;    #odd/even strings
$totals = 0;  #total strings in all modules from modinfo.
$now = time;

%modulenames = (
"da", "Modul",
"de", "Paket",
"el", "Ğáêİôï",
"es", "M&oacute;dulo",
"fi", "Paketti",
"hu", "Modul",
"ja", "¥â¥¸¥å¡¼¥ë",
"no", "Modul",
"ro", "Modul",
"ru", "íÏÄÕÌØ",
"sv", "Modul",
"tr", "Modül",
"uk", "íÏÄÕÌØ",
"wa", "module"
 ); 

%translated = (
"da", "Oversat",
"de", "&Uuml;bersetzt",
"el", "Ìåôáöñáóìİíá",
"es", "Traducidas",
"fi", "K&auml;&auml;nnetty",
"fr", "Traduites",
"hu", "Lefordított",
"ja", "ËİÌõºÑ",
"nl", "Vertaald",
"no", "Oversatt",
"ro", "traduse",
"ru", "ğÅÒÅ×ÅÄÅÎÏ",
"sv", "&Ouml;versatt",
"tr", "Tercüme edilmiş",
"uk", "ğÅÒÅËÌÁÄÅÎÏ",
"wa", "rato&ucirc;rn&eacute;s"
);

%fuzzy = (
"da", "Uklart",
"de", "Ungenau",
"el", "ÁóáöŞ",
"es", "Difusas",
"fi", "Ep&auml;selvi&auml;",
"fr", "Floues",
"hu", "Pontatlan",
"ja", "¥Õ¥¡¥¸¡¼",
"nl", "Wazig",
"no", "Uferdig",
"ro", "neclare",
"ru", "îÅŞÅÔËÏ",
"sv", "Luddigt",
"tr", "Tam tutmayan",
"uk", "îÅŞ¦ÔËÏ",
"wa", "&laquo;fuzzy&raquo;"
);

%untranslated = (
"da", "Uoversat",
"de", "Un&uuml;bersetzt",
"el", "AìåôÜöñáóôá",
"es", "Sin traducir",
"fi", "K&auml;&auml;nt&auml;m&auml;tt&auml;",
"fr", "Non traduites",
"hu", "Fordítatlan",
"ja", "ESC$(BL$LuESC(B",
"nl", "Onvertaald",
"no", "Uoversatt",
"ro", "netraduse",
"ru", "îÅĞÅÒÅ×ÅÄÅÎÏ",
"sv", "O&ouml;versatt",
"tr", "Tercüme edilmemiş",
"uk", "îÅĞÅÒÅËÌÁÄÅÎÏ",
"wa", "n&eacute;n co rato&ucirc;rn&eacute;s"
);

%strings = (
"da", "strenge",
"de", "Meldungen",
"el", "AëöáñéèìçôéêÜ",
"es", "Mensajes",
"fi", "rivi&auml;",
fr", "chaînes",
"hu", "karakterlánc",
"ja", "Ê¸»úÎó",
"nl", "Meldingen",
"no", "Strenger",
"ro", "stringuri",
"ru", "ÓÏÏÂİ.",
"sv", "meddelanden",
"tr", "metinler",
"uk", "ĞÏ×¦ÄÏÍ.",
"wa", "messaedjes"
);

#%totals = (
#"de", "Gesamt",
#"el", "ÓõíïëéêÜ",
#"es", "Totales",
#"fi", "Yhteens&auml;",
#"hu", "Összes",
#"ja", "¥È¡¼¥¿¥ë",
#"nl", "Totaal",
#"no", "Total",
#"ru", "÷ÓÅÇÏ",
#"sv", "Totalt",
#"tr", "Toplam",
#"uk", "÷ÓØÏÇÏ",
#"wa", "tot&aring;"
#);

%details = ( 
"C", "Detailed report for ",
"ca", "Detailed report for Catalan (ca) GNOME translations",
"cs", "Detailed report for Czech (cs) GNOME translations",
"da", "Detaljeret statistik for underst&oslash;ttelse af dansk i GNOME<br>Detailed report for Danish (da) GNOME translations",
"de", "Detaillierte Statistik f&uuml;r deutsche GNOME &Uuml;bersetzung<br>Detailed report for German (de) GNOME translations",
"el", "ËåğôïìåñŞò áíáöïñÜ ãéá ôéò Åëëçíéêİò (el) ìåôáöñÜóåéò ôïõ GNOME",
"en_GB", "Detailed report for English, British (en_GB) GNOME translations",
"es", "Informe detallado de la traducci&oacute;n de GNOME al español (es)",
"et", "Detailed report for Estonian (et) GNOME translations",
"eu", "Detailed report for Basque (eu) GNOME translations",
"fi", "Yksityiskohtainen raportti Suomen (fi) GNOME k&auml;&auml;nn&ouml;ksist&auml;",
"fr", "Rapport détaillé pour les traductions françaises (fr) GNOME",
"ga", "Detailed report for Irish (ga) GNOME translations",
"gl", "Detailed report for Galician (gl) GNOME translations",
"hr", "Detailed report for Croatian (hr) GNOME translations",
"hu", "Részletes jelentés a Magyar (hu) GNOME fordításokról",
"is", "Detailed report for Icelandic (is) GNOME translations",
"it", "Detailed report for Italian (it) GNOME translations",
"ja", "GNOME¤ÎÆüËÜ¸ìÌõ¤Ë´Ø¤¹¤ë¾ÜºÙ¥ì¥İ¡¼¥È<br>(Detailed report for Japanese(ja) translations",
"ko", "Detailed report for Korean (ko) GNOME translations",
"lt", "Detailed report for Lithuanian (lt) GNOME translations",
"nl", "Gedetailleerde rapportage voor de Nederlandse (nl) GNOME vertalingen",
"no", "Detaljert rapport for oversettelse av GNOME til norsk",
"pl", "Detailed report for Polish (pl) GNOME translations",
"pt", "Detailed report for Portuguese (pt) GNOME translations",
"pt_BR", "Detailed report for Portuguese, Brazilian (pt_BR) GNOME translations",
"ro", "Detailed report for Romanian (ro) GNOME translations",
"ru", "ğÏÄÒÏÂÎÙÊ ÏÔŞÅÔ Ï ÓÏÓÔÏÑÎÉÉ ĞÅÒÅ×ÏÄÁ GNOME ÎÁ ÒÕÓÓËÉÊ<br>Detailed report for Russian (ru) GNOME translations",
"sk", "Detailed report for Slovak (sk) GNOME translations",
"sl", "Detailed report for Slovenian (sl) GNOME translations",
"sr", "Detailed report for Serbian (sr) GNOME translations",
"sv", "Detaljerad rapport f&ouml;r st&ouml;d av svenska i GNOME<br>Detailed report for Swedish (sv) GNOME translations",
"tr", "Türkçe GNOME tercümeleri için gelişmiş durum raporu<br>Detailed report for Turkish (tr) GNOME translations",
"uk", "äÅÔÁÌØÎÉÊ Ú×¦Ô ĞÒÏ ÓÔÁÎ ĞÅÒÅËÌÁÄÕ GNOME ÎÁ ÕËÒÁ§ÎÓØËÕ<br>Detailed report for Ukrainian (uk) GNOME translations",
"ru", "ğÏÄÒÏÂÎÙÊ ÏÔŞÅÔ Ï ÓÏÓÔÏÑÎÉÉ ĞÅÒÅ×ÏÄÁ Gnome ÎÁ ÒÕÓÓËÉÊ",
"wa", "Sipepieus rapoirt po les walons (wa) rato&ucirc;rnaedjes di GNOME"
);

%percent_colors = (
"100%", "#00bb00",          
">95%", "#8470ff",  
">90%", "#8a2be2",
">80%", "#1e90ff",          
">70%", "#9400d3",
">60%", "#9932cc",
">50%", "#da70d6",
"other", ""
);

%changed_color = (
"0", "black",
"1", "red",
"2", "green",
"3", "blue",
"4", "yellow",
"5", "grey"
);


$htmldir="/home/kmaraas/cvs/gnome/web-devel-2/content/projects/gtp/status/release";

sub printmodule;
sub printlang;

## Files reading 
if (open (MODINFO, "$htmldir/modinfo.dat")){                                    
    while (<MODINFO>) {                                                         
    chomp;                                                                  
    ($mod, @info) = split(/,/);                                                     
    $index = 0;

$path= $mod;
if ($mod=~/\/po$/){
    $tmp = $`;
    $mod = ($mod=~/helix-install/) ? "rpm (helix-install)" : $tmp;
    } elsif ($mod=~/extra-po\//){
	$mod = $';
    }

    foreach $info (@info){
	${$modinfo{$mod}->[$index]} =  $info;                  
    $index++;
    }
    $modpath{$mod} = $path;
    $totals+=${$modinfo{$mod}->[0]}
    }
}    

if (open (LANGINFO, "$htmldir/langinfo.dat")){                                    
    while (<LANGINFO>) {                                                         
    chomp;                                                                  
    ($lang, @info) = split(/,/);                                                     
    $index = 0;
    foreach $info (@info){
	${$langinfo{$lang}->[$index]} =  $info;                  
    $index++;
    }
    }
}    

if (open (LANGMOD, "$htmldir/langmod.dat")){                                    
    while (<LANGMOD>) {                                                         
    chomp;                                                                  
    ($lang, $mod, @info) = split(/,/);                                                     

if ($mod=~/\/po$/){
    $tmp = $`;
    $mod = ($mod=~/helix-install/) ? "rpm (helix-install)" : $tmp;
    } elsif ($mod=~/extra-po\//){
	$mod = $';
    }

    $index = 0;
    foreach $info (@info){
	${$langmod{$mod}->{$lang}->[$index]} =  $info;                  
    $index++;
    }
    }
}    


open  TABLE, ">$htmldir/status.shtml" || die "can't open $htmldir/status.shtml";
open  DATE, "date '+%a %Y-%m-%d %T %z' |" || die "can't date";
$date = <DATE> ; close DATE ;
print TABLE "<html><head><title>Gnome translation status - $date</title></head>\n";
print TABLE "<body><h1>Gnome translation status<br>$date</h1>\n";
print TABLE "<table cellpadding=1 cellspacing=1 border=1 width=\"90%\"><tr align=center><td></td>\n";

#First string with langs
foreach $lang (sort keys %langinfo){
    $langs_red = substr($lang, 0, 5);
    $link = "$details_" . $langs_red . ".shtml";
    print TABLE "<td><a href=\"$link\">$langs_red</a></td>\n";
}
    print TABLE "<td></td></tr>\n";

foreach $mod (sort keys %modinfo){
    $grey++;
    $trbg = (($gray++) % 2) ? '#deded5' : '#c5c2c5';	
    print TABLE "<tr bgcolor='$trbg'  align=center>";
    printmodule($mod, "L");  
    foreach $lang (sort keys %langinfo){
	printlang($mod, $lang);
    }
    printmodule($mod, "R");
    print TABLE "</tr>";
}
    
# Totals
    print TABLE "<tr align=center bgcolor=\"#ffd700\"><td><b>Totals:</b></td>\n";
foreach $lang (sort keys %langinfo){
    $tot = int(${$langinfo{$lang}->[0]}*100/$totals);
    print TABLE "<td align=right><b>$tot%</b></td>\n";
}
    print TABLE "<td></td></tr>";

# Last langs string
    print TABLE "<tr align=center><td></td>";
    foreach $lang (sort keys %langinfo){
        $langs_red = substr($lang, 0, 5);
        $link = "$details_" . $langs_red . ".shtml";
        print TABLE "<td><a href=\"$link\">$langs_red</a></td>\n";
    }
    print TABLE "<td></td></tr></table>";

    print TABLE "<p>Total number of translatable strings in above modules: $totals<p>\n";
    print TABLE "$date\n<br><br>\n" ;

    print TABLE "Stable branches with tags: <br>\n" ;
    print TABLE " <br>\n" ;

    print TABLE "</body></html>\n" ;
    
# status printed.

# details.shtml
foreach $lang (sort keys %langinfo){
    $link = "$details_" . substr($lang, 0, 5) . ".shtml";

    open  TABLE2, ">$htmldir/$link" || die "can't open $htmldir/$link";
    $detail = $details{$lang} || $details{"C"} . $lang;
    print TABLE2 "<a name=\"$lang\"><b>$detail</b></a><br>";
    print TABLE2 "<table cellpadding=1 cellspacing=1 border=1 >";

    $modulename = $modulenames{$lang} 		|| "Module";
    $translated = $translated{$lang} 		|| "Translated";
    $fuzzy = $fuzzy{$lang} 			|| "Fuzzy";
    $untranslated = $untranslated{$lang} 	|| "Untranslated";

    print TABLE2 "<tr bgcolor=\"#ffd700\"><th>$modulename</th>"
		."<th>$translated</th><th>$fuzzy</th>"
		."<th>$untranslated</th><th>%</th>";

    $stringstot =0; $trnstot =0; $fuzzytot= 0; $untrnstot=0 ;
    print TABLE2 "$date<br>\n" ;
    foreach $mod (sort keys %modinfo){
        $grey++;
        $trbg = (($gray++) % 2) ? '#deded5' : '#c5c2c5';
	$percent = 0;
	if (${$modinfo{$mod}->[0]}) {
		$percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
	}
	$strings = $strings{$lang} || "strings";
	$trns = ("${$langmod{$mod}->{$lang}->[0]}") ? "${$langmod{$mod}->{$lang}->[0]} $strings" : "-";
	$fuzzy = ("${$langmod{$mod}->{$lang}->[1]}") ? "${$langmod{$mod}->{$lang}->[1]} $strings" : "-";
	$untrns = ("${$langmod{$mod}->{$lang}->[2]}") ? "${$langmod{$mod}->{$lang}->[2]} $strings" : "-";
	
	$_ = $mod;
	s/\//-/;
	s/-po//;
	$newname = $_;

	if ($percent eq 0) {
	    print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
	    <a href=\"po/$newname.pot\">$mod</a></td><td align=right>$trns</td>
	    <td align=right>$fuzzy</td><td align=right>$untrns</td>" .
	    "<td align=right>$percent%</td>";
	}else{
	    print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
	    <a href=\"po/$newname-$lang.po\">$mod</a></td><td align=right>
	    $trns</td><td align=right>$fuzzy</td><td align=right>$untrns</td>" .
	    "<td align=right>$percent%</td>";
	}
	$stringstot += ${$modinfo{$mod}->[0]};
	$trnstot += $trns; 
	$fuzzytot += $fuzzy; 
	$untrnstot += $untrns; 
	
    }
    $percent = sprintf("%.2f",$trnstot*100/$stringstot);
    print TABLE2 "<tr bgcolor=\"#ffd700\" align=center><td align=left>Total</td>
    	<td align=right>$trnstot $strings</td>
	<td align=right>$fuzzytot $strings</td>
	<td align=right>$untrnstot $strings</td>\n" .
	"<td align=right>$percent%</td>\n";
    print TABLE2 "</table></center>";
}

## Subroutines code
sub printmodule{
    my ($mod, $align) = @_;
    my ($changed, $release, $string, $trbg);
    
    $changed = "";
    $changed = $changed_color{int(($now - ${$modinfo{$mod}->[1]})/86400)};
    $release = int((${$modinfo{$mod}->[2]} - $now)/86400);

    if ($align eq "L") {
	$string = "<td align=right>";
	$string.= ($release gt 0) ? "[$release] " : "";
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp;*&nbsp;</font>" : "";
	$string.= $mod;
    } else {
	$string = "<td align=left>";
	$string.= $mod;
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp;*&nbsp;</font>" : "";
	$string.= ($release gt 0) ? " [$release]" : "";
    }
    
    print TABLE "$string</td>\n";	
    }
    
    
sub printlang{
    my ($mod, $lang) = @_;
    my ($percent, $color);
    
    $percent = 0;
    if (${$modinfo{$mod}->[0]}) {
    	$percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
    }

    $_ = $mod;
    s/\//-/;
    s/-po//;
    $newname = $_;

    if ($percent eq 0){
        print TABLE "<td align=right><a href=\"po/$newname.pot\">-</a></td>\n";
    }else{
    if ($percent == 100){		$color = $percent_colors{"100%"};	
	} elsif ($percent > 95){	$color = $percent_colors{">95%"};	
	} elsif ($percent > 90){	$color = $percent_colors{">90%"};
	} elsif ($percent > 80){	$color = $percent_colors{">80%"};
	} elsif ($percent > 70){	$color = $percent_colors{">70%"};
	} elsif ($percent > 60){	$color = $percent_colors{">60%"};
	} elsif ($percent > 50){	$color = $percent_colors{">50%"};
	} elsif ($percent >3){		$per =  sprintf("%x",$percent*5);
					$color = "#ff00" .  "$per";
	} else {			$per =  sprintf("%x",$percent*5);
					$color = "#ff000" . "$per"; }

	print TABLE "<td align=right>
		<a href=\"po/$newname-$lang.po\">
		<font color='$color'>
		$percent%</font></a></td>\n";
    }
}
