#!/usr/bin/perl

# (c) 2000 Robert Brady

# Released under the GNU General Public Licence, either version 2
# or at your option, any later version

# NO WARRANTY!

# Script to take a .pot file and make an en_GB translation of it.
# since full AI hasn't been invented yet, you need to inspect it
# by hand afterwards.

# Can be used as-is for Irish, Gibraltan, Channel Isles, Canadian,
# Australian, Falkandese, etc.

use Time::gmtime;

sub do_trans {
  my ($tf, $tt) = @_;
  $msg_str =~ s/$tf/$tt/;
  $msg_str =~ s/\u$tf/\u$tt/;
  $msg_str =~ s/\U$tf/\U$tt/;
}

sub translate() {
  if (!($msg_str eq "\"\"\n")) {
  
    $date = sprintf("%04i-%02i-%02i %02i:%02i+0000\n", gmtime()->year+1900,
    gmtime()->mon+1, gmtime()->mday, gmtime()->hour, gmtime()->min);
  
    $msg_str =~ s/YEAR-MO-DA HO:MI\+ZONE/$date/;
    $msg_str =~ s/YEAR-MO-DA HO:MI\+DIST/$date/;
    $msg_str =~ s/FULL NAME <EMAIL\@ADDRESS>/Robert Brady <rwb197\@zepler.org>/;
    $msg_str =~ s/CHARSET/iso-8859-1/;
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
  # CHECK -> CHEQUE (sometimes)
  do_trans("color", "colour");
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
  do_trans("jeweled", "jewelled");
  do_trans("judgment", "judgement");
  do_trans("kilometer", "kilometre");
  do_trans("labeled", "labelled");
  do_trans("license", "licence");
  do_trans("labor", "labour");
  do_trans("liter", "litre");
  # METER -> METRE (sometimes)
  do_trans("millimeter", "millimetre");
  do_trans("modeled", "modelled");
  do_trans("modeler", "modeller");
  do_trans("modeling", "modelling");
  do_trans("neighbor", "neighbour");
  do_trans("offense", "offence");
  do_trans("paneled", "panelled");
  do_trans("paneling", "panelling");
  do_trans("rumor", "rumour");
  do_trans("saber", "sabre");
  do_trans("scepter", "sceptre");
  do_trans("signaled", "signalled");
  do_trans("specter", "spectre");
  do_trans("theater", "theatre");
  do_trans("totaled", "totalled");
  do_trans("totaler", "totaller");
  do_trans("totaling", "totalling");
  # TIRE -> TYRE (sometimes)
  do_trans("vapor", "vapour");

  if ($msg_str eq $msg_id) {
    $msg_str = "\"\"\n";
  }
}

while (<>) {
   if  (/^#/) {
     s/SOME DESCRIPTIVE TITLE/English (British) translation/;
     $year = gmtime()->year+1900;
     s/YEAR/$year/;
     s/FIRST AUTHOR <EMAIL\@ADDRESS>/Robert Brady <rwb197\@ecs.soton.ac.uk>/;
     print;
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

