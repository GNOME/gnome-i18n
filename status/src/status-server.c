/* Translation Status program
 *
 * Author: Carlos Perelló Marín <carlos@gnome.org>
 * 
 * Copyright (C) 2002-2003 Carlos Perelló Marín
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either server 2 of the License, or
 * (at your option) any later server.
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

#include <sys/stat.h>
#include <unistd.h>
#include "status-server.h"


struct _StatusServer {
	GObject object;

	GString *hostname;
	GString *username;
	GString *password;
};

struct _StatusServerClass {
	GObjectClass parent_class;
};

static void status_server_class_init (StatusServerClass *klass);
static void status_server_init       (StatusServer *server, StatusServerClass *klass);
static void status_server_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

/*
 * StatusServer object implementation
 */
static void
status_server_class_init (StatusServerClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_server_finalize;
}

static void
status_server_init (StatusServer *server, StatusServerClass *klass)
{
	g_return_if_fail (STATUS_IS_SERVER (server));

	server->hostname = NULL;
	server->username = NULL;
	server->password = NULL;
}

static void
status_server_finalize (GObject *object)
{
	StatusServer *server = (StatusServer *) object;

	g_return_if_fail (STATUS_IS_SERVER (server));

	if (server->hostname != NULL) {
		g_string_free (server->hostname, TRUE);
		server->hostname = NULL;
	}
	if (server->username != NULL) {
		g_string_free (server->username, TRUE);
		server->username = NULL;
	}
	if (server->password != NULL) {
		g_string_free (server->password, TRUE);
		server->password = NULL;
	}
	
	parent_class->finalize (object);
}

GType
status_server_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusServerClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_server_class_init,
			NULL,
			NULL,
			sizeof (StatusServer),
			0,
			(GInstanceInitFunc) status_server_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusServer",
					       &info, 0);
	}
	return type;
}

/**
 * status_server_new
 * @hostname:
 * @username:
 * @password:
 * @path:
 *
 * Create a new #StatusServer object
 */
StatusServer *
status_server_new (const gchar *hostname, const gchar *username, const gchar *password)
{
	StatusServer *server;

	g_return_val_if_fail (hostname != NULL, NULL);

	server = STATUS_SERVER (g_object_new (STATUS_TYPE_SERVER, NULL));

	server->hostname = g_string_new (hostname);
	server->username = g_string_new (username);
	server->password = g_string_new (password);

	return server;
}

/**
 * status_server_download
 * @server:
 * @id:
 * @remote_path:
 * @local_path:
 *
 */
gboolean
status_server_download (StatusServer *server, gchar *id, gchar *remote_path, gchar *local_path)
{
	GString *cvs_command;
	gchar *buf;
	struct stat stat_path;

	/* First, we check if the destination path already exists */
	if (!g_file_test (local_path, G_FILE_TEST_EXISTS)) {
		/* FIXME: We should use the mkdir function directly */
		buf = g_strdup_printf ("mkdir -p %s", local_path);
		if (system (buf)) {
			g_warning ("Unable to create the %s directory", local_path);
			g_free (buf);
			return FALSE;
		}
		g_free (buf);
		buf = NULL;
	} else if (!g_file_test (local_path, G_FILE_TEST_IS_DIR)) {
		g_warning ("%s is not a directory!!", local_path);
		return FALSE;
	}
	
	buf = g_strdup_printf ("%s/%s", local_path, remote_path);

	if (!g_file_test (buf, G_FILE_TEST_EXISTS)) {
		g_free (buf);
		buf = NULL;

		chdir (local_path);
		
		cvs_command = g_string_new ("");
		if (strcmp (id, "HEAD")) {
			g_string_printf (cvs_command, "cvs -q -d :pserver:%s@%s co -P -r %s -d %s %s",
					server->username, server->hostname, id, id, remote_path);
		} else {
			g_string_printf (cvs_command, "cvs -q -d :pserver:%s@%s co -P -d %s %s",
					server->username, server->hostname, id, remote_path);
		}
		if (system (cvs_command)) {
			g_warning ("Unable to checkout the module with %s", cvs_command);
			g_free (cvs_command);
			return FALSE;
		} else {
			return TRUE;
		}
	} else if (!g_file_test (buf, G_FILE_TEST_IS_DIR)) {
		g_warning ("%s is not a directory!!", buf);
		g_free (buf);
	} else {
		chdir (buf);
		if (system ("cvs -q update -P")) {
			g_warning ("Unable to update %s ", buf);
			g_free (buf);
			return FALSE;
		} else {
			g_free (buf);
			return TRUE;
		}
	}
}
