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
	print "Usage: ./desk.pl [OPTIONS] ...LANGCODE ENTRY\n";
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

    my %in2;
    foreach (@buf1) {
        $in2{$_} = 1;
    }
 
    foreach (@buf2){
        if (!exists($in2{$_})){
            push @result, $_ } 
        }
    }

    open(OUT1, ">MISSING.$SEARCH");
       print OUT1 @result ;
    close OUT1;


    stat("MISSING.$SEARCH");
        print "\nWell, you need to fix these:\n\n" if -s _;
        print @result if -s _;
        print "\nThe list is saved in MISSING.$SEARCH\n" if -s _;
        print "\nWell, it's all perfect! Congratulation!\n" if -z _;
        unlink "MISSING.$SEARCH" if -z _;
}
