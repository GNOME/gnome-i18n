/* Translation Status program
 *
 * Author: Carlos Perelló Marín <carlos@gnome.org>
 * 
 * Copyright (C) 2002-2003 Carlos Perelló Marín
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

#include <stdio.h>
#include "status.h"
#include "status-module.h"
#include "status-translation.h"

struct _StatusModule {
	GObject object;

	GString *name;
	GList   *versions;
};

struct _StatusModuleClass {
	GObjectClass parent_class;
};

static void status_module_class_init (StatusModuleClass *klass);
static void status_module_init       (StatusModule *module, StatusModuleClass *klass);
static void status_module_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

static void
module_free_versions (gpointer data, gpointer user_data)
{
	StatusVersion *version;

	version = STATUS_VERSION(data);

	g_object_unref (version);
}

/*
 * StatusModule object implementation
 */
static void
status_module_class_init (StatusModuleClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_module_finalize;
}

static void
status_module_init (StatusModule *module, StatusModuleClass *klass)
{
	g_return_if_fail (STATUS_IS_MODULE (module));

	module->name = NULL;
	module->versions = NULL;
}

static void
status_module_finalize (GObject *object)
{
	StatusModule *module = (StatusModule *) object;

	g_return_if_fail (STATUS_IS_MODULE (module));

	if (module->name != NULL) {
		g_string_free (module->name, TRUE);
		module->name = NULL;
	}
	if (module->versions != NULL) {
		g_list_foreach (module->versions, module_free_versions, NULL);
		g_list_free (module->versions);
		module->versions = NULL;
	}

	parent_class->finalize (object);
}

GType
status_module_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusModuleClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_module_class_init,
			NULL,
			NULL,
			sizeof (StatusModule),
			0,
			(GInstanceInitFunc) status_module_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusModule",
					       &info, 0);
	}
	return type;
}


static void
generate_version_report (gpointer data, gpointer user_data)
{
	StatusVersion *version;
	StatusTranslation *translation;
	GHashTable *translations;
	FILE *module_index;
	gint nstrings, translated, fuzzy, untranslated;
	gfloat ptranslated, pfuzzy, puntranslated;
	GList *lang;

	version = STATUS_VERSION (data);
	module_index = (FILE *) user_data;

	translations = status_version_get_translations (version);
	
/*	status_version_report (version);*/

	fprintf (module_index, "    <div class=\"moduleVersion\">\n");
	fprintf (module_index, "      <h1 class=\"moduleVersionTitle\">%s</h1>\n", status_version_get_id (version));
	fprintf (module_index, "      <table class=\"moduleVersionTable\">\n");
	fprintf (module_index, "        <tr>\n");
	fprintf (module_index, "          <td>%s</td>\n", "language");
	fprintf (module_index, "          <td>%s</td>\n", "trans.");
	fprintf (module_index, "          <td>%%</td>\n");
	fprintf (module_index, "          <td>%s</td>\n", "fuzzy");
	fprintf (module_index, "          <td>%%</td>\n");
	fprintf (module_index, "          <td>%s</td>\n", "untrans.");
	fprintf (module_index, "          <td>%%</td>\n");
	fprintf (module_index, "          <td>%s</td>\n", "graph");
	fprintf (module_index, "        </tr>\n");
	
	if (nstrings != translated + fuzzy + untranslated) {
		/* TODO: We should implement a way to show a warning */
	}
	
	for (lang = g_list_first (langs); lang != NULL; lang = g_list_next (lang)) {
		translation = g_hash_table_lookup (translations, lang->data);
		if (translation == NULL) {
			continue;
		}
		
		translated = status_translation_get_ntranslated (translation);
		fuzzy = status_translation_get_nfuzzy (translation);
		untranslated = status_translation_get_nuntranslated (translation);
		version = status_translation_get_version (translation);
		nstrings = status_version_get_nstrings (version);
		ptranslated = (float) translated / (float) nstrings;
		pfuzzy = (float) fuzzy / (float) nstrings;
		puntranslated = (float) untranslated / (float) nstrings;
		
		fprintf (module_index, "        <tr>\n");
		fprintf (module_index, "          <td>%s</td>\n", lang->data);
		fprintf (module_index, "          <td>%d</td>\n", translated);
		fprintf (module_index, "          <td>%.2f</td>\n", ptranslated*100);
		fprintf (module_index, "          <td>%d</td>\n", fuzzy);
		fprintf (module_index, "          <td>%.2f</td>\n", pfuzzy*100);
		fprintf (module_index, "          <td>%d</td>\n", untranslated);
		fprintf (module_index, "          <td>%.2f</td>\n", puntranslated*100);
		fprintf (module_index, "          <td><img src=\"/images/bar0.png\" height=\"15\" width=\"%d\"><img src=\"/images/bar4.png\" height=\"15\" width=\"%d\"><img src=\"/images/bar1.png\" height=\"15\" width=\"%d\"></td>\n", (gint) (200.0*ptranslated), (gint) (200.0*pfuzzy), (gint) (200.0*puntranslated));
		fprintf (module_index, "        </tr>\n");
	}

	fprintf (module_index, "      </table>\n");
	fprintf (module_index, "    </div>\n");
}

/**
 * status_module_new
 * @name: The module name
 *
 * Create a new #StatusModule object, with the name @name
 */
StatusModule *
status_module_new (const gchar *name)
{
	StatusModule *module;

	module = g_object_new (STATUS_TYPE_MODULE, NULL);

	module->name = g_string_new (name);

	return module;
}

/**
 * status_module_get_name
 * @module: a #StatusModule object
 *
 * Returns the module name. The returned string should be freed when no longer needed.
 */
gchar *
status_module_get_name (const StatusModule *module)
{
	g_return_val_if_fail (STATUS_IS_MODULE (module), NULL);
	
	if (module->name != NULL) {
		return g_strdup (module->name->str);
	} else {
		return NULL;
	}
}

/**
 * status_module_add_version
 */
gboolean
status_module_add_version (StatusModule *module, StatusVersion *version)
{
	g_return_val_if_fail (STATUS_IS_MODULE (module), FALSE);
	g_return_val_if_fail (STATUS_IS_VERSION (version), FALSE);

	module->versions = g_list_append (module->versions, g_object_ref (version));
	return TRUE;
}

/**
 * status_module_report
 */
void
status_module_report (StatusModule *module)
{
	FILE *module_index;
	gchar *index_name;
	gchar *title;

	index_name = g_strdup_printf ("%s/modules/%s/index.html", config.install_dir, module->name->str);
	title = g_strdup_printf ("%s - %s", config.default_title, module->name->str);
	
	if (module->versions != NULL) {
		module_index = status_web_new_file (index_name, title, NULL);
		g_list_foreach (module->versions, generate_version_report, module_index);
		status_web_end_file (module_index);
		fclose (module_index);
	}
	g_free (index_name);
	g_free (title);
}
