#!/bin/bash
# By Robert Brady <rwb197@zepler.org>
# public domain
# no warranty

echo "This script has no warranty."

#ENCODING_TO=ISO-8859-1

if [ -z $ENCODING_TO ]; then echo "You must set ENCODING_TO."; exit; fi;

if [ -z $1 ]; then echo "You must specify the file"; exit; fi;

cp $1 $1.tmp
echo "converting from UTF-8 to " $ENCODING_TO
(iconv --from=UTF-8 --to=$ENCODING_TO < $1.tmp | sed -e "s/charset=UTF-8/charset="$ENCODING_TO"/" > $1) || (echo "Could not convert - restoring old .po file" && cp $1.tmp $1)
