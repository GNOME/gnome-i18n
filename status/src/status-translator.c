/* Translator Status program
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
#include <string.h>
#include "status.h"
#include "status-translator.h"

struct _StatusTranslator {
	GObject object;

	GString *name; /* The translator's name */
	GString *email; /* The translator's email */
	GList *teams;  /* The teams where this translator works */
	GList *translations; /* The translator's translations */
};

struct _StatusTranslatorClass {
	GObjectClass parent_class;
};

static void status_translator_class_init (StatusTranslatorClass *klass);
static void status_translator_init       (StatusTranslator *translator, StatusTranslatorClass *klass);
static void status_translator_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

/*
 * StatusTranslator object implementation
 */
static void
status_translator_class_init (StatusTranslatorClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_translator_finalize;
}

static void
status_translator_init (StatusTranslator *translator, StatusTranslatorClass *klass)
{
	g_return_if_fail (STATUS_IS_TRANSLATOR (translator));

	translator->name = NULL;
	translator->email = NULL;
	translator->teams = NULL;
	translator->translations = NULL;
}

static void
status_translator_finalize (GObject *object)
{
	StatusTranslator *translator = (StatusTranslator *) object;

	g_return_if_fail (STATUS_IS_TRANSLATOR (translator));

	if (translator->name != NULL) {
		g_string_free (translator->name, TRUE);
	}
	if (translator->email != NULL) {
		g_string_free (translator->email, TRUE);
	}
	if (translator->teams != NULL) {
		GList *l;

		for (l = g_list_first (translator->teams); l != NULL; l = g_list_next (l)) {
			g_object_unref (STATUS_TEAM (l->data));
		}
		g_list_free (translator->teams);
		translator->teams = NULL;
	}
	if (translator->translations != NULL) {
		GList *l;

		for (l = g_list_first (translator->translations); l != NULL; l = g_list_next (l)) {
			g_object_unref (STATUS_TRANSLATION (l->data));
		}
		g_list_free (translator->translations);
		translator->translations = NULL;
	}

	parent_class->finalize (object);
}

GType
status_translator_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusTranslatorClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_translator_class_init,
			NULL,
			NULL,
			sizeof (StatusTranslator),
			0,
			(GInstanceInitFunc) status_translator_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusTranslator",
					       &info, 0);
	}
	return type;
}

/**
 * status_translator_new
 * @name:
 * @email:
 *
 * Create a new #StatusTranslator object
 *
 * TODO: Perhaps we should move all this code to a constructor function?
 */
StatusTranslator *
status_translator_new (const gchar *name, const gchar *email)
{
	StatusTranslator *translator;

	g_return_val_if_fail (name != NULL, NULL);

	translator = g_object_new (STATUS_TYPE_TRANSLATOR, NULL);

	translator->name = g_string_new (name);
	if (email != NULL) {
		translator->email = g_string_new (email);
	}

	return translator;
}

static gint
sort_team (gconstpointer a, gconstpointer b)
{
	StatusTeam *first, *second;

	first = STATUS_TEAM (a);
	second = STATUS_TEAM (b);

	return (strcmp ( status_team_get_email (first), status_team_get_email (second)));
}

void
status_translator_add_team (StatusTranslator *translator, StatusTeam *team)
{
	GList *l = NULL;
	
	g_return_if_fail (STATUS_IS_TRANSLATOR (translator));
	g_return_if_fail (STATUS_IS_TEAM (team));

	if (translator->teams != NULL) {
		l = g_list_find_custom (translator->teams, team, sort_team);
		if (l == NULL) {
			translator->teams = g_list_insert_sorted (translator->teams,
					g_object_ref (team), sort_team);
		}
	} else {
		translator->teams = g_list_insert_sorted (translator->teams,
				g_object_ref (team), sort_team);
	}
}

static gint
sort_translation (gconstpointer a, gconstpointer b)
{
	StatusTranslation *first, *second;
	StatusVersion *first_version, *second_version;
	const gchar *first_module_name, *second_module_name;
	gint ret;

	first = STATUS_TRANSLATION (a);
	second = STATUS_TRANSLATION (b);

	first_version = status_translation_get_version (first);
	second_version = status_translation_get_version (second);

	first_module_name = status_version_get_module_name (first_version);
	second_module_name = status_version_get_module_name (second_version);

	ret = strcmp (first_module_name, second_module_name);

	if (ret == 0) {
		return strcmp (status_version_get_id (first_version), status_version_get_id (second_version));
	} else {
		return ret;
	}
}

void
status_translator_add_translation (StatusTranslator *translator, StatusTranslation *translation)
{
	GList *l = NULL;
	
	g_return_if_fail (STATUS_IS_TRANSLATOR (translator));
	g_return_if_fail (STATUS_IS_TRANSLATION (translation));

	if (translator->translations != NULL) {
		l = g_list_find_custom (translator->translations, translation, sort_translation);
		if (l == NULL) {
			translator->translations = g_list_insert_sorted (translator->translations,
					g_object_ref (translation), sort_translation);
		}
	} else {
		translator->translations = g_list_insert_sorted (translator->translations,
				g_object_ref (translation), sort_translation);
	}
}

const gchar *
status_translator_get_name (StatusTranslator *translator)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATOR (translator), NULL);

	return translator->name->str;
}

const gchar *
status_translator_get_email (StatusTranslator *translator)
{
	g_return_val_if_fail (STATUS_IS_TRANSLATOR (translator), NULL);

	return translator->email->str;
}

gint
status_translator_get_nstrings (StatusTranslator *translator)
{
	StatusVersion *version;
	StatusTranslation *translation;
	GList *l;
	gint nstrings = 0;
	
	g_return_val_if_fail (STATUS_IS_TRANSLATOR (translator), 0);

	for (l = g_list_first (translator->translations); l != NULL; l = g_list_next (l)) {
		translation = STATUS_TRANSLATION (l->data);
		
		version = status_translation_get_version (translation);

		nstrings += status_version_get_nstrings (version);
	}

	return nstrings;
}

gint
status_translator_get_ntranslated (StatusTranslator *translator)
{
	StatusTranslation *translation;
	GList *l;
	gint ntranslated = 0;
	
	g_return_val_if_fail (STATUS_IS_TRANSLATOR (translator), 0);

	for (l = g_list_first (translator->translations); l != NULL; l = g_list_next (l)) {
		translation = STATUS_TRANSLATION (l->data);
		
		ntranslated += status_translation_get_ntranslated (translation);
	}

	return ntranslated;
}

gint
status_translator_get_nfuzzy (StatusTranslator *translator)
{
	StatusTranslation *translation;
	GList *l;
	gint nfuzzy = 0;
	
	g_return_val_if_fail (STATUS_IS_TRANSLATOR (translator), 0);

	for (l = g_list_first (translator->translations); l != NULL; l = g_list_next (l)) {
		translation = STATUS_TRANSLATION (l->data);
		
		nfuzzy += status_translation_get_nfuzzy (translation);
	}

	return nfuzzy;
}

gint
status_translator_get_nuntranslated (StatusTranslator *translator)
{
	StatusTranslation *translation;
	GList *l;
	gint nuntranslated = 0;
	
	g_return_val_if_fail (STATUS_IS_TRANSLATOR (translator), 0);

	for (l = g_list_first (translator->translations); l != NULL; l = g_list_next (l)) {
		translation = STATUS_TRANSLATION (l->data);
		
		nuntranslated += status_translation_get_nuntranslated (translation);
	}

	return nuntranslated;
}

/**
 * status_translator_report
 */
void
status_translator_report (StatusTranslator *translator)
{
	FILE *index;
	gchar *index_name;
	gchar *title;
	GList *l;
	gint i;
	StatusVersion *version;
	StatusTeam *team;
	StatusTranslation *translation;
	gint translated, fuzzy, untranslated;
	gfloat ptranslated, pfuzzy, puntranslated;

	g_return_if_fail (STATUS_IS_TRANSLATOR (translator));

	index_name = g_strdup_printf ("%s/translators/%s.html", config.install_dir, translator->email->str);
	title = g_strdup_printf ("%s - %s - %s", config.default_title, "Translators", translator->name->str);

	index = status_web_new_file (index_name, title, NULL);
	fprintf (index, "      <table class=\"moduleVersionTable\">\n");
	fprintf (index, "        <tr class=\"moduleVersionRow\">\n");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "Module");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "Version");
	fprintf (index, "          <th class=\"moduleVersionFieldTrans\">%s</th>\n", "trans.");
	fprintf (index, "          <th class=\"moduleVersionFieldTrans\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionFieldFuzzy\">%s</th>\n", "fuzzy");
	fprintf (index, "          <th class=\"moduleVersionFieldFuzzy\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionFieldUntra\">%s</th>\n", "untrans.");
	fprintf (index, "          <th class=\"moduleVersionFieldUntra\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "graph");
	fprintf (index, "          <th class=\"moduleVersionField\">po</th>\n");
	fprintf (index, "          <th class=\"moduleVersionField\">Details</th>\n");
	fprintf (index, "        </tr>\n");

	for (l = g_list_first (translator->translations), i = 1; l != NULL; l = g_list_next (l)) {
		translation = STATUS_TRANSLATION (l->data);
		
		i++;
		translated = status_translation_get_ntranslated (translation);
		fuzzy = status_translation_get_nfuzzy (translation);
		untranslated = status_translation_get_nuntranslated (translation);
		version = status_translation_get_version (translation);
		ptranslated = (float) translated / (float) status_version_get_nstrings (version);
		pfuzzy = (float) fuzzy / (float) status_version_get_nstrings (version);
		puntranslated = (float) untranslated / (float) status_version_get_nstrings (version);
		team = status_translation_get_team (translation);

		if (status_version_get_nstrings (version) != translated + fuzzy + untranslated) {
			/* TODO: We should implement a way to show a warning */
		}
		
		fprintf (index, "        <tr class=\"moduleVersionRow%s\">\n", (i % 2 == 0) ? "Even" : "Odd");
		fprintf (index, "          <td><a href=\"../modules/%s/\">%s</a></td>\n",
					status_version_get_module_name (version), status_version_get_module_name (version));
		fprintf (index, "          <td><a href=\"../modules/%s/%s/\">%s</a></td>\n",
					status_version_get_module_name (version),
					status_version_get_id (version), status_version_get_id (version));
		fprintf (index, "          <td>%d</td>\n", translated);
		if (ptranslated == 1) {
			fprintf (index, "          <td>100</td>\n");
		} else {
			fprintf (index, "          <td>%.2f</td>\n", ptranslated*100);
		}
		fprintf (index, "          <td>%d</td>\n", fuzzy);
		if (pfuzzy == 1) {
			fprintf (index, "          <td>100</td>\n");
		} else {
			fprintf (index, "          <td>%.2f</td>\n", pfuzzy*100);
		}
		fprintf (index, "          <td>%d</td>\n", untranslated);
		if (puntranslated == 1) {
			fprintf (index, "          <td>100</td>\n");
		} else {
			fprintf (index, "          <td>%.2f</td>\n", puntranslated*100);
		}
		fprintf (index, "          <td>\n");
		fprintf (index, "            <img class=\"moduleVersionGraph\" src=\"/images/bar0.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*ptranslated), "translated bar");
		fprintf (index, "<img class=\"moduleVersionGraph\" src=\"/images/bar4.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*pfuzzy), "fuzzy bar");
		fprintf (index, "<img class=\"moduleVersionGraph\" src=\"/images/bar1.png\" height=\"15\" width=\"%d\" alt=\"%s\"/>", (gint) (200.0*puntranslated), "untranslated bar");
		fprintf (index, "          </td>\n");
		fprintf (index, "          <td>\n");
		fprintf (index, "            <a href=\"../modules/%s/%s/%s.%s.%s.po\"><img src=\"/images/download.png\" alt=\"%s\"/></a>\n",
				status_version_get_module_name (version), status_version_get_id (version),
				status_version_get_module_name (version), status_version_get_id (version),
				status_translation_get_lang (translation), "PO Download link");
		fprintf (index, "          </td>\n");
		fprintf (index, "          <td>\n");
		fprintf (index, "            <a href=\"../modules/%s/%s/%s.html\"><img src=\"/images/details.png\" alt=\"%s\"/></a>\n",
				status_version_get_module_name (version), status_version_get_id (version),
				status_translation_get_lang (translation), "Details link");
		fprintf (index, "          </td>\n");
		fprintf (index, "        </tr>\n");
	}


	fprintf (index, "      </table>\n");
	status_web_end_file (index);
	fclose (index);

	g_free (index_name);
	g_free (title);
}
