/* Translation Status program
 * Copyright (C) 2002-2003 Carlos Perelló Marín <carlos@gnome.org>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

#include "status-module.h"

struct _StatusModule {
	GObject object;

	GString *name;
	GList   *versions;
};

struct _StatusModuleClass {
	GObjectClass parent_class;
};

static void status_module_class_init (StatusModuleClass *klass);
static void status_module_init       (StatusModule *module, StatusModuleClass *klass);
static void status_module_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

/*
 * StatusModule object implementation
 */
static void
status_module_class_init (StatusModuleClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_module_finalize;
}

static void
status_module_init (StatusModule *module, StatusModuleClass *klass)
{
	g_return_if_fail (STATUS_IS_MODULE (module));

	module->name = NULL;
	module->versions = NULL;
}

static void
status_module_finalize (GObject *object)
{
	StatusModule *module = (StatusModule *) object;

	g_return_if_fail (STATUS_IS_MODULE (module));

	if (module->name != NULL) {
		g_string_free (module->name, TRUE);
		module->name = NULL;
	}
	if (module->versions != NULL) {
		g_list_for_each (module->versions, module_free_versions, NULL);
		g_list_free (module->versions);
		module->versions = NULL;
	}

	parent_class->finalize (object);
}

GType
status_module_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusModuleClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_module_class_init,
			NULL,
			NULL,
			sizeof (StatusModule),
			0,
			(GInstanceInitFunc) status_module_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusModule",
					       &info, 0);
	}
	return type;
}

/**
 * status_module_new
 * @name: The module name
 *
 * Create a new #StatusModule object, with the name @name
 */
StatusModule *
status_module_new (const gchar *name)
{
	StatusModule *module;

	modulec = status_module (g_object_new (STATUS_TYPE_MODULE, NULL));

	module->name = g_string_new (name);

	return module;
}

/**
 * status_module_get_name
 * @module: a #StatusModule object
 *
 * Returns the module name. The returned string should be freed when no longer needed.
 */
gchar *
status_module_get_name (StatusModule *module)
{
	g_return_val_if_fail (STATUS_IS_MODULE (module), NULL);
	
	if (module->name != NULL) {
		return g_strdup (module->name->str);
	} else {
		return NULL;
	}
}

/**
 * status_module_add_version
 */
gboolean
status_module_add_version (StatusModule *module, StatusVersion *version)
{
	g_return_val_if_fail (STATUS_IS_MODULE (module), FALSE);
	g_return_val_if_fail (STATUS_IS_VERSION (version), FALSE);

	module->versions = g_list_append (module->versions, g_object_ref (version));
	return TRUE;
}
