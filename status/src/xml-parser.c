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

#include <parser.h>
#include <parserInternals.h>

#include "status.h"

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
static GHashTable *locales = NULL;
static gint *translated = NULL;
static gint *total = NULL;

/*
 *
 */
GHashTable *
status_xml_parse (gchar *modulefile)
{
xmlParserCtxtPtr ctxt_ptr;
parse_state parsing_state;

	memset (&mySAXParseCallbacks, sizeof (mySAXParseCallbacks), 0);
	mySAXParseCallbacks.startDocument = start_document;
	mySAXParseCallbacks.endDocument = end_document;
	mySAXParseCallbacks.startElement = start_element;
	mySAXParseCallbacks.endElement = end_element;
	mySAXParseCallbacks.characters = chars_found;

	ctxt_ptr = xmlCreateFileParserCtxt (modulefile);
	if (!ctxt_ptr) {
		fprintf(stderr, "Failed to create file parser\n");
		return NULL;
	}

	ctxt_ptr->sax = &mySAXParseCallbacks; /* Set callback map */
	ctxt_ptr->userData = &parsing_state;

	xmlParseDocument(ctxt_ptr);
	if (!ctxt_ptr->wellFormed) {
		fprintf(stderr, "Document not well formed\n");
	}

	ctxt_ptr->sax = NULL;

	xmlFreeParserCtxt(ctxt_ptr);
	return releases;

} /* main */


static void
start_document (void *ctx)
{
parse_state *state_ptr;

	state_ptr = (parse_state *) ctx;
	*state_ptr = parse_start_s;
	
} /* start_document */

static void
end_document(void *ctx)
{
parse_state *state_ptr;

	state_ptr = (parse_state *) ctx;
	*state_ptr = parse_finish_s;

} /* end_document */

static void
start_element(void *ctx, const CHAR *name, const CHAR **attrs)
{
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
end_element(void *ctx, const CHAR *name)
{
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
chars_found(void *ctx, const CHAR *chars, int len)
{
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
state_event_machine (parse_state curr_state, parse_event curr_event)
{
int i;

	for (i = 0; i < sizeof (event_state) / sizeof (*event_state); i++) {
		if (curr_event == event_state[i].pe)
			return event_state[i].ns;
	}

	return parse_unknown_s;
} /* state_event_machine */
