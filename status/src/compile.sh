#!/bin/sh

CC=gcc
EXTRA_CFLAGS="-Werror -g3 -fno-omit-frame-pointer"
EXTRA_LDFLAGS="-lpopt"
PKGCONFIG=pkg-config
XMLCONFIG=xml-config
GLIBCONFIG=glib-config
FILELIST="html-reports.c main.c po-management.c xml-parser.c"


# Check whether various devel files are present
if which $CC >/dev/null 2>&1; then
	:
else
	echo "C compiler must be present!" >&2
	exit 1
fi

for i in $FILELIST status.h; do
	if test ! -f $i -a ! -f ./$i; then
		echo "$i must be present in the same directory along with this script!" >&2
		exit 1
	fi
done

HAS_GLIB1=false
HAS_GLIB2=false
HAS_XML=false
if which $PKGCONFIG >/dev/null 2>&1; then
	$PKGCONFIG glib && {
		GLIB_CFLAGS=`$PKGCONFIG --cflags glib`
		GLIB_LDFLAGS=`$PKGCONFIG --libs glib`
	}
	$PKGCONFIG glib-2.0 && {
		test -n "$GLIB_CFLAGS" && echo "Both glib 1.x and 2.x present. Will pick 2.x."
		GLIB_CFLAGS=`$PKGCONFIG --cflags glib-2.0`
		GLIB_LDFLAGS=`$PKGCONFIG --libs glib-2.0`
	}
	if $PKGCONFIG xml; then
		XML_CFLAGS=`$PKGCONFIG --cflags xml`
		XML_LDFLAGS=`$PKGCONFIG --libs xml`
	elif $PKGCONFIG libxml; then
		XML_CFLAGS=`$PKGCONFIG --cflags libxml`
		XML_LDFLAGS=`$PKGCONFIG --libs libxml`
	fi
else
	$GLIBCONFIG --version >/dev/null 2>&1 && {
		GLIB_CFLAGS=`$GLIBCONFIG --cflags glib`
		GLIB_LDFLAGS=`$GLIBCONFIG --libs glib`
	}
	$XMLCONFIG --version >/dev/null 2>&1 && {
		XML_CFLAGS=`$XMLCONFIG --cflags`
		XML_LDFLAGS=`$XMLCONFIG --libs`
	}
fi

if test "$GLIB_LDFLAGS" = ""; then
	cat >&2 << _EOF_
You must install glib before proceeding. Either glib 1.x or glib 2.x is OK,
but the devel files must be installed correctly.
_EOF_
	exit 1
fi

if test "$XML_LDFLAGS" = ""; then
	cat >&2 << _EOF_
You must install libxml development files before compiling this stuff.
Note that what you need is not libxml2, but libxml only.
_EOF_
	exit 1
fi


# Really do the work now
COMPILE="$CC $GLIB_CFLAGS $XML_CFLAGS $EXTRA_CFLAGS -o status $FILELIST $GLIB_LDFLAGS $XML_LDFLAGS $EXTRA_LDFLAGS"

if `eval $COMPILE`; then
	cat << _EOF_
Binary named "status" successfully generated.
You must place translation-status.xml and this binary into
the same directory before executing the binary.
_EOF_
fi
