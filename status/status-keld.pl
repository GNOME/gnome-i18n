#!/usr/bin/perl
# Copyright (C) 2000 Free Software Foundation, Inc.
# frob <frob@df.ru>
# with great help of dand, kanikus and kmaraas
# Some changes by keld

use POSIX qw(locale_h);
###############
# "Constants" #
###############
@modules = (
     "achtung/po",
     "ammonite/po",
     "balsa/po",
     "bonobo/po",
     "bug-buddy/po",
     "control-center/po",
     "dia/po",
     "dr-genius/po",
     "ee/po",
     "eog/po",
     "evolution/po",
     "gnome-i18n/extra-po/firestarter",
     "gal/po",
     "gb/po",
     "galeon/po",
     "gconf/po",
     "gdm2/po",
     "gdm3/po",
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
     "glib/po",
     "gnome-i18n/extra-po/glimmer",
     "gnome-i18n/extra-po/gnapster",
     "gnome-applets/po",
     "gnome-chess/po",
     "gnome-core/po",
     "gnome-db/po",
     "gnome-i18n/extra-po/gnome-find",
     "gnome-games/po",
     "gnome-iconedit/po",
     "gnome-libs/po",
     "gnome-lokkit/po",
     "gnome-media/po",
     "gnome-pilot/po",
     "gnome-pim/po",
     "gnome-print/po",
     "gnome-utils/po",
     "gnome-vfs/po",
     "gnomeicu/po",
     "gnopo/po",
     "gnorpm/po",
     "gnome-i18n/extra-po/gnucash",
     "gnumeric/po",
     "gphoto/po",
     "gtk+/po",
     "gtkhtml/po",
     "gnome-i18n/extra-po/gtm",
     "gtop/po",
     "gtranslator/po",
     "guppi3/po",
     "gxsnmp/po",
     "gnome-i18n/extra-po/helix-gdm2",
     "ximian-setup-tools/po",
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

# it's for a current developer :-)
@langs = qw ( da );


# @langs = qw ( bg_BG.cp1251 ca cs da de el en_GB es et eu fi fr ga gl hr hu is it ja ko lt nl nn no pl pt pt_BR ro ru sk sl sp sr sv ta tr uk wa zh_TW.Big5 zh_CN.GB2312 );

$cvsroot = "/home/keld/cvs/gnome";
$htmldir = "/home/keld/www/gnome";

#############################
# "Subroutines declaration" # 
#############################
sub getmsgfmt;
sub generatepot;
sub getmerge;

####################
# "Initialization" #
####################
#
# read old dat-files
#
setlocale(LC_LANG, "C");
setlocale(LC_ALL, "C");

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

################
# "Main loops" #
################
my $modflag = 0; # does we need write modinfo?

foreach $mod (@modules){
	if ($mod=~/extra-po/){ # don't generate in extra-po
	} else {
	generatepot($mod);
	}
	if (-d "$cvsroot/$mod"){
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
	foreach $mod (@modules){
	    if (-f "$cvsroot/$mod/$lang.po" ) {
	    getmerge($lang,$mod);
	    @result = getmsgfmt("$lang.new",$mod);
            $_ = $mod;
            s/\//-/;
            s/-po//;
            $newname = $_;
            unlink("$posdir/$newname-$lang.po");
            link("$cvsroot/$mod/$lang.new.po", "$posdir/$newname-$lang.po");
#	    unlink("$cvsroot/$mod/$lang.new.po");
	    if ($result[1]){
	       $total_msg += $result[1];
	    }
	       ${$langmod{$lang}->{$mod}->[0]} = @result[1];
	       ${$langmod{$lang}->{$mod}->[1]} = @result[2];
    	       ${$langmod{$lang}->{$mod}->[2]} = @result[3];
	}
    }
	${$langinfo{$lang}->[0]} = $total_msg;
}

######################
# "Files generation" #
######################
if ($modflag) {               # Change modinfo if need.
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
close (MODINFO);

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
close (LANGMOD);



#END

########################
########################
## "Subroutines code" ##
########################
########################

sub getmerge{
    ($lang,$mod) = @_;
    my $file;
    $mod=~/\//;
    $file = ($' ne "po") ? $' : $`;
     if($file=~/\//){
         $file = ($' ne "rpm-3.0.3/po") ? $' : "rpm"; 
     }
     
    open(MERGE,"LANGUAGE=C msgmerge --output-file=$cvsroot/$mod/$lang.new.po $cvsroot/$mod/$lang.po $cvsroot/$mod/$file.pot |")
    || die ("unable to msgmerge");
    close(MERGE);
    open(MERGE,"mkdir -p $htmldir/$mod |") || die ("unable to mkdir"); close(MERGE);
    open(MERGE,"cp -p $cvsroot/$mod/$lang.new.po $htmldir/$mod/$lang.po |")
    || die ("unable to cp");
    close(MERGE);
}

sub getmsgfmt{
    ($language,$mod) = @_;
print "$language  $mod\n";
    my ($file, $ext, $total, $trns, $fuzz, $untr);
    if ($language eq "pot"){
	$mod =~/\//;
	$file = ($' ne "po") ? $' : $`;
     if($file=~/\//){
         $file = ($' ne "rpm-3.0.3/po") ? $' : "rpm"; 
     }

	$ext = "pot";
    } else {
	$file = $language;
	$ext = "po";
    }
    
    $file = ($file=~/\//) ? $' : $file;
    
    open(STAT,"LANGUAGE=C msgfmt --statistics -o /dev/null $cvsroot/$mod/$file.$ext 2>&1 |")
    || die ("unable to msgfmt");
    $line=<STAT>;
    close(STAT);
    ($trns) = $line =~ /(\d+) translated\s.+/;
    ($fuzz) = $line =~ /(\d+) fuzzy\s.+/;
    ($untr) = $line =~ /(\d+) untranslated\s.+/;
    $total = $trns + $fuzz + $untr;
    return($total,$trns,$fuzz,$untr);
}

sub generatepot{
    $mod = @_[0];
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
print "generatepot: $mod\n";

    if ($file eq "po-script-fu") {
      open (POTOUT,  "cd $cvsroot/gimp/po-script-fu && ./update.sh 2>&1 && cp gimp-script-fu.pot po-script-fu.pot && ln po-script-fu.pot $cvsroot/$mod/po_script-fu.pot |" );
     } elsif ($file eq "sawfish") {
 	open (POTOUT, "cd $cvsroot/$mod && cp -p $cvsroot/gnome-i18n/extra-po/sawfish/sawfish.pot $cvsroot/$mod/$file.pot |")
               || die ("could not sawfish");
     } else {  
#    } elsif (-x "$cvsroot/$mod/update.pl") {
 	open (POTOUT, "cd $cvsroot/$mod && xml-i18n-update -P 1>&2 && cp -p $cvsroot/$mod/$file.pot $htmldir/$mod/$file.pot |") || die ("could not update.pl");
#    } else {
#      if (-x "$cvsroot/$mod/update.pl") {
#	system ("cd $cvsroot/$mod && ./update.pl -P $file" );
#	link("$cvsroot/$mod/$file.pot", "$posdir/$file.pot");
#	return;
# commented out due to it breaking.
# WHY?
#     } else {  
#	open (POTOUT,  "xgettext --default-domain=$file --directory=$cvsroot/$domain \ " . 
#	      "--add-comments --keyword=_ --keyword=N_ --files-from=$cvsroot/$mod/POTFILES.in \ " . 
#	      " && test ! -f $file.po || ( rm -f $cvsroot/$mod/$file.pot \ " . 
#	      " && cp $file.po $cvsroot/$mod/$file.pot ) 2>&1 |") || die ("could not xgettext");
      }
#    }
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
