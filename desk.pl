#!/usr/bin/perl -w

$LANGUAGE = $ARGV[0];

$a="find ./ -print | egrep '.*\\.(desktop|soundlist"
  ."|directory)' | xargs grep '\\[$LANGUAGE\\]' | cut -d: -f1 "
  ."| uniq | cut -d/ -f2- > desk1";

$b="find ./ -print | egrep '.*\\.(desktop|soundlist"
  ."|directory)' | xargs grep 'Name' | cut -d: -f1 "
  ."| uniq | cut -d/ -f2- > desk2";

`$a`;
`$b`;

system "sort -d desk1 -o desk1";
system "sort -d desk2 -o desk2";

$c="diff desk1 desk2 -u0 | grep '^+' |grep -v '^+++'"
  ."|grep -v '^\@\@' > DESKTOP.missing";

`$c`;
`rm desk1 && rm desk2`;

print "Here you go:\n\n";
system "more DESKTOP.missing";
print "\nThe list is saved in DESKTOP.missing\n";
