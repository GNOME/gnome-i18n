#!/bin/sh
#####################################################################
package=gwvedit
#####################################################################

__backup ()  {
	[ -f $1~ -a -r $1~ ] && rm -f $1~
	cp -p $1 $1~
}

[ $# -lt 1 ] && {
	echo "!ERROR¡ You need to give your language code (e.g. \"tr\")."
		exit 1
}

language="${1%.po}"

[ -f $package.pot ] || {
	echo "!ERROR¡ Necessary file \"$package.pot\" is not present!"
		exit 1
}

[ -f $language.po ] || {
	echo "!ERROR¡ Translation file \"$language.po\" is missing!"
		exit 1
}

__backup $language.po && msgmerge $language.po $package.pot -o $language.po
[ -f $language.po ] && mv $language.po~ $language.po

echo "File \"$language.po\" updated/merged: statistics:"
msgfmt -v $language.po

exit 0
