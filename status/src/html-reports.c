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

#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <time.h>
#include <dirent.h>
#include <string.h>

#include "status.h"

extern gchar *cvsdir;
extern gchar *installdir;

static gint *translated = NULL;
static gint *total = NULL;

static gint *total_trans = NULL;
static gint *total_fuzzy = NULL;
static gint *total_untrans = NULL;


void
generate_release_locales_html (gpointer data, gpointer user_data)
{
	gchar *locale;
	GString **html;
	gchar *temp;

	locale = (gchar *) data;
	html = (GString **) user_data;

	temp = g_strdup_printf ("\t<td><a href=\"%s.html\">%s</a></td>\n", locale, locale);
	//temp = g_strdup_printf ("\t<td>%s</td>\n", locale);	
	*html = g_string_append (*html, temp);
	g_free (temp);
}

void
generate_release_components_html (gpointer data, gpointer user_data)
{
	release *prelease;
	component *pcmp;
	translation *ptrns;
	GString **html;
	gchar *temp;
	gchar *poname;
	gchar *modulelnk;
	gchar *comment;
	GList *llocale;
	
	pcmp = (component *) data;
	prelease = pcmp->release;
	html = (GString **) user_data;

	comment = g_strdup_printf ("\n<!-- %s %s -->\n", pcmp->name, pcmp->branch);

	modulelnk = g_strdup_printf ("\t<td align=right><a href=\"po/%s.%s.pot\">%s</a></td>\n", pcmp->name,
				     pcmp->branch, pcmp->name);
	
	*html = g_string_append (*html, comment);
	*html = g_string_append (*html, "<tr bgcolor='#c5c2c5' align=center>\n");
	*html = g_string_append (*html, modulelnk);
	
	llocale = g_list_first (prelease->locales);
	for (; llocale != NULL; llocale = g_list_next (llocale)) {
		ptrns = g_hash_table_lookup (pcmp->translations, llocale->data);
		if ( ptrns == NULL) { /* We don't have a translation */
			temp = g_strdup_printf ("\t<td align=right>N/A</td>\n");
			total[g_list_position (prelease->locales, llocale)] += pcmp->nstrings;

		} else { /* We have a translation for this locale */

			/* But if the translation is faulty... */
			if ((ptrns->translated == -1) && (ptrns->fuzzy == -1) && (ptrns->untranslated == -1)) {
				temp = g_strdup_printf ("\t<td bgcolor='#d06060' align=right>"\
							"<a href=\"po/%s.%s.%s.po\">Error</a></td>\n",
							pcmp->name, pcmp->branch, ptrns->locale);
				total[g_list_position (prelease->locales, llocale)] += pcmp->nstrings;

			} else {
				gfloat stats;

				stats = ((gfloat) ptrns->translated / (gfloat) pcmp->nstrings)*100;
				temp = g_strdup_printf ("\t<td align=right><a href=\"po/%s.%s.%s.po\">%.2f</a></td>\n",
							pcmp->name, pcmp->branch, ptrns->locale, stats);
				translated[g_list_position (prelease->locales, llocale)] += ptrns->translated;
				total[g_list_position (prelease->locales, llocale)] +=
					ptrns->translated + ptrns->fuzzy + ptrns->untranslated;
			}
		}
		*html = g_string_append (*html, temp);
		g_free (temp);
	}

	*html = g_string_append (*html, modulelnk);
	*html = g_string_append (*html, "</tr>\n");
	g_free (modulelnk);
}
	
/*
 * Generates the HTML reports
 */
void
/*
generate_release_html (gpointer key, gpointer value, gpointer user_data)
*/
generate_release_html (release *prelease)
{
/*	release *prelease;*/
	GString *html;
	gchar *temp;
	gchar *pdate;
	time_t date;
	GList *llocale;
	GList *lcomponent;
	component *cmp;


/*	prelease = (release *) value;*/

	date = time (NULL);
	pdate = g_strdup (asctime (gmtime (&date)));
	/* Remove the '\n' char */
	pdate[strlen (pdate) - 1] = 0;

	/* First we generate "static" section */
	temp = g_strdup_printf ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n"\
				"<html>\n"\
				"<head>\n"\
				"<meta HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">\n"\
				"<title>%s (%s UTC)</title>\n"\
				"</head>\n"\
				"<body>\n"\
				"<center>\n"\
				"<h1>%s<br>(%s UTC)</h1>\n"\
				"</center>\n"\
				"<p>"\
				"<pre>"\
				"%s"\
				"</pre>\n"\
				"<table cellpadding=1 cellspacing=1 border=1 width=\"90%%\">\n"\
				"<tr align=center>\n"\
				"\t<td></td>\n", prelease->maintitle, pdate, prelease->maintitle, pdate,
				prelease->mainhead);
	html = g_string_new (temp);
	g_free (temp);
	g_free (pdate);

	g_list_foreach (prelease->locales, generate_release_locales_html, &html);
	
	html = g_string_append (html, "\t<td></td>\n</tr>\n");

	translated = g_new0 (guint, g_list_length (prelease->locales));
	total = g_new0 (guint, g_list_length (prelease->locales));
	
	g_list_foreach (prelease->components, generate_release_components_html, &html);

	html = g_string_append (html, "<tr align=center bgcolor=\"#ffd700\">\n\t<th>Total</th>\n");

	llocale = g_list_first (prelease->locales);
	for (; llocale != NULL; llocale = g_list_next (llocale)) {
		gfloat stats;
		
		stats = ( (gfloat) translated[g_list_position (prelease->locales, llocale)] /
			 (gfloat) total[g_list_position (prelease->locales, llocale)])*100;
		temp = g_strdup_printf ("\t<th align=right>%.2f</th>\n", stats);
		html = g_string_append (html, temp);
		g_free (temp);
	}
	html = g_string_append (html, "\t<th>Total</th>\n</tr>\n<tr align=center>\n\t<td></td>\n");
	
	g_list_foreach (prelease->locales, generate_release_locales_html, &html);

	html = g_string_append (html, "\t<td></td>\n</tr></table>\n");

	temp = g_strdup_printf ("<p>Total number of translatable strings in above modules: %d\n", total[0]);
	html = g_string_append (html, temp);
	html = g_string_append (html, "<p>Stable branches with tags: <br><br>\n");

	g_free (temp);

	lcomponent = g_list_first (prelease->components);
	for (; lcomponent != NULL; lcomponent = g_list_next (lcomponent)) {
		cmp = (component *) lcomponent->data;
		if (strcmp (cmp->branch, "HEAD")) {
			temp = g_strdup_printf ("%s: %s<br>\n", cmp->name, cmp->branch);
			html = g_string_append (html, temp);
			g_free (temp);
		}
	}
	
	html = g_string_append (html, "</body></html>\n");

	/* TODO: We should made a new function with this code */
	{
		FILE *htmlpage;
		gchar *path;

		path = g_strconcat (installdir, "/", cmp->release->version, "/index.html", NULL);
		htmlpage = fopen (path, "w");
		g_free (path);
		
		if (fwrite (html->str, html->len, 1, htmlpage) != 1) {
			g_error ("We couldn't write status.shtml!!");
		}
		fclose (htmlpage);
		g_string_free (html, TRUE);
	}
	g_free (translated);
	g_free (total);
}



/*
 * Create individual HTML pages for every locale
 */
void
/*
generate_locale_html (gpointer key, gpointer value, gpointer user_data)
*/
generate_locale_html (release *prelease)
{
/*	release *prelease;*/
	GString *html;
	gchar *temp;
	gchar *pdate;
	time_t date;
	GList *llocale;
	
	GList *lcmp;
	translation *ptrns;
	component *pcmp;
	
	gchar *temp_info;
	gchar *modulelnk;
	gchar *comment;
	
/*	prelease = (release *) value;*/

	date = time (NULL);
	pdate = g_strdup (asctime (gmtime (&date)));
	/* Remove the '\n' char */
	pdate[strlen (pdate) - 1] = 0;

	llocale = g_list_first (prelease->locales);
	for (; llocale != NULL; llocale = g_list_next (llocale))
	{
		gfloat stats;

		/* First we generate "static" section */
		temp = g_strdup_printf ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n"\
					"<html>\n"\
					"<head>\n"\
					"<meta HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">\n"\
					"<title>%s (%s UTC)</title>\n"\
					"</head>\n"\
					"<body>\n"\
					"<center>\n"\
					"<h1>%s<br>%s (%s UTC)</h1>\n"\
					"</center>\n"\
					"<p>"\
					"<pre>"\
					"%s"\
					"</pre>\n"\
					"<table cellpadding=1 cellspacing=1 border=1 width=\"90%%\">\n"\
					"<tr align=center>\n"\
					"\t<td>Module</td>\n", prelease->maintitle, pdate, prelease->maintitle, 
					(gchar *)llocale->data, pdate, prelease->mainhead);
		html = g_string_new (temp);
		g_free (temp);
		
	
		//g_list_foreach (prelease->locales, generate_release_locales_html, &html);
		
		html = g_string_append (html, "\t<td>Translated</td>\n");
		html = g_string_append (html, "\t<td>Fuzzy</td>\n");
		html = g_string_append (html, "\t<td>Untranslated</td>\n");
		html = g_string_append (html, "\t<td>%</td>\n</tr>\n");
	
		translated = g_new0 (guint, g_list_length (prelease->locales));
		total = g_new0 (guint, g_list_length (prelease->locales));
		total_trans = g_new0 (guint, g_list_length (prelease->locales));
		total_fuzzy = g_new0 (guint, g_list_length (prelease->locales));
		total_untrans = g_new0 (guint, g_list_length (prelease->locales));
		
		/*Process each module*/	
	
		lcmp = g_list_first (prelease->components);
		for (; lcmp != NULL; lcmp = g_list_next (lcmp))
		{
			pcmp = (component *)lcmp->data;
			comment = g_strdup_printf ("\n<!-- %s %s -->\n", pcmp->name, pcmp->branch);

			modulelnk = g_strdup_printf ("\t<td align=right><a href=\"po/%s.%s.pot\">%s</a></td>\n", pcmp->name,
				     pcmp->branch, pcmp->name);
	
			html = g_string_append (html, comment);
			html = g_string_append (html, "<tr bgcolor='#c5c2c5' align=center>\n");
			html = g_string_append (html, modulelnk);

			g_free (comment);
			g_free (modulelnk);

			ptrns = g_hash_table_lookup (pcmp->translations, llocale->data);
			if ( ptrns == NULL) /* We don't have a translation */
			{ 
				temp = g_strdup_printf ("\t<td align=right>0</td>\n"\
							"\t<td align=right>0</td>\n"\
							"\t<td align=right>%d</td>\n", pcmp->nstrings);

				temp_info = g_strdup_printf ("\t<td align=right><a href=\"po/%s.%s.pot\">N/A</a></td>\n",
							     pcmp->name, pcmp->branch);
				temp = g_strconcat (temp, temp_info, NULL);

				total[g_list_position (prelease->locales, llocale)] += pcmp->nstrings;

				total_untrans[g_list_position (prelease->locales, llocale)] += pcmp->nstrings;
			} 
			else 
			{ /* We have a translation for this locale */

				/* But if the translation is faulty... */
				if ((ptrns->translated == -1) && (ptrns->fuzzy == -1) && (ptrns->untranslated == -1)) {

					temp = g_strdup_printf ("\t<td bgcolor='#d06060' align=right>0</td>\n"\
								"\t<td bgcolor='#d06060' align=right>0</td>\n"\
								"\t<td bgcolor='#d06060' align=right>%d</td>\n", pcmp->nstrings);
					temp_info = g_strdup_printf ("\t<td bgcolor='#d06060' align=right><a href=\"po/%s.%s.%s.po\">Error</a></td>\n",
								pcmp->name, pcmp->branch, ptrns->locale);
					temp = g_strconcat (temp, temp_info, NULL);
					total[g_list_position (prelease->locales, llocale)] += pcmp->nstrings;
					total_untrans[g_list_position (prelease->locales, llocale)] += pcmp->nstrings;

				} else {

					gfloat stats;

					temp = g_strdup_printf ("\t<td align=right>%d</td>\n", ptrns->translated);

					temp_info = g_strdup_printf ("\t<td align=right>%d</td>\n", ptrns->fuzzy);
					temp = g_strconcat (temp, temp_info, NULL);
					temp_info = g_strdup_printf ("\t<td align=right>%d</td>\n", ptrns->untranslated);
					temp = g_strconcat (temp, temp_info, NULL);

					stats = ((gfloat) ptrns->translated / (gfloat) pcmp->nstrings)*100;
					temp_info = g_strdup_printf ("\t<td align=right><a href=\"po/%s.%s.%s.po\">%.2f</a></td>\n",
								pcmp->name, pcmp->branch, ptrns->locale, stats);
					translated[g_list_position (prelease->locales, llocale)] += ptrns->translated;
					total[g_list_position (prelease->locales, llocale)] += ptrns->translated + ptrns->fuzzy + 
						ptrns->untranslated;
					temp = g_strconcat (temp, temp_info, NULL);

					total_trans[g_list_position (prelease->locales, llocale)] += ptrns->translated;
					total_fuzzy[g_list_position (prelease->locales, llocale)] += ptrns->fuzzy;
					total_untrans[g_list_position (prelease->locales, llocale)] += ptrns->untranslated;

				}
			}
			html = g_string_append (html, temp);
			g_free (temp);
			g_free (temp_info);
		}
		
	
		/*end process each module */
	
		html = g_string_append (html, "<tr align=center bgcolor=\"#ffd700\">\n\t<th>Total</th>\n");
		
		temp = g_strdup_printf ("\t<th align=right>%d</th>\n", 
					total_trans[g_list_position (prelease->locales, llocale)]);
		html = g_string_append (html, temp);
		g_free (temp);
		
		temp = g_strdup_printf ("\t<th align=right>%d</th>\n", 
					total_fuzzy[g_list_position (prelease->locales, llocale)]);
		html = g_string_append (html, temp);
		g_free (temp);
		
		temp = g_strdup_printf ("\t<th align=right>%d</th>\n", 
					total_untrans[g_list_position (prelease->locales, llocale)]);
		html = g_string_append (html, temp);
		g_free (temp);

		stats = ((gfloat) translated[g_list_position (prelease->locales, llocale)] /
			 (gfloat) total[g_list_position (prelease->locales, llocale)])*100;
		temp = g_strdup_printf ("\t<th align=right>%.2f</th>\n", stats);
		html = g_string_append (html, temp);
		g_free (temp);

		html = g_string_append (html, "</tr></table>\n");

		html = g_string_append (html, "</body></html>\n");

		/* TODO: We should made a new function with this code */
		{
			FILE *htmlpage;
			gchar *locale;
			gchar *path;
			
			locale = (gchar *)llocale->data;
			path = g_strconcat (installdir, "/", prelease->version, "/", locale, ".html",  NULL);
			htmlpage = fopen (path, "w");
			if (fwrite (html->str, html->len, 1, htmlpage) != 1) {
				g_warning ("We couldn't write %s.html!!", locale);
			}
			fclose (htmlpage);
			g_free (path);
			g_string_free (html, TRUE);
		}
		g_free (translated);
                g_free (total);
                g_free (total_trans);
		g_free (total_fuzzy);
		g_free (total_untrans);
	}
	g_free (pdate);
}

