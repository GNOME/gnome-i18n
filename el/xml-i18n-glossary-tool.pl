#!/usr/bin/perl -w

#########################################################################
#                                                                       #
# We assume that the .gnumeric file is not compressed.                  #
# We assume that the first row of the .gnumeric file contains only      #
# "Term" "English Definition" "De" "De Definition" "El" "El Definition" #
# and so on.                                                            #
#                                                                       #
# Thanks of the XML::Twig/XML::Parse authors for their modules/example. #
#                                                                       #
#########################################################################

die "This script is not in use anymore.\nNow we are using the .csv format and you can use\nthe csv-to-pot.sh script to convert to .po.\nThis file will go away in a few weeks.\n\n";

use strict;
use XML::Twig;
use Getopt::Long;
use FileHandle;

my $VERSION = "0.4";

my %languages;
my %definitions; 

my $english_term;
my $english_definition;
my $local_term;

# Set to true once the first line of the .gnumeric line is processed.
# That is the description line.
my $finished_first_line = 0;

# Specified whether a POT file is to be generated.
my $dopot = 0;

# Marking of the row in  the .gnumeric file.
my $previous_row;

# File handle
my $fh = FileHandle->new();

# Main

GetOptions ("P|pot" => sub { $dopot=1 },
            "V|version" => \&::doversion,
            "h|help" => \&::usage
            );

my $glossary = $ARGV[0];
my $language = $ARGV[1];

# File names
my $glossary_pot = "glossary.pot";

defined($glossary) || die "Please use a glossary file.\nRun \"$0 --help\" for more help.\n";

if ( $dopot == 0 && !defined($language) )
{
  die "Please specify a language.\nRun \"$0 --help\" for more help.\n";
}

if ( $dopot )
{
	( -e $glossary_pot ) && die "$glossary_pot already exists. Remove/rename and try again.\n";

	open($fh, ">$glossary_pot") || die "Could not open file $glossary_pot: $!\n";

	&print_header($fh);
}
else
{
	my $local_po	 = "$language.po";
	( -e "$local_po" ) && die "$local_po already exists. Remove/rename and try again.\n";
	
	open($fh, ">$local_po") || die "Could not open file $local_po: $!\n";

	&print_header($fh);
}

# Initialising the Twig. We are interested in the "Cells".
my $twig= new XML::Twig(
			TwigRoots => { 'gmr:Cells' => 1 }, 
			TwigHandlers => { 'gmr:Cell' => \&gmrCell }
			);


$twig->parsefile($glossary) || die 'Could not parse file: $!\n';    # build the twig


print "Finished.\n";

sub gmrCell
{
	my($twig, $field)= @_;                    

	# If we are at the first column...
	if ( $field->att('Row') == 0 )
	{
		# Data collection, from the first row of the .gnumeric file.
		$_ = $field->text;

          	SWITCH: 
		{
               		if (/Term/) { $languages{'us'} = $field->att('Col'); last SWITCH; }
               		if (/English Definition/) { $definitions{'us'} = $field->att('Col'); last SWITCH; }
               		if (/De/) { $languages{'de'} = $field->att('Col'); last SWITCH; }
               		if (/Es/) { $languages{'es'} = $field->att('Col'); last SWITCH; }
               		if (/Fr/) { $languages{'fr'} = $field->att('Col'); last SWITCH; }
               		if (/It/) { $languages{'it'} = $field->att('Col'); last SWITCH; }
               		if (/Sv/) { $languages{'sv'} = $field->att('Col'); last SWITCH; }
               		if (/Ja/) { $languages{'ja'} = $field->att('Col'); last SWITCH; }
               		if (/Ko/) { $languages{'ko'} = $field->att('Col'); last SWITCH; }
               		if (/El/) { $languages{'el'} = $field->att('Col'); last SWITCH; }
			die "New column name found. Say \"$field->text;\" to simos\@hellug.gr. Exiting...\n";
           	}
	} else
	{
		# Sanity checks -- should run just once after the first row in .gnumeric.
		if ( $finished_first_line == 0 )
		{
			if ( !defined($languages{$language}) && !$dopot)
			{
				die "Could not find your language $language in $glossary. Exiting..\n";
			}

			$finished_first_line = 1;	# Actually, now we finished.

			$previous_row = 1;
		}

		if ( $field->att('Row') > $previous_row )
		{
			# Time to flush data.
			if ( $dopot )
			{
				print $fh "#\. $english_definition\n";
				print $fh "msgid \"$english_term\"\n";
				print $fh "msgstr \"\"\n";
				print $fh "\n";
			} else
			{
			if (!defined($local_term)) {$local_term="";}
				print $fh "#\. $english_definition\n";
				print $fh "msgid \"$english_term\"\n";
				print $fh "msgstr \"$local_term\"\n";
				print $fh "\n";
			}

			$previous_row = $field->att('Row');

			$local_term = "";
			$english_definition = "";
			$english_term = "";
		}

		# Filling in the variables to create a gettext record.
		$_ = $field->att('Col');

                SWITCH:
                {
               		if (/$languages{'us'}/) { $english_term = $field->text; last SWITCH; }
               		if (/$definitions{'us'}/) { $english_definition = $field->text; last SWITCH; }
			if ($languages{$language} == $field->att('Col') ) { $local_term = $field->text; last SWITCH; }
		}

	}


#    	print "Content   :", $field->text, "\n";
#	print "Column    :", $field->att('Col'), "\n";
#	print "Row       :", $field->att('Row'), "\n";
#	print "ValueType :", $field->att('ValueType'), "\n";
#	print "\n";

}

sub print_header
{
	my ($fh) = @_;
	print $fh <<EOF;
# SOME DESCRIPTIVE TITLE.
# Copyright (C) YEAR Free Software Foundation, Inc.
# FIRST AUTHOR <EMAIL\@ADDRESS>, YEAR.
#
#, fuzzy
msgid ""
msgstr ""
"Project-Id-Version: PACKAGE VERSION\\n"
"POT-Creation-Date: 2001-02-22 23:47+0000\\n"
"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n"
"Last-Translator: FULL NAME <EMAIL\@ADDRESS>\\n"
"Language-Team: LANGUAGE <LL\@li.org>\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=CHARSET\\n"
"Content-Transfer-Encoding: ENCODING\\n"

EOF
}

sub usage
{
	print <<EOF;
Usage: $0 [OPTION]... GLOSSARYFILE LANGUAGECODE
Manages the Gnome Glossary file.
Please use uncompressed .gnumeric files.

  -P, --pot                    generate the pot file only
  -h, --help                   show this help and exit
  -V, --version                show the version and exit

Examples of use:
xml-i18n-update --pot g.gnumeric   creates a new pot file from the glossary
xml-i18n-update g.gnumeric el   creats el.po for translation

Report bugs to <simos\@hellug.gr>.
EOF

exit (0);
}

sub doversion
{
	print "$0 Version $VERSION\n";
	exit (0);
}
