AC_INIT(src/status.h)

AM_CONFIG_HEADER(config.h)

AM_INIT_AUTOMAKE(translation-status, 1.99.0)

AM_MAINTAINER_MODE

AC_PROG_INTLTOOL([0.26])

AC_PROG_CC
AC_ISC_POSIX
AC_HEADER_STDC
AM_PROG_LIBTOOL

PKG_CHECK_MODULES(TRANSLATION_STATUS, glib-2.0 >= 2.0.0 gobject-2.0 >= 2.0.0 libxml-2.0)
AC_SUBST(TRANSLATION_STATUS_CFLAGS)
AC_SUBST(TRANSLATION_STATUS_LIBS)

dnl ******************************
dnl Check for popt
dnl ******************************

AC_CHECK_LIB(popt,poptGetContext,POPT_LIBS=-lpopt,
	[AC_CHECK_HEADER(popt.h, , AC_MSG_ERROR([[
*** Couldn't find popt. Please download and install from
*** ftp://ftp.rpm.org/pub/rpm/dist/rpm-4.0.x/ and try again.]]))])
AC_SUBST(POPT_LIBS)


AC_DEFINE_UNQUOTED(STATUS_DATA, "${prefix}/var/cache/translation-status", "definition of STATUS_DATA")

ALL_LINGUAS=""

GETTEXT_PACKAGE=translation-status
AC_SUBST(GETTEXT_PACKAGE)
AM_GLIB_GNU_GETTEXT
AC_DEFINE_UNQUOTED(GETTEXT_PACKAGE,"$GETTEXT_PACKAGE", "definition of GETTEXT_PACKAGE")

AC_OUTPUT([
Makefile
src/Makefile
po/Makefile.in
data/Makefile
doc/Makefile
])
