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

#include "status-view.h"

struct _StatusView {
	GObject object;

	GString    *name;
	GHashTable *modules;
};

struct _StatusViewClass {
	GObjectClass parent_class;
};

static void status_view_class_init (StatusViewClass *klass);
static void status_view_init       (StatusView *view, StatusViewClass *klass);
static void status_view_finalize   (GObject * object);

static GObjectClass *parent_class = NULL;

static void
view_free_modules (gpointer data, gpointer user_data)
{
	StatusVersion *module;

	module = STATUS_VERSION(data);

	g_object_unref (module);
}

static void
view_free_list_modules (gpointer key, gpointer value, gpointer user_data)
{
	GList *module_list;

	g_free (key);

	g_return_if_fail (value != NULL);

	module_list = (GList *) value;
	g_list_foreach (module_list, view_free_modules, NULL);
	g_list_free (module_list);

}

/*
 * StatusView object implementation
 */
static void
status_view_class_init (StatusViewClass * klass)
{
	GObjectClass *object_class = G_OBJECT_CLASS (klass);

	parent_class = g_type_class_peek_parent (klass);
	object_class->finalize = status_view_finalize;
}

static void
status_view_init (StatusView *view, StatusViewClass *klass)
{
	g_return_if_fail (STATUS_IS_VIEW (view));

	view->name = NULL;
	view->modules = NULL;
}

static void
status_view_finalize (GObject *object)
{
	StatusView *view = (StatusView *) object;

	g_return_if_fail (STATUS_IS_VIEW (view));

	if (view->name != NULL) {
		g_string_free (view->name, TRUE);
		view->name = NULL;
	}
	if (view->modules != NULL) {
		g_hash_table_foreach (view->modules, view_free_list_modules, NULL);
		g_hash_table_destroy (view->modules);
		view->modules = NULL;
	}

	parent_class->finalize (object);
}

GType
status_view_get_type (void)
{
	static GType type = 0;

	if (!type) {
		static const GTypeInfo info = {
			sizeof (StatusViewClass),
			(GBaseInitFunc) NULL,
			(GBaseFinalizeFunc) NULL,
			(GClassInitFunc) status_view_class_init,
			NULL,
			NULL,
			sizeof (StatusView),
			0,
			(GInstanceInitFunc) status_view_init
		};
		type = g_type_register_static (G_TYPE_OBJECT,
					       "StatusView",
					       &info, 0);
	}
	return type;
}

/**
 * status_view_new
 * @name: The view name
 *
 * Create a new #StatusView object, with the name @name
 */
StatusView *
status_view_new (const gchar *name)
{
	StatusView *view;

	viewc = status_view (g_object_new (STATUS_TYPE_VIEW, NULL));

	view->name = g_string_new (name);
	view->modules = g_hash_table_new (g_str_hash, g_str_equal);

	return view;
}

/**
 * status_view_get_name
 * @view: a #StatusView object
 *
 * Returns the view name. The returned string should be freed when no longer needed.
 */
gchar *
status_view_get_name (StatusView *view)
{
	g_return_val_if_fail (STATUS_IS_VIEW (view), NULL);
	
	if (view->name != NULL) {
		return g_strdup (view->name->str);
	} else {
		return NULL;
	}
}

/**
 * status_view_add_module
 */
gboolean
status_view_add_module (StatusView *view, const gchar *group, StatusVersion *module)
{
	GList *modules_group, *new;
	
	g_return_val_if_fail (STATUS_IS_VIEW (view), FALSE);
	g_return_val_if_fail (group != NULL, FALSE);
	g_return_val_if_fail (STATUS_IS_VERSION (module), FALSE);

	modules_group = g_hash_table_lookup (view->modules, group);

	if (modules_group == NULL) {
		modules_group = g_list_append (modules_group, g_object_ref (module));
		g_hash_table_insert (view->modules, group, modules_group);
	} else {
		new = g_list_append (modules_group, g_object_ref (module));
		/* We only change the hash table if the head of the list has changed */
		if (new != modules_group) {
			g_hash_table_replace (view->modules, group, new);
		}
	}
	return TRUE;
}
