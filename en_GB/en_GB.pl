#!/usr/bin/perl

# (c) 2000 Robert Brady
#     2002 Bastien Nocera

# Released under the GNU General Public Licence, either version 2
# or at your option, any later version

# NO WARRANTY!

# Script to take a .pot file and make an en_GB translation of it.
# since full AI hasn't been invented yet, you need to inspect it
# by hand afterwards.
# 
# Interactive mode added for certain words by Peter Oliver, 2002-08-25.

# These are as-is

# en_AI         Anguillan English
# en_AC         Ascension Island English
# en_AU         Australian English
# en_AQ         Antartican English
# en_BB         Barbados English
# en_BM         Bermudan English
# en_BS         Bahamas English
# en_BZ         Belize English
# en_CX         Christmas Island English
# en_EU         European English
# en_FK         Fakland Islands English
# en_GB         British English
# en_GI         Gibraltan English
# en_GG         Guernsey English
# en_HK         Hong Kong English
# en_IE         Irish English
# en_IM         Manx English
# en_IO         British Indian Ocean English
# en_HM         Heard & McDonald Islands English
# en_JE         Jersey English
# en_JM         Jamaican English
# en_KY         Cayman Islands English
# en_MS         Montserrat English
# en_NF         Norfolk Island English
# en_NZ         New Zealand English
# en_PN         Pitcairn English
# en_SH         Saint Helena English
# en_TC         Turks & Caicos English
# en_VG         British Virgin Isles English
# en_ZA         South African English

# en_CA         Canadian English
#               (Canadian uses some US forms)

# The government websites of the following countries use British forms.

# en_BW         Botswanan English
# en_KE         Kenyan English
# en_ZM         Zambian English
# en_ZW         Zimbabwe English



# if you find an inaccuracy in this list, please mail me at 
# <rwb197@zepler.org>

use Time::gmtime;
use Term::ReadLine;

sub do_trans {
  my ($tf, $tt) = @_;
  $msg_str =~ s/\b$tf/$tt/g;
  $msg_str =~ s/\b\u$tf/\u$tt/g;
  $msg_str =~ s/\b\U$tf/\U$tt/g;
}

sub query_trans {
    my ($tf, $tt) = @_;
    if ( $msg_str =~ m/\b$tf/i ) {
	print STDERR "\nmsgid: ${msg_id}msgstr: $msg_str";
	if ( $rl->readline( "Change '$tf' to '$tt'? (y/N) " )
	     =~ m/^\s*y(es)?\s*$/i ) {
	    print STDERR "Changed\n";
	    do_trans( $tf, $tt );
	}
	else {
	    print STDERR "Not changed\n";
	}
    }
}

sub translate() {
  if (!($msg_str eq "\"\"\n")) {
  
    $date = sprintf("%04i-%02i-%02i %02i:%02i+0000", gmtime()->year+1900,
    gmtime()->mon+1, gmtime()->mday, gmtime()->hour, gmtime()->min);
  
    $msg_str =~ s/YEAR-MO-DA HO:MI\+ZONE/$date/;
    $msg_str =~ s/YEAR-MO-DA HO:MI\+DIST/$date/;
    $msg_str =~ s/FULL NAME <EMAIL\@ADDRESS>/Robert Brady <rwb197\@zepler.org>/;
    $msg_str =~ s/CHARSET/UTF-8/;
    $msg_str =~ s/ENCODING/8-bit/;
    $msg_str =~ s/LANGUAGE <LL\@li.org>//;
    return;
  }

  $msg_str = $msg_id;
  
  do_trans("analog", "analogue");
  do_trans("armor", "armour");
  do_trans("caliber", "calibre");
  do_trans("canceled", "cancelled");
  do_trans("canceling", "cancelling");
  do_trans("catalog", "catalogue");
  do_trans("centimeter", "centimetre");
  do_trans("centered", "centred");
  do_trans("center", "centre");
  query_trans("check", "cheque");
  do_trans("color", "colour");
  do_trans("customize", "customise");
  do_trans("customized", "customised");
  do_trans("defense", "defence");
  do_trans("dialer", "dialler");
  do_trans("dialing", "dialling");
  do_trans("dialed", "dialled");
  do_trans("dialog", "dialogue");
  do_trans("encyclopedia", "encyclopaedia");
  do_trans("endeavor", "endeavour");
  do_trans("favor", "favour");
  do_trans("fiber", "fibre");
  do_trans("flavor", "flavour");
  do_trans("fueled", "fuelled");
  do_trans("fueling", "fuelling");
  do_trans("harbor", "harbour");
  do_trans("honor", "honour");
  do_trans("humor", "humour");
  do_trans("initialize", "initialise");
  do_trans("jeweled", "jewelled");
  do_trans("judgment", "judgement");
  do_trans("kilometer", "kilometre");
  do_trans("labeled", "labelled");
  do_trans("license", "licence");
  do_trans("labor", "labour");
  do_trans("liter", "litre");
  query_trans("meter", "metre");
  do_trans("millimeter", "millimetre");
  do_trans("modeled", "modelled");
  do_trans("modeler", "modeller");
  do_trans("modeling", "modelling");
  do_trans("neighbor", "neighbour");
  do_trans("offense", "offence");
  do_trans("organize", "organise");
  do_trans("paneled", "panelled");
  do_trans("paneling", "panelling");
  do_trans("rumor", "rumour");
  do_trans("saber", "sabre");
  do_trans("scepter", "sceptre");
  do_trans("signaled", "signalled");
  do_trans("specter", "spectre");
  do_trans("theater", "theatre");
  query_trans("tire", "tyre");
  do_trans("totaled", "totalled");
  do_trans("totaler", "totaller");
  do_trans("totaling", "totalling");
  do_trans("trash", "wastebasket");
  do_trans("vapor", "vapour");
  do_trans("translator_credits", "Robert Brady <rwb197\@ecs.soton.ac.uk>\\n\"\n\"Bastien Nocera <hadess\@hadess.net>");

# This causes the string not to be copied
#  if ($msg_str eq $msg_id) {
#    $msg_str = "\"\"\n";
#  }
}

$mode = 0;
$rl = Term::ReadLine->new();

while (<>) {
   if  (/^#/) {
     s/SOME DESCRIPTIVE TITLE/English (British) translation/;
     $year = gmtime()->year+1900;
     s/YEAR/$year/;
     s/FIRST AUTHOR <EMAIL\@ADDRESS>/Robert Brady <rwb197\@ecs.soton.ac.uk>, Bastien Nocera <hadess\@hadess.net>/;
     print unless ((/^#, fuzzy/) && ($mode eq 0));
   } elsif (/^msgid /) {
     $msg_id .= substr($_, 6);
     $mode = 1;
   } elsif (/^msgstr "/) {
     $msg_str .= substr($_, 7);
     $mode = 2;
   } elsif (/^"/) {
       if ($mode == 1) {
         $msg_id .= $_;
         $mode = 1;
       } elsif ($mode == 2) {
         $msg_str .= $_;
         $mode = 2;
       }
   } else {
     if ($msg_id || $msg_str) {
       translate();
       print "msgid $msg_id";
       print "msgstr $msg_str";
       $msg_id = "";
       $msg_str = "";
     }
     print $_;
   }

}


if ($msg_id || $msg_str) {
  translate();
  print "msgid $msg_id";
  print "msgstr $msg_str";
  $msg_id = "";
  $msg_str = "";
}

