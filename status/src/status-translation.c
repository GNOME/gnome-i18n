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
#include <dirent.h>
#include <sys/wait.h>
#include "status-translation.h"

struct _StatusTranslation {
	GObject object;

	StatusVersion *version;
/*	StatusLocale *locale;*/
/*	StatusTranslator *translator; */
	GString *path;
	gint translated;
	gint fuzzy;
	gint untranslated;
	gint last_update;
};

struct _StatusTranslationClass {
	GObjectClass parent_class;
};

static void status_translation_class_init (StatusTranslationClass *klass);
static void status_translation_init       (StatusTranslation *translation, StatusTranslationClass *klass);
static void status_translation_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

/*
 * StatusTranslation object implementation
 */
static void
status_translation_class_init (StatusTranslationClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_translation_finalize;
}

static void
status_translation_init (StatusTranslation *translation, StatusTranslationClass *klass)
{
	g_return_if_fail (STATUS_IS_TRANSLATION (translation));

	translation->version = NULL;
/*	translation->locale = NULL;*/
/*	translation->translator = NULL;*/
	translation->path = NULL;
	translation->translated = 0;
	translation->fuzzy = 0;
	translation->untranslated = 0;
	translation->last_update = 0;
}

static void
status_translation_finalize (GObject *object)
{
	StatusTranslation *translation = (StatusTranslation *) object;

	g_return_if_fail (STATUS_IS_TRANSLATION (translation));

	if (translation->version != NULL) {
		g_object_unref (translation->version);
	}
/*	if (translation->locale != NULL) {
		g_object_unref (translation->locale);
	}*/
/*	if (translation->translator != NULL) {
		g_object_unref (translation->translator);
	}*/
	if (translation->path != NULL) {
		g_string_free (translation->path, TRUE);
		translation->path = NULL;
	}

	parent_class->finalize (object);
}

GType
status_translation_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusTranslationClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_translation_class_init,
			NULL,
			NULL,
			sizeof (StatusTranslation),
			0,
			(GInstanceInitFunc) status_translation_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusTranslation",
					       &info, 0);
	}
	return type;
}

/**
 * status_translation_new
 * @version:
 * @path:
 *
 * Create a new #StatusTranslation object
 *
 * TODO: Perhaps we should move all this code to a constructor function?
 */
StatusTranslation *
status_translation_new (StatusVersion *version, const gchar *path)
{
	StatusTranslation *translation;
	gchar *command;
	gchar *output, *error;
	gint exit_status;
	gchar **tfu;
	gchar **tfu_temp;
	gint ntoken;

	g_return_val_if_fail (STATUS_IS_VERSION (version), NULL);

	translation = g_object_new (STATUS_TYPE_TRANSLATION, NULL);

	translation->version = g_object_ref (version);
	translation->path = g_string_new (path);

	command = g_strdup_printf ("msgfmt --statistics %s -o /dev/null", translation->path);

	if (g_spawn_command_line_sync (command, &output, &error, &exit_status, NULL) &&
			WIFEXITED (exit_status) && WEXITSTATUS (exit_status) == 0) {
		tfu_temp = g_strsplit (output, "\n",0);
		ntoken = 0;
		while (tfu_temp[ntoken] != NULL) {
			ntoken++;
		}
		/*
		 * We had ntoken - 1, but it seems that it's different with glib 2.0 :-?
		 */
		tfu = g_strsplit (tfu_temp[ntoken - 2 ], ",", 3);
		if ( tfu[0] != NULL) {
			if ( strstr (tfu[0], " translated")) {
				sscanf (tfu[0], "%d translated", &translation->translated);
			} else if ( strstr (tfu[0], " fuzzy")) {
				sscanf (tfu[0], "%d fuzzy", &translation->fuzzy);
			} else if ( strstr (tfu[0], " untranslated")) {
				sscanf (tfu[0], "%d untranslated", &translation->untranslated);
			}
			if ( tfu[1] != NULL) {
				if ( strstr (tfu[1], " fuzzy")) {
					sscanf (tfu[1], "%d fuzzy", &translation->fuzzy);
				} else if ( strstr (tfu[1], " untranslated")) {
					sscanf (tfu[1], "%d untranslated", &translation->untranslated);
				}
				if ( tfu[2] != NULL && strstr (tfu[2], " untranslated")) {
					sscanf (tfu[2], "%d untranslated", &translation->untranslated);
				}
			}
		}
		g_strfreev (tfu);
		g_strfreev (tfu_temp);
	}
	g_free (command);
	
	return translation;
}

gint status_translation_get_ntranslated (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), -1);

	return translation->translated;
}

gint status_translation_get_nfuzzy (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), -1);

	return translation->fuzzy;
}

gint status_translation_get_nuntranslated (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), -1);

	return translation->untranslated;
}
