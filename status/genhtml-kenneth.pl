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

#use strict; #fix the file, so this can be enabled

$grey = 0;    #odd/even strings
$totals = 0;  #total strings in all modules from modinfo.
$now = time;

%modulenames = (
"da", "Modul",
"de", "Paket",
"el", "ÐáêÝôï",
"es", "M&oacute;dulo",
"fi", "Paketti",
"ro", "Modul",
"ru", "íÏÄÕÌØ",
"sv", "Modul",
"tr", "Modül",
"uk", "íÏÄÕÌØ"
 ); 

%translated = (
"da", "Oversat",
"de", "&Uuml;bersetzt",
"el", "ÌåôáöñáóìÝíá",
"es", "Traducidas",
"fi", "K&auml;&auml;nnetty",
"ro", "traduse",
"ru", "ðÅÒÅ×ÅÄÅÎÏ",
"sv", "&oslash;versatt",
"tr", "Tercüme edilmiþ",
"uk", "ðÅÒÅËÌÁÄÅÎÏ"
);

%fuzzy = (
"da", "Uklart",
"de", "Ungenau",
"el", "ÁóáöÞ",
"es", "Difusas",
"fi", "Ep&auml;selvi&auml;",
"ro", "neclare",
"ru", "îÅÞÅÔËÏ",
"sv", "oklart",
"tr", "Tam tutmayan",
"uk", "îÅÞ¦ÔËÏ"
);

%untranslated = (
"da", "Uoversat",
"de", "Un&uuml;bersetzt",
"el", "AìåôÜöñáóôá",
"es", "Sin traducidas",
"fi", "K&auml;&auml;nt&auml;m&auml;tt&auml;",
"ro", "netraduse",
"ru", "îÅÐÅÒÅ×ÅÄÅÎÏ",
"sv", "o&oslash;versatt",
"tr", "Tercüme edilmemiþ",
"uk", "îÅÐÅÒÅËÌÁÄÅÎÏ"
);

%strings = (
"da", "strenge",
"de", "Meldungen",
"el", "AëöáñéèìçôéêÜ",
"es", "Mensajes",
"fi", "Rivi&auml;",
"ro", "stringuri",
"ru", "ÓÏÏÂÝ.",
"sv", "str&auml;ngar",
"tr", "metinler",
"uk", "ÐÏ×¦ÄÏÍ."
);

%details = ( 
"C", "Detail report for ",
"da", "Detaljeret statistik for underst&oslash;ttelse af dansk i Gnome",
"de", "Detaillierte Statistik f&uuml;r deutsche GNOME &Uuml;bersetzung",
"el", "ËåðôïìåñÞò áíáöïñÜ ãéá ",
"es", "Informe detallado de la traducci&oacute;n de GNOME al español (es)",
"fi", "Yksityiskohtainen raportti Suomen GNOME-k&auml;&auml;nn&ouml;ksist&auml;",
"no", "Detaljert rapport for oversettelse av GNOME til norsk",
"ru", "ðÏÄÒÏÂÎÙÊ ÏÔÞÅÔ Ï ÓÏÓÔÏÑÎÉÉ ÐÅÒÅ×ÏÄÁ Gnome ÎÁ ÒÕÓÓËÉÊ",
"sv", "Detaljerad rapport f&Oslash;r Sverige-support i Gnome",
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


$htmldir="/home/kmaraas/cvs/gnome/web-devel-2/content/projects/gtp/status/apps";

sub printmodule;
sub printlang;

## Files reading 
if (open (MODINFO, "$htmldir/modinfo.dat")){                                    
    while (<MODINFO>) {                                                         
    chomp;                                                                  
    ($mod, @info) = split(/,/);                                                     
    $index = 0;

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
print TABLE "<table cellpadding=1 cellspacing=1 border=1 width=\"90%\"><tr align=center><td></td>";

#First string with langs
foreach $lang (sort keys %langinfo){
    $langs_red = substr($lang, 0, 5);
    $link = "$details_" . $langs_red . ".shtml";
    print TABLE "<td><a href=\"$link\">$langs_red</a></td>\n";
}
    print TABLE "<td></td></tr>";

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
    print TABLE "<tr align=center bgcolor=\"#ffd700\"><td><b>Totals:</b></td>";
foreach $lang (sort keys %langinfo){
    $tot = int(${$langinfo{$lang}->[0]}*100/$totals);
    print TABLE "<td><b>$tot%</b></td>\n";
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
    print TABLE scalar localtime(time);    

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

    foreach $mod (sort keys %modinfo){
        $grey++;
        $trbg = (($gray++) % 2) ? '#deded5' : '#c5c2c5';	
	$percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]}) . "%";
	$strings = $strings{$lang} || "strings";
	$trns = ("${$langmod{$mod}->{$lang}->[0]}") ? "${$langmod{$mod}->{$lang}->[0]} $strings" : "-";
	$fuzzy = ("${$langmod{$mod}->{$lang}->[1]}") ? "${$langmod{$mod}->{$lang}->[1]} $strings" : "-";
	$untrns = ("${$langmod{$mod}->{$lang}->[2]}") ? "${$langmod{$mod}->{$lang}->[2]} $strings" : "-";
	print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>$mod</td><td>$trns</td><td>$fuzzy</td><td>$untrns</td>" .
		     "<td>$percent</td>";
    }
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
    
    print TABLE "$string</td>";	
    }
    
    
sub printlang{
    my ($mod, $lang) = @_;
    my ($percent, $color);
    
    $percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
    if ($percent eq 0){
    print TABLE "<td>-</td>";
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

    	print TABLE "<td><font color='$color'>$percent%</font></td>";
    }
}
