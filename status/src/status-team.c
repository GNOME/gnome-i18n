/* Team Status program
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
#include "status-team.h"

struct _StatusTeam {
	GObject object;

	GString *name; /* The language name */
	GString *mail;
	GList *codes;  /* The iso codes used by this team */
	GList *coordinators; /* The coordinators "name <email>" string */
	GList *urls;   /* The team URLs */
	GList *translators; /* All translators inside this team */
};

struct _StatusTeamClass {
	GObjectClass parent_class;
};

static void status_team_class_init (StatusTeamClass *klass);
static void status_team_init       (StatusTeam *team, StatusTeamClass *klass);
static void status_team_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

/*
 * StatusTeam object implementation
 */
static void
status_team_class_init (StatusTeamClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_team_finalize;
}

static void
status_team_init (StatusTeam *team, StatusTeamClass *klass)
{
	g_return_if_fail (STATUS_IS_TEAM (team));

	team->name = NULL;
	team->mail = NULL;
	team->codes = NULL;
	team->coordinators = NULL;
	team->urls = NULL;
	team->translators = NULL;
}

static void
status_team_finalize (GObject *object)
{
	StatusTeam *team = (StatusTeam *) object;

	g_return_if_fail (STATUS_IS_TEAM (team));

	if (team->name != NULL) {
		g_string_free (team->name, TRUE);
	}
	if (team->mail != NULL) {
		g_string_free (team->mail, TRUE);
	}
	if (team->codes != NULL) {
		GList *l;

		for (l = g_list_first (team->codes); l != NULL; l = g_list_next (l)) {
			g_string_free ((GString *) l->data, TRUE);
		}
		g_list_free (team->codes);
		team->codes = NULL;
	}
	if (team->coordinators != NULL) {
		GList *l;

		for (l = g_list_first (team->coordinators); l != NULL; l = g_list_next (l)) {
			g_object_unref (STATUS_TRANSLATOR(l->data));
		}
		g_list_free (team->coordinators);
		team->coordinators = NULL;
	}
	if (team->urls != NULL) {
		GList *l;

		for (l = g_list_first (team->urls); l != NULL; l = g_list_next (l)) {
			g_string_free ((GString *) l->data, TRUE);
		}
		g_list_free (team->urls);
		team->urls = NULL;
	}
	if (team->translators != NULL) {
		GList *l;

		for (l = g_list_first (team->translators); l != NULL; l = g_list_next (l)) {
			g_object_unref (STATUS_TRANSLATOR(l->data));
		}
		g_list_free (team->translators);
		team->translators = NULL;
	}

	parent_class->finalize (object);
}

GType
status_team_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusTeamClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_team_class_init,
			NULL,
			NULL,
			sizeof (StatusTeam),
			0,
			(GInstanceInitFunc) status_team_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusTeam",
					       &info, 0);
	}
	return type;
}

/**
 * status_team_new
 * @name:
 * @mail: 
 * @codes:
 * @coordinators:
 * @urls:
 *
 * Create a new #StatusTeam object
 *
 * TODO: Perhaps we should move all this code to a constructor function?
 */
StatusTeam *
status_team_new (const gchar *name, const gchar *mail, GList *coordinators, GList *urls)
{
	StatusTeam *team;

	g_return_val_if_fail (name != NULL, NULL);
	g_return_val_if_fail (mail != NULL, NULL);

	team = g_object_new (STATUS_TYPE_TEAM, NULL);

	team->name = g_string_new (name);
	team->mail = g_string_new (mail);
	team->coordinators = coordinators;
	team->urls = urls;

	return team;
}

static gint
sort_translator (gconstpointer a, gconstpointer b)
{
	StatusTranslator *first, *second;

	first = STATUS_TRANSLATOR (a);
	second = STATUS_TRANSLATOR (b);

	return (strcmp (status_translator_get_email (first), status_translator_get_email (second)));
}

void
status_team_add_translator (StatusTeam *team, StatusTranslator *translator)
{
	GList *l = NULL;
	
	g_return_if_fail (STATUS_IS_TEAM (team));
	g_return_if_fail (STATUS_IS_TRANSLATOR (translator));

	if (team->translators != NULL) {
		l = g_list_find_custom (team->translators, translator, sort_translator);
		if (l == NULL) {
			team->translators = g_list_insert_sorted (team->translators,
					g_object_ref (translator), sort_translator);
		}
	} else {
		team->translators = g_list_insert_sorted (team->translators,
				g_object_ref (translator), sort_translator);
	}
}

const gchar *
status_team_get_name (StatusTeam *team)
{
	g_return_val_if_fail (STATUS_IS_TEAM (team), NULL);

	return team->name->str;
}

const gchar *
status_team_get_email (StatusTeam *team)
{
	g_return_val_if_fail (STATUS_IS_TEAM (team), NULL);

	return team->mail->str;
}

/* FIXME: We should get only strings from one translator that are for this team */
gint
status_team_get_nstrings (StatusTeam *team)
{
	StatusTranslator *translator;
	GList *l;
	gint nstrings = 0;
	
	g_return_val_if_fail (STATUS_IS_TEAM (team), 0);

	for (l = g_list_first (team->translators); l != NULL; l = g_list_next (l)) {
		translator = STATUS_TRANSLATOR (l->data);
		
		nstrings += status_translator_get_nstrings (translator);
	}

	return nstrings;
}

/* FIXME: We should get only strings from one translator that are for this team */
gint
status_team_get_ntranslated (StatusTeam *team)
{
	StatusTranslator *translator;
	GList *l;
	gint ntranslated = 0;
	
	g_return_val_if_fail (STATUS_IS_TEAM (team), 0);

	for (l = g_list_first (team->translators); l != NULL; l = g_list_next (l)) {
		translator = STATUS_TRANSLATOR (l->data);
		
		ntranslated += status_translator_get_ntranslated (translator);
	}

	return ntranslated;
}

/* FIXME: We should get only strings from one translator that are for this team */
gint
status_team_get_nfuzzy (StatusTeam *team)
{
	StatusTranslator *translator;
	GList *l;
	gint nfuzzy = 0;
	
	g_return_val_if_fail (STATUS_IS_TEAM (team), 0);

	for (l = g_list_first (team->translators); l != NULL; l = g_list_next (l)) {
		translator = STATUS_TRANSLATOR (l->data);
		
		nfuzzy += status_translator_get_nfuzzy (translator);
	}

	return nfuzzy;
}

/* FIXME: We should get only strings from one translator that are for this team */
gint
status_team_get_nuntranslated (StatusTeam *team)
{
	StatusTranslator *translator;
	GList *l;
	gint nuntranslated = 0;
	
	g_return_val_if_fail (STATUS_IS_TEAM (team), 0);

	for (l = g_list_first (team->translators); l != NULL; l = g_list_next (l)) {
		translator = STATUS_TRANSLATOR (l->data);
		
		nuntranslated += status_translator_get_nuntranslated (translator);
	}

	return nuntranslated;
}


/**
 * status_team_report
 */
void
status_team_report (StatusTeam *team)
{
	FILE *index;
	gchar *index_name;
	gchar *title;
	GList *l;
	gint i;
	StatusTranslator *translator;
	gint translated, fuzzy, untranslated;
	gfloat ptranslated, pfuzzy, puntranslated;

	g_return_if_fail (STATUS_IS_TEAM (team));

	index_name = g_strdup_printf ("%s/teams/%s.html", config.install_dir, team->mail->str);
	title = g_strdup_printf ("%s - %s - %s", config.default_title, "Teams", team->name->str);

	
	index = status_web_new_file (index_name, title, NULL);
	fprintf (index, "      <table class=\"moduleVersionTable\">\n");
	fprintf (index, "        <tr class=\"moduleVersionRow\">\n");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "Translator");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "Mail");
	fprintf (index, "          <th class=\"moduleVersionFieldTrans\">%s</th>\n", "trans.");
	fprintf (index, "          <th class=\"moduleVersionFieldTrans\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionFieldFuzzy\">%s</th>\n", "fuzzy");
	fprintf (index, "          <th class=\"moduleVersionFieldFuzzy\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionFieldUntra\">%s</th>\n", "untrans.");
	fprintf (index, "          <th class=\"moduleVersionFieldUntra\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "graph");
	fprintf (index, "        </tr>\n");

	for (l = g_list_first (team->translators), i = 0; l != NULL; l = g_list_next (l), i++) {
		translator = STATUS_TRANSLATOR (l->data);

		translated = status_translator_get_ntranslated (translator);
		fuzzy = status_translator_get_nfuzzy (translator);
		untranslated = status_translator_get_nuntranslated (translator);
		ptranslated = (float) translated / (float) status_translator_get_nstrings (translator);
		pfuzzy = (float) fuzzy / (float) status_translator_get_nstrings (translator);
		puntranslated = (float) untranslated / (float) status_translator_get_nstrings (translator);
		
		fprintf (index, "        <tr class=\"moduleVersionRow%s\">\n", (i % 2 == 0) ? "Even" : "Odd");
		fprintf (index, "          <td><a href=\"../translators/%s.html\">%s</a></td>\n",
			status_translator_get_email (translator), status_translator_get_name (translator));
		fprintf (index, "          <td><a href=\"mailto:%s\"><img src=\"/images/mail.png\" alt=\"%s\" /></a></td>\n",
			status_translator_get_email (translator), "Mail image");
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
		fprintf (index, "        </tr>\n");
	}
	fprintf (index, "      </table>\n");
	status_web_end_file (index);
	fclose (index);

	g_free (index_name);
	g_free (title);
}
