#!/usr/bin/perl
# Copyright (C) 2000-2001 Free Software Foundation, Inc.
# frob <frob@df.ru>
# with great help of dand, kanikus and kmaraas
# Some changes by keld
# Rewrited by Carlos Perelló Marín <carlos@gnome-db.org>

use POSIX qw(locale_h);
#use strict; We should fix some checks to enable it :-(
###############
# "Constants" #
###############

## Always print as the first thing
$| = 1;

# it's for a current developer :-)
#my @langs = ( "es" );

my (%modules,%langinfo,%modinfo,%modinfoold,$line,%langinfoold,
    $mod,%langmodold,%langmod); 
my @langs = qw ( az bg br ca cs da de el en en_GB es et eu fi fr fr_BE fr_FR
		 ga gl hr hu id is it ja ko lt lv ms nl nn no pl pt pt_BR
		 pt_PT ro ru sk sl sp sr sr_YU sv ta tr uk vi wa zh_CN zh_TW );

my @Usage = ("Usage: status.pl [OPTION]...\n".
          "Generate stats with the status of .po files translations.\n".
	  "\n".
	  "  --cvsroot-dir <location>    Specifies the directory where are\n".
	  "                              the CVS modules.\n".
	  "\n".
	  "  --help                      Prints this page to standard error.\n".
	  "\n".
	  "  --html-dir <location>       Specifies the directory where will\n".
	  "                              be stored the stats files.\n".
	  "\n".
	  "  --modules-file <location>   Specifies the file name that has\n".
	  "                              the modules info.\n".
	  "\n".
	  "  --pos-dir <location>        Specifies the directory where will\n".
	  "                              be stored the .po/.pot files.\n".
	  "\n".
	  "  --ttl <seconds>             Time (in seconds) to mark a translation\n".
	  "                              as available to other translators.\n");


#############################
# "Subroutines declaration" # 
#############################
sub getmsgfmt;
sub generatepot;
sub getmerge;
sub gettrnsinfo;

####################
# "Initialization" #
####################

setlocale (LC_ALL, "C");

my ($cvsroot,$htmldir,$posdir,$ttl,$modulesfile);

while (@ARGV) {
    if ($ARGV[0] eq "--cvsroot-dir") {
        $cvsroot = $ARGV[1];
	shift @ARGV;
    } elsif ($ARGV[0] eq "--html-dir") {
        $htmldir = $ARGV[1];
	shift @ARGV;
    } elsif ($ARGV[0] eq "--pos-dir") {
        $posdir = $ARGV[1];
	shift @ARGV;
    } elsif ($ARGV[0] eq "--ttl") {
        $ttl = $ARGV[1];
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

#
# Now we setup the default options
#
if ($cvsroot eq "") {
    $cvsroot = "/home/carlos/cvs/gnome2/";
}

if ($htmldir eq "") {
    $htmldir = "/home/carlos/cvs/gnome/web-devel-2/content/projects/gtp/status/";
}

if ($posdir eq "") {
    $posdir = $htmldir."po/";
}

if ($ttl eq "") {
    $ttl = 15552000; # This is when a module is marked as unmantained ( 6 months )
}

if ($modulesfile eq "") {
    $modulesfile = $htmldir."modules.dat";
}

open (POSDIR, "mkdir -p $posdir |") || die ("unable to mkdir");
close (POSDIR);

#
# read modules info
#

if (open (MODULES, $modulesfile)){
    while (<MODULES>) {
        chomp;
	my @info = split (/,/);
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
								  

#
# read old dat-files
#

if (open (MODINFO, "$htmldir/modinfo.dat")){
    while (<MODINFO>) {
	chomp;
	my @info = split (/,/);
	my $index = 1;
	while ($info[$index]){
	    ${$modinfoold{$info[0]}->[$index-1]} = $info[$index];
	    $index++;
	}
    }
    close (MODINFO);
}

if (open (LANGINFO, "$htmldir/langinfo.dat")){
    while (<LANGINFO>) {
	chomp;
	my @info = split (/,/);
	my $index = 1;
	while ($info[$index]){
	    ${$langinfoold{$info[0]}->[$index-1]} = $info[$index];
	    $index++;
	}
    }
    close (LANGINFO);
}

if (open (LANGMOD, "$htmldir/langmod.dat")){
    while (<LANGMOD>) {
	chomp;
	my @info = split(/,/);
    my $stupid;
	($stupid, $stupid, @{$langmodold{$info[0]}->{$info[1]}}) = @info;
    }
}

################
# "Main loops" #
################
my $modflag = 0; # does we need write modinfo?

foreach $mod (sort (keys %modules)){
    generatepot($mod);
    if (-d "$cvsroot/${$modules{$mod}->[0]}"){
	my @result = getmsgfmt("pot",$mod);
	${ $modinfo { $mod } -> [0] } = ($result[0] - 1);
        if ( $modinfoold{$mod} && (${$modinfoold{$mod}->[0]} eq ($result[0] - 1))){
	    ${ $modinfo { $mod } -> [1] } = ${ $modinfoold { $mod } -> [1] };
	} else {
	    ${ $modinfo { $mod } -> [1] } = time;
	    $modflag = 1;
	}
   }
}

foreach my $lang (sort (@langs)){
    my $total_msg = 0;
    foreach my $mod (sort (keys %modules)){
	if (-f "$cvsroot/${$modules{$mod}->[0]}/$lang.po" ) {
	    getmerge($lang,$mod);
	    my @result = getmsgfmt ("$lang.new", $mod);
	    my @info = gettrnsinfo ("$lang.new", $mod);
	    unlink("$cvsroot/${$modules{$mod}->[0]}/$lang.new.po");
	    if ($result[1]){
		$total_msg += $result[1];
	    }
	    ${$langmod{$lang}->{$mod}->[0]} = $result[1];
	    ${$langmod{$lang}->{$mod}->[1]} = $result[2];
	    ${$langmod{$lang}->{$mod}->[2]} = $result[3];
	    if ($result[2] != 0 || $result[3] != 0) {
		${$langmod{$lang}->{$mod}->[3]} = $info[0];
	    } else {
		${$langmod{$lang}->{$mod}->[3]} = 1;
	    }
	    if ($info[1]) {
	        ${$langmod{$lang}->{$mod}->[4]} = $info[1];
	    } else {
	        ${$langmod{$lang}->{$mod}->[4]} = " ";
	    }
	} else {
	    ${$langmod{$lang}->{$mod}->[0]} = 0;
	    ${$langmod{$lang}->{$mod}->[1]} = 0;
	    ${$langmod{$lang}->{$mod}->[2]} = 0;
	    ${$langmod{$lang}->{$mod}->[3]} = 0;
	    ${$langmod{$lang}->{$mod}->[4]} = " ";
	}
    }
    ${$langinfo{$lang}->[0]} = $total_msg;
}

######################
# "Files generation" #
######################
if ($modflag) {               # Change modinfo if need.
    open (MODINFO, ">$htmldir/modinfo.dat") || die ("can't open $htmldir/modinfo.dat");

    foreach my $mod (sort (keys %modules)){
	my $string = "${$modinfo{$mod}->[0]}" . "," . "${$modinfo{$mod}->[1]}";
	my $index = 2;
	if ($modinfoold{$mod}) {
	    while (${$modinfoold{$mod}->[$index]}){
	        $string = $string . "," . ${$modinfoold{$mod}->[$index]};
	        $index++;
	    }
	}
	print MODINFO "$mod,$string\n";
    }
    close (MODINFO);
}

######################
open (LANGINFO, ">$htmldir/langinfo.dat") || die ("can't open $htmldir/langinfo.dat");

foreach my $lang (sort (@langs)){
    my $string = "${$langinfo{$lang}->[0]}"; # in the future we add [1] and [2]
    my $index = 1;
    if ($langinfoold{$lang}) {
        while (${$langinfoold{$lang}->[$index]}){
	    $string = $string . "," . ${$langinfoold{$lang}->[$index]};
	    $index++;
	    print $string . "\n";
        }
    }
    print LANGINFO "$lang,$string\n";
}
close (LANGINFO);

######################
open (LANGMOD, ">$htmldir/langmod.dat") || die ("can't open $htmldir/langmod.dat");

foreach my $lang (sort (@langs)){
    foreach my $mod (sort (keys %modules)){
	my $string = "${$langmod{$lang}->{$mod}->[0]}" . "," . "${$langmod{$lang}->{$mod}->[1]}" . "," . "${$langmod{$lang}->{$mod}->[2]}" . "," . "${$langmod{$lang}->{$mod}->[3]}" . "," . "${$langmod{$lang}->{$mod}->[4]}";
	my $index = 5;
	
	#
	# I don't see the utility for this code :-?
	#
	#while (${$langmodold{$lang}->{$mod}->[$index]}){
	#    $string = $string . "," . ${$langmodold{$lang}->{$mod}->[$index]};
	#    $index++;
	#}

	print LANGMOD "$lang,$mod,$string\n";
    }
}
close (LANGMOD);

#END

########################
########################
## "Subroutines code" ##
########################
########################

sub getmerge{
    my ($lang,$mod) = @_;
   
   print "getmerge: $mod $lang\n";
   
    open(MERGE,"LANGUAGE=C msgmerge --output-file=$cvsroot/${$modules{$mod}->[0]}/$lang.new.po $cvsroot/${$modules{$mod}->[0]}/$lang.po $cvsroot/${$modules{$mod}->[0]}/${$modules{$mod}->[1]} |")
    || die ("unable to msgmerge");
    close(MERGE);
    open(MERGE,"cp -p $cvsroot/${$modules{$mod}->[0]}/$lang.new.po $posdir/$mod-$lang.po |")
    || die ("unable to cp");
    close(MERGE);
}

sub getmsgfmt{
    my ($language,$mod) = @_;
    my ($file, $total, $trns, $fuzz, $untr);
    if ($language eq "pot"){
        $file = ${$modules{$mod}->[1]};
    } else {
        $file = $language.".po";
    }

    open(STAT,"LANGUAGE=C msgfmt --statistics -o /dev/null $cvsroot/${$modules{$mod}->[0]}/$file 2>&1 |")
    || die ("unable to msgfmt");
    $line=<STAT>;
    close(STAT);
    ($trns) = $line =~ /(\d+) translated\s.+/;
    ($fuzz) = $line =~ /(\d+) fuzzy\s.+/;
    ($untr) = $line =~ /(\d+) untranslated\s.+/;
    if ("$trns" == "") {
        $trns = "0";
    }
    if ("$fuzz" == "") {
        $fuzz = "0";
    }
    if ("$untr" == "") {
        $untr = "0";
    }
    $total = $trns + $fuzz + $untr;
    return ($total,$trns,$fuzz,$untr);
}

sub gettrnsinfo {
    my ($language,$mod) = @_;
   
    if (-f "$cvsroot/${$modules{$mod}->[0]}/$language.po") {
	open (AUTHOR, "LANGUAGE=C grep ^\\\"Last-Translator $cvsroot/${$modules{$mod}->[0]}/$language.po |")
	|| die ("unable to get the Last-Translator field");
	my $author = <AUTHOR>;
	close (AUTHOR);
	$author =~ s/.*:\ //g;
	$author =~ s/\\n.*$//g;
	# With this line we remove the new line char
	$author = substr($author, 0, -1);

	open (DATEPOT, "LANGUAGE=C grep ^\\\"POT-Creation-Date $cvsroot/${$modules{$mod}->[0]}/$language.po |")
	|| die ("unable to get the POT-Creation-Date field");
	my $date_pot = <DATEPOT>;
	close (DATEPOT);
	$date_pot =~ s/.*:\ //g;
	$date_pot =~ s/\\n.*$//g;

	open (DATEPO, "LANGUAGE=C grep ^\\\"PO-Revision-Date $cvsroot/${$modules{$mod}->[0]}/$language.po |")
	|| die ("unable to get the PO-Revision-Date field");
	my $date_po = <DATEPO>;
	close (DATEPO);
	$date_po =~ s/.*:\ //g;
	$date_po =~ s/\\n.*$//g;	

	my $status = getstatusfromdate ($date_pot, $date_po, $ttl);
	return ($status, $author);
    } else {
        return (0, "");
    }
}

sub getstatusfromdate {
    my ($date_pot, $date_po, $ttl) = @_;

    open (POTTIME, "LANGUAGE=C date -d \"$date_pot\" +%s |")
    || die ("unable to execute date");
    my $potseconds = <POTTIME>;
    close (POTTIME);

    open (POTIME, "LANGUAGE=C date -d \"$date_po\" +%s |")
    || die ("unable to execute date");
    my $poseconds = <POTIME>;
    close (POTIME);
   
    my $time = $potseconds - $poseconds;
    if ($time > $ttl) {
        return 0;
    } else {
        return 1;
    }
}

sub generatepot{
    my $mod = $_[0];

    print "generatepot: $mod\n";

    if (${$modules{$mod}->[3]} eq "TRUE"){
      open (POTOUT,  "cd $cvsroot/${$modules{$mod}->[0]} && intltool-update -P 1>&2 && cp -p $cvsroot/${$modules{$mod}->[0]}/${$modules{$mod}->[1]} $posdir |") || die ("could not generate ${$modules{$mod}->[1]}");
    } else {
      open (POTOUT, "cd $cvsroot/${$modules{$mod}->[0]} && cp -p $cvsroot/${$modules{$mod}->[0]}/${$modules{$mod}->[1]} $posdir |");
    }
    close (POTOUT);
}

# Array structures:
# modules  $mod:$moddir:$modpot:$brahch_name:$modpotregenerate:$next_release_date
# ${$modules{$mod}->[0]} = "Directory where its .po/.pot files are"
# ${$modules{$mod}->[1]} = "Its .pot file name"
# ${$modules{$mod}->[2]} = "branch of module name"
# ${$modules{$mod}->[3]} = "Regenerate .pot file (TRUE/FALSE)"
# ${$modules{$mod}->[4]} = "date of next release (in seconds)"
#
# langmod  $mod:$lang:$trns:$fuzz:$untr:$trns_status:$last_trns
# ${$langmod{$lang}->{$mod}->[0]} = "trns";
# ${$langmod{$lang}->{$mod}->[1]} = "fuzz";
# ${$langmod{$lang}->{$mod}->[2]} = "untr";
# ${$langmod{$lang}->{$mod}->[3]} = "trns status";
# ${$langmod{$lang}->{$mod}->[4]} = "last trns";
#
# modinfo   $mod:$msgs:$recent_chng_date
# ${$modinfo{$mod}->[0]} = "msgs in module";
# ${$modinfo{$mod}->[1]} = "date of msgs num changed";
#
# langinfo   $lang:$total_trns:$score:$score_date
# ${$langinfo{$lang}->[0]} = "total trns msg for lang";
# ${$langinfo{$lang}->[1]} = "score of team";
# ${$langinfo{$lang}->[2]} = "date when score was achived";
