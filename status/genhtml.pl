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
#		   Carlos Perell� Mar�n <carlos@gnome-db.org>
#		   Juan Fuentes <juan.fuentes@codetel.net.do>

#use strict; #fix the file, so this can be enabled
use POSIX qw(locale_h);
use POSIX qw(strftime);

# Ugly, ugly,damn ugly, but it works for now.

my ($now,%modulenames,%translated,%fuzzy,%untranslated,%total,%status,%available,%assigned,%unknown,%modules,$totalname,$status,$lasttranslator,$translatorname,$transtatus);
my (%strings,%details,%lasttranslator,%percent_colors,%changed_color,$totals); 
my ($path,$mod,@info,$info,$index,$htmldir,$htmlpodir,$tmp,$lang,$grey);
my (%langinfo,$langs_red,$link,%modinfo,$langmod,%langmod);
my ($date,$fuzzy,$detail,$modulename,$translated,$untranslated);
my ($stringstot,$trnstot,$fuzzytot,$untrnstot,$trbg,$percent);
my ($strings,$newname,$trns,$untrns,$align,$changed,$release);
my ($color,$per,%modpath,$gray,$tot,$details_,$string);

# no need to call the shell.

$date   = strftime "%a %Y-%m-%d %T %z",localtime;

###############
# "Constants" #
###############

$grey     = 0;    #odd/even strings
$totals   = 0;  #total strings in all modules from modinfo.
$now      = time;
$details_ = "";

$htmldir   ="/home/carlos/cvs/gnome/web-devel-2/content/projects/gtp/status";
$htmlpodir ="po/";

# used => for readability.

%modulenames = (
"da" => "Modul",
"de" => "Paket",
"el" => "������",
"es" => "M&oacute;dulo",
"hu" => "Modul",
"no" => "Modul",
"ro" => "Modul",
"ru" => "������",
"sv" => "Modul",
"tr" => "Mod�l",
"uk" => "������",
"wa" => "module"
 ); 

%translated = (
"da" => "Oversat",
"de" => "&Uuml;bersetzt",
"el" => "������������",
"es" => "Traducidos",
"hu" => "Leford�tott",
"no" => "Oversatt",
"ro" => "traduse",
"ru" => "����������",
"sv" => "&oslash;versatt",
"tr" => "Terc�me edilmi�",
"uk" => "�����������",
"wa" => "rato&ucirc;rn&eacute;s"
);

%fuzzy = (
"da" => "Uklart",
"de" => "Ungenau",
"el" => "�����",
"es" => "Difusos",
"hu" => "Pontatlan",
"no" => "Uferdig",
"ro" => "neclare",
"ru" => "�������",
"sv" => "oklart",
"tr" => "Tam tutmayan",
"uk" => "��ަ���",
"wa" => "&laquo;fuzzy&raquo;"
);

%untranslated = (
"da" => "Uoversat",
"de" => "Un&uuml;bersetzt",
"el" => "A����������",
"es" => "Sin traducir",
"hu" => "Ford�tatlan",
"no" => "Uoversatt",
"ro" => "netraduse",
"ru" => "������������",
"sv" => "o&oslash;versatt",
"tr" => "Terc�me edilmemi�",
"uk" => "�������������",
"wa" => "n&eacute;n co rato&ucirc;rn&eacute;s"
);

%strings = (
"da" => "strenge",
"de" => "Meldungen",
"el" => "A������������",
"es" => "Mensajes",
"hu" => "karakterl�nc",
"no" => "strenger",
"ro" => "stringuri",
"ru" => "�����.",
"sv" => "str&auml;ngar",
"tr" => "metinler",
"uk" => "��צ���.",
"wa" => "messaedjes"
);

%total = (
"de" => "Gesamt",
"el" => "��������",
"es" => "Totales",
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

%lasttranslator = (
"C"  => "Last Translator",
"es" => "�ltimo traductor"
);

%status = (
"C"  => "Status",
"es" => "Estado"
);

%available = (
"C"  => "Available",
"es" => "Disponible"
);

%assigned = (
"C"  => "Assigned",
"es" => "Asignado"
);

%unknown = (
"C"  => "Unknown",
"es" => "Desconocido"
);

%details = ( 
"C"  => "Detail report for ",
"da" => "Detaljeret statistik for underst&oslash;ttelse af dansk i Gnome",
"de" => "Detaillierte Statistik f&uuml;r deutsche GNOME &Uuml;bersetzung",
"el" => "���������� ������� ��� ",
"es" => "Informe detallado de la traducci&oacute;n de GNOME al espa�ol (es)",
"hu" => "R�szletes jelent�s a Magyar (hu) GNOME ford�t�sokr�l",
"no" => "Detaljert rapport for oversettelse av GNOME til norsk",
"ru" => "��������� ����� � ��������� �������� Gnome �� �������",
"sv" => "Detaljerad rapport f&Oslash;r Sverige-support i Gnome",
"tr" => "Geli�mi� durum raporu : ",
"uk" => "��������� �צ� ��� ���� ��������� GNOME �� ����������",
"wa" => "Sipepieus rapoirt po les walons (wa) rato&ucirc;rnaedjes di GNOME"
);

%percent_colors = (
"100%"  => "#00bb00",          
">95%"  => "#8470ff",  
">90%"  => "#8a2be2",
">80%"  => "#1e90ff",          
">70%"  => "#9400d3",
">60%"  => "#9932cc",
">50%"  => "#da70d6",
"other" => ""
);

%changed_color = (
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

#setlocale (LC_LANG, "C");
setlocale (LC_ALL, "C");

#
# read dat-files
#
if (open (MODULES, "modules.dat")){
    while (<MODULES>) {
        chomp;
	@info = split (/,/);
	$index = 1;
	while ($info[$index]){
	    ${$modules{$info[0]}->[$index-1]} = $info[$index];
	    $index++;
	}
    }
    close (MODULES);
}

if (open (MODINFO, "$htmldir/modinfo.dat")){
    while (<MODINFO>) {
	chomp;
	@info = split (/,/);
	$index = 1;
	$mod = $info[0];
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
	$index = 1;
	$lang = $info[0];
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
	($lang, $mod, @info) = split(/,/);
	$index = 0;
	foreach $info (@info) {
	    ${$langmod{$mod}->{$lang}->[$index]} = $info;
	    $index++;
	}
    }
}    

################
# "Main loops" #
################

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
    
$totalname = $total{$lang} || "Total";

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

    print TABLE "Stable branches with tags: <br>\n" ;
    print TABLE " <br>\n" ;

    print TABLE "</body></html>\n" ;
    
# status printed.

# details.shtml
foreach $lang (sort keys %langinfo){
    $link = "$details_" . substr($lang, 0, 5) . ".shtml";
    open  TABLE2, ">$htmldir/$link" || die "can't open $htmldir/$link";
    $detail = $details{$lang} || $details{"C"} . $lang;
    print TABLE2 "<html><head><title>$detail</title></head><body>\n";
    print TABLE2 "<a name=\"$lang\"><b>$detail</b></a><br>";
    print TABLE2 "<table cellpadding=1 cellspacing=1 border=1 >";

    $modulename = $modulenames{$lang} 		|| "Module";
    $translated = $translated{$lang} 		|| "Translated";
    $fuzzy = $fuzzy{$lang} 			|| "Fuzzy";
    $untranslated = $untranslated{$lang} 	|| "Untranslated";
    $status = $status{$lang}			|| "Status";
    $lasttranslator = $lasttranslator{$lang}	|| "Last Translator";
    $strings = $strings{$lang}			|| "strings";
    $totalname = $total{$lang}			|| "Total";

    print TABLE2 "<tr bgcolor=\"#ffd700\"><th>$modulename</th>"
		."<th>$translated</th><th>$fuzzy</th>"
		."<th>$untranslated</th><th>%</th>"
		."<th>$status</th><th>$lasttranslator</th>\n";

    $stringstot =0; $trnstot =0; $fuzzytot= 0; $untrnstot=0 ;
    print TABLE2 "$date<br>\n" ;
    foreach $mod (sort keys %modinfo){
        $grey++;
        $trbg = (($gray++) % 2) ? '#deded5' : '#c5c2c5';
	$percent = 0;
	if (${$modinfo{$mod}->[0]}) {
		$percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
	}
	$trns = ("${$langmod{$mod}->{$lang}->[0]}") ? "${$langmod{$mod}->{$lang}->[0]} $strings" : "0 $strings";
	$fuzzy = ("${$langmod{$mod}->{$lang}->[1]}") ? "${$langmod{$mod}->{$lang}->[1]} $strings" : "0 $strings";
	$untrns = ("${$langmod{$mod}->{$lang}->[2]}") ? "${$langmod{$mod}->{$lang}->[2]} $strings" : ("${$modinfo{$mod}->[0]}" ne "-1" && "${$modinfo{$mod}->[0]}" ne "${$langmod{$mod}->{$lang}->[0]}") ? "${$modinfo{$mod}->[0]} $strings" : "0 $strings";
	$translatorname = ("${$langmod{$mod}->{$lang}->[4]}") ? "${$langmod{$mod}->{$lang}->[4]}" : "";

	if ("$translatorname" ne "") {
	    $translatorname =~ s/</&lt;/;
	    $translatorname =~ s/>/&gt;/;
	} else {
	    $translatorname = "<br>";
	}

	if ("${$langmod{$mod}->{$lang}->[3]}" eq "0") {
	    $transtatus = ("$available{$lang}") ? "<font color=\"#F80000\">$available{$lang}</font>" : "<font color=\"#F80000\">Available</font>";
	} elsif ("${$langmod{$mod}->{$lang}->[3]}" eq "1") {
	    $transtatus = ("$assigned{$lang}") ? "$assigned{$lang}" : "Assigned";
	} else {
	    $transtatus = ("$unknown{$lang}") ? "$unknown{$lang}" : "Unknown";
	}
	   
	if ($percent eq 0) {
	    print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
	    <a href=\"$htmlpodir${$modules{$mod}->[1]}\">$mod</a></td>
	    <td align=right>$trns</td><td align=right>$fuzzy</td>
	    <td align=right>$untrns</td><td align=right>$percent%</td>
	    <td>$transtatus</td><td>$translatorname</td>\n";
	}else{
	    print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>
	    <a href=\"$htmlpodir$mod-$lang.po\">$mod</a></td>
	    <td align=right>$trns</td><td align=right>$fuzzy</td>
	    <td align=right>$untrns</td><td align=right>$percent%</td>
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
    my($changed, $release, $string, $trbg);
    
    $changed = "";
    $changed = $changed_color{int(($now - ${$modinfo{$mod}->[1]})/86400)};
    $release = int((${$modinfo{$mod}->[2]} - $now)/86400);

    if ($align eq "L") {
	$string = "<td align=right>";
	$string.= ($release gt 0) ? "[$release] " : "";
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp;*&nbsp;</font>" : "";
	$string.= "<a href=\"$htmlpodir${$modules{$mod}->[1]}\">$mod</a>";
    } else {
	$string = "<td align=left>";
	$string.= "<a href=\"$htmlpodir${$modules{$mod}->[1]}\">$mod</a>";
	$string.= ($changed ne "") ? "<font color='$changed'>&nbsp;*&nbsp;</font>" : "";
	$string.= ($release gt 0) ? " [$release]" : "";
    }
    
    print TABLE "$string</td>\n";	
    }
    
    
sub printlang{
    ($mod, $lang) = @_;
    my($percent, $color);
    
    $percent = 0;
    if (${$modinfo{$mod}->[0]}) {
    	$percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
    }

    if ($percent eq 0){
        print TABLE "<td align=right><a href=\"$htmlpodir${$modules{$mod}->[1]}\">0%</a></td>\n";
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
		<a href=\"$htmlpodir$mod-$lang.po\">
		<font color='$color'>
		$percent%</font></a></td>\n";
    }
}
