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

#include <sys/types.h>
#include <sys/stat.h>
#include <sys/wait.h>
#include <string.h>
#include "status.h"
#include "status-version.h"
#include "status-translation.h"

struct _StatusVersion {
	GObject object;

	StatusServer *server;
	GHashTable *translations;
	GString *module;
	GString *id;
	GString *path;
	gint nstrings;
};

struct _StatusVersionClass {
	GObjectClass parent_class;
};

static void status_version_class_init (StatusVersionClass *klass);
static void status_version_init       (StatusVersion *version, StatusVersionClass *klass);
static void status_version_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

/*
 * StatusVersion object implementation
 */
static void
status_version_class_init (StatusVersionClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_version_finalize;
}

static void
status_version_init (StatusVersion *version, StatusVersionClass *klass)
{
	g_return_if_fail (STATUS_IS_VERSION (version));

	version->server = NULL;
	version->translations = NULL;
	version->module = NULL;
	version->id = NULL;
	version->path = NULL;
	version->nstrings = 0;
}

static void
status_version_finalize (GObject *object)
{
	StatusVersion *version = (StatusVersion *) object;

	g_return_if_fail (STATUS_IS_VERSION (version));

	if (version->server != NULL) {
		g_object_unref (version->server);
		version->server = NULL;
	}
	if (version->translations != NULL) {
		g_hash_table_destroy (version->translations);
		version->translations = NULL;
	}
	if (version->module != NULL) {
		g_string_free (version->module, TRUE);
		version->module = NULL;
	}
	if (version->id != NULL) {
		g_string_free (version->id, TRUE);
		version->id = NULL;
	}
	if (version->path != NULL) {
		g_string_free (version->path, TRUE);
		version->path = NULL;
	}

	parent_class->finalize (object);
}

GType
status_version_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusVersionClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_version_class_init,
			NULL,
			NULL,
			sizeof (StatusVersion),
			0,
			(GInstanceInitFunc) status_version_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusVersion",
					       &info, 0);
	}
	return type;
}

/**
 * status_version_new
 * @server:
 * @module:
 * @id:
 * @path:
 *
 * Create a new #StatusVersion object
 */
StatusVersion *
status_version_new (StatusServer *server, const gchar *module, const gchar *id, const gchar *path)
{
	StatusVersion *version;

	g_return_val_if_fail (STATUS_IS_SERVER (server), NULL);

	version = g_object_new (STATUS_TYPE_VERSION, NULL);

	version->server = g_object_ref (server);
	version->translations = g_hash_table_new_full (g_str_hash, g_str_equal, g_free, g_object_unref);
	version->module = g_string_new (module);
	version->id = g_string_new (id);
	version->path = g_string_new (path);

	return version;
}

/**
 * status_version_download
 * @version:
 * @download_dir:
 *
 * Downloads the #StatusVersion object to the @download_dir
 */
gboolean
status_version_download (StatusVersion *version, gchar *download_dir)
{

	g_return_val_if_fail (STATUS_IS_VERSION (version), FALSE);
	
	g_print ("Downloading %s - %s ...\n", version->module->str, version->id->str);

	return status_server_download (version->server, version->module->str, version->id->str,
				       version->path->str, download_dir);

}

/**
 * status_version_generate_pot
 * @ version:
 * @ download_dir:
 * @ install_dir:
 *
 * Generates the .pot files and store it at install_dir
 */
/* TODO: We should be able to support multiple po/ directories (like GIMP)
 * We should also check and create the needed directories
 */
gboolean
status_version_generate_pot (StatusVersion *version, gchar *download_dir, gchar *install_dir)
{
	gchar *buf, *command, *command_info;
	gchar **tfu_temp, **tfu, *output, *error;
	gint exit_status, ntoken;
	gchar *path;

	g_return_val_if_fail (STATUS_IS_VERSION (version), FALSE);

	g_print ("\tGenerating %s.%s.pot ...\n", version->module->str, version->id->str);
	
	buf = g_strdup_printf ("%s/%s/%s", download_dir, version->module->str, version->id->str);

	if (!chdir (buf)) {
		/* TODO: Here we should scan for po/ directories and then, generate the .pot files */
		if (!chdir ("po")) {
			path = g_strdup_printf ("%s/modules/%s/%s", install_dir,
					version->module->str, version->id->str);
			/* First, we check if the destination path already exists */
			if (!g_file_test (path, G_FILE_TEST_EXISTS)) {
				/* FIXME: We should use the mkdir function directly */
				command = g_strdup_printf ("mkdir -p %s", path);
				if (system (command)) {
					g_warning ("Unable to create the %s directory", path);
					g_free (command);
					g_free (path);
					return FALSE;
				}
				g_free (command);
				command = NULL;
			} else if (!g_file_test (buf, G_FILE_TEST_IS_DIR)) {
				g_warning ("%s is not a directory!!", path);
				g_free (path);
				return FALSE;
			}
			command = g_strdup_printf ("intltool-update -p -g %s/%s.%s", path,
						   version->module->str, version->id->str);
			if (system (command)) {
				g_warning ("Unable to regenerate the %s/%s.%s.pot file", path,
					   version->module->str, version->id->str);
				g_free (command);
				g_free (buf);
				g_free (path);
				return FALSE;
			} else {
				command_info = g_strdup_printf ("msgfmt --statistics %s/%s.%s.pot -o /dev/null",
						path, version->module->str, version->id->str);
				if (g_spawn_command_line_sync (command_info, &output, &error, &exit_status, NULL) &&
						WIFEXITED (exit_status) && WEXITSTATUS (exit_status) == 0) {
					tfu_temp = g_strsplit (error, "\n",0);
					ntoken = 0;
					while (tfu_temp[ntoken] != NULL) {
						ntoken++;
					}
					/*
					 * We had ntoken-1, but it seems that it's different with glib 2.0 :-?
					 */
					tfu = g_strsplit (tfu_temp[ntoken - 2 ], ",", 3);
					if ( tfu[0] != NULL) {
						if ( strstr (tfu[0], " untranslated")) {
							sscanf (tfu[0], "%d untranslated",
								&version->nstrings);
						}
						if ( tfu[1] != NULL) {
							if ( strstr (tfu[1], " untranslated")) {
								sscanf (tfu[1], "%d untranslated",
									&version->nstrings);
							}
							if ( tfu[2] != NULL && strstr (tfu[2], " untranslated")) {
								sscanf (tfu[2], "%d untranslated",
									&version->nstrings);
							}
						}
					}
					g_strfreev (tfu);
					g_strfreev (tfu_temp);
				}
				g_free (command_info);
				g_free (output);
				g_free (error);
				g_free (path);
			}
			g_free (command);
			return TRUE;
		} else {
			g_warning ("Unable to chdir into the po dir");
			g_free (buf);
			return FALSE;
		}
	} else {
		g_warning ("Unable to chdir into the %s dir", buf);
		g_free (buf);
		return FALSE;
	}
}

/**
 * status_version_update_po
 * @ version:
 * @ download_dir:
 * @ install_dir:
 *
 * Updates the .po files and store it at install_dir
 */
/* TODO: We should be able to support multiple po/ directories (like GIMP)
 * We should also check and create the needed directories
 */
gboolean
status_version_update_po (StatusVersion *version, gchar *download_dir, gchar *install_dir)
{
	gchar *buf, *command;
        const gchar *file_name;
	GDir *podir;
        gchar **filesplit;
	gchar *po_file;
	gchar *path;

	g_return_val_if_fail (STATUS_IS_VERSION (version), FALSE);

	buf = g_strdup_printf ("%s/%s/%s", download_dir, version->module->str, version->id->str);

	if (!chdir (buf)) {
		/* TODO: Here we should scan for po/ directories and then, generate the .pot files */
		if (!chdir ("po")) {
			podir = g_dir_open (".", 0, NULL);
			if (podir != NULL) {
				file_name = g_dir_read_name (podir);
				while (file_name != NULL) {
					filesplit = g_strsplit (file_name, ".", 2);
					if (filesplit[1] != NULL && filesplit[2] == NULL && !strcmp (filesplit[1], "po")) {
						if (g_list_find_custom (langs, filesplit[0], strcmp) == NULL) {
							langs = g_list_insert_sorted (langs, g_strdup (filesplit[0]), strcmp);
						}						
						path = g_strdup_printf ("%s/modules/%s/%s", install_dir,
								version->module->str, version->id->str);

						po_file = g_strdup_printf ("%s/%s.%s.%s.po", path,
								version->module->str, version->id->str, filesplit[0]);
						g_print ("\tUpdating %s.%s.%s.po ...\n", version->module->str, version->id->str, filesplit[0]);
						command = g_strdup_printf (
							"msgmerge -q %s %s/%s.%s.pot -o %s > /dev/null",
							file_name, path, version->module->str,
							version->id->str, po_file);
						if (system (command)) {
							g_warning ("Unable to update the %s/%s.%s.%s.po file",
								   path, version->module->str,
								   version->id->str, filesplit[0]);
						} else {
							StatusTranslation *translation;

							translation = status_translation_new (version, filesplit[0], po_file);
							if (translation != NULL) {
								g_hash_table_insert (version->translations,
										g_strdup(filesplit[0]),
										translation);
							}
						}
						g_free (command);
						command = NULL;
						g_free (po_file);
						po_file = NULL;
						g_free (path);
						path = NULL;
					}
					g_strfreev (filesplit);
					filesplit = NULL;
					file_name = g_dir_read_name (podir);
				}
				file_name = NULL;
				g_dir_close (podir);
				podir = NULL;
			} else {
				g_warning ("Unable to get po file list");
				g_free (buf);
				return FALSE;
			}
		} else {
			g_warning ("Unable to chdir into the po dir");
			g_free (buf);
			return FALSE;
		}
	} else {
		g_warning ("Unable to chdir into the %s dir", buf);
		g_free (buf);
		return FALSE;
	}
	g_free (buf);
	return TRUE;
}

gint
status_version_get_nstrings (StatusVersion *version)
{
	g_return_val_if_fail (STATUS_IS_VERSION (version), -1);
	
	return version->nstrings;
}

GHashTable *
status_version_get_translations (StatusVersion *version)
{
	g_return_val_if_fail (STATUS_IS_VERSION (version), NULL);

	return version->translations;
}

const gchar *
status_version_get_id (StatusVersion *version)
{
	g_return_val_if_fail (STATUS_IS_VERSION (version), NULL);

	return version->id->str;
}

const gchar *
status_version_get_module_name (StatusVersion *version)
{
	g_return_val_if_fail (STATUS_IS_VERSION (version), NULL);

	return version->module->str;
}

gchar *
status_version_get_html_table (StatusVersion *version, const gchar *html_path)
{
	StatusTranslation *translation;
	gint translated, fuzzy, untranslated;
	gfloat ptranslated, pfuzzy, puntranslated;
	GString *table_str;
	gchar *ret;
	GList *lang;
	gint i;

	table_str = g_string_new ("      <table class=\"moduleVersionTable\">\n");
	table_str = g_string_append (table_str, "        <tr class=\"moduleVersionRow\">\n");
	g_string_append_printf (table_str, "          <th class=\"moduleVersionField\">%s</th>\n", "language");
	g_string_append_printf (table_str, "          <th class=\"moduleVersionFieldTrans\">%s</th>\n", "trans.");
	table_str = g_string_append (table_str, "          <th class=\"moduleVersionFieldTrans\">%</th>\n");
	g_string_append_printf (table_str, "          <th class=\"moduleVersionFieldFuzzy\">%s</th>\n", "fuzzy");
	table_str = g_string_append (table_str, "          <th class=\"moduleVersionFieldFuzzy\">%</th>\n");
	g_string_append_printf (table_str, "          <th class=\"moduleVersionFieldUntra\">%s</th>\n", "untrans.");
	table_str = g_string_append (table_str, "          <th class=\"moduleVersionFieldUntra\">%</th>\n");
	g_string_append_printf (table_str, "          <th class=\"moduleVersionField\">%s</th>\n", "graph");
	table_str = g_string_append (table_str, "          <th class=\"moduleVersionField\">po</th>\n");
	table_str = g_string_append (table_str, "          <th class=\"moduleVersionField\">Details</th>\n");
	table_str = g_string_append (table_str, "        </tr>\n");
	
	for (lang = g_list_first (langs), i = 1; lang != NULL; lang = g_list_next (lang)) {
		translation = g_hash_table_lookup (version->translations, lang->data);
		if (translation == NULL) {
			continue;
		}

		if (html_path == NULL) {
			status_translation_report (translation);
		}
		
		i++;
		translated = status_translation_get_ntranslated (translation);
		fuzzy = status_translation_get_nfuzzy (translation);
		untranslated = status_translation_get_nuntranslated (translation);
		version = status_translation_get_version (translation);
		ptranslated = (float) translated / (float) version->nstrings;
		pfuzzy = (float) fuzzy / (float) version->nstrings;
		puntranslated = (float) untranslated / (float) version->nstrings;

		if (version->nstrings != translated + fuzzy + untranslated) {
			/* TODO: We should implement a way to show a warning */
		}
		
		g_string_append_printf (table_str, "        <tr class=\"moduleVersionRow%s\">\n", (i % 2 == 0) ? "Even" : "Odd");
		if (html_path != NULL) {
			g_string_append_printf (table_str, "          <td><a href=\"%s/%s.html\">%s</a></td>\n", html_path, lang->data, lang->data);
		} else {
			g_string_append_printf (table_str, "          <td><a href=\"%s.html\">%s</a></td>\n", lang->data, lang->data);
		}
		g_string_append_printf (table_str, "          <td>%d</td>\n", translated);
		if (ptranslated == 1) {
			table_str = g_string_append (table_str, "          <td>100</td>\n");
		} else {
			g_string_append_printf (table_str, "          <td>%.2f</td>\n", ptranslated*100);
		}
		g_string_append_printf (table_str, "          <td>%d</td>\n", fuzzy);
		if (pfuzzy == 1) {
			table_str = g_string_append (table_str, "          <td>100</td>\n");
		} else {
			g_string_append_printf (table_str, "          <td>%.2f</td>\n", pfuzzy*100);
		}
		g_string_append_printf (table_str, "          <td>%d</td>\n", untranslated);
		if (puntranslated == 1) {
			table_str = g_string_append (table_str, "          <td>100</td>\n");
		} else {
			g_string_append_printf (table_str, "          <td>%.2f</td>\n", puntranslated*100);
		}
		table_str = g_string_append (table_str, "          <td>\n");
		g_string_append_printf (table_str, "            <img class=\"moduleVersionGraph\" src=\"/images/bar0.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*ptranslated), "translated bar");
		g_string_append_printf (table_str, "<img class=\"moduleVersionGraph\" src=\"/images/bar4.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*pfuzzy), "fuzzy bar");
		g_string_append_printf (table_str, "<img class=\"moduleVersionGraph\" src=\"/images/bar1.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*puntranslated), "untranslated bar");
		table_str = g_string_append (table_str, "          </td>\n");
		table_str = g_string_append (table_str, "          <td>\n");
		if (html_path != NULL) {
			g_string_append_printf (table_str, "            <a href=\"%s/%s.%s.%s.po\"><img src=\"/images/download.png\" alt=\"%s\"/></a>\n", html_path, version->module->str, version->id->str, lang->data, "PO Download link");
		} else {
			g_string_append_printf (table_str, "            <a href=\"%s.%s.%s.po\"><img src=\"/images/download.png\" alt=\"%s\"/></a>\n", version->module->str, version->id->str, lang->data, "PO Download link");

		}
		table_str = g_string_append (table_str, "          </td>\n");
		table_str = g_string_append (table_str, "          <td>\n");
		if (html_path != NULL) {
			g_string_append_printf (table_str, "            <a href=\"%s/%s.html\"><img src=\"/images/details.png\" alt=\"%s\"/></a>\n", html_path, lang->data, "Details link");
		} else {
			g_string_append_printf (table_str, "            <a href=\"%s.html\"><img src=\"/images/details.png\" alt=\"%s\"/></a>\n", lang->data, "Details link");

		}
		table_str = g_string_append (table_str, "          </td>\n");
		table_str = g_string_append (table_str, "        </tr>\n");
	}

	table_str = g_string_append (table_str, "      </table>\n");
	
	ret = table_str->str;
	g_string_free (table_str, FALSE);

	return ret;
}

/**
 * status_version_report
 */
void
status_version_report (StatusVersion *version)
{
	FILE *module_index;
	gchar *index_name;
	gchar *title;
	gchar *table_str;

	index_name = g_strdup_printf ("%s/modules/%s/%s/index.html", config.install_dir, version->module->str, version->id->str);
	title = g_strdup_printf ("%s - %s - %s", config.default_title, version->module->str, version->id->str);
	
	module_index = status_web_new_file (index_name, title, NULL);
	table_str = status_version_get_html_table (version, NULL);
	fprintf (module_index, "%s", table_str);
	status_web_end_file (module_index);
	fclose (module_index);
	
	g_free (index_name);
	g_free (title);
	g_free (table_str);
}
