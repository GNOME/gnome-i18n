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

#include <stdio.h>
#include "status.h"


FILE *
status_web_new_file (gchar *file_name, gchar *title, gchar *lang)
{
	FILE *file, *header_template;
	gchar *template;
	gchar buf[256];
	gint nread = 0;
	const gchar *xml_header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	const gchar *doctype_header = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";

	file = fopen (file_name, "w");
	fprintf (file, "%s\n%s\n", xml_header, doctype_header);

	template = g_strdup_printf ("%s/main-header.template", config.templates_dir);
	header_template = fopen (template, "r");

	fprintf (file, "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"%s\" xml:lang=\"%s\">\n", (lang == NULL) ? "en" : lang, (lang == NULL) ? "en" : lang);
	fprintf (file, "  <head>\n    <title>%s</title>\n", title);

	while (fgets (buf, 256, header_template) != NULL) {
		fprintf (file, "%s", buf);
	}
	fclose (header_template);
	g_free (template);

	fprintf (file, "  </head>\n");

	return file;
}
