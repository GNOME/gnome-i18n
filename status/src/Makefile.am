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
	status-translation.h	\
	status-translation.c	\
	status-translator.h	\
	status-translator.c	\
	status-team.h		\
	status-team.c		\
	status-xml.c		\
	status-web.c		\
	main.c

translation_status_LDADD = $(TRANSLATION_STATUS_LIBS) $(INTLLIBS) $(POPT_LIBS)
