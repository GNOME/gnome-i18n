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
#                  Keld Simonsen

#use strict; #fix the file, so this can be enabled

$grey = 0;    #odd/even strings
$totals = 0;  #total strings in all modules from modinfo.
$now = time;

%modulenames = (
"da", "Modul",
"hu", "Modul",
"ro", "Modul",
"ru", "íÏÄÕÌØ",
"sv", "Modul",
"tr", "Modül",
"uk", "íÏÄÕÌØ"
 ); 

%translated = (
"da", "Oversat",
"hu", "Lefordított",
"ro", "traduse",
"ru", "ðÅÒÅ×ÅÄÅÎÏ",
"sv", "&Ouml;versatt",
"tr", "Tercüme edilmiþ",
"uk", "ðÅÒÅËÌÁÄÅÎÏ"
);

%fuzzy = (
"da", "Uklart",
"hu", "Pontatlan",
"ro", "neclare",
"ru", "îÅÞÅÔËÏ",
"sv", "Luddigt",
"tr", "Tam tutmayan",
"uk", "îÅÞ¦ÔËÏ"
);

%untranslated = (
"da", "Uoversat",
"hu", "Fordítatlan",
"ro", "netraduse",
"ru", "îÅÐÅÒÅ×ÅÄÅÎÏ",
"sv", "O&ouml;versatt",
"tr", "Tercüme edilmemiþ",
"uk", "îÅÐÅÒÅËÌÁÄÅÎÏ"
);

%strings = (
"da", "strenge",
"hu", "karakterlánc",
"ro", "stringuri",
"ru", "ÓÏÏÂÝ.",
"sv", "meddelanden",
"tr", "metinler",
"uk", "ÐÏ×¦ÄÏÍ."
);

%details = ( 
"C", "Detail report for ",
"da", "Detaljeret statistik for underst&oslash;ttelse af dansk i Gnome",
"hu", "Részletes jelentés a Magyar (hu) GNOME fordításokról",
"no", "Detaljert rapport for oversettelse av GNOME til norsk",
"ru", "ðÏÄÒÏÂÎÙÊ ÏÔÞÅÔ Ï ÓÏÓÔÏÑÎÉÉ ÐÅÒÅ×ÏÄÁ Gnome ÎÁ ÒÕÓÓËÉÊ",
"sv", "Detaljerad rapport f&ouml;r st&ouml;d av svenska i GNOME",
"tr", "Geliþmiþ durum raporu : ",
"uk", "äÅÔÁÌØÎÉÊ Ú×¦Ô ÐÒÏ ÓÔÁÎ ÐÅÒÅËÌÁÄÕ GNOME ÎÁ ÕËÒÁ§ÎÓØËÕ"
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


$htmldir="/home/keld/www/gnome";

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
    print TABLE "<td align=right><b>$tot</b></td>\n";
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
    print TABLE "gnome-libs: gnome-libs-1-0 <br>\n" ;
    print TABLE "gnome-core: gnome-core-1-2 <br>\n" ;
    print TABLE "gnome-applets: gnome-applets-1-2 <br>\n" ;
    print TABLE "gnome-pim: gnome-pim-1-2 <br>\n" ;
    print TABLE "oaf: oaf-stable-0-6 <br>\n" ;
    print TABLE "ORBit: orbit-stable-0-5 <br>\n" ;
    print TABLE "gtk+: gtk-1-2 <br>\n" ;
    print TABLE "glib: glib-1-2 <br>\n" ;
    print TABLE "control-center: control-center-1-0 <br>\n" ;
    print TABLE "libgtop: LIBGTOP_STABLE_1_0 <br>\n" ;
    print TABLE "gnome-xml: LIB_XML_1_BRANCH <br>\n" ;
    print TABLE "gconf: gconf-1-0 <br>\n" ;


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
	$percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]}) ; }
	$strings = $strings{$lang} || "strings";
	$trns = ("${$langmod{$mod}->{$lang}->[0]}") ? "${$langmod{$mod}->{$lang}->[0]} $strings" : "-";
	$fuzzy = ("${$langmod{$mod}->{$lang}->[1]}") ? "${$langmod{$mod}->{$lang}->[1]} $strings" : "-";
	$untrns = ("${$langmod{$mod}->{$lang}->[2]}") ? "${$langmod{$mod}->{$lang}->[2]} $strings" : "-";
        $modfile = "$lang.po";
        if ($percent eq 0){ $modfile = "$mod.pot"; } else { $modfile = "$lang.po"; }
	print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left><a href=\"$modpath{$mod}/$modfile\">$mod</a></td><td align=right>$trns</td><td align=right>$fuzzy</td><td align=right>$untrns</td>\n" .
		     "<td align=right>$percent %</td>\n";
        $stringstot += ${$modinfo{$mod}->[0]};
        $trnstot += $trns; 
        $fuzzytot += $fuzzy; 
        $untrnstot += $untrns; 

    }
    $percent = sprintf("%.2f",$trnstot*100/$stringstot);
    print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>Total</td
             ><td align=right>$trnstot $strings</td
             ><td align=right>$fuzzytot $strings</td
             ><td align=right>$untrnstot $strings</td>\n" .
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
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp*&nbsp</font>" : "";
	$string.= $mod;
    } else {
	$string = "<td align=left>";
	$string.= $mod;
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp*&nbsp</font>" : "";
	$string.= ($release gt 0) ? " [$release]" : "";
    }
    
    print TABLE "$string</td>\n";	
    }
    
    
sub printlang{
    my ($mod, $lang) = @_;
    my ($percent, $color);
    
    $percent = 0;
    if (${$modinfo{$mod}->[0]}) {
    $percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]}); }
    if ($percent eq 0){
    print TABLE "<td align=right><a href=\"$modpath{$mod}/$mod.pot\">-</a></td>\n";
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
            <a href=\"$modpath{$mod}/$lang.po\">
            <font color='$color'>
            $percent</font></a></td>\n";
    }
}
