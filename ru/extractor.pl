#!/usr/bin/perl
# Copyright (C) 2000 FreeSoftware Foundation
# Valek Filippov frob@df.ru

# This script extract translations from *.desktop etc files
# to LL.desktop.po.

$counter = 1;

open (DESKTOP, "DESKTOP") || die ("Can't open DESKTOP file: $!");                 
while (<DESKTOP>) {                                                               
    chomp;                                                                        
    chop; chop; chop;                                                             
    $input = $_;     
    open (READ, $input) || die ("Can't open $_: $!");                                
    while (<READ>) {
	chomp;  
    	if (/\x5d=/){
	    $value = $';
	    $lang = $`;
	    $lang =~ /\x5b/;
	    $lang = $';
	    ${$value{$lang}->{$input}->[$counter]} = $value;
	    ${$value{"C"}->{$input}->[$counter]} = $prevstr;
	next;
	}
	/=/;
	$prevstr = $';
	$counter++;
    }
}

# here we have hash with all existent translations for all langs.
# we need remove repeated msgid's and write hash to files.

foreach $lang (keys %value){
    open (OUTPUT, ">$lang.desktop.po");
	foreach $file (sort keys %{@value{$lang}}){
	    foreach $string (sort scalar @{$value{$lang}->{$file}}){
		print OUTPUT "#: $file:$string\n";
	if (! $multiple{${$value{'C'}->{$file}->[$string-1]}}){
		print OUTPUT "msgid \"${$value{'C'}->{$file}->[$string-1]}\"\n";
		print OUTPUT "msgstr \"${$value{$lang}->{$file}->[$string-1]}\"\n\n";
		$multiple{${$value{'C'}->{$file}->[$string-1]}} = 1;
		}
	    }
	}
undef %multiple;
}