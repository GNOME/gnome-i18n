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

#include "status.h"


#define CVS_CO_OPTIONS "cvs -q -d \":pserver:anonymous@cvs.gnome-db.org:/cvs/gnome\" co -P"

extern char *cvsdir;
extern char *installdir;

/* Mis funciones */
gboolean
download_component (component *cmp)
{
	gchar *list;
	gchar *remove;
	gchar *checkout;

	g_return_val_if_fail (cmp != NULL, FALSE);

	if (!chdir (cvsdir)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			list = g_strdup_printf ("ls -d %s.%s > /dev/null 2>&1", cmp->dir, cmp->branch);
			if (!system (list)) {
				g_free (list);
				remove = g_strdup_printf ("rm -Rf %s.%s", cmp->dir, cmp->branch);
				g_print ("Deleting %s.%s directory...\n", cmp->dir, cmp->branch);
				if ( system (remove)) {
					g_free (remove);
					g_warning ("Unable to remove the dir %s at %s", cmp->dir, cvsdir);
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
			g_warning ("Unable to checkout the module %s at %s", cmp->dir, cvsdir);
			return FALSE;
		}
		g_free (checkout);
	} else {
		g_warning ("Unable to chdir into %s", cvsdir);
		return FALSE;
	}
	return TRUE;
}

gboolean
regenerate_component_pot (component *cmp)
{
	gchar *dir;
	
	if (!chdir (cvsdir)) {
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
			g_free (dir);
		} else {
			g_warning ("Unable to chdir into %s", dir);
			g_free (dir);
			return FALSE;
		}
	} else {
		g_warning ("Unable to chdir into %s", cvsdir);
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
	gint ntoken;
	gint return_val;
	gchar buf[200];
	GString *stats = NULL;
	gchar **tfu_temp;

	if (! chdir (cvsdir)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			dir = g_strdup_printf ("%s.%s/%s", cmp->dir, cmp->branch, cmp->podir);
		} else {
			dir = g_strdup_printf ("%s/%s", cmp->dir, cmp->podir);
		}
		if (!chdir (dir)) {
			g_print ("Copying %s as %s.%s.pot...\n", cmp->potname, cmp->name, cmp->branch);
			copy_pot = g_strdup_printf ("cp -a %s %s/%s/po/%s.%s.pot > /dev/null", cmp->potname,
						    installdir, cmp->release->version, cmp->name, cmp->branch);
			if (system (copy_pot)) {
				g_warning ("Unable to copy the file %s to %s/%s/po",
					 cmp->potname, installdir, cmp->release->version);
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
					path = g_strdup_printf ("%s/%s/po/%s.%s.pot", installdir, cmp->release->version,
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
					/* We read the output of msgfmt */
					while ((nread = read (pipefd[0], buf, 100)) > 0) {
						buf[nread] = '\0';
						if (stats == NULL) {
							stats = g_string_new (buf);
						} else {
							stats = g_string_append (stats, buf);
						}
					}
					close (pipefd[0]);
					if ( pid == wait (&return_val) && WIFEXITED (return_val) &&
					     WEXITSTATUS (return_val) == 0 && nread == 0 ) {
						/* We have all our string */
						/* Now we parse the msgfmt output */
						tfu_temp = g_strsplit (stats->str, "\n",0);
						ntoken = 0;
						while (tfu_temp[ntoken] != NULL) {
							ntoken++;
						}
						tfu = g_strsplit (tfu_temp[ntoken - 1 ], ",", 3);
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
						g_strfreev (tfu_temp);
					} else {
						cmp->nstrings = -1;
					}
					if (stats) {
						g_string_free (stats, TRUE);
					}
					break;
			}
		} else {
			g_warning ("Unable to chdir into %s", dir);
			g_free (dir);
			return FALSE;
		}
	} else {
		g_warning ("Unable to chdir into %s", cvsdir);
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
fill_translation (component *cmp, gchar *locale)
{
	pid_t pid;
	int pipefd [2];
	gchar **tfu;
	gchar **tfu_temp;
	gchar *path;
	gint ntoken;
	gint nread;
	gint return_val;
	gchar buf[200];
	GString *stats = NULL;
	release *currrelease;
	translation *trans;


	trans = (translation *) g_new0 (translation, 1);

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
			path = g_strdup_printf ("%s/%s/po/%s.%s.%s.po", installdir, cmp->release->version,
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
			/* We read the output of msgfmt */
			while ((nread = read (pipefd[0], buf, 100)) > 0) {
				buf[nread] = '\0';
				if (stats == NULL) {
					stats = g_string_new (buf);
				} else {
					stats = g_string_append (stats, buf);
				}
			}
			close (pipefd[0]);
			if ( pid == wait (&return_val) && WIFEXITED (return_val) &&
			     WEXITSTATUS (return_val) == 0 && nread == 0 ) {
				/* We have all our string */
				/* Now we parse the msgfmt output */
				tfu_temp = g_strsplit (stats->str, "\n",0);
				ntoken = 0;
				while (tfu_temp[ntoken] != NULL) {
					ntoken++;
				}
				tfu = g_strsplit (tfu_temp[ntoken - 1 ], ",", 3);
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
				g_strfreev (tfu_temp);
			} else {
				trans->translated = -1;
				trans->fuzzy = -1;
				trans->untranslated = -1;
			}
			if (stats) {
				g_string_free (stats, TRUE);
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
	
	if (!chdir (cvsdir)) {
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
						merge = g_strdup_printf ("msgmerge %s %s -o %s/%s/po/%s.%s.%s.po > /dev/null",
									 direntry->d_name, cmp->potname,
									 installdir, cmp->release->version, cmp->name, cmp->branch,
									 filesplit[0]);
						if (system (merge)) {
							g_warning ("Unable to update the file %s at %s",
								   direntry->d_name, dir);

							/* copy po file even when mesmerge fails, so that broken
							 * file is available in webpage for translator to fix.
							 */
							copy = g_strdup_printf ("cp -f %s %s/%s/po/%s.%s.%s.po",
										direntry->d_name, installdir, cmp->release->version,
										cmp->name, cmp->branch, filesplit[0]);
							if (system (copy)) {
								g_warning ("Unable to copy file %s to %s",
									   direntry->d_name, dir);
							} else {
								fill_translation (cmp, filesplit[0]);
							}
							g_free (copy);
						} else {
							fill_translation (cmp, filesplit[0]);
						}
						g_free (merge);
					}
					g_strfreev (filesplit);
					direntry = readdir (podir);
				}
				closedir (podir);
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
	
	if (cmp->download && !download_component (cmp)) {
		if (strcmp (cmp->dir, "gnome-i18n")) {
			g_warning ("Downloading: We have problems with %s.%s (module %s), skiped...",
				   cmp->dir, cmp->branch, cmp->name);
		} else {
			g_warning ("Downloading: We have problems with %s (module %s), skiped...",
				   cmp->dir, cmp->name);
		}
	}
	
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
status_update_po_release (gpointer key, gpointer value, gpointer user_data)
{
	release *rls;

	rls = (release *) value;
	g_list_foreach (rls->components, process_component, NULL);
	generate_release_html (rls);
	generate_locale_html (rls);

}
