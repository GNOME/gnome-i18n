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

#include "status-version.h"

struct _StatusVersion {
	GObject object;

	StatusServer *server;
	GString *module;
	GString *id;
	GString *path;
};

struct _StatusVersionClass {
	GObjectClass parent_class;
};

static void status_version_class_init (StatusVersionClass *klass);
static void status_version_init       (StatusVersion *version, StatusVersionClass *klass);
static void status_version_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

/*
 * StatusVersion object implementation
 */
static void
status_version_class_init (StatusVersionClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_version_finalize;
}

static void
status_version_init (StatusVersion *version, StatusVersionClass *klass)
{
	g_return_if_fail (STATUS_IS_VERSION (version));

	version->server = NULL;
	version->module = NULL;
	version->id = NULL;
	version->path = NULL;
}

static void
status_version_finalize (GObject *object)
{
	StatusVersion *version = (StatusVersion *) object;

	g_return_if_fail (STATUS_IS_VERSION (version));

	if (version->server != NULL) {
		g_object_unref (version->server);
	}
	if (version->module != NULL) {
		g_string_free (version->module, TRUE);
		version->module = NULL;
	}
	if (version->id != NULL) {
		g_string_free (version->id, TRUE);
		version->id = NULL;
	}
	if (version->path != NULL) {
		g_string_free (version->path, TRUE);
		version->path = NULL;
	}

	parent_class->finalize (object);
}

GType
status_version_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusVersionClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_version_class_init,
			NULL,
			NULL,
			sizeof (StatusVersion),
			0,
			(GInstanceInitFunc) status_version_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusVersion",
					       &info, 0);
	}
	return type;
}

/**
 * status_version_new
 * @server:
 * @module:
 * @id:
 * @path:
 *
 * Create a new #StatusVersion object
 */
StatusVersion *
status_version_new (StatusServer *server, const gchar *module, const gchar *id, const gchar *path)
{
	StatusVersion *version;

	g_return_val_if_fail (STATUS_IS_SERVER (server), NULL);

	version = g_object_new (STATUS_TYPE_VERSION, NULL);

	version->server = g_object_ref (server);
	version->module = g_string_new (module);
	version->id = g_string_new (id);
	version->path = g_string_new (path);

	return version;
}

/**
 * status_version_download
 * @version:
 * @downloaddir:
 *
 * Downloads the #StatusVersion object to the @downloaddir
 */
gboolean
status_version_download (StatusVersion *version, gchar *downloaddir)
{

	return status_server_download (version->server, version->module->str, version->id->str,
				       version->path->str, downloaddir);

}
