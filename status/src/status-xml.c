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

#include <libxml/parser.h>
#include <glib-object.h>
#include "status.h"
#include "status-server.h"
#include "status-module.h"
#include "status-version.h"
#include "status-view.h"
#include "status-team.h"

status_data *
status_xml_get_main_data (const gchar *views_file)
{
	xmlFreeFunc old_free;
	xmlMallocFunc old_malloc;
	xmlReallocFunc old_realloc;
	xmlStrdupFunc old_strdup;
	xmlDocPtr doc;
	xmlNodePtr root, cur;
	status_data *ret;

	g_return_val_if_fail (views_file != NULL, NULL);

	xmlMemGet (&old_free,
		   &old_malloc,
		   &old_realloc,
		   &old_strdup);

	xmlMemSetup (g_free,
		     (xmlMallocFunc) g_malloc,
		     (xmlReallocFunc) g_realloc,
		     g_strdup);

	doc = xmlParseFile (views_file);
	if (doc == NULL) {
		g_warning ("The file is wrong, empty or not well-formed.");
		xmlMemSetup (old_free,
			     old_malloc,
			     old_realloc,
			     old_strdup);
		return NULL;
	}

	root = xmlDocGetRootElement (doc);
	if (root == NULL){
		g_warning ("Cannot get root element!");
		xmlFreeDoc (doc);
		xmlMemSetup (old_free,
			     old_malloc,
			     old_realloc,
			     old_strdup);
		return NULL;
	}

	if (strcmp (root->name, "translation-status") != 0){
		g_warning ("root node != 'translation-status' -> '%s'", cur->name);
		xmlFreeDoc (doc);
		xmlMemSetup (old_free,
			     old_malloc,
			     old_realloc,
			     old_strdup);
		return NULL;
	}

	ret = (status_data *) g_new (status_data, 1);
	ret->servers = g_hash_table_new_full (g_str_hash, g_str_equal, g_free, g_object_unref);
	ret->modules = g_hash_table_new_full (g_str_hash, g_str_equal, g_free, g_object_unref);
	ret->versions = g_hash_table_new_full (g_str_hash, g_str_equal, g_free, g_object_unref);
	ret->views = g_hash_table_new_full (g_str_hash, g_str_equal, g_free, g_object_unref);
	ret->teams = g_hash_table_new_full (g_str_hash, g_str_equal, g_free, g_object_unref);

	/* We load first the servers */
	for (cur = root->children; cur != NULL; cur = cur->next) {
		gchar *id, *hostname, *username, *password;
		StatusServer *server;

		id = hostname = username = password = NULL;
		
		if (strcmp(cur->name, "server")) {
			continue;
		}

		id = xmlGetProp (cur, "id");
		hostname = xmlGetProp (cur, "hostname");
		username = xmlGetProp (cur, "username");
		password = xmlGetProp (cur, "password");

		server = status_server_new (hostname, username, password);

		/* We don't check if it already exists because we get a valid XML and the DTD
		 * file does not allow two servers with the same ID
		 */
		g_hash_table_insert (ret->servers, g_strdup(id), server);

		xmlFree (id);
		xmlFree (hostname);
		xmlFree (username);
		xmlFree (password);

	}

	for (cur = root->children; cur != NULL; cur = cur->next) {
		gchar *module_name;
		StatusModule *module;
		xmlNodePtr cur_version;

		module_name = NULL;
		
		if (strcmp(cur->name, "module")) {
			continue;
		}

		module_name = xmlGetProp (cur, "name");
		
		module = status_module_new (module_name);

		if (module == NULL) {
			xmlFree (module_name);
			continue;
		}

		for (cur_version = cur->children; cur_version != NULL; cur_version = cur_version->next) {
			gchar *name, *id, *path, *sserver;
			StatusVersion *version;
			StatusServer *server;

			name = id = path = sserver = NULL;
		
			if (strcmp(cur_version->name, "version")) {
				if (strcmp(cur_version->name, "text") && strcmp (cur_version->name, "comment")) {
					g_warning ("Bad .xml file, please fix it!! (%s)",cur_version->name );
				}
				continue;
			}

			name = xmlGetProp (cur_version, "name");
			id = xmlGetProp (cur_version, "id");
			path = xmlGetProp (cur_version, "path");
			sserver = xmlGetProp (cur_version, "server");

			server = (StatusServer *) g_hash_table_lookup (ret->servers, sserver);

			if (server == NULL) {
				g_warning ("The %s server is unknown", sserver);
				xmlFree (name);
				xmlFree (id);
				xmlFree (path);
				xmlFree (sserver);
				continue;
			}
		
			version = status_version_new (g_object_ref (server), module_name, id, path);

			/* We don't check if it already exists because we get a valid XML and the DTD
			 * file does not allow two versions with the same name
			 */
			g_hash_table_insert (ret->versions, g_strdup (name), version);
			
			status_module_add_version (module, g_object_ref (version));
			
			xmlFree (name);
			xmlFree (id);
			xmlFree (path);
			xmlFree (sserver);
		}

		/* We don't check if it already exists because we get a valid XML and the DTD
		 * file does not allow two modules with the same name
		 */
		g_hash_table_insert (ret->modules, g_strdup (module_name), module);

		xmlFree (module_name);
	}

	for (cur = root->children; cur != NULL; cur = cur->next) {
		gchar *view_name;
		StatusView *view;
		xmlNodePtr cur_group;

		view_name = NULL;
		
		if (strcmp(cur->name, "view")) {
			continue;
		}

		view_name = xmlGetProp (cur, "name");
		
		view = status_view_new (view_name);

		if (view == NULL) {
			xmlFree (view_name);
			continue;
		}

		for (cur_group = cur->children; cur_group != NULL; cur_group = cur_group->next) {
			gchar *group_name;
			xmlNodePtr cur_ver;

			group_name = NULL;
		
			if (strcmp(cur_group->name, "group")) {
				if (strcmp(cur_group->name, "text") && strcmp (cur_version->name, "comment")) {
					g_warning ("Bad .xml file, please fix it!! (%s)",cur_group->name);
				}
				continue;
			}

			group_name = xmlGetProp (cur_group, "name");

			for (cur_ver = cur_group->children; cur_ver != NULL; cur_ver = cur_ver->next) {
				gchar *name;
				StatusVersion *version;

				name = NULL;
		
				if (strcmp(cur_ver->name, "versionrefs")) {
					if (strcmp(cur_ver->name, "text") && strcmp (cur_version->name, "comment")) {
						g_warning ("Bad .xml file, please fix it!! (%s)",cur_ver->name);
					}
					continue;
				}

				name = xmlGetProp (cur_ver, "name");

				version = (StatusVersion *) g_hash_table_lookup (ret->versions, name);

				if (version == NULL) {
					g_warning ("The %s versionref is unknown", name);
					xmlFree (name);
					continue;
				}
		

				/* We don't check if it already exists because we get a valid XML and the DTD
				 * file does not allow two versions with the same name
				 */
			
				status_view_add_module (view, group_name, g_object_ref (version));
			
				xmlFree (name);
			}
		
			xmlFree (group_name);
		}

		/* We don't check if it already exists because we get a valid XML and the DTD
		 * file does not allow two modules with the same name
		 */
		g_hash_table_insert (ret->views, g_strdup (view_name), view);

		xmlFree (view_name);
	}

	for (cur = root->children; cur != NULL; cur = cur->next) {
		gchar *team_name, *team_mail, *buf;
		StatusTeam *team;
		GList *coordinators = NULL, *urls=NULL;
		xmlNodePtr cur_group;

		team_name = NULL;
		
		if (strcmp(cur->name, "team")) {
			continue;
		}

		team_name = xmlGetProp (cur, "name");
		buf = xmlGetProp (cur, "mail");
		team_mail = status_obfuscate_email (buf);
		
		for (cur_group = cur->children; cur_group != NULL; cur_group = cur_group->next) {
			url_t *url;

			if (strcmp (cur_group->name, "coordinator") && strcmp (cur_group->name, "url")) {
				if (strcmp(cur_group->name, "text") && strcmp (cur_version->name, "comment")) {
					g_warning ("Bad .xml file, please fix it!! (%s)",cur_group->name);
				}
				continue;
			}

			if (!strcmp (cur_group->name, "coordinator")) {
				gchar *coordinator_mail;

				coordinator_mail = xmlGetProp (cur_group, "mail");
				coordinators = g_list_append (coordinators, status_obfuscate_email (coordinator_mail));
				xmlFree (coordinator_mail);
			} else { /* It's an URL */
				url = g_new0 (url_t, 1);
				url->uri = xmlGetProp (cur_group, "uri");
				url->description = xmlGetProp (cur_group, "description");
				urls = g_list_append (urls, url);
			}	
		}

		team = status_team_new (team_name, team_mail, coordinators, urls);

		/* We don't check if it already exists because we get a valid XML and the DTD
		 * file does not allow two modules with the same name
		 */
		g_hash_table_insert (ret->teams, g_strdup (team_mail), team);

		xmlFree (team_name);
		xmlFree (buf);
		g_free (team_mail);
	}


	xmlFreeDoc (doc);
	xmlMemSetup (old_free,
		     old_malloc,
		     old_realloc,
		     old_strdup);
	return ret;
}
