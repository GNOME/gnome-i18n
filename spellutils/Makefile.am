## Process this file with automake to produce Makefile.in

AUTOMAKE_OPTIONS = foreign

SUBDIRS = intl po

bin_PROGRAMS = pospell newsbody

common = lib.c exec.c lib.h

pospell_SOURCES = pospell.c pospell_write.c pospell_read.c pospell.h \
                  $(common)
EXTRA_pospell_SOURCES = getopt.h
pospell_LDADD = @LIBOBJS@

newsbody_SOURCES = newsbody.c newsbody_write.c newsbody_read.c header.c \
                   newsbody.h $(common)
EXTRA_newsbody_SOURCES = getopt.h strncasecmp.c strncasecmp.h
newsbody_LDADD = @LIBOBJS@ @newsbody_LIBOBJS@

man_MANS = pospell.1 newsbody.1

EXTRA_DIST = $(man_MANS) FILES
