#!/usr/bin/perl
# Copyright (C) 2000 Free Software Foundation
# frob <frob@df.ru>
#
# and don't ask me why i don't complete singular/plural
# simply poke kanikus to gap

$grey = 0;    #odd/even strings
$totals = 0;  #total strings in all modules from modinfo.
$now = time;

%modulenames = (
"da", "Modul",
"de", "Paket",
"el", "������",
"es", "M&oacute;dulo",
"fi", "Paketti",
"hu", "Modul",
"ja", "�⥸�塼��",
"no", "Modul",
"ro", "Modul",
"ru", "������",
"sv", "Modul",
"tr", "Mod�l",
"uk", "������",
"wa", "module"
 );

%translated = (
"da", "Oversat",
"de", "&Uuml;bersetzt",
"el", "������������",
"es", "Traducidas",
"fi", "K&auml;&auml;nnetty",
"fr", "Traduites",
"hu", "Leford�tott",
"ja", "������",
"nl", "Vertaald",
"no", "Oversatt",
"ro", "traduse",
"ru", "����������",
"sv", "&Ouml;versatt",
"tr", "Terc�me edilmi�",
"uk", "�����������",
"wa", "rato&ucirc;rn&eacute;s"
);

%fuzzy = (
"da", "Uklart",
"de", "Ungenau",
"el", "�����",
"es", "Difusas",
"fi", "Ep&auml;selvi&auml;",
"fr", "Floues",
"hu", "Pontatlan",
"ja", "�ե�����",
"nl", "Wazig",
"no", "Uferdig",
"ro", "neclare",
"ru", "�������",
"sv", "Luddigt",
"tr", "Tam tutmayan",
"uk", "��ަ���",
"wa", "&laquo;fuzzy&raquo;"
);

%untranslated = (
"da", "Uoversat",
"de", "Un&uuml;bersetzt",
"el", "A����������",
"es", "Sin traducir",
"fi", "K&auml;&auml;nt&auml;m&auml;tt&auml;",
"fr", "Non traduites",
"hu", "Ford�tatlan",
"ja", "$(BL$Lu(B",
"nl", "Onvertaald",
"no", "Uoversatt",
"ro", "netraduse",
"ru", "������������",
"sv", "O&ouml;versatt",
"tr", "Terc�me edilmemi�",
"uk", "�������������",
"wa", "n&eacute;n co rato&ucirc;rn&eacute;s"
);

%strings = (
"da", "strenge",
"de", "Meldungen",
"el", "A������������",
"es", "Mensajes",
"fi", "rivi&auml;",
"fr", "cha�nes",
"hu", "karakterl�nc",
"ja", "ʸ����",
"nl", "Meldingen",
"no", "Strenger",
"ro", "stringuri",
"ru", "�����.",
"sv", "meddelanden",
"tr", "metinler",
"uk", "��צ���.",
"wa", "messaedjes"
);

%totals = (
"de", "Gesamt",
"el", "��������",
"es", "Totales",
"fi", "Yhteens&auml;",
"hu", "�sszes",
"ja", "�ȡ�����",
"nl", "Totaal",
"no", "Total",
"ru", "�����",
"sv", "Totalt",
"tr", "Toplam",
"uk", "������",
"wa", "tot&aring;"
);

%details = (
"C", "Detailed report for ",
"ca", "Detailed report for Catalan (ca) GNOME translations",
"cs", "Detailed report for Czech (cs) GNOME translations",
"da", "Detaljeret statistik for underst&oslash;ttelse af dansk i GNOME<br>Detailed report for Danish (da) GNOME translations",
"de", "Detaillierte Statistik f&uuml;r deutsche GNOME &Uuml;bersetzung<br>Detailed report for German (de) GNOME translations",
"el", "���������� ������� ��� ��� ��������� (el) ����������� ��� GNOME",
"en_GB", "Detailed report for English, British (en_GB) GNOME translations",
"es", "Informe detallado de la traducci&oacute;n de GNOME al espa�ol (es)",
"et", "Detailed report for Estonian (et) GNOME translations",
"eu", "Detailed report for Basque (eu) GNOME translations",
"fi", "Yksityiskohtainen raportti Suomen (fi) GNOME k&auml;&auml;nn&ouml;ksist&auml;",
"fr", "Rapport d�taill� pour les traductions fran�aises (fr) GNOME",
"ga", "Detailed report for Irish (ga) GNOME translations",
"gl", "Detailed report for Galician (gl) GNOME translations",
"hr", "Detailed report for Croatian (hr) GNOME translations",
"hu", "R�szletes jelent�s a Magyar (hu) GNOME ford�t�sokr�l",
"is", "Detailed report for Icelandic (is) GNOME translations",
"it", "Detailed report for Italian (it) GNOME translations",
"ja", "GNOME�����ܸ����˴ؤ���ܺ٥�ݡ���",
"ko", "Detailed report for Korean (ko) GNOME translations",
"lt", "Detailed report for Lithuanian (lt) GNOME translations",
"nl", "Gedetailleerde rapportage voor de Nederlandse (nl) GNOME vertalingen",
"no", "Detaljert rapport for oversettelse av GNOME til norsk",
"pl", "Detailed report for Polish (pl) GNOME translations",
"pt", "Detailed report for Portuguese (pt) GNOME translations",
"pt_BR", "Detailed report for Portuguese, Brazilian (pt_BR) GNOME translations",
"ro", "Detailed report for Romanian (ro) GNOME translations",
"ru", "��������� ����� � ��������� �������� GNOME �� �������<br>Detailed report for Russian (ru) GNOME translations",
"sk", "Detailed report for Slovak (sk) GNOME translations",
"sl", "Detailed report for Slovenian (sl) GNOME translations",
"sr", "Detailed report for Serbian (sr) GNOME translations",
"sv", "Detaljerad rapport f&ouml;r st&ouml;d av svenska i GNOME<br>Detailed report for Swedish (sv) GNOME translations",
"tr", "T�rk�e GNOME terc�meleri i�in geli�mi� durum raporu<br>Detailed report for Turkish (tr) GNOME translations",
"uk", "��������� �צ� ��� ���� ��������� GNOME �� ����������<br>Detailed report for Ukrainian (uk) GNOME translations",
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

#################
# Files reading #
#################
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



open (TABLE, ">$htmldir/status.shtml") || die("can't open $htmldir/status.shtml");
print TABLE "<html><head><title>Report for GNOME translations</title></head><body>";
print TABLE "<table cellpadding=1 cellspacing=1 border=1 width=\"90%\"><tr align=center><td></td>";

#First string with langs
foreach $lang (sort keys %langinfo){
    $langs_red = substr($lang, 0, 5);
    $link = "$details_" . $langs_red . ".shtml";
    print TABLE "<td><a href=\"$link\">$langs_red</a></td>\n";
}
    print TABLE "<td></td></tr>";

foreach $mod (sort keys %modinfo){
    $trbg = (($grey++) % 2) ? '#deded5' : '#c5c2c5';	
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
    print TABLE "Stable branches with tags: <br>\n" ;
    print TABLE " <br>\n" ;
    print TABLE "control-center: control-center-1-0 <br>\n" ;
    print TABLE "gconf: gconf-1-0 <br>\n" ;
    print TABLE "gnome-libs: gnome-libs-1-0 <br>\n" ;
    print TABLE "gnome-print: gnome-1-4-branch <br>\n" ;
    print TABLE "gtk+: gtk-1-2 <br>\n" ;
    print TABLE "libgtop: LIBGTOP_STABLE_1_0 <br>\n" ;
    print TABLE "oaf: oaf-stable-0-6 <br>\n" ;

    print TABLE scalar localtime(time);
    print TABLE "</body></html>";

# status printed.

# details.shtml
foreach $lang (sort keys %langinfo){
    $link = "$details_" . substr($lang, 0, 5) . ".shtml";
open (TABLE2, ">$htmldir/$link") || die("can't open $htmldir/$link");
    $detail = $details{$lang} || $details{"C"} . $lang;
    print TABLE2 "<html><head><title>$detail</title></head><body>\n";
    print TABLE2 "<a name=\"$lang\"><b>$detail</b></a><br>\n";
    print TABLE2 "<table cellpadding=1 cellspacing=1 border=1>\n";
    $modulename = $modulenames{$lang} || "Module";
    $translated = $translated{$lang} || "Translated";
    $fuzzy = $fuzzy{$lang} || "Fuzzy";
    $untranslated = $untranslated{$lang} || "Untranslated";
    print TABLE2 "<tr bgcolor=\"#ffd700\"><th>$modulename</th><th>$translated</th><th>$fuzzy</th><th>$untranslated</th><th>%</th>\n";
    $strings = $strings{$lang} || "strings";
    $totalname = $totals{$lang} || "Total";
    @tot = (0, 0, 0, 0);

    foreach $mod (sort keys %modinfo){
        $trbg = (($grey++) % 2) ? '#deded5' : '#c5c2c5';	
        $percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]}) . "%";
        $trns = $fuzzy = $untrns = "-"; 

        if ("${$langmod{$mod}->{$lang}->[0]}") {
            $trns = "${$langmod{$mod}->{$lang}->[0]} $strings";
            $tot[0] += ${$langmod{$mod}->{$lang}->[0]};
        }
        if ("${$langmod{$mod}->{$lang}->[1]}") {
            $fuzzy = "${$langmod{$mod}->{$lang}->[1]} $strings";
            $tot[1] += ${$langmod{$mod}->{$lang}->[1]};
        }
        if ("${$langmod{$mod}->{$lang}->[2]}") {
            $untrns = "${$langmod{$mod}->{$lang}->[2]} $strings";
            $tot[2] += ${$langmod{$mod}->{$lang}->[2]};
        }
        print TABLE2 "<tr bgcolor='$trbg' align=center><td align=left>$mod</td><td>$trns</td><td>$fuzzy</td><td>$untrns</td><td>$percent</td>\n";
    }
    $tot[3] = int(${$langinfo{$lang}->[0]}*100/$totals);
    print TABLE2 "<tr bgcolor=\"#ffd700\"><th>$totalname</th><th>$tot[0]</th><th>$tot[1]</th><th>$tot[2]</th><th>$tot[3]%</th>\n";
    print TABLE2 "</table></center></body></html>";
}

####################
# Subroutines code #
####################
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

    print TABLE "$string</td>";	
    }


sub printlang{
    my ($mod, $lang) = @_;
    my ($percent, $color);

    $percent = int(${$langmod{$mod}->{$lang}->[0]}*100/${$modinfo{$mod}->[0]});
    if ($percent eq 0){
    print TABLE "<td>-</td>";
    }else{
    if ($percent == 100){
	$color = $percent_colors{"100%"};
	} elsif ($percent > 95){
	$color = $percent_colors{">95%"};
	} elsif ($percent > 90){
	$color = $percent_colors{">90%"};
	} elsif ($percent > 80){
	$color = $percent_colors{">80%"};
	} elsif ($percent > 70){
	$color = $percent_colors{">70%"};
	} elsif ($percent > 60){
	$color = $percent_colors{">60%"};
	} elsif ($percent > 50){
	$color = $percent_colors{">50%"};
	} elsif ($percent >3){
	$per =  sprintf("%x",$percent*5);
	$color = "#ff00" .  "$per";
	} else {
	$per =  sprintf("%x",$percent*5);
	$color = "#ff000" . "$per";
	}

    print TABLE "<td><font color='$color'>$percent%</font></td>";
    }
}
