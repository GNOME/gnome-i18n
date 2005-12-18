#!/usr/bin/perl -w

# (c) 2005 Abel Cheung <abelcheung [AT] gmail [DOT] com>

# Based on Americal -> British english conversion script written by:
# (c) 2000 Abigail Brady
#     2002 Bastien Nocera

# Released under the GNU General Public Licence, either version 2
# or at your option, any later version

# NO WARRANTY!

# This script converts a finished traditional Chinese translation with
# Taiwan flavored words into Hong Kong flavored words. No AI here, that
# is a university research topic and much more difficult than corresponding
# English one.

# NOTE: You should make sure zh_TW.po is under no-wrap mode, so that words
# with multiple characters are not seperated into 2 lines; otherwise they
# are not replaced.

require 5.8.0;
use strict;
use encoding 'utf8';
use POSIX qw(strftime);
use Term::ReadLine;

binmode(STDIN, ":utf8");
binmode(STDOUT, ":utf8");
binmode(STDERR, ":utf8");

my $mode = 0;
my $rl = Term::ReadLine->new("String Replacement");

my $msg_id = "";
my $msg_str = "";

sub do_trans {
  my ($tf, $tt) = @_;

  #tf =~ s/(.)/sprintf "\\x{%x}", $1/ge;
  #tt =~ s/(.)/sprintf "\\x{%x}", $1/ge;

  $msg_str =~ s/$tf/$tt/g;
}

sub query_trans {
    my ($tf, $tt) = @_;
    if ( $msg_str =~ m/\b$tf/i ) {
	print STDERR "\n=============\nmsgid:\n${msg_id}\nmsgstr:\n$msg_str\n";
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

sub translate_header() {
    my $curdate = strftime ("%Y-%m-%d %H:%M+0800", localtime());
  
    $msg_str =~ s/^("PO-Revision-Date: ).*\\n"/$1$curdate\\n"/;
    # $msg_str =~ s/FULL NAME <EMAIL\@ADDRESS>/Abigail Brady <morwen\@evilmagic.org>/;
    $msg_str =~ s/^("Language-Team: ).*\\n"/$1GNOME HK Team <community\@linuxhall.org>\\n"/;
    return;
}

sub translate() {
# order of the words are important!
  do_trans("自由軟體基金會", "Free Software Foundation");
  do_trans("網際網路", "互聯網");
  do_trans("正體中文", "繁體中文");
  do_trans("布林值", "邏輯值");
  do_trans("命令殼", "shell");
  do_trans("團隊", "隊伍");
  do_trans("硬體", "硬件");
  do_trans("軟體", "軟件");
  do_trans("網路", "網絡");
  do_trans("連繫", "聯繫");
  do_trans("連絡", "聯絡");
  do_trans("公分", "厘米");
  do_trans("公尺", "米");
  do_trans("您", "你");
  do_trans("裡", "裏");
  query_trans("影像", "圖像");
  query_trans("著", "着");
  query_trans("郵件", "電郵");
  query_trans("路由器", "router");
  query_trans("閘道器", "gateway");
  query_trans("資訊", "資料");

# This causes the string not to be copied
#  if ($msg_str eq $msg_id) {
#    $msg_str = "\"\"\n";
#  }
}

while (<>) {
   if  (/^#/) {
     print;
   } elsif (/^msgid/) {
     $msg_id .= $_;
     $mode = 1;
   } elsif (/^msgstr/) {
     $msg_str .= $_;
     $mode = 2;
   } elsif (/^"/) {
       if ($mode == 1) {
         $msg_id .= $_;
       } elsif ($mode == 2) {
         $msg_str .= $_;
       }
   } else {
     if ($msg_id || $msg_str) {
       if ($msg_id eq "msgid \"\"\n") {
	 translate_header();
       } else {
	 translate();
       }
       print "$msg_id";
       print "$msg_str";
       $msg_id = "";
       $msg_str = "";
     }
     print $_;
   }

}


if ($msg_id || $msg_str) {
  if ($msg_id eq "\"\"\n") {
    translate_header();
  } else {
    translate();
  }
  print "$msg_id";
  print "$msg_str";
  $msg_id = "";
  $msg_str = "";
}

