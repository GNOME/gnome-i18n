#!/bin/sh

CC=gcc
EXTRA_CFLAGS="-Werror -static -g3 -fno-omit-frame-pointer"
PKGCONFIG=pkg-config
XMLCONFIG=xml-config
GLIBCONFIG=glib-config


# Check whether various devel files are present
if which $CC >/dev/null 2>&1; then
	:
else
	echo "C compiler must be present!" >&2
	exit 1
fi

if test ! -f status.c -a ! -f ./status.c; then
	echo "status.c must be present in the same directory along with this script!" >&2
	exit 1
fi

if test ! -f status.h -a ! -f ./status.h; then
	echo "status.h must be present in the same directory along with this script!" >&2
	exit 1
fi


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
	$PKGCONFIG xml && {
		XML_CFLAGS=`$PKGCONFIG --cflags xml`
		XML_LDFLAGS=`$PKGCONFIG --libs xml`
	}
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
COMPILE="$CC $EXTRA_CFLAGS -o status status.c $GLIB_CFLAGS $XML_CFLAGS $GLIB_LDFLAGS $XML_LDFLAGS"

if `eval $COMPILE`; then
	cat << _EOF_
Binary named "status" successfully generated.
You must place translation-status.xml and this binary into
the same directory before executing the binary.
_EOF_
fi
