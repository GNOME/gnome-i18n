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

#include <sys/types.h>
#include <sys/stat.h>
#include <sys/wait.h>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <time.h>
#include <dirent.h>
#include <string.h>
#include <parser.h>
#include <parserInternals.h>

#include "status.h"

/* DOWNLOAD_MODULES: if you don't want to download from CVS (use local copy of
 *                   CVS tree), set this to 0.
 */
#define DOWNLOAD_MODULES 1

/* FIXME: We should let change those values at runtime */
#define CVS_CO_OPTIONS "cvs -q -d \":pserver:carlos@cvs.gnome.org:/cvs/gnome\" co -P"
#define CVSROOTDIR "/home/carlos/cvs/"
#define HTMLROOTDIR "/home/carlos/html/"

/* Event map */
typedef enum {
	parse_start_e = 0,
	parse_finish_e,
	parse_translation_status_e,
	parse_release_e,
	parse_maintitle_e,
	parse_mainhead_e,
	parse_mainfoot_e,
	parse_module_e,
	parse_version_e,
	parse_component_e,
	parse_podir_e,
	parse_potname_e,
	parse_branch_e,
	parse_regenerate_e,
	parse_download_e,
	parse_nextrelease_e,
	parse_end_element_e,
	parse_other_e
} parse_event;

/* State map */
typedef enum {
	parse_start_s = 0,
	parse_finish_s,
	parse_release_s,
	parse_maintitle_s,
	parse_mainhead_s,
	parse_mainfoot_s,
	parse_module_s,
	parse_version_s,
	parse_component_s,
	parse_podir_s,
	parse_potname_s,
	parse_branch_s,
	parse_regenerate_s,
	parse_download_s,
	parse_nextrelease_s,
	parse_valid_string_s,
	parse_skip_string_s,
	parse_unknown_s
} parse_state;

/* Callback prototypes */
static void start_document(void *ctx);
static void end_document(void *ctx);
static void start_element(void *ctx, const CHAR *name, const CHAR **attrs);
static void end_element(void *ctx, const CHAR *name);
static void chars_found(void *ctx, const CHAR *chars, int len);

/* Utility functions */
static parse_event get_event_from_name (const char *name);
static parse_state state_event_machine (parse_state curr_state, parse_event
		curr_event);

static xmlSAXHandler mySAXParseCallbacks;

static module *currmodule = NULL;
static component *currcomponent = NULL;
static release *currrelease = NULL;
static GList *modules = NULL;
static GHashTable *releases = NULL;
static gint *translated = NULL;
static gint *total = NULL;

static gint *total_trans = NULL;
static gint *total_fuzzy = NULL;
static gint *total_untrans = NULL;


/* Mis funciones */
gboolean
download_component (component *cmp)
{
	gchar *list;
	gchar *remove;
	gchar *checkout;

	g_return_val_if_fail (cmp != NULL, FALSE);

	if (!chdir (CVSROOTDIR)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			list = g_strdup_printf ("ls -d %s.%s > /dev/null 2>&1", cmp->dir, cmp->branch);
			if (!system (list)) {
				g_free (list);
				remove = g_strdup_printf ("rm -Rf %s.%s", cmp->dir, cmp->branch);
				g_print ("Deleting %s.%s directory...\n", cmp->dir, cmp->branch);
				if ( system (remove)) {
					g_free (remove);
					g_warning ("Unable to remove the dir %s at %s", cmp->dir, CVSROOTDIR);
					return FALSE;
				}
				g_free (remove);
			} else {
				g_free (list);
			}
			if (strcmp (cmp->branch, "HEAD")) {
				checkout = g_strdup_printf (CVS_CO_OPTIONS " -r %s -d %s.%s %s > /dev/null",
							    cmp->branch, cmp->dir, cmp->branch, cmp->dir);
				g_print ("=== Checking out %s branch of %s module to %s.%s  ===\n",
					 cmp->branch, cmp->dir, cmp->dir, cmp->branch);
			} else {
				checkout = g_strdup_printf (CVS_CO_OPTIONS " -d %s.%s %s > /dev/null", cmp->dir,
							    cmp->branch, cmp->dir);
				g_print ("=== Checking out %s module to %s.%s  ===\n", cmp->dir, cmp->dir, cmp->branch);
			}
		} else {
			checkout = g_strdup_printf (CVS_CO_OPTIONS " %s > /dev/null", cmp->dir);
			g_print ("=== Checking out %s module ===\n", cmp->dir);
		}
		if (system (checkout)) {
			g_free (checkout);
			g_warning ("Unable to checkout the module %s at %s", cmp->dir, CVSROOTDIR);
			return FALSE;
		}
		g_free (checkout);
	} else {
		g_warning ("Unable to chdir into %s", CVSROOTDIR);
		return FALSE;
	}
	return TRUE;
}

gboolean
regenerate_component_pot (component *cmp)
{
	gchar *dir;
	
	if (!chdir (CVSROOTDIR)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			dir = g_strdup_printf ("%s.%s/%s", cmp->dir, cmp->branch, cmp->podir);
		} else {
			dir = g_strdup_printf ("%s/%s", cmp->dir, cmp->podir);
		}
		if (!chdir (dir)) {
			g_print ("Regenerating %s...\n", cmp->potname);
			if (system ("intltool-update -P > /dev/null")) {
				g_warning ("Unable to regenerate the file %s at %s",
					 cmp->potname, dir);
				g_free (dir);
				return FALSE;
			}
		} else {
			g_warning ("Unable to chdir into %s", dir);
			g_free (dir);
			return FALSE;
		}
	} else {
		g_warning ("Unable to chdir into %s", CVSROOTDIR);
		return FALSE;
	}
	return TRUE;
}

gboolean
copy_component_pot (component *cmp)
{
	gchar *dir;
	gchar *copy_pot;
	pid_t pid;
	int pipefd [2];
	gchar **tfu;
	gchar *path;
	gint nread;
	gint i;
	gint return_val;
	gchar buf[50];
	gchar stats[300];

	if (! chdir (CVSROOTDIR)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			dir = g_strdup_printf ("%s.%s/%s", cmp->dir, cmp->branch, cmp->podir);
		} else {
			dir = g_strdup_printf ("%s/%s", cmp->dir, cmp->podir);
		}
		if (!chdir (dir)) {
			g_print ("Copying %s as %s.%s.pot...\n", cmp->potname, cmp->name, cmp->branch);
			copy_pot = g_strdup_printf ("cp -a %s %s/po/%s.%s.pot > /dev/null", cmp->potname,
						    HTMLROOTDIR, cmp->name, cmp->branch);
			if (system (copy_pot)) {
				g_warning ("Unable to copy the file %s to %s/po",
					 cmp->potname, HTMLROOTDIR);
				g_free (copy_pot);
				g_free (dir);
				return FALSE;
			}
			g_free (copy_pot);
			g_free (dir);
			pipe (pipefd);

			switch (pid = fork ()) {
				case 0:
					close (2);
					dup (pipefd[1]);
					close (pipefd[1]);
					close (pipefd[0]);
					path = g_strdup_printf ("%s/po/%s.%s.pot", HTMLROOTDIR,
								cmp->name, cmp->branch);
					/* We get the file stats */
					execlp("msgfmt", "msgfmt", "--statistics", path, "-o", "/dev/null", NULL);
					g_error ("Fork error!!");
					break;
				case -1:
					g_error ("Fork error!!");
					break;
				default:
					close (pipefd[1]);
					i = 0;
					/* We read the output of msgfmt */
					nread = read (pipefd[0], stats, 300);
					while (nread > 0 && i <= 250) {
						i += nread;
						nread = read (pipefd[0], buf, 50);
						strncat (stats, buf, nread);
					}
					i += nread;
					close (pipefd[0]);
					if ( pid == wait (&return_val) && WIFEXITED (return_val) &&
					     WEXITSTATUS (return_val) == 0 && nread == 0 ) {
						/* We have all our string */
						/* Now we parse the msgfmt output */
						tfu = g_strsplit (stats, ",", 3);
						if ( tfu[0] != NULL) {
							if ( strstr (tfu[0], " untranslated")) {
								sscanf (tfu[0], "%d untranslated",
									&cmp->nstrings);
							}
							if ( tfu[1] != NULL) {
								if ( strstr (tfu[1], " untranslated")) {
									sscanf (tfu[1], "%d untranslated",
										&cmp->nstrings);
								}
								if ( tfu[2] != NULL && strstr (tfu[2],
											" untranslated")) {
									sscanf (tfu[2], "%d untranslated",
										&cmp->nstrings);
								}
							}
						}
						g_strfreev (tfu);
					} else {
						g_warning ("Implement me!! (copy_component_pot)");
					}
						break;
			}
		} else {
			g_warning ("Unable to chdir into %s", dir);
			g_free (dir);
			return FALSE;
		}
	} else {
		g_warning ("Unable to chdir into %s", CVSROOTDIR);
		return FALSE;
	}
	return TRUE;
}

gint
g_list_strcmp (gconstpointer a, gconstpointer b)
{
	return strcmp ((gchar *) a, (gchar *) b);
}

void
fill_translation (translation *trans, component *cmp, gchar *locale)
{
	pid_t pid;
	int pipefd [2];
	gchar **tfu;
	gchar *path;
	gint nread;
	gint i;
	gint return_val;
	gchar buf[50];
	gchar stats[300];
	
	trans->locale = g_strdup (locale);
	trans->pcomponent = cmp;

	g_hash_table_insert (cmp->translations, g_strdup (locale), trans);

	currrelease = cmp->release;
	if (currrelease != NULL) {
		if (g_list_find_custom (currrelease->locales, locale, g_list_strcmp) == NULL) {
			currrelease->locales = g_list_insert_sorted (currrelease->locales, g_strdup (locale),
								     g_list_strcmp);
		}
		currrelease = NULL;
	} else {
		g_warning ("The release does not exist (fill_translation)!!");
	}
	
	pipe (pipefd);

	switch (pid = fork ()) {
		case 0:
			close (2);
			dup (pipefd[1]);
			close (pipefd[1]);
			close (pipefd[0]);
			path = g_strdup_printf ("%s/po/%s.%s.%s.po", HTMLROOTDIR,
						cmp->name, cmp->branch,	locale);
			/* We get the file stats */
			execlp("msgfmt", "msgfmt", "--statistics", path, "-o", "/dev/null", NULL);
			g_error ("Fork error!!");
			break;
		case -1:
			g_error ("Fork error!!");
			break;
		default:
			close (pipefd[1]);
			i = 0;
			/* We read the output of msgfmt */
			nread = read (pipefd[0], stats, 300);
			while (nread > 0 && i <= 250) {
				i += nread;
				nread = read (pipefd[0], buf, 50);
				strncat (stats, buf, nread);
			}
			i += nread;
			close (pipefd[0]);
			if ( pid == wait (&return_val) && WIFEXITED (return_val) &&
			     WEXITSTATUS (return_val) == 0 && nread == 0 ) {
				/* We have all our string */
				/* Now we parse the msgfmt output */
				tfu = g_strsplit (stats, ",", 3);
				if ( tfu[0] != NULL) {
					if ( strstr (tfu[0], " translated")) {
						sscanf (tfu[0], "%d translated", &trans->translated);
					} else if ( strstr (tfu[0], " fuzzy")) {
						sscanf (tfu[0], "%d fuzzy", &trans->fuzzy);
					} else if ( strstr (tfu[0], " untranslated")) {
						sscanf (tfu[0], "%d untranslated", &trans->untranslated);
					}
					if ( tfu[1] != NULL) {
						if ( strstr (tfu[1], " fuzzy")) {
							sscanf (tfu[1], "%d fuzzy", &trans->fuzzy);
						} else if ( strstr (tfu[1], " untranslated")) {
							sscanf (tfu[1], "%d untranslated",
								&trans->untranslated);
						}
						if ( tfu[2] != NULL && strstr (tfu[2], " untranslated")) {
							sscanf (tfu[2], "%d untranslated",
								&trans->untranslated);
						}
					}
				}
				g_strfreev (tfu);
			} else {
				trans->translated = -1;
				trans->fuzzy = -1;
				trans->untranslated = -1;
			}
	}
}


gboolean
update_component_po (component *cmp)
{
	gchar *dir;
	gchar *merge;
	gchar *copy;
	DIR *podir;
	struct dirent *direntry;
	gchar **filesplit;
	translation *currtrans;
	
	if (!chdir (CVSROOTDIR)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			dir = g_strdup_printf ("%s.%s/%s", cmp->dir, cmp->branch, cmp->podir);
		} else {
			dir = g_strdup_printf ("%s/%s", cmp->dir, cmp->podir);
		}
		if (!chdir (dir)) {
			podir = opendir (".");
			if ( podir != NULL) {
				direntry = readdir (podir);
				while (direntry != NULL) {
					filesplit = g_strsplit (direntry->d_name, ".", 2);
					if (filesplit[1] != NULL && filesplit[2] == NULL &&
					   !strcmp (filesplit[1], "po")) {
						g_print ("Updating %s.%s.%s.po:\n", cmp->name, cmp->branch,
							 filesplit[0]);
						merge = g_strdup_printf ("msgmerge %s %s -o %s/po/%s.%s.%s.po > /dev/null",
									 direntry->d_name, cmp->potname,
									 HTMLROOTDIR, cmp->name, cmp->branch,
									 filesplit[0]);
						if (system (merge)) {
							g_warning ("Unable to update the file %s at %s",
								   direntry->d_name, dir);

							/* copy po file even when mesmerge fails, so that broken
							 * file is available in webpage for translator to fix.
							 */
							copy = g_strdup_printf ("cp -f %s %s/po/%s.%s.%s.po",
										direntry->d_name, HTMLROOTDIR,
										cmp->name, cmp->branch, filesplit[0]);
							if (system (copy)) {
								g_warning ("Unable to copy file %s to %s",
									   direntry->d_name, dir);
							} else {
								currtrans = (translation *) g_new0 (translation, 1);
								fill_translation (currtrans, cmp, filesplit[0]);
							}
							g_free (copy);
						} else {
							currtrans = (translation *) g_new0 (translation, 1);
							fill_translation (currtrans, cmp, filesplit[0]);
						}
						g_free (merge);
					}
					g_strfreev (filesplit);
					direntry = readdir (podir);
				}
			} else {
				g_free (dir);
				return FALSE;
			}
			g_free (dir);
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
	return TRUE;
}

void
process_component (gpointer data, gpointer user_data)
{
	component *cmp;
	
	cmp = (component *) data;

	cmp->translations = g_hash_table_new (g_str_hash, g_str_equal);

	/* Download the component */
	
#if DOWNLOAD_MODULES
	if (cmp->download && !download_component (cmp)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			g_warning ("Downloading: We have problems with %s.%s (module %s), skiped...",
				   cmp->dir, cmp->branch, cmp->name);
		} else {
			g_warning ("Downloading: We have problems with %s (module %s), skiped...",
				   cmp->dir, cmp->name);
		}
	}
#endif
	
	/* Regenerate the .pot file */
	if (cmp->regenerate && !regenerate_component_pot (cmp)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			g_warning ("Regenerating: We have problems with %s at %s.%s (module %s), skiped...",
				   cmp->potname, cmp->dir, cmp->branch, cmp->name);
		} else {
			g_warning ("Regenerating: We have problems with %s at %s (module %s), skiped...",
				   cmp->potname, cmp->dir, cmp->name);
		}
	}

	/* Copy the .pot file to web dir */
	if (!copy_component_pot (cmp)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			g_warning ("Copying: We have problems with %s at %s.%s (module %s), skiped...",
				   cmp->potname, cmp->dir, cmp->branch, cmp->name);
		} else {
			g_warning ("Copying: We have problems with %s at %s (module %s), skiped...",
				   cmp->potname, cmp->dir, cmp->name);
		}	
	}

	/* Update *.po files */
	if (!update_component_po (cmp)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			g_warning ("Updating: We have problems updating %s's po files at %s.%s, skiped...",
				   cmp->name, cmp->dir, cmp->branch);
		} else {
			g_warning ("Updating: We have problems with %s's po files at %s, skiped...",
				   cmp->name, cmp->dir);
		}
	}
}

void
generate_release_locales_html (gpointer data, gpointer user_data)
{
	gchar *locale;
	gchar **html;
	gchar *temp;

	locale = (gchar *) data;
	html = (gchar **) user_data;

	temp = g_strdup_printf ("\t<td><a href=\"%s.html\">%s</a></td>\n", locale, locale);
	//temp = g_strdup_printf ("\t<td>%s</td>\n", locale);	
	*html = g_strconcat (*html, temp, NULL);
	g_free (temp);
}

void
generate_release_components_html (gpointer data, gpointer user_data)
{
	release *prelease;
	component *pcmp;
	translation *ptrns;
	gchar **html;
	gchar *temp;
	gchar *poname;
	gchar *modulelnk;
	gchar *comment;
	GList *llocale;
	struct stat buf;
	
	pcmp = (component *) data;
	prelease = pcmp->release;
	html = (gchar **) user_data;

	comment = g_strdup_printf ("\n<!-- %s %s -->\n", pcmp->name, pcmp->branch);

	modulelnk = g_strdup_printf ("\t<td align=right><a href=\"po/%s.%s.pot\">%s</a></td>\n", pcmp->name,
				     pcmp->branch, pcmp->name);
	
	*html = g_strconcat (*html, comment, "<tr bgcolor='#c5c2c5' align=center>\n", modulelnk, NULL);
	
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
		*html = g_strconcat (*html, temp, NULL);
		g_free (temp);
	}

	*html = g_strconcat (*html, modulelnk, "</tr>\n", NULL);
	g_free (modulelnk);
}
	
/*
 * Generates the HTML reports
 */
void
generate_release_html (gpointer key, gpointer value, gpointer user_data)
{
	release *prelease;
	gchar *html;
	gchar *temp;
	gchar *pdate;
	time_t date;
	GList *llocale;
	GList *lcomponent;
	component *cmp;


	prelease = (release *) value;

	date = time (NULL);
	pdate = g_strdup (asctime (gmtime (&date)));
	/* Remove the '\n' char */
	pdate[strlen (pdate) - 1] = 0;

	/* First we generate "static" section */
	html = g_strdup_printf ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n"\
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
	g_free (pdate);

	g_list_foreach (prelease->locales, generate_release_locales_html, &html);
	
	html = g_strconcat (html, "\t<td></td>\n</tr>\n", NULL);

	translated = g_new0 (guint, g_list_length (prelease->locales));
	total = g_new0 (guint, g_list_length (prelease->locales));
	
	g_list_foreach (prelease->components, generate_release_components_html, &html);

	html = g_strconcat (html, "<tr align=center bgcolor=\"#ffd700\">\n\t<th>Total</th>\n", NULL);

	llocale = g_list_first (prelease->locales);
	for (; llocale != NULL; llocale = g_list_next (llocale)) {
		gfloat stats;
		
		stats = ( (gfloat) translated[g_list_position (prelease->locales, llocale)] /
			 (gfloat) total[g_list_position (prelease->locales, llocale)])*100;
		temp = g_strdup_printf ("\t<th align=right>%.2f</th>\n", stats);
		html = g_strconcat (html, temp, NULL);
		g_free (temp);
	}
	html = g_strconcat (html, "\t<th>Total</th>\n</tr>\n<tr align=center>\n\t<td></td>\n", NULL);
	
	g_list_foreach (prelease->locales, generate_release_locales_html, &html);

	html = g_strconcat (html, "\t<td></td>\n</tr></table>\n", NULL);

	temp = g_strdup_printf ("<p>Total number of translatable strings in above modules: %d\n", total[0]);
	html = g_strconcat (html, temp, "<p>Stable branches with tags: <br><br>\n", NULL);

	g_free (temp);

	lcomponent = g_list_first (prelease->components);
	for (; lcomponent != NULL; lcomponent = g_list_next (lcomponent)) {
		cmp = (component *) lcomponent->data;
		if (strcmp (cmp->branch, "HEAD")) {
			temp = g_strdup_printf ("%s: %s<br>\n", cmp->name, cmp->branch);
			html = g_strconcat (html, temp, NULL);
			g_free (temp);
		}
	}
	
	html = g_strconcat (html, "</body></html>\n", NULL);

	/* TODO: We should made a new function with this code */
	{
		FILE *htmlpage;

		htmlpage = fopen (HTMLROOTDIR "/status.shtml", "w");
		if (fwrite (html, strlen (html), 1, htmlpage) != 1) {
			g_error ("We couldn't write status.shtml!!");
		}
	}
	
}



/*
 * Create individual HTML pages for every locale
 */
void
generate_locale_html (gpointer key, gpointer value, gpointer user_data)
{
	release *prelease;
	gchar *html;
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
	
	prelease = (release *) value;

	date = time (NULL);
	pdate = g_strdup (asctime (gmtime (&date)));
	/* Remove the '\n' char */
	pdate[strlen (pdate) - 1] = 0;

	llocale = g_list_first (prelease->locales);
	for (; llocale != NULL; llocale = g_list_next (llocale))
	{
		gfloat stats;

		/* First we generate "static" section */
		html = g_strdup_printf ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n"\
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
		
	
		//g_list_foreach (prelease->locales, generate_release_locales_html, &html);
		
		html = g_strconcat (html, "\t<td>Translated</td>\n", NULL);
		html = g_strconcat (html, "\t<td>Fuzzy</td>\n", NULL);
		html = g_strconcat (html, "\t<td>Untranslated</td>\n", NULL);
		html = g_strconcat (html, "\t<td>%</td>\n</tr>\n", NULL);
	
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
	
			html = g_strconcat (html, comment, "<tr bgcolor='#c5c2c5' align=center>\n", modulelnk, NULL);

			g_free (comment);
			g_free (modulelnk);

			ptrns = g_hash_table_lookup (pcmp->translations, llocale->data);
			if ( ptrns == NULL) /* We don't have a translation */
			{ 
				temp = g_strdup_printf ("\t<td align=right>N/A</td>\n"\
							"\t<td align=right>N/A</td>\n"\
							"\t<td align=right>N/A</td>\n");

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

					temp = g_strdup_printf ("\t<td bgcolor='#d06060' align=right>Error</td>\n"\
								"\t<td bgcolor='#d06060' align=right>Error</td>\n"\
								"\t<td bgcolor='#d06060' align=right>Error</td>\n");
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
			html = g_strconcat (html, temp, NULL);
			g_free (temp);
			g_free (temp_info);
		}
		
	
		/*end process each module */
	
		html = g_strconcat (html, "<tr align=center bgcolor=\"#ffd700\">\n\t<th>Total</th>\n", NULL);
		
		temp = g_strdup_printf ("\t<th align=right>%d</th>\n", 
					total_trans[g_list_position (prelease->locales, llocale)]);
		html = g_strconcat (html, temp, NULL);
		
		temp = g_strdup_printf ("\t<th align=right>%d</th>\n", 
					total_fuzzy[g_list_position (prelease->locales, llocale)]);
		html = g_strconcat (html, temp, NULL);
		
		temp = g_strdup_printf ("\t<th align=right>%d</th>\n", 
					total_untrans[g_list_position (prelease->locales, llocale)]);
		html = g_strconcat (html, temp, NULL);

		stats = ((gfloat) translated[g_list_position (prelease->locales, llocale)] /
			 (gfloat) total[g_list_position (prelease->locales, llocale)])*100;
		temp = g_strdup_printf ("\t<th align=right>%.2f</th>\n", stats);
		html = g_strconcat (html, temp, NULL);

		html = g_strconcat (html, "</tr></table>\n", NULL);

		html = g_strconcat (html, "</body></html>\n", NULL);

		/* TODO: We should made a new function with this code */
		{
			FILE *htmlpage;
			gchar *locale;

			locale = (gchar *)llocale->data;
			/*g_print("filename: %s\n", locale);*/
			temp = g_strdup_printf (HTMLROOTDIR "/%s.html", locale);
			htmlpage = fopen (temp, "w");
			if (fwrite (html, strlen (html), 1, htmlpage) != 1) {
				g_warning ("We couldn't write %s.html!!", locale);
			}
		}
		g_free (temp);

	}
	g_free (pdate);
}



void
process_module (gpointer data, gpointer user_data)
{
	module *mdl;

	mdl = (module *) data;
	g_list_foreach (mdl->components, process_component, NULL);
//	g_list_foreach (mdl->components, generate_html, NULL);

}

int
main() {

  xmlParserCtxtPtr ctxt_ptr;
  parse_state parsing_state;

  memset(&mySAXParseCallbacks, sizeof(mySAXParseCallbacks), 0);
  mySAXParseCallbacks.startDocument = start_document;
  mySAXParseCallbacks.endDocument = end_document;
  mySAXParseCallbacks.startElement = start_element;
  mySAXParseCallbacks.endElement = end_element;
  mySAXParseCallbacks.characters = chars_found;

  ctxt_ptr = xmlCreateFileParserCtxt("translation-status.xml");
  if (!ctxt_ptr) {
    fprintf(stderr, "Failed to create file parser\n");
    exit(EXIT_FAILURE);
  }

  ctxt_ptr->sax = &mySAXParseCallbacks; /* Set callback map */
  ctxt_ptr->userData = &parsing_state;

  xmlParseDocument(ctxt_ptr);
  if (!ctxt_ptr->wellFormed) {
    fprintf(stderr, "Document not well formed\n");
  }

  ctxt_ptr->sax = NULL;

  xmlFreeParserCtxt(ctxt_ptr);

  /* We have readed the input file */

  /* Start the status pages regeneration... */
  g_list_foreach (modules, process_module, NULL);

  g_hash_table_foreach (releases, generate_release_html, NULL);
  
  g_hash_table_foreach (releases, generate_locale_html, NULL);

  exit(EXIT_SUCCESS);
} /* main */


static void
start_document(void *ctx) {
  parse_state *state_ptr;
  
  state_ptr = (parse_state *) ctx;

  *state_ptr = parse_start_s;
} /* start_document */

static void
end_document(void *ctx) {
  parse_state *state_ptr;

  state_ptr = (parse_state *) ctx;

  *state_ptr = parse_finish_s;
} /* end_document */

static void
start_element(void *ctx, const CHAR *name, const CHAR **attrs) {
  parse_state *state_ptr;

  parse_state curr_state;
  parse_event curr_event;

  state_ptr = (parse_state *) ctx;
  curr_state = *state_ptr;
  curr_event = get_event_from_name (name);

  *state_ptr = state_event_machine (curr_state, curr_event);

  if (! currmodule) {
	  currmodule = (module *) g_new0 (module, 1);
  }

  if (! currcomponent) {
	  currcomponent = (component *) g_new0 (component, 1);
  }
  
  switch (curr_event) {
	case parse_release_e:
		if ( ! strcmp (attrs[0], "name")) {
			if ( !currrelease) {
				currrelease = (release *) g_new0 (release, 1);
			}
			currrelease->version = g_strdup (attrs[1]);
		} else {
			g_warning ("Option %s not supported here\n", attrs[0]);
		}
		break;
	case parse_module_e:
		if ( ! strcmp (attrs[0], "name")) {
			currmodule->name = g_strdup (attrs[1]);
		} else {
			g_warning ("Option %s not supported here\n", attrs [0]);
		}
		break;
	case parse_version_e:
		if ( ! strcmp (attrs[0], "release")) {
			if (currrelease == NULL) {
				currrelease = (release *) g_hash_table_lookup (releases, attrs[1]);
			} else {
				g_error ("currrelease should be NULL inside a version tag!!");
			}
			if (currrelease == NULL) {
				g_warning ("The release %s does not exists!!", attrs[1]);
			}
		} else {
			g_warning ("Option %s not supported here\n", attrs [0]);
		}
		break;
	case parse_component_e:
		if ( ! strcmp (attrs[0], "name")) {
			currcomponent->name = g_strdup (attrs[1]);
		} else {
			g_warning ("Option %s not supported here\n", attrs [0]);
		}
		if ( ! strcmp (attrs[2], "dir")){
			currcomponent->dir = g_strdup (attrs[3]);
		} else {
			g_warning ("Option %s not supported here\n", attrs [2]);
		}
		break;
	case parse_podir_e:
		if ( ! strcmp (attrs[0], "dir")) {
			currcomponent->podir = g_strdup (attrs[1]);
		} else {
			g_warning ("Option %s not supported here\n", attrs [0]);
		}
		break;
	case parse_potname_e:
		if ( ! strcmp (attrs[0], "name")) {
			currcomponent->potname = g_strdup (attrs[1]);
		} else {
			g_warning ("Option %s not supported here\n", attrs [0]);
		}
		break;
	case parse_branch_e:
		if ( ! strcmp (attrs[0], "name")) {
			currcomponent->branch = g_strdup (attrs[1]);
		} else {
			g_warning ("Option %s not supported here", attrs [0]);
		}
		break;
	case parse_regenerate_e:
		currcomponent->regenerate = TRUE;
		break;
	case parse_download_e:
		currcomponent->download = TRUE;
		break;
	case parse_nextrelease_e:
		if ( ! strcmp (attrs[0], "nextrelease")) {
			g_message ("The nextrelease will be at %s (disabled)", attrs[1]);
		} else {
			g_warning ("Option %s not supported here", attrs [0]);
		}
		break;
	default:
		break;
  }


} /* start_element */

static void
end_element(void *ctx, const CHAR *name) {
	parse_state *state_ptr;

	parse_state curr_state;
	parse_event curr_event;
	parse_event end_event;

	state_ptr = (parse_state *) ctx;
	curr_state = *state_ptr;
	curr_event = parse_end_element_e;
	end_event = get_event_from_name (name);

	switch (end_event) {
		case parse_module_e:
			if (currmodule != NULL) {
				if (currcomponent == NULL ) {
					modules = g_list_append (modules, currmodule);
					currmodule = NULL;
				} else {
					g_error ("We have finished a module but we have an active component");
				}
			}
			break;
		case parse_component_e:
			if (currcomponent != NULL) {
				if ( currmodule != NULL) {
					currmodule->components = g_list_append (currmodule->components,
										currcomponent);
					currcomponent->release = currrelease;
					if (currcomponent->release != NULL) {
						currcomponent->release->components =
							g_list_append (currcomponent->release->components,
								       currcomponent);
					} else {
						g_warning ("Implement me!!! (end_element)");
					}
					currcomponent->pmodule = currmodule;
					currcomponent = NULL;
				} else {
					g_error ("I have a component but I don't have any module :-?");
				}
			}
			break;
		case parse_release_e:
			if (currrelease != NULL) {
				if (releases == NULL) {
					releases = g_hash_table_new (g_str_hash, g_str_equal);
				}
				/* TODO: We should look if the key is already at Hash table */
				g_hash_table_insert (releases, currrelease->version, currrelease);
				currrelease = NULL;
			}
			break;
		case parse_version_e:
			currrelease = NULL;
			break;
		default:
			break;
	}


	*state_ptr = state_event_machine (curr_state, curr_event);
} /* end_element */

static void
chars_found(void *ctx, const CHAR *chars, int len) {
  gchar *buff;
  gchar *old;
  parse_state *state_ptr;
  state_ptr = (parse_state *) ctx;
  
  buff = g_strndup (chars, len);
  switch (*state_ptr) {
	case parse_start_s:
	case parse_finish_s:
	case parse_release_s:
	case parse_module_s:
	case parse_version_s:
	case parse_component_s:
	case parse_podir_s:
	case parse_potname_s:
	case parse_branch_s:
	case parse_regenerate_s:
	case parse_download_s:
	case parse_nextrelease_s:
		g_free (buff);
		break;
	case parse_maintitle_s:
		if (currrelease != NULL) {
			if ( currrelease->maintitle != NULL ){
				old = currrelease->maintitle;
				currrelease->maintitle = g_strconcat (old, buff, NULL);
				g_free (buff);
			} else {
				currrelease->maintitle = buff;
			}
		}
		break;
	case parse_mainhead_s:
		if (currrelease != NULL) {
			if ( currrelease->mainhead != NULL ){
				old = currrelease->mainhead;
				currrelease->mainhead = g_strconcat (old, buff, NULL);
				g_free (buff);
			} else {
				currrelease->mainhead = buff;
			}
		}
		break;
	case parse_mainfoot_s:
		if (currrelease != NULL) {
			if ( currrelease->mainfoot != NULL ){
				old = currrelease->mainfoot;
				currrelease->mainfoot = g_strconcat (old, buff, NULL);
				g_free (buff);
			} else {
				currrelease->mainfoot = buff;
			}
		}
		break;
	default:
		g_free (buff);
		break;
  } /* switch */

} /* chars_found */

const struct {
	const char *name;
	parse_event event;
} events[] = {
	{"translation-status", parse_translation_status_e},
	{"release", parse_release_e},
	{"maintitle", parse_maintitle_e},
	{"mainhead", parse_mainhead_e},
	{"mainfoot", parse_mainfoot_e},
	{"module", parse_module_e},
	{"version", parse_version_e},
	{"component", parse_component_e},
	{"podir", parse_podir_e},
	{"potname", parse_potname_e},
	{"branch", parse_branch_e},
	{"regenerate", parse_regenerate_e},
	{"download", parse_download_e},
	{"nextrelease", parse_nextrelease_e}
};

static parse_event
get_event_from_name (const char *name)
{

	int i;

	for (i = 0; i < sizeof (events) / sizeof (*events); i++) {
		if (!strcmp (name, events[i].name))
			return events[i].event;
	}
	return parse_other_e;

} /* get_event_from_name */

/* State machine lookup */
const struct {
	const parse_event pe;
	parse_state ns;
} event_state [] = {
	{parse_start_e, parse_start_s},
	{parse_finish_e, parse_finish_s},
	{parse_translation_status_e, parse_skip_string_s},
	{parse_release_e, parse_release_s},
	{parse_maintitle_e, parse_maintitle_s},
	{parse_mainhead_e, parse_mainhead_s},
	{parse_mainfoot_e, parse_mainfoot_s},
	{parse_module_e, parse_module_s},
	{parse_version_e, parse_version_s},
	{parse_component_e, parse_component_s},
	{parse_podir_e, parse_podir_s},
	{parse_potname_e, parse_potname_s},
	{parse_branch_e, parse_branch_s},
	{parse_regenerate_e, parse_regenerate_s},
	{parse_download_e, parse_download_s},
	{parse_nextrelease_e, parse_nextrelease_s}
};

static parse_state
state_event_machine (parse_state curr_state, parse_event
		curr_event)
{
	int i;

	for (i = 0; i < sizeof (event_state) / sizeof (*event_state); i++) {
		if (curr_event == event_state[i].pe)
			return event_state[i].ns;
	}

	return parse_unknown_s;
} /* state_event_machine */
