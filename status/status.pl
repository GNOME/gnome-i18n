#!/usr/bin/perl
# Copyright (C) 2000-2001 Free Software Foundation, Inc.
# frob <frob@df.ru>
# with great help of dand, kanikus and kmaraas
# Some changes by keld
# Rewrited by Carlos Perelló Marín <carlos@gnome-db.org>

use POSIX qw(locale_h);
###############
# "Constants" #
###############

# it's for a current developer :-)
@langs = ( "es" );

@langs = qw ( az bg ca cs da de el en_GB es et eu fi fr ga gl hr hu is it
	      ja ko lt lv ms nl no nn pl pt pt_BR ro ru sk sl sr sv ta tr uk
	      vi wa zh_TW.Big5 zh_CN.GB2312 );

$cvsroot = "/home/carlos/cvs/gnome2";
$htmldir = "/home/carlos/cvs/gnome/web-devel-2/content/projects/gtp/status";
$posdir = "$htmldir/po";
$ttl = 7776000; # This is when a module is marked as unmantained ( 3 months )

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

setlocale (LC_LANG, "C");
setlocale (LC_ALL, "C");

open (POSDIR, "mkdir -p $posdir |") || die ("unable to mkdir");
close (POSDIR);

#
# read modules info
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
								  

#
# read old dat-files
#

if (open (MODINFO, "$htmldir/modinfo.dat")){
    while (<MODINFO>) {
	chomp;
	@info = split (/,/);
	$index = 1;
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
	@info = split (/,/);
	$index = 1;
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
	@info = split(/,/);
	($stupid, $stupid, @{$langmodold{$info[0]}->{$info[1]}}) = @info;
    }
}

################
# "Main loops" #
################
my $modflag = 0; # does we need write modinfo?

foreach $mod (keys %modules){
    if (${$modules{$mod}->[0]}=~/extra-po/){ # don't generate in extra-po
    } else {
	generatepot($mod);
    }
    if (-d "$cvsroot/${$modules{$mod}->[0]}"){
	@result = getmsgfmt("pot",$mod);
	${ $modinfo { $mod } -> [0] } = ($result[0] - 1);
        if ( ${$modinfoold{$mod}->[0]} ne ($result[0] - 1)){
	    ${ $modinfo { $mod } -> [1] } = time;
	    $modflag = 1;
	} else {
	    ${ $modinfo { $mod } -> [1] } = ${ $modinfoold { $mod } -> [1] };
	}
   }
}

foreach $lang (@langs){
    $total_msg = 0;
    foreach $mod (keys %modules){
	if (-f "$cvsroot/${$modules{$mod}->[0]}/$lang.po" ) {
	    getmerge($lang,$mod);
	    @result = getmsgfmt ("$lang.new", $mod);
	    @info = gettrnsinfo ("$lang.new", $mod);
	    unlink("$cvsroot/$mod/$lang.new.po");
	    if ($result[1]){
		$total_msg += $result[1];
	    }
	    ${$langmod{$lang}->{$mod}->[0]} = @result[1];
	    ${$langmod{$lang}->{$mod}->[1]} = @result[2];
	    ${$langmod{$lang}->{$mod}->[2]} = @result[3];
	    if (@result[2] != 0 || @result[3] != 0) {
		${$langmod{$lang}->{$mod}->[3]} = @info[0];
	    } else {
		${$langmod{$lang}->{$mod}->[3]} = 1;
	    }
	    ${$langmod{$lang}->{$mod}->[4]} = @info[1];
	} else {
	    ${$langmod{$lang}->{$mod}->[3]} = 0;
	}
    }
    ${$langinfo{$lang}->[0]} = $total_msg;
}

######################
# "Files generation" #
######################
if ($modflag) {               # Change modinfo if need.
    open (MODINFO, ">$htmldir/modinfo.dat") || die ("can't open $htmldir/modinfo.dat");

    foreach $mod (keys %modules){
	$string = "${$modinfo{$mod}->[0]}" . "," . "${$modinfo{$mod}->[1]}";
	$index = 2;
	while (${$modinfoold{$mod}->[$index]}){
	    $string = $string . "," . ${$modinfoold{$mod}->[$index]};
	    $index++;
	}
	print MODINFO "$mod,$string\n";
    }
    close (MODINFO);
}

######################
open (LANGINFO, ">$htmldir/langinfo.dat") || die ("can't open $htmldir/langinfo.dat");

foreach $lang (@langs){
    $string = "${$langinfo{$lang}->[0]}"; # in the future we add [1] and [2]
    $index = 1;
    while (${$langinfoold{$lang}->[$index]}){
	$string = $string . "," . ${$langinfoold{$lang}->[$index]};
	$index++;
	print $string . "\n";
    }
    print LANGINFO "$lang,$string\n";
}
close (LANGINFO);

######################
open (LANGMOD, ">$htmldir/langmod.dat") || die ("can't open $htmldir/langmod.dat");

foreach $lang (@langs){
    foreach $mod (keys %modules){
	$string = "${$langmod{$lang}->{$mod}->[0]}" . "," . "${$langmod{$lang}->{$mod}->[1]}" . "," . "${$langmod{$lang}->{$mod}->[2]}" . "," . "${$langmod{$lang}->{$mod}->[3]}" . "," . "${$langmod{$lang}->{$mod}->[4]}";
	$index = 5;
	
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
    ($lang,$mod) = @_;
   
   print "getmerge: $mod $lang\n";
   
    open(MERGE,"LANGUAGE=C msgmerge --output-file=$cvsroot/${$modules{$mod}->[0]}/$lang.new.po $cvsroot/${$modules{$mod}->[0]}/$lang.po $cvsroot/${$modules{$mod}->[0]}/${$modules{$mod}->[1]} |")
    || die ("unable to msgmerge");
    close(MERGE);
    open(MERGE,"cp -p $cvsroot/${$modules{$mod}->[0]}/$lang.new.po $posdir/$mod-$lang.po |")
    || die ("unable to cp");
    close(MERGE);
}

sub getmsgfmt{
    ($language,$mod) = @_;
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
    $total = $trns + $fuzz + $untr;
    return ($total,$trns,$fuzz,$untr);
}

sub gettrnsinfo {
    ($language,$mod) = @_;
   
    if (-f "$cvsroot/${$modules{$mod}->[0]}/$language.po") {
	open (AUTHOR, "LANGUAGE=C grep ^\\\"Last-Translator $cvsroot/${$modules{$mod}->[0]}/$language.po |")
	|| die ("unable to get the Last-Translator field");
	$author = <AUTHOR>;
	close (AUTHOR);
	$author =~ s/.*:\ //g;
	$author =~ s/\\n.*$//g;
	# With this line we remove the new line char
	$author = substr($author, 0, -1);

	open (DATEPOT, "LANGUAGE=C grep ^\\\"POT-Creation-Date $cvsroot/${$modules{$mod}->[0]}/$language.po |")
	|| die ("unable to get the POT-Creation-Date field");
	$date_pot = <DATEPOT>;
	close (DATEPOT);
	$date_pot =~ s/.*:\ //g;
	$date_pot =~ s/\\n.*$//g;

	open (DATEPO, "LANGUAGE=C grep ^\\\"PO-Revision-Date $cvsroot/${$modules{$mod}->[0]}/$language.po |")
	|| die ("unable to get the PO-Revision-Date field");
	$date_po = <DATEPO>;
	close (DATEPO);
	$date_po =~ s/.*:\ //g;
	$date_po =~ s/\\n.*$//g;	

	$status = getstatusfromdate ($date_pot, $date_po, $ttl);
	return ($status, $author);
    } else {
        return (0, "");
    }
}

sub getstatusfromdate {
    ($date_pot, $date_po, $ttl) = @_;

    open (POTTIME, "LANGUAGE=C date -d \"$date_pot\" +%s |")
    || die ("unable to execute date");
    $potseconds = <POTTIME>;
    close (POTTIME);

    open (POTIME, "LANGUAGE=C date -d \"$date_po\" +%s |")
    || die ("unable to execute date");
    $poseconds = <POTIME>;
    close (POTIME);
   
    $time = $potseconds - $poseconds;
    if ($time > $ttl) {
        return 0;
    } else {
        return 1;
    }
}
sub generatepot{
    $mod = @_[0];

    print "generatepot: $mod\n";

    if (${$modules{$mod}->[2]} ne ""){
      open (POTOUT,  "${$modules{$mod}->[2]} && cp -p $cvsroot/${$modules{$mod}->[0]}/${$modules{$mod}->[1]} $posdir |" ) || die ("could not generate ${$modules{$mod}->[1]}");
    } else {
      open (POTOUT,  "cd $cvsroot/${$modules{$mod}->[0]} && xml-i18n-update -P 1>&2 && cp -p $cvsroot/${$modules{$mod}->[0]}/${$modules{$mod}->[1]} $posdir |") || die ("could not generate ${$modules{$mod}->[1]}");
    }
    close (POTOUT);
}

# Array structures:
# modules  $mod:$moddir:$modpot:$modpotcreate:$modpomerge
# ${$modules{$mod}->[0]} = "Directory where its .po/.pot files are"
# ${$modules{$mod}->[1]} = "Its .pot file name"
# ${$modules{$mod}->[2]} = "Command to generate the .pot file"
# ${$modules{$mod}->[3]} = "Command to update the .po files"
#
# langmod  $mod:$lang:$trns:$fuzz:$untr:$trns_status:$last_trns
# ${$langmod{$lang}->{$mod}->[0]} = "trns";
# ${$langmod{$lang}->{$mod}->[1]} = "fuzz";
# ${$langmod{$lang}->{$mod}->[2]} = "untr";
# ${$langmod{$lang}->{$mod}->[3]} = "trns status";
# ${$langmod{$lang}->{$mod}->[4]} = "last trns";
#
# modinfo   $mod:$msgs:$recent_chng_date:$stable_brahch_name:$next_release_date
# ${$modinfo{$mod}->[0]} = "msgs in module";
# ${$modinfo{$mod}->[1]} = "date of msgs num changed";
# ${$modinfo{$mod}->[2]} = "stable branch of module name";
# ${$modinfo{$mod}->[3]} = "date of next release";
#
# langinfo   $lang:$total_trns:$score:$score_date
# ${$langinfo{$lang}->[0]} = "total trns msg for lang";
# ${$langinfo{$lang}->[1]} = "score of team";
# ${$langinfo{$lang}->[2]} = "date when score was achived";
