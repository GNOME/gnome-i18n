#!/usr/bin/perl
# Run this script from gendb.sh, it just creates a kimenet.txt 
# translation memory file from a cvs tree.

$cvsdir = "./cvs";

chdir ($cvsdir);

sub get_po_msgs;
sub escape;

system ("rm kimenet.txt");

open(STAT,"find . -name hu.po 2>&1 |") or die ("unable to find anything");

while(<STAT>)
 {
   $pathofpo = $_;
   
   chomp($pathofpo);
   
   get_po_msgs($pathofpo); 

 } # While stat

close (STAT);
 
sub get_po_msgs {
 ($ppo) = $_;

 chomp($ppo);

 $skip = 0;
 $id_c = 0;
 $str_c = 0;
 $tag = 0;

 open(OPUT,">> kimenet.txt") or die ("I can't write!!!\n");

 open(PO,$ppo) or die("Cannot open $ppo! (Permissions?)");

 print "processing ,$ppo,\n";

my $refs = "";
my $flags = "";
my $msgid = "";
my $msgstr = "";
my $id = 0;

my $action=0;
my $lineno=0;

while(<PO>)
{
        chomp $_;
        $lineno++;

	$_ =~ s/\r$//;

        if( /^\#[^:,]/ || /^\#$/ )
        {
                # ignore
        } 
        elsif( /^[ \t]*$/ )
        {
                if($flags ne "fuzzy")
                {

		 if ($msgstr ne "")
		  {
			print OPUT escape("$ppo"),"ﬂ", escape("$msgid"),"ﬂ", escape("$msgstr"),"\n";
		  }
                }
                $refs="";
                $flags="";
                $msgid="";
                $msgstr="";
        }
        elsif( /^\#:/ )
        {
                $_ =~ s/^\#: //;
                $refs = $_;
        }
        elsif( /^\#,/ )
        {
                $_ =~ s/^\#, //;
                $flags = $_;
        }
        elsif ( /^msgid/ )
        {
                $_ =~ s/^msgid "//;
                $_ =~ s/"$//;
                $_ =~ s/\\/\\\\/g;
                $msgid = $_;
                $action = 1;
        }
        elsif (/^msgstr/)
        {
                $_ =~ s/^msgstr "//;
                $_ =~ s/"$//;
                $_ =~ s/\\/\\\\/g;
                $msgstr = $_;
                $action = 2; 
        }
        elsif (/^s*".*"/)
        {
                # append
                $_ =~ s/^s*"//;
                $_ =~ s/"$//;
                $_ =~ s/\\/\\\\/g;
                if($action == 1)  
                {
                        $msgid=$msgid.$_;
                }
                elsif($action == 2)
                {
                        $msgstr=$msgstr.$_;
                }
                else
                {   
                        print "Whoa, I don't know what to do with this line:\n$_";
                }
        }
        else
        {   
                print "Unknown error reading this line no. $lineno :\n$_\n\n";
                exit 1;
        }
 } # while
if($flags ne "fuzzy")
{
 if ($msgstr ne "")
	{
	 print OPUT "$ppoﬂ$msgidﬂ$msgstr\n"; 
	}
}

  close(PO);
  close(OPUT);
  print "done\n";
} # SUB open_po

sub escape {
  shift() if ref($_[0]) || (defined $_[1] && $_[0] eq $DefaultClass);
  my $toencode = shift;
  return undef unless defined($toencode);
  $toencode=~s/"\n"/"<br>"/eg;
  $toencode=~s/</"&lt;"/eg;
  $toencode=~s/>/"&gt;"/eg;
  return $toencode;
}
