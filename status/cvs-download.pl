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
#                  Carlos Perell� Mar�n <carlos@gnome-db.org>

#use strict;

## Constants
 
my $cvsroot = "../..";
my $i18n = 0;

## Use the supplied arguments

if ($ARGV[0]=~/^-(.)*/){
   if ("$ARGV[0]" eq "--modules-file"
    || "$ARGV[0]" eq "-M"){ $ARG1=$ARGV[1];         &Main; }
}  else {                   $ARG1="modules-release.dat";    &Main; }
 
sub Main{

   if (! -s $ARG1) { print "File $ARG1 does not exist\n"; exit; }

   open MODULES, "$ARG1" || die "Cannot open file: $ARG1";

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
          print ("Actualizando $mod de la rama ${$modules{$mod}->[2]}\n");
          if ($module =~ /gnome-i18n/) {
              $i18n = 1;
	      next;
          }
          if (-f "$cvsroot/$module/CVS/Entries" ) {
              if (-f "$cvsroot/$module/CVS/Tag") {
	          if ("${$modules{$mod}->[2]}" ne "HEAD") {
                      system("cd $cvsroot/$mod && cvs update");
	          } else {
	              print "rama equivocada para el m�dulo $mod\n";
	          }
	      } elsif ("${$modules{$mod}->[2]}" ne "HEAD") {
	          print "rama equivocada para el m�dulo $mod\n";
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
          print ("No se actualizar� $mod\n");
	  if ($module =~ /gnome-i18n/) {
	      $i18n = 1;
	  }
      }
   }
   if ($i18n == 1) {
       if (-f "$cvsroot/gnome-i18n/CVS/Entries") {
          system("cd $cvsroot/gnome-i18n && cvs update");
       } else {
          system("cd $cvsroot && cvs co gnome-i18n");
       }
   }
}
