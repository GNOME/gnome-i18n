#!/usr/bin/perl

# 1999-09-23 - commented out all stuff not in gnome 1.0.50

#Set the default locale so we know we get english strings
#from msgfmt.

# Multi-po-dirs module format: ("module_name", "custom_po_dir", ... )
@gimp = ("gimp", "po-libgimp", "po-plug-ins", "po-script-fu");

#Changed by frob (1)
%branches = (
	   "gnome-libs", "gnome-libs-1-0",
	   "libgtop", "LIBGTOP_STABLE_1_0",
	   "control-center", "control-center-1-0",
	   "gtk+", "gtk-1-2"
	  );

$gray = 0;
#End of changed by frob (1)

@modules = (
    "achtung",
#    "audiofile",
    "balsa",
    "bonobo",
    "bug-buddy",
    "control-center",
    "dia",
    "dr-genius",
#    "ee",
#    "esound",
    "eog",
    "evolution",
#    "g-print",
#    "gaby",
#    "gcad",
    "gconf",
    "gdict",
    "gdm2",
    "gedit",
    "gfloppy",
    "ggv",
    "ghex",
     \@gimp,
    "glade",
#    "glib",
#    "gnome-admin",
    "gnome-applets",
    "gnome-chess",
    "gnome-core",
    "gnome-db",
#    "gnome-fm",
    "gnome-games",
#    "gnome-hello",
    "gnome-libs",
    "gnome-media",
#    "gnome-network",
#    "gnome-objc",
    "gnome-pilot",
    "gnome-pim",
    "gnome-print",
#    "gnome-python",
    "gnome-utils",
    "gnome-vfs",
    "gnomeicu",
    "gnorpm",
    "gnumeric",
    "gphoto",
    "gtk+",
#    "gtkicq",
    "gtop",
    "guppi3",
    "gxsnmp",
    "libgtop",
    "magicdev",
    "mc",
    "nautilus",
#    "sane",
    "sodipodi",
);

# it's for a current developer :-)
#@langs = ( "ru");

@langs = ( "bg_BG.cp1251", "ca", "cs", "da","de", "el", "en_GB", "es", "et", 
"eu", "fi", "fr", "ga", "gl", "hr", "hu", "is", "it", "ja", "ko", "lt", "nl", 
"no", "pl", "pt", "pt_BR", "ro", "ru", "sk", "sv", "tr", "uk", "wa", 
"zh_TW.Big5", "zh_CN.GB2312" );

$msgfmt="msgfmt --statistics";
$cvsroot="/home/kmaraas/cvs/gnome";
$htmldir="/home/kmaraas/public_html/genreport";

sub generate_pot;
sub report_success;
sub get_stats;
sub fill_stats;

print "Generating the pot files...\n";

foreach $mod (@modules) {

  # Do we have multiple po dirs, like in Gimp?
  $multi = 0;
  while ($$mod[$multi]) {
    print "updating " . $$mod[$multi] .".pot... ";
    generate_pot ($$mod[0], $$mod[$multi]);
    report_success ($$mod[0], $$mod[$multi]);
    $multi++;
  }
  
  # Process modules with single po dirs.
  if (!$multi) {
    print "updating $mod.pot... ";
    generate_pot ($mod, $mod);
    report_success ($mod, $mod);
  }
}

print "\nGenerating statistics...\n";

open (TABLE, ">$htmldir/status.shtml") || die("can't open $htmldir/status.shtml");
print TABLE <<'EOF';
<!-- Config Stuff and Banner -->
<!--#set var="title" value="GNOME Project i18n Stats" -->
<!--#include virtual="/include/header.shtml" -->

<!-- Standard Page Table Heading -->
<!--#set var="title" value="The Stats" --> 
<!--#include virtual="/include/table-start.shtml" -->
<table cellpadding=1 cellspacing=1 border=1 width="90%">
<tr><td></td>
EOF
    $index = 0;
foreach $lang (@langs) {
    $langs_red = substr($lang, 0, 5);
    $langs_red[++$index] = $langs_red;
    print TABLE "<td align=center><a href=\"details.shtml#$lang\"><font face=\"arial, helvetica\" size=2>$langs_red</font></a></td>\n";
}
print TABLE "</tr>\n";

$gray = 0;

foreach $mod (@modules) {
  $multi = 0;
  $po_tr = 0;
  $mod_total = 0;
  
  foreach $lang (@langs) {
    $mod_tr{$lang} = 0;
  }

  while ($$mod[$multi]) {
    if ($$mod[0] eq $$mod[$multi]) {
      print "updating ";
      $po_dir = $$mod[0] . "/po";
    } else {
      $po_dir = $$mod[0] . "/" . $$mod[$multi];
    }
    print "$$mod[$multi]... ";
    if (-d "$cvsroot/$po_dir") {
      open(STAT,"msgfmt --statistics -o /dev/null $cvsroot/$po_dir/$$mod[$multi].pot 2>&1 |") || die ("unable to msgfmt");
      $line=<STAT>;
      close(STAT);  
      ($trans) = $line =~ /(\d+) translated\s.+/;
      ($fuzzy) = $line =~ /(\d+) fuzzy\s.+/;
      ($un) = $line =~ /(\d+) untranslated\s.+/;
      $mod_total += $trans + $fuzzy + $un - 1; # one fuzzy off for the pot header
      foreach $lang (@langs) {
	$po_tr = 0;
	get_stats ($po_dir, $lang);
	$mod_tr{$lang} += $po_tr;
#	print "[$mod_tr{$lang} ($lang)]\t\t\t";
      }
    }
    $multi++;
  }

  if (!$multi) {
    $po_dir = $mod . "/po";
    if (-d "$cvsroot/$po_dir") {
      print "updating $mod... ";
      open(STAT,"msgfmt --statistics -o /dev/null $cvsroot/$po_dir/$mod.pot 2>&1 |") || die ("unable to msgfmt");
      $line=<STAT>;
      close(STAT);  
      ($trans) = $line =~ /(\d+) translated\s.+/;
      ($fuzzy) = $line =~ /(\d+) fuzzy\s.+/;
      ($un) = $line =~ /(\d+) untranslated\s.+/;
      $mod_total = $trans + $fuzzy + $un  - 1; # one fuzzy off for the pot header
      print "$mod_total translations.\n"; 
      foreach $lang (@langs) {
	$po_tr = 0;
	get_stats ($po_dir, $lang);
	$mod_tr{$lang} = $po_tr;
      }
    }
    $module = $mod;
  } else {
    $module = $$mod[0];
    if ($mod_total) {
      print "$mod_total translations.\n";
    }
  }

  if (-d "$cvsroot/$po_dir") {
    
    
    $modulebg =$trbg = (($gray++) % 2) ? '#deded5' : '#c5c2c5';
    $modulecolor = "black";
    $moduleprint = $module;
        
    if ($branches{$module}){
    $modulebg = "#ffd700";
    $modulecolor = "#1e90ff";
    $moduleprint = "<a href=\"#branches_name\">$module</>";
    };
    
        print TABLE "<tr bgcolor='$trbg'  align=right><td bgcolor='$modulebg'><nobr><font color='$modulecolor' face=\"arial, helvetica\" size=2>$moduleprint</nobr></td>\n";

    # print "Totals:\n";
    foreach $lang (@langs) {
      if ($mod_total != 0) {
	$percent = $mod_tr{$lang} / $mod_total;
      } else {
	$percent = 0;
      }
      $num = int($percent * 100);
      #    print "[$lang: $mod_tr{$lang}/$mod_total=$num]\t\t";
      $translated{$lang} += $mod_tr{$lang};
      fill_stats ($num);
    }
    print TABLE "<td align=left bgcolor='$modulebg'><nobr><font color='$modulecolor' face=\"arial, helvetica\" size=2>$moduleprint</nobr></td>\n";
    $totals += $mod_total;
  }
}

print TABLE "<tr bgcolor=\"#ffd700\"><th><font face=\"arial, helvetica\" size=2>Total: </th>\n";
foreach $lang (@langs) {
  if (!$translated{$lang}) {
    $percent = 0;
  } else {
    if ($totals) {
      $percent = $translated{$lang} / $totals;
    } else {
      $percent = 0;
    }
  }
  $num = int($percent * 100);
#  print "$translated{$lang} / $totals = $percent, num: $num ($lang)\n";  
  printf TABLE "<td align=right><font face=\"arial, helvetica\" size=2>%d%%</font></td>\n", $num;
}
  print TABLE "<tr><td></td>";                                                                          
    foreach $lang (@langs_red) {
        print TABLE "<td align=center><a href=\"details.shtml#$lang\"><font face=\"arial, helvetica\" size=2>$lang</font></a></td>\n";
} 
print "\n";
print TABLE "</table><br><b>Report last generated: ";
print TABLE scalar localtime;
print TABLE " GMT+0200<br></b>";
print TABLE "<a name=\"branches_name\"><br>
	     <font color=\"#0000ff\"><b><u>Well, The Translation Monsters, the names of stable branches is:</u></b><br><br></a>
	     <table cellpadding=1 cellspacing=1 border=0 width=\"40%\">";

    foreach $key (keys (%branches)){
	print TABLE "<td><font color=\"#00008b\"><b>$key</b></td><td><font color=\"#36648b\"><b>$branches{$key}</b></td><tr>";
    }
print TABLE "</table>";

print TABLE "<a name=\"more_than_100\"><br>
	     <font color=\"#0000ff\"><b>Ok. You say: \"Vah!! That is `<font color=\"red\">101%!</font>'? Ahalam-mahalam! Why `<font color=\"red\">150%!</font>'?\"<br></a>
	     <font color=\"#000000\">It means that module (exclude gimp) is <font color=\"#ff3030\">NOT UP-TO-DATE!</font>
	     <br> Some strings were deleted from the module and you didn't update the translation after that.<br>
	     WARNING: After deadline we will not include such module scores in the your team total scores.";
	     

print TABLE <<"EOF";
<!-- End of Table -->
<!--#include virtual="/include/table-end.html" -->
EOF

close(TABLE);

print "Generating detailed report...\n";

open (REPORT, ">$htmldir/details.shtml") || die("can't open $htmldir/details.shtml");

print REPORT <<'EOF';
<!-- Config Stuff and Banner -->
<!--#set var="title" value="GNOME Project i18n Stats" -->
<!--#include virtual="/include/header.shtml" -->

<!-- Standard Page Table Heading -->
<!--#set var="title" value="The Stats (Stable Branches Where Applicable)" --> 
<!--#include virtual="/include/table-start.shtml" -->
EOF

foreach $lang (@langs) {

  print "updating language $lang: ";
  print REPORT "<a name=\"$lang\">\n";
  print REPORT "<h3>Translation status for $lang\n</h3><ul>";
  
  foreach $mod (@modules) {
    $multi = 0;
    
    while ($$mod[$multi]) {
      if ($$mod[$multi] eq $$mod[0]) {
	$po_dir = $$mod[0] . "/po";
      } else {
	$po_dir = $$mod[0] . "/" . $$mod[$multi];
      }
      
      if (-d "$cvsroot/$po_dir") {
	if (-f "$cvsroot/$po_dir/$lang.po") {
	  print "$$mod[$multi] ";
	  print REPORT "<li>$$mod[$multi]:<br>";
	  open(STAT,"msgfmt --statistics -o /dev/null $cvsroot/$po_dir/$lang.po 2>&1 |") || die ("unable to msgfmt");
	  $line=<STAT>;
	  close(STAT);
	  print REPORT "$line\n";
	} else {
	  print REPORT "<li><b>Missing $lang translation for the $po_dir module</b>\n";
	}
      }
      print REPORT "</ul>\n";
      $multi++;
    }
    if (-d "$cvsroot/$mod/po") {
      if (-f "$cvsroot/$mod/po/$lang.po") {
	print "$mod  ";
	print REPORT "<li>$mod:<br>";
	open(STAT,"msgfmt --statistics -o /dev/null $cvsroot/$mod/po/$lang.po 2>&1 |") || die ("unable to msgfmt");
	$line=<STAT>;
	close(STAT);
	print REPORT "$line\n";
      } else {
	print REPORT "<li><b>Missing $lang translation for the $mod module</b>\n";
      }
    }
    print REPORT "</ul>\n";
  }
  print "\n";
  print REPORT "<hr noshade size=1>";
}


print REPORT "Report last generated: ";
print REPORT scalar localtime;

print REPORT <<"EOF";
<!-- End of Table -->
<!--#include virtual="/include/table-end.html" -->
EOF
close(REPORT);

printf "done.\n";

# END

sub generate_pot {
  ($module, $pot_dir) = @_;
  my $domain;
  
  if ($module eq $pot_dir) {
    $domain = $module;
    $pot_dir = $module . "/po";
  } else {
    $domain = $pot_dir;
    $pot_dir = $module . "/" . $pot_dir;
  }
  
#  print "\ngenerate_pot(): module: $module ; pot_dir: $pot_dir ; domain: $domain \n";

#  if (!open (POTOUT, "cd $cvsroot/$pot_dir && ./update.sh 2>&1 |")) {
#    print " (no update.sh)"; 
    open (POTOUT,  "xgettext --default-domain=$domain --directory=$cvsroot/$module \ " . 
	  "--add-comments --keyword=_ --keyword=N_ --files-from=$cvsroot/$pot_dir/POTFILES.in \ " . 
	  " && test ! $domain.po || ( rm -f $cvsroot/$pot_dir/$domain.pot \ " . 
	  " && mv $domain.po $cvsroot/$pot_dir/$domain.pot ) 2>&1 |") || die ("could not xgettext");
#  }
  close (POTOUT);
}

sub report_success {
  ($module, $potfile) = @_;
  my $pot_dir, $path;
  
  if ($module eq $potfile) {
    $pot_dir = "po";
  } else {
    $pot_dir = $potfile;
  }

  $path = $cvsroot . "/" . $module . "/" . $pot_dir . "/" . $potfile . ".pot";
  if (-f "$path") {
    print "ok.\n";
  } else {
    print "FAILED! ($path)\n";
  }
}

sub get_stats {
  ($po_dir, $lang) = @_;
  my $trans, $fuzzy, $un;
  if (-f "$cvsroot/$po_dir/$lang.po") {
    open(STAT,"msgfmt --statistics -o /dev/null $cvsroot/$po_dir/$lang.po 2>&1 |") || die ("unable to msgfmt");
    $line=<STAT>;
    close(STAT);
    ($trans) = $line =~ /(\d+) translated\s.+/;
    $po_tr = $trans;
  }
}

sub fill_stats {
  ($percent) = @_;
  if ($num == 100) {
    print TABLE "<td><font color=\"blue\" face=\"arial, helvetica\" size=2>100%</font></td>\n";
  } else {
    if ($num == 0) {
      print TABLE "<td align=center><font face=\"arial, helvetica\" size=2>-</td>\n";
    } else {      
      if ($num < 25) {
	print TABLE "<td><font color=\"red\" face=\"arial, helvetica\" size=2>$percent%</font></td>\n";
      } else {
	if ($num < 50) {
	  print TABLE "<td><font color=\"magenta\" face=\"arial, helvetica\" size=2>$percent%</font></td>\n";
	} else {
	  if ($num > 100) {
	    print TABLE "<td><font color=\"red\" face=\"arial, helvetica\" size=2><b>$percent%!</b></font></td>\n";
	  } else {
	    print TABLE "<td><font color=\"#808000\" face=\"arial, helvetica\" size=2>$percent%</font></td>\n";
	  }
	}
      }
    }
  }
}


sub make_tarball {
  open (TEST, "tar chozf potfiles.tar.gz pot 2>&1") || die ("could not tar.gz");
}
