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

#ifndef STATUS_SERVER_H
#define STATUS_SERVER_H

#include <glib-object.h>

G_BEGIN_DECLS

#define STATUS_TYPE_SERVER            (status_server_get_type())
#define STATUS_SERVER(obj)            (G_TYPE_CHECK_INSTANCE_CAST (obj, STATUS_TYPE_SERVER, StatusServer))
#define STATUS_SERVER_CLASS(klass)    (G_TYPE_CHECK_CLASS_CAST (klass, STATUS_TYPE_SERVER, StatusServerClass))
#define STATUS_IS_SERVER(obj)         (G_TYPE_CHECK_INSTANCE_TYPE (obj, STATUS_TYPE_SERVER))
#define STATUS_IS_SERVER_CLASS(klass) (G_TYPE_CHECK_CLASS_TYPE((klass), STATUS_TYPE_SERVER))

typedef struct _StatusServer StatusServer;
typedef struct _StatusServerClass StatusServerClass;

GType          status_server_get_type (void);

StatusServer *status_server_new (const gchar *hostname, const gchar *username, const gchar *password);

gboolean status_server_download (StatusServer *server, gchar *module, gchar *id,
				 gchar *remote_path, gchar *local_path);

G_END_DECLS

#endif
