#!/usr/bin/perl
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
#		   Carlos Perell� Mar�n <carlos@gnome-db.org>
#		   Juan Fuentes <juan.fuentes@codetel.net.do>

use strict; #fix the file, so this can be enabled
# setlocale is needed here, so that the POSIX module don't
# complain of an invalid locale entry.
use POSIX qw(locale_h strftime);

# Ugly, ugly,damn ugly, but it works for now.
# Better now.

my (@info,%langmod,%langinfo,%modinfo,%modpath,%modules);

# no need to call the shell.

my $date   = strftime "%a %Y-%m-%d %T %z",localtime;
my $prog   = "getnhtml";

###############
# "Constants" #
###############

my $grey     = 0;  #odd/even strings
my $totals   = 0;  #total strings in all modules from modinfo.
my $now      = time;
my $details_ = "";

## Always print as the first thing
$| = 1;

# used => for readability.

my %modulenames = (
    "da" => "Modul",
    "de" => "Paket",
    "el" => "������",
    "es" => "M&oacute;dulo",
    "fr" => "Module",
    "ja" => "�⥸�塼��",
    "hu" => "Modul",
    "no" => "Modul",
    "ro" => "Modul",
    "ru" => "������",
    "sv" => "Modul",
    "tr" => "Mod�l",
    "uk" => "������",
    "wa" => "module"
); 

my %translated = (
    "da" => "Oversat",
    "de" => "&Uuml;bersetzt",
    "el" => "������������",
    "es" => "Traducidos",
    "fr" => "Traduites",
    "hu" => "Leford�tott",
    "ja" => "�����Ѥ�",
    "no" => "Oversatt",
    "ro" => "traduse",
    "ru" => "����������",
    "sv" => "&Ouml;versatt",
    "tr" => "Terc�me edilmi�",
    "uk" => "�����������",
    "wa" => "rato&ucirc;rn&eacute;s"
);

my %fuzzy = (
    "da" => "Uklart",
    "de" => "Ungenau",
    "el" => "�����",
    "es" => "Difusos",
    "fr" => "Approxim�es",
    "hu" => "Pontatlan",
    "ja" => "�ե�����",
    "no" => "Uferdig",
    "ro" => "neclare",
    "ru" => "�������",
    "sv" => "Luddigt",
    "tr" => "Tam tutmayan",
    "uk" => "��ަ���",
    "wa" => "&laquo;fuzzy&raquo;"
);

my %untranslated = (
    "da" => "Uoversat",
    "de" => "Un&uuml;bersetzt",
    "el" => "A����������",
    "es" => "Sin traducir",
    "fr" => "Non traduites",
    "hu" => "Ford�tatlan",
    "ja" => "̤��",
    "no" => "Uoversatt",
    "ro" => "netraduse",
    "ru" => "������������",
    "sv" => "O&ouml;versatt",
    "tr" => "Terc�me edilmemi�",
    "uk" => "�������������",
    "wa" => "n&eacute;n co rato&ucirc;rn&eacute;s"
);

my %strings = (
    "da" => "strenge",
    "de" => "Meldungen",
    "el" => "A������������",
    "es" => "Mensajes",
    "fr" => "Cha�nes",
    "hu" => "karakterl�nc",
    "ja" => "��",
    "no" => "strenger",
    "ro" => "stringuri",
    "ru" => "�����.",
    "sv" => "meddelanden",
    "tr" => "metinler",
    "uk" => "��צ���.",
    "wa" => "messaedjes"
);

my %total = (
    "de" => "Gesamt",
    "el" => "��������",
    "es" => "Totales",
    "fr" => "Total",
    "fi" => "Yhteens&auml;",
    "hu" => "�sszes",
    "ja" => "���",
    "nl" => "Totaal",
    "no" => "Total",
    "ru" => "�����",
    "sv" => "Totalt",
    "tr" => "Toplam",
    "uk" => "������",
    "wa" => "tot&aring;"
);

my %lasttranslator = (
    "C"  => "Last Translator",
    "de" => "Letzter &Uuml;bersetzer", 
    "el" => "���������� �����������",
    "es" => "�ltimo traductor",
    "fr" => "Dernier traducteur",
    "ja" => "�ǽ�������",
    "sv" => "Senaste &ouml;vers&auml;ttare"
);

my %status = (
    "C"  => "Status",
    "de" => "Status",
    "el" => "���������",
    "es" => "Estado",
    "fr" => "�tat",
    "ja" => "���ơ�����",
    "sv" => "Status"
);

my %outdated = (
    "C"  => "OutDated",
    "de" => "Veraltet",
    "el" => "�����������",
    "es" => "Desactualizada",
    "fr" => "� l'abandon",
    "sv" => "F&ouml;r&aring;ldrad",
);

my %wip = (
    "C"  => "Work in Progress",
    "de" => "In Arbeit",
    "el" => "�� �������",
    "es" => "En proceso",
    "fr" => "En cours",
    "sv" => "P&aring;g&aring;ende arbete",
);

my %finished = (
    "C"	 => "Finished",
    "el" => "������������",
    "es" => "Terminada",
    "fr" => "Termin�",
    "sv" => "Slutf&ouml;rd",
);

my %unknown = (
    "C"  => "Unknown",
    "de" => "Unbekannt",
    "el" => "�������",
    "es" => "Desconocido",
    "fr" => "Inconnu",
    "sv" => "Ok&auml;nd"
);

my %details = ( 
    "C"  => "Detail report for ",
    "da" => "Detaljeret statistik for underst&oslash;ttelse af dansk i Gnome",
    "de" => "Detaillierte Statistik f&uuml;r deutsche GNOME &Uuml;bersetzung",
    "el" => "���������� ������� ��� ",
    "es" => "Informe detallado de la traducci&oacute;n de GNOME al espa�ol (es)",
    "fr" => "Rapport d�taill� pour la traduction francophone (fr) du projet GNOME",
    "hu" => "R�szletes jelent�s a Magyar (hu) GNOME ford�t�sokr�l",
    "ja" => "���ܸ�(Japanese)���������ܺ٥�ݡ���",
    "no" => "Detaljert rapport for oversettelse av GNOME til norsk",
    "ru" => "��������� ����� � ��������� �������� GNOME �� �������",
    "sv" => "Detaljerad rapport f&ouml;r st&ouml;d av svenska i GNOME",
    "tr" => "Geli�mi� durum raporu : ",
    "uk" => "��������� �צ� ��� ���� ��������� GNOME �� ����������",
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


#############################
# "Subroutines declaration" # 
#############################
sub printmodule;
sub printlang;

####################
# "Initialization" #
####################

setlocale (LC_ALL, "C");

# Parse command line arguments, we'll use getopt::long for this.
# It's easier and work for multiple command line options.

use Getopt::Long;

# default values for options
my ($help,$debug,$htmldir,$htmlpourl,$modulesfile,$title);
$help = '';
$debug = '';
$htmldir = '';
$htmlpourl = undef ;
$modulesfile = undef;
$title = '';

GetOptions ('html-dir=s'     => \$htmldir,
            'html-po-url=s'  => \$htmlpourl,
            'modules-file=s' => \$modulesfile,
            'help'           => \$help,
	    'title=s'          => \$title,
	    'debug'          => \$debug,	
        ) or Usage();

if ($help) {
    Usage();
}

if (!$htmldir) {
    $htmldir = "/home/carlos/cvs/gnome/web-devel-2/content/projects/gtp/status/";
}

if (!$htmlpourl) {
    $htmlpourl = "po/";
}

if (!$modulesfile) {
    $modulesfile = $htmldir."modules.dat";
}

if ($debug) {
    print " >> html-dir $htmldir << \n";
    print " >> html-po-url $htmlpourl << \n";
    print " >> modules-file  $modulesfile << \n";
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
print TABLE "<html><head><title>GNOME translation status - $title ($date)</title></head>\n";
print TABLE "<body><center><h1>GNOME translation status<br>$title ($date)</h1></center>\n";
print TABLE "<p>This table shows the GNOME translation status for the $title</p>";
print TABLE "<p>All data is showed as percent of translated messages and you";
print TABLE "could download the latest .po && .pot files from this pages</p>";
print TABLE "<p>You could see more detailed information at locale links</p>";
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
    next if $mod eq "";
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
    $tot = sprintf("%.2f",${$langinfo{$lang}->[0]}*100/$totals);
    print TABLE "<th align=right>$tot</th>\n";
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

close TABLE;

# status printed.

# details.shtml

my $percent;
my $percent_int;
my $use_pot;

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
    ."<th>$strings<br>$translated</th><th>$strings<br>$fuzzy</th>"
    ."<th>$strings<br>$untranslated</th><th>%</th>"
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
	$use_pot = 1;
        if (${$modinfo{$mod}->[0]}) {
            $percent = sprintf("%.2f",${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
        }
        my $trns = ("${$langmod{$mod}->{$lang}->[0]}") ? "${$langmod{$mod}->{$lang}->[0]}" : 0;
        my $fuzzy = ("${$langmod{$mod}->{$lang}->[1]}") ? "${$langmod{$mod}->{$lang}->[1]}" : 0;
        my $untrns = ("${$langmod{$mod}->{$lang}->[2]}" && "${$langmod{$mod}->{$lang}->[2]}" != -1 ) ? "${$langmod{$mod}->{$lang}->[2]}" : ($trns != 0 || $fuzzy != 0) ? 0 : "${$modinfo{$mod}->[0]}";
        my $translatorname = ("${$langmod{$mod}->{$lang}->[4]}") ? "${$langmod{$mod}->{$lang}->[4]}" : " ";

	if ($percent != 0 || ${$langmod{$mod}->{$lang}->[1]} != 0) {
	    $use_pot = 0;
	}
        if ("$translatorname" ne " ") {
            $translatorname =~ s/</&lt;/;
            $translatorname =~ s/>/&gt;/;
        } else {
            $translatorname = "<br>";
        }

        my $transtatus;
	my $defaultlang = "C";
        if (${$langmod{$mod}->{$lang}->[3]} ==  0) {
            $transtatus = ("$outdated{$lang}") ? "<font color=\"#F80000\">$outdated{$lang}</font>" : "<font color=\"#F80000\">$outdated{$defaultlang}</font>";
        } elsif (${$langmod{$mod}->{$lang}->[3]} == 1) {
	    if ($percent == 100) {
	        $transtatus = ("$finished{$lang}") ? "$finished{$lang}" : "$finished{$defaultlang}";
	    } else {
                $transtatus = ("$wip{$lang}") ? "$wip{$lang}" : "$wip{$defaultlang}";
	    }
        } else {
            $transtatus = ("$unknown{$lang}") ? "$unknown{$lang}" : "$unknown{$defaultlang}";
        }

	$percent_int = sprintf ("%d", $percent);
        if ($use_pot == 1) {
            print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
            <a href=\"$htmlpourl${$modules{$mod}->[1]}\">$mod</a></td>
            <td align=right>$trns</td><td align=right>$fuzzy</td>
            <td align=right>$untrns</td><td align=right>$percent_int</td>
            <td>$transtatus</td><td>$translatorname</td>\n";
        }else{
            print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
            <a href=\"$htmlpourl$mod-$lang.po\">$mod</a></td>
            <td align=right>$trns</td><td align=right>$fuzzy</td>
            <td align=right>$untrns</td><td align=right>$percent_int</td>
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
    <th align=right>$trnstot</th>
    <th align=right>$fuzzytot</th>
    <th align=right>$untrnstot</th>
    <th align=right>$percent</th>
    <th><br></th><th><br></th>\n";
    print TABLE2 "</table></center>";
    close TABLE2;
}

## Subroutines code
sub printmodule{
    my($mod, $align) = @_;
    if ($debug) {
        print ">> mod  $mod <<\n";
        print ">> align  $align <<\n";
    }
    
    my ($changed, $string, $trbg);

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
    my ($mod, $lang) = @_;
    my($percent, $percent_int, $color,$per);

    $percent = 0;
    if (${$modinfo{$mod}->[0]}) {
        $percent = sprintf("%.2f",${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
    }

    if ($percent == 0){
        print TABLE "<td align=right><a href=\"$htmlpourl${$modules{$mod}->[1]}\">0</a></td>\n";
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
	$percent_int = sprintf("%d",$percent);
        print TABLE "<td align=right><a href=\"$htmlpourl$mod-$lang.po\">
        <font color='$color'>$percent_int</font></a></td>\n";
    }
}

sub Usage 
{
    print <<"HELP";

Usage: $prog [OPTION]....
Generate html stats with the stats from status.pl.

    --help                      Prints this page to standard error.

    --html-dir <location>       Specifies the directory where will
                                be stored the stats files.

    --html-po-url <location>    Specifies the url where will
                                be stored the .po/.pot files.

    --modules-file <location>   Specifies the file name that has
                                the modules info.

HELP

exit -1;
}
