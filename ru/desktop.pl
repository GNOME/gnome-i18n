#!/usr/bin/perl
# Copyright 2000 Free Software Foundation
# Valek Filippov <frob@df.ru>
#
# This script read LL.desktop.po files and files from DESKTOP list.
# Parse it and create *.desktop, *.soundfiles and so on files.
# In DESKTOP we list *.desktop.in *.soundfiles.in files which format is
# same as *.desktop etc, but translatable string marked for gettext as
# _()
#
# Send any ideas/suggestions to frob@df.ru
 
# ${$value{$filename}->{$lang}->[$string]} = translated string
# $string -- the number of string in the *.*.in file.

@langs = qw (ca cs da de en en_GB es et eu fi fr ga gl hr hu it ja ko lt no pl pt pt_BR ru sk sv tr uk wa zh_TW.Big5 zh_CN.GB2312 );

# i need change it for reading all LL.desktop.po

# Reading *.po

$files = "";

foreach $lang (@lang){
open (READ, "$lang.desktop.po");
    while (<READ>){
	chomp;
	if (/#: /){
	    $files.= $' . " ";
	    next;
	}
	    chop($files);
	    @files = split(/ /, $files); # splited if more than 1 files
	    $files ="";
	    
				 
		while (<READ>){
		    if (/msgstr /){
		    $value = $';
		    chomp($value);
		    chop ($value);
		    $value = unpack ("x1 A*", $value ); # remove ""
		    last;
		    }
		}
		while (<READ>){
		  if ($_ ne "\n"){ 	
		     chop;    
		     $value.= unpack ("x1 A*", $_);
		  } else {
		       foreach $filestring (@files){
	    	        ($file , $string) = split(/:/, $filestring);
			${$value{$file}->{$lang}->[$string]} = $value;
			}
		      last;
	    	    }
		}    
	  	foreach $filestring (@files){
	            ($file , $string) = split(/:/, $filestring);
			${$value{$file}->{$lang}->[$string]} = $value;
		}
		    
    }
}

# Writing *.desktop and so on

open (DESKTOP, "DESKTOP") || die ("Can't open DESKTOP file: $!");
while (<DESKTOP>) {
    chomp;
    open (INPUT, $_) || die ("Can't open $_: $!");
    $input = $_;
    chop; chop; chop; # i don't found how to beuty do it 
    open (OUTPUT, ">$_") || die ("Can't open $_: $!");
    $counter = 1;
    while (<INPUT>) {
    $instrings = $_;
	chomp;
	if (/_\x28\"/) {
	$orig = $';
	chop($orig);
	chop($orig);
	$right = $orig;
	$left = $`;
	chop($left);
	$orig = $left . "=" . $right . "\n";;
	print OUTPUT $orig;
	foreach $lang (sort keys (%{@value{$input}})) {
	    if (${$value{$input}->{$lang}->[$counter]}){
	    print OUTPUT $left . "[" . $lang. "]=".  ${$value{$input}->{$lang}->[$counter]}. "\n";
	    }
	  }
	} else {
		print OUTPUT $instrings;
	}
	$counter++; # the number of string
	}
}