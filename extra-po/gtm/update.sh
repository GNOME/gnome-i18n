#!/bin/sh

PACKAGE="gtm"

if [ "x$1" = "x--help" ]; then

echo Usage: ./update.sh langcode
echo --help                  display this help and exit
echo
echo Examples of use:
echo ./update.sh da -- updated the da.po file 

elif [ "x$1" = "x" ]; then 

echo "Remember to type language code, ie. da for Danish, etc"

else

echo "Now merging $1.po with $PACKAGE.pot, and creating an updated $1.po ..." 

mv $1.po $1.po.old && msgmerge $1.po.old $PACKAGE.pot -o $1.po \
&& rm $1.po.old;

msgfmt $1.po --statistics

fi;
