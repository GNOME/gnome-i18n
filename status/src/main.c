/*
 * Copyright (C) 2002 GNOME Foundation.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 * Authors:
 *   Carlos Perelló Marín <carlos@gnome-db.org>
 */

#include <popt.h>
#include <glib.h>

#define N_(String) String

char *modules;
char *cvsdir = "/home/gnome-i18n/cvs/";
char *installdir = "/home/gnome-i18n/html/";
int ttl;

struct poptOption options[] = {
	{ "modules-file", 0, POPT_ARG_STRING, &modules, 0, N_("Set the file where is stored all modules information"), N_("FILE") },
	{ "cvs-dir", 0, POPT_ARG_STRING, &cvsdir, 0, N_("Set the directory where we can find cvs modules"), N_("PATH") },
	{ "install-dir", 0, POPT_ARG_STRING, &installdir, 0, N_("Set the directory where we will store the output"), N_("PATH") },
	{ "ttl", 0, POPT_ARG_INT, &ttl, 0, N_("Default ttl for translations"), N_("SECONDS") },
	POPT_AUTOHELP
        { NULL, 0, 0, NULL, 0 }
};


GHashTable *status_xml_parse (gchar *modules);
void status_update_po_release (gpointer key, gpointer value, gpointer user_data);
void generate_locale_html (gpointer key, gpointer value, gpointer user_data);
void generate_release_html (gpointer key, gpointer value, gpointer user_data);

int
main (int argc, const char *argv[])
{

poptContext context;
gint rc;
GHashTable *releases;

	context = poptGetContext (NULL, argc, argv, options, 0);

	while ((rc = poptGetNextOpt (context)) > 0) {
		switch (rc) {
		/* specific arguments are handled here */
		}
	}

	/* 1.- Parse the input .xml file */
	releases = status_xml_parse (modules);
	
	/* 2.- Update all .po files */
	
	/* TODO: We should be able to tell at runtime the releases
	 * we want to update
	 */
	g_hash_table_foreach (releases, status_update_po_release, NULL);
	
	/* 2b.- Save the data ?? */
	/* 3.- Create the .html files */
	g_hash_table_foreach (releases, generate_release_html, NULL);
	g_hash_table_foreach (releases, generate_locale_html, NULL);

}
