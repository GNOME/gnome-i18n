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

#ifndef STATUS_H
#define STATUS_H

#include <stdio.h>
#include <glib.h>

typedef struct {
	GHashTable *servers;
	GHashTable *modules;
	GHashTable *versions;
	GHashTable *views;
	GHashTable *teams;
} status_data;

typedef struct {
	gchar *modules;
	gchar *download_dir;
	gchar *install_dir;
	gchar *templates_dir;
	gchar *default_title;
} config_t;

typedef struct {
	gchar *uri;
	gchar *description;
} url_t;

extern config_t config;
extern status_data *sdata;
extern GList *langs;
extern GHashTable *translators;

status_data *status_xml_get_main_data (const gchar *views_file);
FILE        *status_web_new_file (gchar *file_name, gchar *title, gchar *lang);
void         status_web_end_file (FILE *file);
gchar       *status_obfuscate_email (const gchar *email);


#endif
