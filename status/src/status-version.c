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

	g_message ("Downloading %s - %s ...", version->module->str, version->id->str);

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

	g_message ("Generating %s.%s.pot ...", version->module->str, version->id->str);
	
	buf = g_strdup_printf ("%s/%s/%s", download_dir, version->module->str, version->id->str);

	if (!chdir (buf)) {
		/* TODO: Here we should scan for po/ directories and then, generate the .pot files */
		if (!chdir ("po")) {
			command = g_strdup_printf ("intltool-update -p -g %s/PO/%s.%s", install_dir,
						   version->module->str, version->id->str);
			if (system (command)) {
				g_warning ("Unable to regenerate the %s/PO/%s.%s.pot file", install_dir,
					   version->module->str, version->id->str);
				g_free (command);
				g_free (buf);
				return FALSE;
			} else {
				command_info = g_strdup_printf ("msgfmt --statistics %s/PO/%s.%s.pot -o /dev/null",
						install_dir, version->module->str, version->id->str);
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
				/* FIXME: Is it correct if we free the output and error strings? */
				g_free (output);
				g_free (error);
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
						po_file = g_strdup_printf ("%s/PO/%s.%s.%s.po", install_dir,
								version->module->str, version->id->str, filesplit[0]);
						g_message ("Updating %s.%s.%s.po:", version->module->str, version->id->str, filesplit[0]);
						command = g_strdup_printf (
							"msgmerge -q %s %s/PO/%s.%s.pot -o %s > /dev/null",
							file_name, install_dir, version->module->str,
							version->id->str, po_file);
						if (system (command)) {
							g_warning ("Unable to update the %s/PO/%s.%s.%s.po file",
								   install_dir, version->module->str,
								   version->id->str, filesplit[0]);
						} else {
							StatusTranslation *translation;

							translation = status_translation_new (version, po_file);
							if (translation != NULL) {
								g_hash_table_insert (version->translations,
										g_strdup(filesplit[0]),
										translation);
							}
						}
						g_free (command);
						g_free (po_file);
					}
					g_strfreev (filesplit);
					file_name = g_dir_read_name (podir);
				}
				g_dir_close (podir);
			} else {
				g_warning ("Unable to get po file list");
				return FALSE;
			}
		} else {
			g_warning ("Unable to chdir into the po dir");
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
