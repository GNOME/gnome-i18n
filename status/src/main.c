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

#include <stdlib.h>
#include <popt.h>
#include <glib.h>
#include <locale.h>
#include "status.h"
#include "status-version.h"
#include "status-module.h"
#include "status-team.h"
#include "status-translator.h"

config_t config;
GList *langs;
status_data *sdata;
GHashTable *translators;

gchar *
status_obfuscate_email (const gchar *email)
{
	gchar **str;
	GString *buf = NULL;
	gchar *ret;
	gint i;

	str = g_strsplit (email, "@", 0);

	buf = g_string_new (str[0]);

	for (i = 1; str[i] != NULL; i++) {
		g_string_append_printf (buf, "_at_%s", str[i]);
	}
	
	g_strfreev (str);

	str = g_strsplit (buf->str, ".", 0);

	buf = g_string_assign (buf, str[0]);

	for (i = 1; str[i] != NULL; i++) {
		g_string_append_printf (buf, "_dot_%s", str[i]);
	}
		
	ret = buf->str;
	g_string_free (buf, FALSE);

	return ret;
}

static void
update_versions (gpointer key, gpointer value, gpointer user_data)
{
	StatusVersion *version;
	status_data *sdata;

	version = STATUS_VERSION (value);
	sdata = (status_data *) user_data;

	if (status_version_download (version, config.download_dir)) {
		/* TODO: Save a timestamp to remember the last update time */

		if (status_version_generate_pot (version, config.download_dir, config.install_dir)) {
			status_version_update_po (version, config.download_dir, config.install_dir);
		}
	}
}

static void
generate_module_report (gpointer key, gpointer value, gpointer user_data)
{
	StatusModule *module;

	module = STATUS_MODULE (value);
	
	status_module_report (module);
}

static void
generate_team_report (gpointer key, gpointer value, gpointer user_data)
{
	StatusTeam *team;
	FILE *index;
	gint nstrings, translated, fuzzy, untranslated;
	gfloat ptranslated, pfuzzy, puntranslated;
	gint i;


	team = STATUS_TEAM (value);
	index = (FILE *) user_data;

	status_team_report (team);

	translated = status_team_get_ntranslated (team);
	fuzzy = status_team_get_nfuzzy (team);
	untranslated = status_team_get_nuntranslated (team);
	nstrings = status_team_get_nstrings (team);
	ptranslated = (float) translated / (float) nstrings;
	pfuzzy = (float) fuzzy / (float) nstrings;
	puntranslated = (float) untranslated / (float) nstrings;

	fprintf (index, "        <tr class=\"moduleVersionRow%s\">\n", (i % 2 == 0) ? "Even" : "Odd");
	fprintf (index, "          <td><a href=\"%s.html\">%s</a></td>\n",
			status_team_get_email (team), status_team_get_name (team));
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

static void
generate_translator_report (gpointer key, gpointer value, gpointer user_data)
{
	StatusTranslator *translator;

	translator = STATUS_TRANSLATOR (value);

	status_translator_report (translator);
}

int
main (int argc, const char *argv[])
{
	int ttl;
	poptContext context;
	gint rc;
	FILE *index;
	gchar *index_name, *title;
	struct poptOption options[] = {
		{ "modules-file", 0, POPT_ARG_STRING, &config.modules, 0,
		"Set the file where is stored all modules information", "FILE" },
		{ "download-dir", 0, POPT_ARG_STRING, &config.download_dir, 0,
		"Set the directory where we will store the downloaded modules", "PATH" },
		{ "install-dir", 0, POPT_ARG_STRING, &config.install_dir, 0,
		"Set the directory where we will store the output", "PATH" },
		{ "templates-dir", 0, POPT_ARG_STRING, &config.templates_dir, 0,
		"Set the directory where is stored the web templates", "PATH" },
		{ "ttl", 0, POPT_ARG_INT, &ttl, 0, "Default ttl for translations", "SECONDS" },
		POPT_AUTOHELP
	        { NULL, 0, 0, NULL, 0 }
	};

	config.modules = "/home/carlos/Desarrollos/gnome/gnome-i18n/status/data/status-gnome.xml";
	config.download_dir = "/home/carlos/Desarrollos/gnome/";
	config.install_dir = "/home/carlos/public_html/GNOME/l10n/";
	config.templates_dir = "/home/carlos/Desarrollos/gnome/gnome-i18n/status/data/templates/";
	config.default_title = "GNOME's GUI messages translation statistics";

	/* We want always default locale for sort, messages, etc... we will change it
	 * as needed.
	 */
	setlocale (LC_MESSAGES, "C");
	setenv ("LC_MESSAGES", "C", 1);

	context = poptGetContext (NULL, argc, argv, options, 0);

	while ((rc = poptGetNextOpt (context)) > 0) {
		switch (rc) {
		/* specific arguments are handled here */
		}
	}

	g_type_init ();

	/* 1.- Parse the .xml file */
	sdata = status_xml_get_main_data (config.modules);
	langs = NULL;
	translators = g_hash_table_new_full (g_str_hash, g_str_equal, g_free, g_object_unref);

	/* 2.- Download/Update
	 * 2a.- The version files
	 * 2b.- The .pot file
	 * 2c.- The translations (*.po)
	 */

	g_hash_table_foreach (sdata->versions, update_versions, sdata);
	
	/* 3b.- Save the data ?? */
	/* 4.- Create the .html files */

	g_hash_table_foreach (sdata->modules, generate_module_report, NULL);


	index_name = g_strdup_printf ("%s/teams/index.html", config.install_dir);
	title = g_strdup_printf ("%s - %s", config.default_title, "Teams");
	
	index = status_web_new_file (index_name, title, NULL);
	fprintf (index, "      <table class=\"moduleVersionTable\">\n");
	fprintf (index, "        <tr class=\"moduleVersionRow\">\n");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "Team");
	fprintf (index, "          <th class=\"moduleVersionFieldTrans\">%s</th>\n", "Trans.");
	fprintf (index, "          <th class=\"moduleVersionFieldTrans\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionFieldFuzzy\">%s</th>\n", "Fuzzy");
	fprintf (index, "          <th class=\"moduleVersionFieldFuzzy\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionFieldUntra\">%s</th>\n", "Untrans.");
	fprintf (index, "          <th class=\"moduleVersionFieldUntra\">%%</th>\n");
	fprintf (index, "          <th class=\"moduleVersionField\">%s</th>\n", "Graph");
	fprintf (index, "        </tr>\n");
	
	g_hash_table_foreach (sdata->teams, generate_team_report, index);

	fprintf (index, "      </table>\n");
	status_web_end_file (index);
	fclose (index);
	g_free (index_name);
	g_free (title);

	
	g_hash_table_foreach (translators, generate_translator_report, NULL);

	/* We should free now the dinamic memory */
	g_hash_table_destroy (sdata->servers);
	g_hash_table_destroy (sdata->modules);
	g_hash_table_destroy (sdata->versions);
	g_hash_table_destroy (sdata->views);
	g_free (sdata);

	g_hash_table_destroy (translators);

	return 0;
}
