#!/usr/bin/perl -w
#
#  The GNOME Statustable Generator - HTML part
#
#  Copyright (C) 2000-2001 Free Software Foundation.
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
#		   Juan Fuentes <juan.fuentes@codetel.net.do>

#use strict; #fix the file, so this can be enabled
# setlocale is needed here, so that the POSIX module don't
# complain of an invalid locale entry.
use POSIX qw(locale_h strftime setlocale LC_ALL LC_CTYPE);

# Ugly, ugly,damn ugly, but it works for now.
# Better now.

my (@info,%langmod,%langinfo,%modinfo,%modpath,%modules);

# no need to call the shell.

my $date   = strftime "%a %Y-%m-%d %T %z",localtime;

###############
# "Constants" #
###############

my $grey     = 0;  #odd/even strings
my $totals   = 0;  #total strings in all modules from modinfo.
my $now      = time;
my $details_ = "";

# used => for readability.

my %modulenames = (
    "da" => "Modul",
    "de" => "Paket",
    "el" => "ÐáêÝôï",
    "es" => "M&oacute;dulo",
    "hu" => "Modul",
    "no" => "Modul",
    "ro" => "Modul",
    "ru" => "íÏÄÕÌØ",
    "sv" => "Modul",
    "tr" => "Modül",
    "uk" => "íÏÄÕÌØ",
    "wa" => "module"
); 

my %translated = (
    "da" => "Oversat",
    "de" => "&Uuml;bersetzt",
    "el" => "ÌåôáöñáóìÝíá",
    "es" => "Traducidos",
    "hu" => "Lefordított",
    "no" => "Oversatt",
    "ro" => "traduse",
    "ru" => "ðÅÒÅ×ÅÄÅÎÏ",
    "sv" => "&oslash;versatt",
    "tr" => "Tercüme edilmiþ",
    "uk" => "ðÅÒÅËÌÁÄÅÎÏ",
    "wa" => "rato&ucirc;rn&eacute;s"
);

my %fuzzy = (
    "da" => "Uklart",
    "de" => "Ungenau",
    "el" => "ÁóáöÞ",
    "es" => "Difusos",
    "hu" => "Pontatlan",
    "no" => "Uferdig",
    "ro" => "neclare",
    "ru" => "îÅÞÅÔËÏ",
    "sv" => "oklart",
    "tr" => "Tam tutmayan",
    "uk" => "îÅÞ¦ÔËÏ",
    "wa" => "&laquo;fuzzy&raquo;"
);

my %untranslated = (
    "da" => "Uoversat",
    "de" => "Un&uuml;bersetzt",
    "el" => "AìåôÜöñáóôá",
    "es" => "Sin traducir",
    "hu" => "Fordítatlan",
    "no" => "Uoversatt",
    "ro" => "netraduse",
    "ru" => "îÅÐÅÒÅ×ÅÄÅÎÏ",
    "sv" => "o&oslash;versatt",
    "tr" => "Tercüme edilmemiþ",
    "uk" => "îÅÐÅÒÅËÌÁÄÅÎÏ",
    "wa" => "n&eacute;n co rato&ucirc;rn&eacute;s"
);

my %strings = (
    "da" => "strenge",
    "de" => "Meldungen",
    "el" => "AëöáñéèìçôéêÜ",
    "es" => "Mensajes",
    "hu" => "karakterlánc",
    "no" => "strenger",
    "ro" => "stringuri",
    "ru" => "ÓÏÏÂÝ.",
    "sv" => "str&auml;ngar",
    "tr" => "metinler",
    "uk" => "ÐÏ×¦ÄÏÍ.",
    "wa" => "messaedjes"
);

my %total = (
    "de" => "Gesamt",
    "el" => "ÓõíïëéêÜ",
    "es" => "Totales",
    "fi" => "Yhteens&auml;",
    "hu" => "Összes",
    "ja" => "¹ç·×",
    "nl" => "Totaal",
    "no" => "Total",
    "ru" => "÷ÓÅÇÏ",
    "sv" => "Totalt",
    "tr" => "Toplam",
    "uk" => "÷ÓØÏÇÏ",
    "wa" => "tot&aring;"
);

my %lasttranslator = (
    "C"  => "Last Translator",
    "es" => "Último traductor"
);

my %status = (
    "C"  => "Status",
    "es" => "Estado"
);

my %available = (
    "C"  => "Available",
    "es" => "Disponible"
);

my %assigned = (
    "C"  => "Assigned",
    "es" => "Asignado"
);

my %unknown = (
    "C"  => "Unknown",
    "es" => "Desconocido"
);

my %details = ( 
    "C"  => "Detail report for ",
    "da" => "Detaljeret statistik for underst&oslash;ttelse af dansk i Gnome",
    "de" => "Detaillierte Statistik f&uuml;r deutsche GNOME &Uuml;bersetzung",
    "el" => "ËåðôïìåñÞò áíáöïñÜ ãéá ",
    "es" => "Informe detallado de la traducci&oacute;n de GNOME al español (es)",
    "hu" => "Részletes jelentés a Magyar (hu) GNOME fordításokról",
    "no" => "Detaljert rapport for oversettelse av GNOME til norsk",
    "ru" => "ðÏÄÒÏÂÎÙÊ ÏÔÞÅÔ Ï ÓÏÓÔÏÑÎÉÉ ÐÅÒÅ×ÏÄÁ Gnome ÎÁ ÒÕÓÓËÉÊ",
    "sv" => "Detaljerad rapport f&Oslash;r Sverige-support i Gnome",
    "tr" => "Geliþmiþ durum raporu : ",
    "uk" => "äÅÔÁÌØÎÉÊ Ú×¦Ô ÐÒÏ ÓÔÁÎ ÐÅÒÅËÌÁÄÕ GNOME ÎÁ ÕËÒÁ§ÎÓØËÕ",
    "wa" => "Sipepieus rapoirt po les walons (wa) rato&ucirc;rnaedjes di GNOME"
);

my %percent_colors = (
    "100%"  => "#00bb00",          
    ">95%"  => "#8470ff",  
    ">90%"  => "#8a2be2",
    ">80%"  => "#1e90ff",          
    ">70%"  => "#9400d3",
    ">60%"  => "#9932cc",
    ">50%"  => "#da70d6",
    "other" => ""
);

my %changed_color = (
    "0" => "black",
    "1" => "red",
    "2" => "green",
    "3" => "blue",
    "4" => "yellow",
    "5" => "grey"
);

@Usage = ("Usage: genhtml.pl [OPTION]...\n".
          "Generate html stats with the stats from status.pl.\n".
	  "\n".
	  "  --help                      Prints this page to standard error.\n".
	  "\n".
	  "  --html-dir <location>       Specifies the directory where will\n".
	  "                              be stored the stats files.\n".
	  "\n".
	  "  --html-po-url <location>    Specifies the url where will\n".
	  "                              be stored the .po/.pot files.\n".
	  "\n".
	  "  --modulesdat <location>     Specifies the file name that has\n".
	  "                              the modules info.\n".
	  "\n");


#############################
# "Subroutines declaration" # 
#############################
sub printmodule;
sub printlang;

####################
# "Initialization" #
####################

setlocale (LC_ALL, "C");

while (@ARGV) {
    if ($ARGV[0] eq "--html-dir") {
        $htmldir = $ARGV[1];
	shift @ARGV;
    } elsif ($ARGV[0] eq "--html-po-url") {
        $htmlpourl = $ARGV[1];
	shift @ARGV;
    } elsif ($ARGV[0] eq "--modules-file") {
        $modulesfile = $ARGV[1];
	shift @ARGV;
    } elsif ($ARGV[0] eq "--help") {
        print STDERR @Usage;
	exit (0);
    } else {
        print STDERR "Error: Unrecognized option '$ARGV[0]'.\n\n";
	print STDERR @Usage;
	exit(1);
    }
    shift @ARGV;
}

if ($htmldir eq "") {
    $htmldir = "/home/carlos/cvs/gnome/web-devel-2/content/projects/gtp/status/";
}

if ($htmlpourl eq "") {
    $htmlpourl = $htmldir."po/";
}

if ($modulesfile eq "") {
    $modulesfile = $htmldir."modules.dat";
}

#
# read dat-files
#
if (open (MODULES, "$modulesfile")){
    while (<MODULES>) {
        chomp;
	@info = split (/,/);
	my $index = 1;
	while ($info[$index]){
	    ${$modules{$info[0]}->[$index-1]} = $info[$index];
	    $index++;
	}
    }
    close (MODULES);
} else {
    print STDERR "Error: Unable to open $modulesfile.\n\n";
    exit(1);
}

if (open (MODINFO, "$htmldir/modinfo.dat")){
    while (<MODINFO>) {
	chomp;
	@info = split (/,/);
	my $index = 1;
	my $mod = $info[0];
	while ($info[$index]){
	    ${$modinfo{$mod}->[$index-1]} = $info[$index];
	    $index++;
	}
	$totals+=${$modinfo{$mod}->[0]}
    }
    close (MODINFO);
}

if (open (LANGINFO, "$htmldir/langinfo.dat")){
    while (<LANGINFO>) {
	chomp;
	@info = split (/,/);
	my $index = 1;
	my $lang = $info[0];
	while ($info[$index]){
	    ${$langinfo{$lang}->[$index-1]} = $info[$index];
	    $index++;
	}
    }
    close (LANGINFO);
}

if (open (LANGMOD, "$htmldir/langmod.dat")){
    while (<LANGMOD>) {
        chomp;
	my ($lang, $mod, @info) = split(/,/);
	my $index = 0;
	my $info;
	foreach $info (@info) {
	    ${$langmod{$mod}->{$lang}->[$index]} = $info;
	    $index++;
	}
    }
    close (LANGMOD);
}    

################
# "Main loops" #
################

my ($lang,$langs_red,$link,$gray,$mod,$trbg,$tot,$trns,$detail,$strings);

# || has a higher precedence so this doesn't work use "or" or parentheses.

open  TABLE, ">$htmldir/status.shtml" or die "can't open $htmldir/status.shtml";
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
    
my $totalname = $total{$lang} || "Total";

# Totals
print TABLE "<tr align=center bgcolor=\"#ffd700\"><th>$totalname</th>\n";
foreach $lang (sort keys %langinfo){
    $tot = int(${$langinfo{$lang}->[0]}*100/$totals);
    print TABLE "<th align=right>$tot%</th>\n";
}
print TABLE "<th>$totalname</th></tr>";

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

my $string = "Stable branches with tags: <br>\n<br>\n" ;
my $branches = "0";

foreach $mod (sort keys %modules) {
    if ("${$modules{$mod}->[2]}" && "${$modules{$mod}->[2]}" ne "HEAD") {
	$string = "$string" . "$mod: ${$modules{$mod}->[2]} <br>\n";
	$branches = "1";
    }
}
if ($branches == 1) {
    print TABLE $string;
}

print TABLE "</body></html>\n" ;
    
# status printed.

# details.shtml

my $percent;

foreach $lang (sort keys %langinfo){
    $link = "$details_" . substr($lang, 0, 5) . ".shtml";
    open  TABLE2, ">$htmldir/$link" or die "can't open $htmldir/$link";
    $detail = $details{$lang} || $details{"C"} . $lang;
    print TABLE2 "<html><head><title>$detail</title></head><body>\n";
    print TABLE2 "<a name=\"$lang\"><b>$detail</b></a><br>";
    print TABLE2 "<table cellpadding=1 cellspacing=1 border=1 >";

    my $modulename = $modulenames{$lang}	|| "Module";
    my $translated = $translated{$lang}		|| "Translated";
    my $fuzzy = $fuzzy{$lang} 			|| "Fuzzy";
    my $untranslated = $untranslated{$lang} 	|| "Untranslated";
    my $status = $status{$lang}			|| "Status";
    my $lasttranslator = $lasttranslator{$lang}	|| "Last Translator";
    my $strings = $strings{$lang}		|| "strings";
    my $totalname = $total{$lang}		|| "Total";

    print TABLE2 "<tr bgcolor=\"#ffd700\"><th>$modulename</th>"
		."<th>$translated</th><th>$fuzzy</th>"
		."<th>$untranslated</th><th>%</th>"
		."<th>$status</th><th>$lasttranslator</th>\n";

    my $stringstot =0;
    my $trnstot =0;
    my $fuzzytot= 0;
    my $untrnstot=0 ;
    print TABLE2 "$date<br>\n" ;
    foreach $mod (sort keys %modinfo){
        $grey++;
        $trbg = (($gray++) % 2) ? '#deded5' : '#c5c2c5';
	$percent = 0;
	if (${$modinfo{$mod}->[0]}) {
	    $percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
	}
	my $trns = ("${$langmod{$mod}->{$lang}->[0]}") ? "${$langmod{$mod}->{$lang}->[0]}" : 0;
	my $fuzzy = ("${$langmod{$mod}->{$lang}->[1]}") ? "${$langmod{$mod}->{$lang}->[1]}" : 0;
	my $untrns = ("${$langmod{$mod}->{$lang}->[2]}" && "${$langmod{$mod}->{$lang}->[2]}" != -1 ) ? "${$langmod{$mod}->{$lang}->[2]}" : ($trns != 0 || $fuzzy != 0) ? 0 : "${$modinfo{$mod}->[0]}";
	my $translatorname = ("${$langmod{$mod}->{$lang}->[4]}") ? "${$langmod{$mod}->{$lang}->[4]}" : "";

	if ("$translatorname" ne "") {
	    $translatorname =~ s/</&lt;/;
	    $translatorname =~ s/>/&gt;/;
	} else {
	    $translatorname = "<br>";
	}

	my $transtatus;
	if (${$langmod{$mod}->{$lang}->[3]} ==  0) {
	    $transtatus = ("$available{$lang}") ? "<font color=\"#F80000\">$available{$lang}</font>" : "<font color=\"#F80000\">Available</font>";
	} elsif (${$langmod{$mod}->{$lang}->[3]} == 1) {
	    $transtatus = ("$assigned{$lang}") ? "$assigned{$lang}" : "Assigned";
	} else {
	    $transtatus = ("$unknown{$lang}") ? "$unknown{$lang}" : "Unknown";
	}
	   
	if ($percent == 0) {
	    print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
	    <a href=\"$htmlpourl${$modules{$mod}->[1]}\">$mod</a></td>
	    <td align=right>$trns $strings</td><td align=right>$fuzzy $strings</td>
	    <td align=right>$untrns $strings</td><td align=right>$percent%</td>
	    <td>$transtatus</td><td>$translatorname</td>\n";
	}else{
	    print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
	    <a href=\"$htmlpourl$mod-$lang.po\">$mod</a></td>
	    <td align=right>$trns $strings</td><td align=right>$fuzzy $strings</td>
	    <td align=right>$untrns $strings</td><td align=right>$percent%</td>
	    <td>$transtatus</td><td>$translatorname</td>\n";
	}
	$stringstot += ${$modinfo{$mod}->[0]};
	$trnstot += $trns; 
	$fuzzytot += $fuzzy; 
	$untrnstot += $untrns; 
	
    }
    $percent = sprintf("%.2f",$trnstot*100/$stringstot);
    print TABLE2 "<tr bgcolor=\"#ffd700\" align=center>
                  <th align=left>$totalname</th>
    	          <th align=right>$trnstot $strings</th>
	          <th align=right>$fuzzytot $strings</th>
	          <th align=right>$untrnstot $strings</th>
	          <th align=right>$percent%</th>
	          <th><br></th><th><br></th>\n";
    print TABLE2 "</table></center>";
}

## Subroutines code
sub printmodule{
    my($mod, $align) = @_;
    my($changed, $string, $trbg);

    $changed = "";
    $changed = $changed_color{int(($now - ${$modinfo{$mod}->[1]})/86400)};

    if ($align eq "L") {
	$string = "<td align=right>";
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp;*&nbsp;</font>" : "";
	$string.= "<a href=\"$htmlpourl${$modules{$mod}->[1]}\">$mod</a>";
    } else {
	$string = "<td align=left>";
	$string.= "<a href=\"$htmlpourl${$modules{$mod}->[1]}\">$mod</a>";
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp;*&nbsp;</font>" : "";
    }

    print TABLE "$string</td>\n";	
}


sub printlang{
    ($mod, $lang) = @_;
    my($percent, $color,$per);

    $percent = 0;
    if (${$modinfo{$mod}->[0]}) {
    	$percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
    }

    if ($percent == 0){
        print TABLE "<td align=right><a href=\"$htmlpourl${$modules{$mod}->[1]}\">0%</a></td>\n";
    }else{
        if ($percent == 100) {
            $color = $percent_colors{"100%"};
        } elsif ($percent > 95) {
            $color = $percent_colors{">95%"};
        } elsif ($percent > 90) {
            $color = $percent_colors{">90%"};
        } elsif ($percent > 80) {
            $color = $percent_colors{">80%"};
        } elsif ($percent > 70) {
            $color = $percent_colors{">70%"};
        } elsif ($percent > 60) {
            $color = $percent_colors{">60%"};
        } elsif ($percent > 50) {
            $color = $percent_colors{">50%"};
        } elsif ($percent >3) {
            $per =  sprintf("%x",$percent*5);
            $color = "#ff00" .  "$per";
        } else {
            $per =  sprintf("%x",$percent*5);
            $color = "#ff000" . "$per";
        }

        print TABLE "<td align=right><a href=\"$htmlpourl$mod-$lang.po\">
		     <font color='$color'>$percent%</font></a></td>\n";
    }
}
