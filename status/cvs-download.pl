#!/usr/bin/perl -w
#
#  The GNOME Statustable Generator
#
#  Copyright (C) 2000-2001 Free Software Foundation.
#
#  This library is free software; you can redistribute it and/or
#  modify it under the terms of the GNU General Public License as
#  published by the Free Software Foundation; either version 2 of the
#  License, or (at your option) any later version.
#
#  This script is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
#  General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this library; if not, write to the Free Software
#  Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
#
#  Authors:        Frob <frob@df.ru>
#  With help from: Kenneth Christiansen <kenneth@gnu.org>
#                  Kjartan Maraas
#                  Dand
#                  Carlos Perelló Marín <carlos@gnome-db.org>

#use strict;
use Getopt::Long;
use POSIX qw(locale_h);

sub Usage;

####################
# "Initialization" #
####################

setlocale (LC_ALL, "C");

my $prog = "cvs-download.pl";

# Parse command line arguments, we'll use getopt::long for this.
# It's easier and work for multiple command line options.

# default values for options
my ($help, $cvsroot, $modfile);
$help = '';
$cvsroot = "~/cvs/gnome";
$modfile = "~/cvs/gnome/web-devel-2/content/projects/gtp/status/stable/modules.dat";

GetOptions ('cvsroot-dir=s'     => \$cvsroot,
	    'modules-file=s' => \$modfile,
	    'help'           => \$help,
	    ) or Usage();

if ($help) {
    Usage();
}

my $i18n = 0;

   open MODULES, "$modfile" || die "Cannot open file: $modfile";

   my @modules;
   while (<MODULES>) { 
#      next if /^#/; next if /^\n/; next if /^\s/;
#      chomp $_; push @modules, "$_";
      chomp;
      @info = split (/,/);
      $index = 1;
      while ($info[$index]){
          ${$modules{$info[0]}->[$index-1]} = $info[$index];
	  $index++;
      }
   }
   close (MODULES);


   foreach $mod (sort (keys %modules)){

      $module = "${$modules{$mod}->[0]}";
      if (${$modules{$mod}->[4]} eq "TRUE") {
          print ("Updating $mod from ${$modules{$mod}->[2]} branch\n");
          if ($module =~ /gnome-i18n/) {
              $i18n = 1;
	      next;
          }
          if (-f "$cvsroot/$module/CVS/Entries" ) {
              if (-f "$cvsroot/$module/CVS/Tag") {
	          if ("${$modules{$mod}->[2]}" ne "HEAD") {
                      system("cd $cvsroot/$mod && cvs update");
	          } else {
	              print "you have a wrong branch for $mod\n";
	          }
	      } elsif ("${$modules{$mod}->[2]}" ne "HEAD") {
	          print "you have a wrong branch for $mod\n";
	      } else {
	          system("cd $cvsroot/$mod && cvs update");
	      }
          } else {
              if ("${$modules{$mod}->[2]}" ne "HEAD") {
                  system("cd $cvsroot && cvs co -r ${$modules{$mod}->[2]} $mod");
	      } else {
	          system("cd $cvsroot && cvs co $mod");
	      }
          }
      } else {
	  if ($module =~ /gnome-i18n/) {
	      $i18n = 1;
	  }
      }
   }
   if ($i18n == 1) {
       print ("Updating gnome-i18n\n");
       if (-f "$cvsroot/gnome-i18n/CVS/Entries") {
          system("cd $cvsroot/gnome-i18n && cvs update");
       } else {
          system("cd $cvsroot && cvs co gnome-i18n");
       }
   }


sub Usage
{
    print <<"HELP";

Usage: $prog [OPTION]....
Generate html stats with the stats from status.pl.

    --help                      Prints this page to standard error.

    --cvsroot-dir <location>    Specifies the directory where will
                                be download the cvs modules.

    --modules-file <location>   Specifies the file name that has
                                the modules info.

HELP

exit -1;
}
