#include <glib.h>

typedef struct {
	gchar *version;
	gchar *maintitle;
	gchar *mainhead;
	gchar *mainfoot;
	GList *components;
	GList *locales;
} release;

typedef struct {
	gchar *name;
	GList *components;
} module;

typedef struct {
	gchar *name;
	gchar *dir;
	gchar *branch;
	gchar *podir;
	gchar *potname;
	release *release;
	gint nstrings;
	gint last_update;
	gint next_release;
	gboolean regenerate;
	gboolean download;
	GHashTable *translations;
	module *pmodule;
} component;

typedef struct {
	gchar *name;
	gchar *email;
	GList *translations;
} translator;

typedef struct {
	gint translated;
	gint fuzzy;
	gint untranslated;
	gchar *locale;
	gint last_update;
	component *pcomponent;
	translator *ptranslator;
} translation;
