#!/usr/bin/perl -w

use Text::ParseWords;

$in="el.po";

open(IN, $in) || die "Could not open $in: $!\n";

undef $term_en;
undef $term_LL;

print <<EOS;
%20020123~231723	%PP First Last
%TU=00000003	%EN-US	%Wordfast TM v3.3x %EL-01      % User n°=xxxxxx

EOS

while (<IN>)
{
	if (/^msgid/) 
	{ 
		($null, $term_en) = quotewords("\"", 0, $_);
		if ( $term_en eq "" ) 
		{ 
			undef $term_en;
			next; 
		}

		$_ = <IN>;
		if (/^msgstr/)
		{
			($null, $term_LL) = quotewords("\"", 0, $_);
		}
		else
		{
			die "Expected msgstr contruct after $term_en in $in\n";
		}

		if ( $term_en eq "Default" )
		{
			$term_LL = "Εξ\' ορισμού";
		}

		printf "20020123~231759\tPP\t0\tEN-US\t%s\tEL-01\t%s\n", $term_en, $term_LL;
	}
}

$null = 0;
