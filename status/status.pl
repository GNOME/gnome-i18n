#!/usr/bin/perl
# Copyright (C) 2000 Free Software Foundation, Inc.
# frob <frob@df.ru>
# with great help of dand, kanikus and kmaraas

###############
# "Constants" #
###############
@modules = (
     "control-center/po",
     "glib/po",
     "gtk+/po",
     "libbonobo/po",
     "libbonoboui/po",
     "libgnome/po",
     "libgnomebase/po",
     "libgnomecanvas/po",
     "libgnomeui/po",
     "oaf/po",
     "ximian-setup-tools/po"
);

@xml_i18n_tools_compliants = (
    "gtranslator/po",
    "ximian-setup-tools/po"
);

# it's for a current developer :-)
#@langs = ( "uk" );


@langs = qw ( az bg ca cs da de el en_GB es et eu fi fr ga gl hr hu 
	      is it ja ko lt ms nl no nn pl pt pt_BR ro ru sk sl sr sv ta tr uk
	      vi wa zh_TW.Big5 zh_CN.GB2312 );

$cvsroot = "/home/kmaraas/cvs/gnome2";
$htmldir = "/home/kmaraas/cvs/gnome/web-devel-2/content/projects/gtp/status";

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
# find xml-i18n-tools 
#
$xmldir = `which xml-i18n-toolize 2> /dev/null`;
chomp $xmldir;
die "couldn't found xml-i18n-toolize" unless -e $xmldir;
$xmldir =~ s@/bin/xml-i18n-toolize@@;

#
# read old dat-files
#
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
     
    open(MERGE,"msgmerge --output-file=$cvsroot/$mod/$lang.new.po $cvsroot/$mod/$lang.po $cvsroot/$mod/$file.pot |")
    || die ("unable to msgmerge");
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

sub generatepot{
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
    print "generatepot: $mod\n";

    if ($file eq "po-script-fu"){
      open (POTOUT,  "cd $cvsroot/gimp/po-script-fu && ./update.sh 2>&1 && cp gimp-script-fu.pot po-script-fu.pot |" );
    } elsif (grep /^$mod$/, @xml_i18n_tools_compliants) {
      open (POTOUT, "PACKAGE=$mod XML_I18N_EXTRACT=$xmldir/share/xml-i18n-tools/xml-i18n-update |");
    } else {  
      open (POTOUT,  "xgettext --default-domain=$file --directory=$cvsroot/$domain \ " . 
	  "--add-comments --keyword=_ --keyword=N_ --files-from=$cvsroot/$mod/POTFILES.in \ " . 
	  " && test ! -f $file.po || ( rm -f $cvsroot/$mod/$file.pot \ " . 
	  " && cp $file.po $cvsroot/$mod/$file.pot ) 2>&1 |") || die ("could not xgettext");
    }
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
