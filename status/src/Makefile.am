INCLUDES = -I$(top_srcdir)					\
	   $(TRANSLATION_STATUS_CFLAGS)				\
           -DG_LOG_DOMAIN=\"Status\"				\
	   -DLOCALEDIR=\""$(prefix)/$(DATADIRNAME)/locale"\"	\
	   -DDATADIR=\""$(datadir)"\"

bin_PROGRAMS = translation-status

translation_status_SOURCES =         \
	status.h		\
	status-server.h		\
	status-server.c		\
	status-module.h		\
	status-module.c		\
	status-version.h	\
	status-version.c	\
	status-view.h		\
	status-view.c		\
	status-xml.c		\
	main.c

translation_status_LDADD = $(TRANSLATION_STATUS_LIBS) $(INTLLIBS)
