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

#ifndef STATUS_VIEW_H
#define STATUS_VIEW_H

#include <glib-object.h>
#include "status-version.h"

G_BEGIN_DECLS

#define STATUS_TYPE_VIEW            (status_view_get_type())
#define STATUS_VIEW(obj)            (G_TYPE_CHECK_INSTANCE_CAST (obj, STATUS_TYPE_VIEW, StatusView))
#define STATUS_VIEW_CLASS(klass)    (G_TYPE_CHECK_CLASS_CAST (klass, STATUS_TYPE_VIEW, StatusViewClass))
#define STATUS_IS_VIEW(obj)         (G_TYPE_CHECK_INSTANCE_TYPE (obj, STATUS_TYPE_VIEW))
#define STATUS_IS_VIEW_CLASS(klass) (G_TYPE_CHECK_CLASS_TYPE((klass), STATUS_TYPE_VIEW))

typedef struct _StatusView StatusView;
typedef struct _StatusViewClass StatusViewClass;

GType         status_view_get_type (void);

StatusView *status_view_new (const gchar *name);

gchar        *status_view_get_name (const StatusView *view);
gboolean      status_view_add_module (StatusView *view, gchar *group, StatusVersion *module);

G_END_DECLS

#endif
