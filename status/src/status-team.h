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

#ifndef STATUS_TEAM_H
#define STATUS_TEAM_H

#include <glib-object.h>

G_BEGIN_DECLS

#define STATUS_TYPE_TEAM            (status_team_get_type())
#define STATUS_TEAM(obj)            (G_TYPE_CHECK_INSTANCE_CAST (obj, STATUS_TYPE_TEAM, StatusTeam))
#define STATUS_TEAM_CLASS(klass)    (G_TYPE_CHECK_CLASS_CAST (klass, STATUS_TYPE_TEAM, StatusTeamClass))
#define STATUS_IS_TEAM(obj)         (G_TYPE_CHECK_INSTANCE_TYPE (obj, STATUS_TYPE_TEAM))
#define STATUS_IS_TEAM_CLASS(klass) (G_TYPE_CHECK_CLASS_TYPE((klass), STATUS_TYPE_TEAM))

typedef struct _StatusTeam StatusTeam;
typedef struct _StatusTeamClass StatusTeamClass;

#include "status-translator.h"

GType          status_team_get_type (void);

StatusTeam *status_team_new (const gchar *name, const gchar *email, GList *coordinators, GList *urls);

void status_team_add_translator (StatusTeam *team, StatusTranslator *translator);
const gchar *status_team_get_name (StatusTeam *team);
const gchar *status_team_get_email (StatusTeam *team);
gint status_team_get_nstrings (StatusTeam *team);
gint status_team_get_ntranslated (StatusTeam *team);
gint status_team_get_nfuzzy (StatusTeam *team);
gint status_team_get_nuntranslated (StatusTeam *team);

void status_team_report (StatusTeam *team);


G_END_DECLS

#endif
