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

#ifndef STATUS_TRANSLATOR_H
#define STATUS_TRANSLATOR_H

#include <glib-object.h>

G_BEGIN_DECLS

#define STATUS_TYPE_TRANSLATOR            (status_translator_get_type())
#define STATUS_TRANSLATOR(obj)            (G_TYPE_CHECK_INSTANCE_CAST (obj, STATUS_TYPE_TRANSLATOR, StatusTranslator))
#define STATUS_TRANSLATOR_CLASS(klass)    (G_TYPE_CHECK_CLASS_CAST (klass, STATUS_TYPE_TRANSLATOR, StatusTranslatorClass))
#define STATUS_IS_TRANSLATOR(obj)         (G_TYPE_CHECK_INSTANCE_TYPE (obj, STATUS_TYPE_TRANSLATOR))
#define STATUS_IS_TRANSLATOR_CLASS(klass) (G_TYPE_CHECK_CLASS_TYPE((klass), STATUS_TYPE_TRANSLATOR))

typedef struct _StatusTranslator StatusTranslator;
typedef struct _StatusTranslatorClass StatusTranslatorClass;

#include "status-team.h"
#include "status-translation.h"

GType          status_translator_get_type (void);

StatusTranslator *status_translator_new (const gchar *name, const gchar *email);

void status_translator_add_team (StatusTranslator *translator, StatusTeam *team);
void status_translator_add_translation (StatusTranslator *translator, StatusTranslation *translation);

const gchar *status_translator_get_name (StatusTranslator *translator);
const gchar *status_translator_get_email (StatusTranslator *translator);

gint status_translator_get_nstrings (StatusTranslator *translator);
gint status_translator_get_ntranslated (StatusTranslator *translator);
gint status_translator_get_nfuzzy (StatusTranslator *translator);
gint status_translator_get_nuntranslated (StatusTranslator *translator);

void status_translator_report (StatusTranslator *translator);


G_END_DECLS

#endif
