#!/bin/bash
# By Robert Brady <rwb197@zepler.org>
# public domain
# no warranty

echo "This script has no warranty."

if [ -z $1 ]; then echo "You must specify the file"; exit; fi;

cp $1 $1.tmp
FROM=`cat $1 | grep Content-Type\: | cut -d "=" -f 2 | sed -e "s/.n\"//" | head -n 1`
echo "converting from" $FROM

if [ -z $1 ]; then echo "Could not find the encoding"; exit; fi;

(iconv --from=$FROM --to=UTF-8 < $1.tmp | sed -e "s/text\/plain; charset="$FROM"/text\/plain; charset=UTF-8/" > $1) || (echo "Could not convert - restoring old .po file" && cp $1.tmp $1)
