:
#!/bin/sh
package=gnome-find
#############################################################################
case $1 in
--help|-h)
echo "Simply call $0 with your LANG, e.g. $0 tr"
	exit 1
;;
*)
[ -f $package.pot -a -f $1.po ] && msgmerge $1.po $package.pot -o $1.po
msgfmt --statistics $1.po 
[ -f messages ] && rm -f messages
;;
esac
#############################################################################
