#!/usr/bin/perl -w

$LANGUAGE = $ARGV[0];

if ("$LANGUAGE" eq ""){
print "You need to specify language code, ie. ./desk.pl da";
print "for Danish and so on...";
}

else{
$a="find ./ -print | egrep '.*\\.(desktop|soundlist"
  ."|directory)' | xargs grep 'Name\\[$LANGUAGE\\]\\=' | cut -d: -f1 "
  ."| uniq | cut -d/ -f2- > desk1";

$b="find ./ -print | egrep '.*\\.(desktop|soundlist"
  ."|directory)' | xargs grep 'Name\\=' | cut -d: -f1 "
  ."| uniq | cut -d/ -f2- > desk2";

print "Searching for missing Name[$LANGUAGE] entries...\n";

`$a`;
`$b`;

system "sort -d desk1 -o desk1";
system "sort -d desk2 -o desk2";

$c="diff desk1 desk2 -u0 | grep '^+' |grep -v '^+++'"
  ."|grep -v '^\@\@' > DESKTOP.missing";

`$c`;
`rm desk1 && rm desk2`;

print "Well, you need to fix these:\n\n";
system "more DESKTOP.missing";
print "\nThe list is saved in DESKTOP.missing\n";
}
