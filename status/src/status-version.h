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

#ifndef STATUS_VERSION_H
#define STATUS_VERSION_H

#include <glib-object.h>
#include "status-server.h"

G_BEGIN_DECLS

#define STATUS_TYPE_VERSION            (status_version_get_type())
#define STATUS_VERSION(obj)            (G_TYPE_CHECK_INSTANCE_CAST (obj, STATUS_TYPE_VERSION, StatusVersion))
#define STATUS_VERSION_CLASS(klass)    (G_TYPE_CHECK_CLASS_CAST (klass, STATUS_TYPE_VERSION, StatusVersionClass))
#define STATUS_IS_VERSION(obj)         (G_TYPE_CHECK_INSTANCE_TYPE (obj, STATUS_TYPE_VERSION))
#define STATUS_IS_VERSION_CLASS(klass) (G_TYPE_CHECK_CLASS_TYPE((klass), STATUS_TYPE_VERSION))

typedef struct _StatusVersion StatusVersion;
typedef struct _StatusVersionClass StatusVersionClass;

GType          status_version_get_type (void);

StatusVersion *status_version_new (StatusServer *server, const gchar *module,
				   const gchar *id, const gchar *path);

gboolean       status_version_download (StatusVersion *version, gchar *downloaddir);
gboolean       status_version_generate_pot (StatusVersion *version, gchar *download_dir, gchar *install_dir);
gint           status_version_get_nstrings (StatusVersion *version);
GHashTable    *status_version_get_translations (StatusVersion *version);
const gchar   *status_version_get_id (StatusVersion *version);
const gchar   *status_version_get_module_name (StatusVersion *version);
gchar         *status_version_get_html_table (StatusVersion *version, const gchar *html_path);
void           status_version_report (StatusVersion *version);



G_END_DECLS

#endif
