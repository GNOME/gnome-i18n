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
    $msg_str =~ s/^("Language-Team: ).*\\n"/$1Chinese (Hong Kong) <community\@linuxhall.org>\\n"/;
    return;
}

sub translate() {
# place/country names
  do_trans("巴布亞紐幾內亞", "巴布亞新幾內亞");
  do_trans("沙烏地阿拉伯", "沙地阿拉伯");
  do_trans("哥斯大黎加", "哥斯達黎加");
  do_trans("斯洛凡尼亞", "斯洛文尼亞");
  do_trans("斯洛維尼亞", "斯洛文尼亞");
  do_trans("索羅門群島", "所羅門群島");
  do_trans("克羅埃西亞", "克羅地亞");
  do_trans("坦尚尼亞", "坦桑尼亞");
  do_trans("亞賽拜然", "亞賽拜彊");
  do_trans("波士尼亞", "波斯尼亞");
  do_trans("賽普勒斯", "賽浦路斯");
  do_trans("依索比亞", "衣索匹亞");
  do_trans("瓜地馬拉", "危地馬拉");
  do_trans("宏都拉斯", "洪都拉斯");
  do_trans("馬爾地夫", "馬爾代夫");
  do_trans("模里西斯", "毛里裘斯");
  do_trans("莫三比克", "莫桑比克");
  do_trans("厄瓜多", "厄瓜多爾");
  do_trans("辛巴威", "津巴布韋");
  do_trans("義大利", "意大利");
  do_trans("盧安達", "盧旺達");
  do_trans("漢城", "首爾");
  do_trans("葉門", "也門");

# language names
# do_trans("", "");
  do_trans("布里多尼", "不列塔尼");
  do_trans("加泰羅尼亞", "加泰隆尼亞");
  do_trans("弗里西亞", "弗里斯蘭");
  do_trans("加利西亞", "加里西亞");
  do_trans("喬治亞", "格魯吉亞");
  do_trans("印地語", "印度語");

# other terms
# order of the words are important!
  do_trans("自由軟體基金會", "Free Software Foundation");
  do_trans("筆記型電腦", "手提電腦");
  do_trans("網際網路", "互聯網");
  do_trans("行動電話", "流動電話");
  do_trans("布林值", "邏輯值");
  do_trans("命令殼", "shell");
  do_trans("團隊", "隊伍");
  do_trans("名片", "咭片");
  do_trans("內建", "內置");
  do_trans("硬體", "硬件");
  do_trans("軟體", "軟件");
  do_trans("網路", "網絡");
  do_trans("連繫", "聯繫");
  do_trans("連絡", "聯絡");
  do_trans("正體", "繁體");
  do_trans("建構", "建立");
  do_trans("函式", "函數");
  do_trans("憑證", "證書");
  do_trans("隱私", "私隱");
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
  query_trans("數位", "數碼");

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
# make sure substitution won't fail because of something like
# 硬"\n"體
	 $msg_str =~ s/"\n"// unless ($msg_id eq "msgid \"\"\n");
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

