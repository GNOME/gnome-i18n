#!/usr/bin/perl -w

#  GNOME entry finder utility.
#  (C) 2000 The Free Software Foundation
# 
#  Author(s): Kenneth Christiansen


$VERSION = "1.0.0 beta";
$LANGUAGE = $ARGV[0];
$OPTION2 = $ARGV[1];
$SEARCH = "Name";

if (! $LANGUAGE){
    print "desk.pl:  missing file arguments\n";
    print "Try `desk.pl --help' for more information.\n";
}

if ($OPTION2){
    $SEARCH=$OPTION2;
}

if ($LANGUAGE){

if ($LANGUAGE=~/^-(.)*/){

    if ("$LANGUAGE" eq "--version" || "$LANGUAGE" eq "-V"){
        print "GNOME Entry finder $VERSION\n";
	print "Written by Kenneth Christiansen <kenneth\@gnome.org>, 2000.\n\n";
	print "Copyright (C) 2000 Free Software Foundation, Inc.\n";
	print "This is free software; see the source for copying conditions.  There is NO\n";
	print "warranty; not even for MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.\n";
    }

    elsif ($LANGUAGE eq "--help" || "$LANGUAGE" eq "-H"){
	print "Usage: ./desk.pl [LANGCODE] [ENTRY]\n";
	print "Checks .desktop and alike files for missing translations.\n\n";
	print "  -V, --version                shows the version\n";
	print "  -H, --help                   shows this help page\n";
	print "\nReport bugs to <kenneth\@gnome.org>.\n";
    }

    else{
    	print "desk.pl: invalid option -- $LANGUAGE\n";
    	print "Try `desk.pl --help' for more information.\n";
    }
}

else{

    $a="find ./ -print | egrep '.*\\.(desktop|soundlist"
      ."|directory)' | xargs grep '$SEARCH\\[$LANGUAGE\\]\\=' | cut -d: -f1 "
      ."| uniq | cut -d/ -f2- ";

    $b="find ./ -print | egrep '.*\\.(desktop|soundlist"
      ."|directory)' | xargs grep '$SEARCH\\=' | cut -d: -f1 "
      ."| uniq | cut -d/ -f2- ";

    print "Searching for missing $SEARCH\[$LANGUAGE\] entries...\n";

    open(BUF1, "$a|");
    open(BUF2, "$b|");

    @buf1 = sort (<BUF1>);
    @buf2 = sort (<BUF2>);

    open(OUT1, ">desk1");
	print OUT1 @buf1 ;
    close OUT1;

    open(OUT2, ">desk2");
        print OUT2 @buf2 ;
    close OUT2;

    $c="diff desk1 desk2 -u0 | grep '^+' |grep -v '^+++'"
      ."|grep -v '^\@\@' > DESKTOP.missing";

    `$c`;
    `rm desk1 && rm desk2`;

    print "Well, you need to fix these:\n\n";
    system "more DESKTOP.missing";
    print "\nThe list is saved in DESKTOP.missing\n";
    }
}

# while $file (@buf2){
# $list.=(@buf1=~/$file/) ? $file : "";
# }
