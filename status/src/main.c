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

#include <popt.h>
#include <glib.h>
#include <locale.h>
#include "status.h"
#include "status-version.h"
#include "status-module.h"

config_t config;

static void
update_versions (gpointer key, gpointer value, gpointer user_data)
{
	StatusVersion *version;

	version = STATUS_VERSION (value);

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

int
main (int argc, const char *argv[])
{
	int ttl;
	poptContext context;
	gint rc;
	status_data *sdata;
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
	setlocale (LC_ALL, "C");

	context = poptGetContext (NULL, argc, argv, options, 0);

	while ((rc = poptGetNextOpt (context)) > 0) {
		switch (rc) {
		/* specific arguments are handled here */
		}
	}

	g_type_init ();

	/* 1.- Parse the .xml file */
	sdata = status_xml_get_main_data (config.modules);

	/* 2.- Download/Update
	 * 2a.- The version files
	 * 2b.- The .pot file
	 * 2c.- The translations (*.po)
	 */

	g_hash_table_foreach (sdata->versions, update_versions, NULL);
	
	/* 3b.- Save the data ?? */
	/* 4.- Create the .html files */

	g_hash_table_foreach (sdata->modules, generate_module_report, NULL);

	/* We should free now the dinamic memory */
	g_hash_table_destroy (sdata->servers);
	g_hash_table_destroy (sdata->modules);
	g_hash_table_destroy (sdata->versions);
	g_hash_table_destroy (sdata->views);
	g_free (sdata);

	return 0;
}
