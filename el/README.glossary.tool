
Documentation file that is out of date. Do not use.
It will go away soon. Read the README file instead.

=====

A way to extract the terms from the GnomeGlossary.gnumeric file
into a .po file for translation.

*** REQUIREMENTS
	- A recent installation of Perl
	- expat library (from http://sourceforge.net/projects/expat/)
	- XML::Parser Perl module
	- XML::Twig Perl module

*** INSTALLATION

A. For the expat library, download the latest version of the library
(at this moment it's: http://download.sourceforge.net/expat/expat-1.95.1.tar.gz)
and 
	. ./configure
	. make
	. make install

B. For the two Perl modules, you can install them easily with

	# perl -MCPAN -eshell

	cpan shell -- CPAN exploration and modules installation (v1.59)
	ReadLine support enabled

	cpan> install XML::Parser
	...
	cpan> install XML::Twig
	...

If it is the first time you run perl like that, it may ask you some 
configuration questions.

*** USAGE

At the moment, we cover two test cases.

1. You want to start translating from scratch. You run

xml-i18n-update --pot GnomeGlossary.gnumeric

A glossary.pot is created. Rename it to XX.po and you may
start translating. 
For the header of the .po file you may change it to put your local
information (at the moment I write this, the header says not to put
any personal information. This document takes precedent untill it's
fixed.

The first comment line for each term has a significant role. It's the
term description provided in the .gnumeric file. Please do not alter
it. If you want to add your own comments, do so on a second comment line.
At the moment, please use a single line for the comment.

In the future there will be functionality to put the .po file back
in the .gnumeric document. 
For now, you only use your XX.po file.

2. You have already done some work on your copy of GnomeGlossary.gnumeric
and you want to make a nice XX.po file out of it.
Suppose your language is "Es". Just do:

xml-i18n-update  es

You will get a es.po file with the already made translations.

*** FEEDBACK

For any feedback, please contact Simos Xenitellis <simos@hellug.gr>.

