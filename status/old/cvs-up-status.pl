#!/usr/bin/perl

#
#  The GNOME Statustable Generator
#
#  Copyright (C) 2000 Free Software Foundation.
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

#use strict;

## Constants
 
$cvsroot = "../..";

## Use the supplied arguments

if ($ARGV[0]=~/^-(.)*/){
   if ("$ARGV[0]" eq "--module-file"
    || "$ARGV[0]" eq "-M"){ $ARG1=$ARGV[1];         &Main; }
}  else {                   $ARG1="STATUSFILES.in"; &Main; }
 
sub Main{

   if (! -s $ARG1) { print "File $ARG1 does not exist\n"; exit; }

   open STATUS, "$ARG1" || die "Cannot open file: $ARG1";

   my @modules;
   while (<STATUS>) { 
      next if /^#/; next if /^\n/; next if /^\s/;
      chomp $_; push @modules, "$_";
   }

   close STATUS;

   for ($n = 0; $n < @modules; $n++) {
      $module = $modules[$n];
      if ($module =~ /gnome-i18n/) { next; } 
      $module =~ s/\/po//;
      system("cd $cvsroot && cvs co $module");
   }
   system("cd $cvsroot && cvs co gnome-i18n");
}
