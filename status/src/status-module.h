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

#ifndef STATUS_MODULE_H
#define STATUS_MODULE_H

#include <glib-object.h>
#include "status.h"
#include "status-version.h"

G_BEGIN_DECLS

#define STATUS_TYPE_MODULE            (status_module_get_type())
#define STATUS_MODULE(obj)            (G_TYPE_CHECK_INSTANCE_CAST (obj, STATUS_TYPE_MODULE, StatusModule))
#define STATUS_MODULE_CLASS(klass)    (G_TYPE_CHECK_CLASS_CAST (klass, STATUS_TYPE_MODULE, StatusModuleClass))
#define STATUS_IS_MODULE(obj)         (G_TYPE_CHECK_INSTANCE_TYPE (obj, STATUS_TYPE_MODULE))
#define STATUS_IS_MODULE_CLASS(klass) (G_TYPE_CHECK_CLASS_TYPE((klass), STATUS_TYPE_MODULE))

typedef struct _StatusModule StatusModule;
typedef struct _StatusModuleClass StatusModuleClass;

GType         status_module_get_type (void);

StatusModule *status_module_new (const gchar *name);

gchar        *status_module_get_name (const StatusModule *module);
gboolean      status_module_add_version (StatusModule *module, StatusVersion *version);
void          status_module_report (StatusModule *module);

G_END_DECLS

#endif
