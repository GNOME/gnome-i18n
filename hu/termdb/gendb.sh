#!/bin/bash

# parameters

files=`cat cvsfiles`
cvsroot=./cvs

# action (with hardcoded branches)

cd $cvsroot

# use your own access instead this, better

export CVSROOT=:pserver:anonymous@anoncvs.gnome.org:/cvs/gnome

cvs -q -z3 co $files

cvs -q -z3 co -r control-center-1-0 -d control-center-1-0 control-center/po/hu.po
cvs -q -z3 co -r gnome-core-1-2 -d gnome-core-1-2 gnome-core/po/hu.po
cvs -q -z3 co -r LIBGTOP_STABLE_1_0 -d libgtop-1-0 libgtop/po/hu.po
cvs -q -z3 co -r gtk-1-2 -d gtk+-1-2 gtk+/po/hu.po
cvs -q -z3 co -r gnome-libs-1-0 -d gnome-libs-1-0 gnome-libs/po/hu.po

# call this from here

./gendb.pl

# database recreation (database's name is term, on a postgres server)

dropdb term
createdb term
psql term -f create.sql
