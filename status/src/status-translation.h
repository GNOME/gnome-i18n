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

#ifndef STATUS_TRANSLATION_H
#define STATUS_TRANSLATION_H

#include <glib-object.h>
#include "status-version.h"

G_BEGIN_DECLS

#define STATUS_TYPE_TRANSLATION            (status_translation_get_type())
#define STATUS_TRANSLATION(obj)            (G_TYPE_CHECK_INSTANCE_CAST (obj, STATUS_TYPE_TRANSLATION, StatusTranslation))
#define STATUS_TRANSLATION_CLASS(klass)    (G_TYPE_CHECK_CLASS_CAST (klass, STATUS_TYPE_TRANSLATION, StatusTranslationClass))
#define STATUS_IS_TRANSLATION(obj)         (G_TYPE_CHECK_INSTANCE_TYPE (obj, STATUS_TYPE_TRANSLATION))
#define STATUS_IS_TRANSLATION_CLASS(klass) (G_TYPE_CHECK_CLASS_TYPE((klass), STATUS_TYPE_TRANSLATION))

typedef struct _StatusTranslation StatusTranslation;
typedef struct _StatusTranslationClass StatusTranslationClass;

GType          status_translation_get_type (void);

StatusTranslation *status_translation_new (StatusVersion *version, const gchar *locale, const gchar *path);

StatusVersion *status_translation_get_version (StatusTranslation *translation);
gint status_translation_get_ntranslated (StatusTranslation *translation);
gint status_translation_get_nfuzzy (StatusTranslation *translation);
gint status_translation_get_nuntranslated (StatusTranslation *translation);
void status_translation_report (StatusTranslation *translation);


G_END_DECLS

#endif
