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
#include <stdio.h>
#include <sys/wait.h>
#include "status.h"
#include "status-translation.h"

struct _StatusTranslation {
	GObject object;

	StatusVersion *version;
	StatusTeam *team;
	GString *locale;
	StatusTranslator *translator;
	GString *path;
	gint translated;
	gint fuzzy;
	gint untranslated;
	GString *last_update;
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
	translation->team = NULL;
	translation->locale = NULL;
	translation->translator = NULL;
	translation->path = NULL;
	translation->translated = 0;
	translation->fuzzy = 0;
	translation->untranslated = 0;
	translation->last_update = NULL;
}

static void
status_translation_finalize (GObject *object)
{
	StatusTranslation *translation = (StatusTranslation *) object;

	g_return_if_fail (STATUS_IS_TRANSLATION (translation));

	if (translation->version != NULL) {
		g_object_unref (translation->version);
	}
	if (translation->team != NULL) {
		g_object_unref (translation->team);
	}
	if (translation->locale != NULL) {
		g_string_free (translation->locale, TRUE);
	}
	if (translation->translator != NULL) {
		g_object_unref (translation->translator);
	}
	if (translation->path != NULL) {
		g_string_free (translation->path, TRUE);
		translation->path = NULL;
	}
	if (translation->last_update != NULL) {
		g_string_free (translation->last_update, TRUE);
		translation->last_update = NULL;
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
status_translation_new (StatusVersion *version, const gchar *locale, const gchar *path)
{
	StatusTranslation *translation;
	gchar *command;
	gchar *output, *error;
	gint exit_status;
	gchar **tfu;
	gchar **tfu_temp;
	gint ntoken;
	gchar *encoding;

	g_return_val_if_fail (STATUS_IS_VERSION (version), NULL);

	translation = g_object_new (STATUS_TYPE_TRANSLATION, NULL);

	translation->version = g_object_ref (version);
	translation->path = g_string_new (path);
	translation->locale = g_string_new (locale);

	command = g_strdup_printf ("msgfmt --statistics %s -o /dev/null", translation->path->str);

	if (g_spawn_command_line_sync (command, &output, &error, &exit_status, NULL) &&
			WIFEXITED (exit_status) && WEXITSTATUS (exit_status) == 0) {
		tfu_temp = g_strsplit (error, "\n",0);
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
	g_free (output);
	g_free (error);
	output = NULL;
	error = NULL;

	/* We get the .po encoding */

	command = g_strdup_printf ("grep \"\\\"Content-Type: text/plain;\" %s", translation->path->str);
	if (g_spawn_command_line_sync (command, &output, &error, &exit_status, NULL) &&
			WIFEXITED (exit_status) && WEXITSTATUS (exit_status) == 0) {
		gchar **str, **str2;
		gint i = 0;
		gint len;
		
		str = g_strsplit (output, "charset=", 0);

		while (str[i] != NULL) {
			i++;
		}

		if (i > 2) {
			g_message ("Wrong Content-Type header (%s)", translation->path->str);
			g_free (command);
			g_free (output);
			g_free (error);
			output = NULL;
			error = NULL;
			g_strfreev (str);
			str = NULL;
			return translation;
		} else {
			str2 = g_strsplit (str[1], "\\n", 0);
			encoding = g_strdup (str2[0]);
			g_strfreev (str2);
			str2 = NULL;
		}
		g_strfreev (str);
		str = NULL;
	}
	g_free (command);
	g_free (output);
	g_free (error);
	output = NULL;
	error = NULL;

	/* We get the Revision-Date field */
	command = g_strdup_printf ("grep \"\\\"PO-Revision-Date:\" %s", translation->path->str);
	if (g_spawn_command_line_sync (command, &output, &error, &exit_status, NULL) &&
			WIFEXITED (exit_status) && WEXITSTATUS (exit_status) == 0) {
		gchar **str, **str2;
		gint i = 0;
		gint len;
		
		str = g_strsplit (output, "PO-Revision-Date:", 0);

		while (str[i] != NULL) {
			i++;
		}

		if (i > 2) {
			g_message ("Wrong PO-Revision-Date header (%s)", translation->path->str);
			g_free (command);
			g_free (output);
			g_free (error);
			output = NULL;
			error = NULL;
			g_strfreev (str);
			str = NULL;
			return translation;
		} else {
			str2 = g_strsplit (str[1], "\\n", 0);
			translation->last_update = g_string_new (str2[0]);
			g_strfreev (str2);
			str2 = NULL;
		}
		g_strfreev (str);
		str = NULL;
	}
	g_free (command);
	g_free (output);
	g_free (error);
	output = NULL;
	error = NULL;

	/* We get the team info */
	command = g_strdup_printf ("grep \\\"Language-Team\\: %s", translation->path->str);
	if (g_spawn_command_line_sync (command, &output, &error, &exit_status, NULL) &&
			WIFEXITED (exit_status) && WEXITSTATUS (exit_status) == 0) {
		StatusTeam *team;
		gchar *team_mail;
		gchar **str;
		gint i = 0;
		gchar **str2;

		str = g_strsplit (output, "<", 0);

		while (str[i] != NULL) {
			i++;
		}

		str2 = g_strsplit (str[i-1], ">", 0);
		g_strfreev (str);
		str = NULL;

		i = 0;
		while (str2[i] != NULL) {
			i++;
		}

		if (i > 2) {
			g_message ("Wrong Language-Team header (%s)", translation->path->str);
		} else {

			if (sdata->teams == NULL) {
				g_message ("Team database empty!!");
			}

			team_mail = status_obfuscate_email (str2[0]);

			team = (StatusTeam *) g_hash_table_lookup (sdata->teams, team_mail);

			if (team != NULL) {
				translation->team = g_object_ref (team);
			} else {
				translation->team = g_object_ref (STATUS_TEAM (g_hash_table_lookup (sdata->teams, "unknown")));
			}
			g_free (team_mail);
		}

		g_strfreev (str2);
		str2 = NULL;
	}
	g_free (command);
	g_free (output);
	g_free (error);
	output = NULL;
	error = NULL;

	/* We get the translator info */
	command = g_strdup_printf ("grep \\\"Last-Translator\\: %s", translation->path->str);
	if (g_spawn_command_line_sync (command, &output, &error, &exit_status, NULL) &&
			WIFEXITED (exit_status) && WEXITSTATUS (exit_status) == 0) {
		StatusTranslator *translator;
		gchar *translator_name_utf8;
		gchar *translator_mail;
		gchar **str, **str2, **str3;
		gint i = 0, j = 0;

		str = g_strsplit (output, "<", 0);

		while (str[i] != NULL) {
			i++;
		}

		str2 = g_strsplit (str[i-1], ">", 0);
		
		while (str2[j] != NULL) {
			j++;
		}

		if (j > 2) {
			g_message ("Wrong Last-Translator header (%s)", translation->path->str);
		} else {

			if (translators == NULL) {
				g_message ("Translators database broken!!");
			}

			translator_mail = status_obfuscate_email (str2[0]);

			translator = (StatusTranslator *) g_hash_table_lookup (translators, translator_mail);

			if (translator != NULL) {
				translation->translator = g_object_ref (translator);
			} else {
				if (i > 2) {
					g_message ("FIXME: We should handle this header %s from %s", output, translation->path->str);
				} else {
					str3 = g_strsplit (str[0], ":", 2);

					if (strcmp (encoding, "UTF-8")) {
						translator_name_utf8 = g_convert (str3[1], strlen (str3[1]), "UTF-8", encoding, NULL, NULL, NULL);
						if (translator_name_utf8 == NULL) {
							translator = status_translator_new (str3[1], translator_mail);
						} else {
							translator = status_translator_new (translator_name_utf8, translator_mail);
							g_free (translator_name_utf8);
						}
					} else {
						translator = status_translator_new (str3[1], translator_mail);
					}
										
					g_hash_table_insert (translators, g_strdup (translator_mail), translator);
					translation->translator = g_object_ref (translator);
					g_strfreev (str3);
					str3 = NULL;
				}
			g_free (translator_mail);
			}
			if (translation->team != NULL) {
				status_translator_add_team (translator, translation->team);
				status_team_add_translator (translation->team, translator);
			}
			status_translator_add_translation (translator, translation);
		}
		g_strfreev (str);
		str = NULL;
		g_strfreev (str2);
		str2 = NULL;
	}
	g_free (command);
	g_free (output);
	g_free (error);
	output = NULL;
	error = NULL;

	return translation;
}

StatusVersion *
status_translation_get_version (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), NULL);

	return translation->version;
}

StatusTeam *
status_translation_get_team (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), NULL);

	return translation->team;
}

const gchar *
status_translation_get_lang (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), NULL);

	return translation->locale->str;
}

gint
status_translation_get_ntranslated (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), -1);

	return translation->translated;
}

gint
status_translation_get_nfuzzy (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), -1);

	return translation->fuzzy;
}

gint
status_translation_get_nuntranslated (StatusTranslation *translation)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATION (translation), -1);

	return translation->untranslated;
}

/**
 * status_translation_report
 */
void
status_translation_report (StatusTranslation *translation)
{
	FILE *index;
	gchar *index_name;
	gchar *title;
	gfloat ptranslated, pfuzzy, puntranslated;

	index_name = g_strdup_printf ("%s/modules/%s/%s/%s.html", config.install_dir,
			status_version_get_module_name (translation->version),
			status_version_get_id (translation->version),
			translation->locale->str);
	title = g_strdup_printf ("%s - %s - %s - %s", config.default_title,
			status_version_get_module_name (translation->version),
			status_version_get_id (translation->version),
			translation->locale->str);

	ptranslated = (float) translation->translated / (float) status_version_get_nstrings (translation->version);
	pfuzzy = (float) translation->fuzzy / (float) status_version_get_nstrings (translation->version);
	puntranslated = (float) translation->untranslated / (float) status_version_get_nstrings (translation->version);

	
	index = status_web_new_file (index_name, title, NULL);
	fprintf (index, "      <div id=\"TableAlign\">\n");
	fprintf (index, "      <table class=\"GTPTranslationTable\">\n");
	fprintf (index, "        <tr class=\"moduleVersionRowEven\">\n");
	fprintf (index, "          <th>%s</th>\n", "Module");
	fprintf (index, "          <td><a href=\"../\">%s</a></td>\n",
			status_version_get_module_name (translation->version),
			status_version_get_module_name (translation->version));
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowOdd\">\n");
	fprintf (index, "          <th>%s</th>\n", "Version");
	fprintf (index, "          <td><a href=\"./\">%s</a></td>\n",
			status_version_get_id (translation->version));
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowEven\">\n");
	fprintf (index, "          <th>%s</th>\n", "Translation Team");
	fprintf (index, "          <td><a href=\"../../../teams/%s.html\">%s</a></td>\n",
			status_team_get_email (translation->team),
			status_team_get_name (translation->team));
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowOdd\">\n");
	fprintf (index, "          <th>%s</th>\n", "Last Translator");
	fprintf (index, "          <td><a href=\"../../../translators/%s.html\">%s</a> <a href=\"mailto:%s\"><img src=\"/images/mail.png\" alt=\"%s\" /></a></td>\n",
			status_translator_get_email (translation->translator), status_translator_get_name (translation->translator),
			status_translator_get_email (translation->translator), "Mail image");
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowEven\">\n");
	fprintf (index, "          <th>%s</th>\n", "Last Update");
	fprintf (index, "          <td>%s</td>\n", translation->last_update ? translation->last_update->str : "Unknown");
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowOdd\">\n");
	fprintf (index, "          <th class=\"moduleVersionFieldTrans\">%s</th>\n", "Strings Translated");
	fprintf (index, "          <td>%d</td>\n", translation->translated);
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowEven\">\n");
	fprintf (index, "          <th class=\"moduleVersionFieldFuzzy\">%s</th>\n", "Strings Fuzzy");
	fprintf (index, "          <td>%d</td>\n", translation->fuzzy);
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowOdd\">\n");
	fprintf (index, "          <th class=\"moduleVersionFieldUntra\">%s</th>\n", "Strings Untranslated");
	fprintf (index, "          <td>%d</td>\n", translation->untranslated);
	fprintf (index, "        </tr>\n");
	fprintf (index, "        <tr class=\"moduleVersionRowEven\">\n");
	fprintf (index, "          <th>%s</th>\n", "Graph");
	fprintf (index, "          <td>");
	fprintf (index, "<img class=\"moduleVersionGraph\" src=\"/images/bar0.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*ptranslated), "translated bar");
	fprintf (index, "<img class=\"moduleVersionGraph\" src=\"/images/bar4.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*pfuzzy), "fuzzy bar");
	fprintf (index, "<img class=\"moduleVersionGraph\" src=\"/images/bar1.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*puntranslated), "untranslated bar");
	fprintf (index, "</td>\n");
	fprintf (index, "        </tr>\n");
	fprintf (index, "      </table>\n");
	fprintf (index, "      </div>\n");
	status_web_end_file (index);
	fclose (index);

	g_free (index_name);
	g_free (title);
}
