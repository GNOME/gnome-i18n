#!/usr/bin/perl  

#
#  The GNOME Statustable Generator
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
my $EXTDIR = "gnome-i18n/extra-po";

## Constants

$cvsroot = "/home/kanikus/cvs";
$htmldir = "$cvsroot/web-devel-2/content/projects/gtp/status";

@coreModules = (
     "nautilus/po",
     "oaf/po",
     "pan/po",
     "pybliographer/po",
     "rp3/po",
     "sodipodi/po",
     "sawfish/po",
     "$EXTDIR/xchat", 
     "achtung/po",                      
     "balsa/po",
     "bonobo/po",   
     "bug-buddy/po",
     "control-center/po",
     "control-center-HEAD/po",
     "dia/po",
     "dr-genius/po",
     "ee/po", 
     "eog/po",
     "evolution/po",
     "$EXTDIR/firestarter",
     "$EXTDIR/galeon",
     "gal/po",
     "gconf/po",
     "gdm2/po"
);

@normalModules = (
     "$EXTDIR/gdm2beta5",
     "gedit/po",
     "gernel/po",
     "gfax/po",  
     "gfloppy/po",
     "ggv/po",
     "ghex/po",
     "gimp/po",
     "gimp/po-libgimp",
     "gimp/po-script-fu",
     "gimp/po-plug-ins", 
     "gimp-freetype/po", 
     "glade/po",
     "$EXTDIR/gnapster",
     "gnome-admin-tools/po",
     "gnome-applets/po"
);

@extraModules = (
     "gnome-chess/po",  
     "gnome-core/po",   
     "gnome-applets/po",
     "gnome-chess/po",
     "gnome-core/po",
     "gnome-db/po",
     "$EXTDIR/gnome-find",
     "gnome-games/po",
     "gnome-iconedit/po",
     "gnome-libs/po",
     "gnome-libs-HEAD/po",
     "gnome-lokkit/po",
     "gnome-media/po",
     "gnome-pilot/po",    
     "gnome-pim/po",
     "gnome-print/po",
     "gnome-utils/po",    
     "gnome-vfs/po", 
     "gnomeicu/po",       
     "gnop/po",
     "gnopo/po",  
     "gnome-utils/po",
     "gnome-vfs/po",
     "gnomeicu/po",
     "gnopo/po",
     "gnorpm/po",
     "$EXTDIR/gnucash",
     "gnumeric/po", 
     "gphoto/po",
     "gtkhtml/po",
     "gtk+/po",
     "gtk+-HEAD/po",
     "$EXTDIR/gtm",
     "gtop/po",
     "gtranslator/po",
     "guppi3/po",  
     "gxsnmp/po",
     "$EXTDIR/helix-gdm2",
     "gtkhtml/po",
     "helix-setup-tools/po",
     "libgda/po",
     "libgtop/po",
     "magicdev/po",
     "mc/po",
     "memprof/po",
     "nautilus/po",
     "oaf/po",
     "pan/po",
     "pong/po",
     "pybliographer/po",
     "rp3/po",
     "sawfish/po",
     "gnome-i18n/extra-po/screem",
     "sodipodi/po",
     "gnome-i18n/extra-po/xchat",
     "xpdf/po"
);

push @modules, @coreModules, @normalModules, @extraModules; 

# Uncomment if you just want to do it for yourself
#@langs = ( "no" );

@langs = qw ( bg_BG.cp1251 ca cs da de el en_GB es et eu fi fr ga gl hr hu is it ja ko lt nl nn no pl pt pt_BR ro ru sk sl sp sr sv ta tr uk wa zh_TW.Big5 zh_CN.GB2312 );

sub getmsgfmt;
sub generatepot;
sub getmerge;

## Initialization

## read old dat-files
if (open (MODINFO, "$htmldir/modinfo.dat")){
    while (<MODINFO>) {
	chomp;
        @info = split(/,/);
	$index = 1;
	while ($info[$index]){
           ${$modinfoold{$info[0]}->[$index-1]} = $info[$index];
	   $index++;
	}
    }
}

if (open (LANGINFO, "$htmldir/langinfo.dat")){
    while (<LANGINFO>) {
	chomp;
        @info = split(/,/);
	$index = 1;
	while ($info[$index]){
	   ${$langinfoold{$info[0]}->[$index-1]} = $info[$index];
	   $index++;
	}
    }
}

if (open (LANGMOD, "$htmldir/langmod.dat")){
    while (<LANGMOD>) {
        chomp;
        @info = split(/,/);
        ($stupid, $stupid, @{$langmodold{$info[0]}->{$info[1]}}) = @info;
}
}

## Main loops
my $modflag = 0; # does we need write modinfo?

foreach $mod (@modules){
	if ($mod=~/extra-po/){ # don't generate in extra-po
	} else {
	GeneratePot($mod);
	}
	if (-d "$cvsroot/$mod"){
	@result = GetMsgfmt("pot",$mod);
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
	foreach $mod (@modules){
	    if (-f "$cvsroot/$mod/$lang.po" ) {
	    GetMerge($lang,$mod);
	    @result = getmsgfmt("$lang.new",$mod);
            $_ = $mod;
            s/\//-/;
            s/-po//;
            $newname = $_;
            unlink("$posdir/$newname-$lang.po");
            link("$cvsroot/$mod/$lang.new.po", "$posdir/$newname-$lang.po");
	    unlink("$cvsroot/$mod/$lang.new.po");
	    if ($result[1]){
	       $total_msg += $result[1];
	    }
	       ${$langmod{$lang}->{$mod}->[0]} = $result[1];
	       ${$langmod{$lang}->{$mod}->[1]} = $result[2];
    	       ${$langmod{$lang}->{$mod}->[2]} = $result[3];
	}
    }
	${$langinfo{$lang}->[0]} = $total_msg;
}

## Files generation
if ($modflag) {               # Change modinfo if needed.

open (MODINFO, ">$htmldir/modinfo.dat") || die ("can't open $htmldir/modinfo.dat");

foreach $mod (@modules){
$string = "${$modinfo{$mod}->[0]}" . "," . "${$modinfo{$mod}->[1]}";
$index = 2;
    while (${$modinfoold{$mod}->[$index]}){
	$string = $string . "," . ${$modinfoold{$mod}->[$index]};
	$index++;
    }
    print MODINFO "$mod,$string\n";
}
}
close MODINFO;

##-----------------------
open LANGINFO, ">$htmldir/langinfo.dat" || die "can't open $htmldir/langinfo.dat";

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

##-----------------------
open LANGMOD, ">$htmldir/langmod.dat" || die "Can't open file: $htmldir/langmod.dat";

foreach $lang (@langs){
    foreach $mod (@modules){
     $string = "${$langmod{$lang}->{$mod}->[0]}" . "," . "${$langmod{$lang}->{$mod}->[1]}" . "," . "${$langmod{$lang}->{$mod}->[2]}";
    $index = 3;
    while (${$langmodold{$lang}->{$mod}->[$index]}){
	$string = $string . "," . ${$langmodold{$lang}->{$mod}->[$index]};
	$index++;
    }
    print LANGMOD "$lang,$mod,$string\n";
}
}
close LANGMOD;

## Subroutines

sub GetMerge{
    ($lang,$mod) = @_;
    my $file;
    $mod=~/\//;
    $file = ($' ne "po") ? $' : $`;
     if($file=~/\//){
         $file = ($' ne "rpm-3.0.3/po") ? $' : "rpm"; 
     }
    
    $OUTFILE = "$cvsroot/$mod/$lang.new.po";
    $POFILE  = "$cvsroot/$mod/$lang.po";
    $POTFILE = "$cvsroot/$mod/$file.pot";

    open MERGE, "msgmerge --output-file=$OUTFILE $POFILE $POTFILE |" || die "Unable to merge";
    close(MERGE);
}

sub GetMsgfmt{
    ($language,$mod) = @_;
    print "$language  $mod\n";
    my ($file, $ext, $total, $trns, $fuzz, $untr);

    if ($language eq "pot"){ 	$mod =~/\//; $file = ($' ne "po") ? $' : $`;
        if($file=~/\//){ 	$file = ($' ne "rpm-3.0.3/po") ? $' : "rpm"; }
	$ext = "pot";
    } else {			$file = $language; 		$ext = "po"; }
    
    $file = ($file=~/\//) ? $' : $file;
    
    open(STAT,"msgfmt --statistics -o /dev/null $cvsroot/$mod/$file.$ext 2>&1 |")
    || die ("unable to msgfmt");
    $line=<STAT>;
    close(STAT);
    ($trns) = $line =~ /(\d+) translated\s.+/;
    ($fuzz) = $line =~ /(\d+) fuzzy\s.+/;
    ($untr) = $line =~ /(\d+) untranslated\s.+/;
    $total = $trns + $fuzz + $untr;
    return($total,$trns,$fuzz,$untr);
}

sub GeneratePot{
    $mod = $_[0];
    my ($domain, $file);
    $xgettext_plus = "";
    $mod=~/\//;
    $domain = $`;
    $file = ($' ne "po") ? $' : $`;
    if ($domain eq "helix-install"){
        $domain = "helix-install/src/rpm-3.0.3";
        $file = "rpm";
    }
    if ($' eq "po-script-fu"){
    $xgettext_plus = "&& $cvsroot/gimp/po-script-fu/script-fu-xgettext \ $cvsroot/gimp/plug-ins/script-fu/scripts/*.scm \ $cvsroot/gimp/plug-ins/gap/sel-to-anim-img.scm \ $cvsroot/gimp/plug-ins/webbrowser/web-browser.scm \ >> $cvsroot/gimp/po-script-fu/gimp-script-fu.po \ ";
    }
    print "Generatepot: $mod\n";

    if ($file eq "po-script-fu") {
      open (POTOUT,  "cd $cvsroot/gimp/po-script-fu && ./update.sh 2>&1 && cp gimp-script-fu.pot po-script-fu.pot |" );
    } else {
      if (-x "$cvsroot/$mod/update.pl") {
	system ("cd $cvsroot/$mod && ./update.pl -P $file" );
	return;
      } else {  
	open (POTOUT,  "xgettext --default-domain=$file --directory=$cvsroot/$domain \ " . 
	      "--add-comments --keyword=_ --keyword=N_ --files-from=$cvsroot/$mod/POTFILES.in \ " . 
	      " && test ! -f $file.po || ( rm -f $cvsroot/$mod/$file.pot \ " . 
	      " && cp $file.po $cvsroot/$mod/$file.pot ) 2>&1 |") || die ("could not xgettext");
      }
    }
    link("$cvsroot/$mod/$file.pot", "$posdir/$file.pot");
    close (POTOUT);
  }


# Array structures:
# langmod  $mod:$lang:$trns:$fuzz:$untr
# ${$langmod{$lang}->{$mod}->[0]} = "trns";
# ${$langmod{$lang}->{$mod}->[1]} = "fuzz";
# ${$langmod{$lang}->{$mod}->[2]} = "untr";
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
