/*
 * Copyright (C) 1998-2000 Byrial Jensen <byrial@image.dk>
 *
 *     This program is free software; you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation; either version 2 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program; if not, write to the Free Software
 *     Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "newsbody.h"

#ifndef HAVE_STRNCASECMP
#include "strncasecmp.h"
#endif

char *read_header (char *header, size_t *len, FILE *file)
{
  int next_char;

  header = read_line (header, len, file);
  if (!header)
    /* We reached EOF */
    return header;

  while (1)
  {
    next_char = getc (file);
    if (next_char == EOF)
      return header;

    ungetc (next_char, file);
    if (next_char != ' ' && next_char != '\t')
      return header;

    /* The current header is continued on the next line */
    {
      char *cont = nb_malloc (256);
      size_t contlen = 256;
      int space_needed;

      cont = read_line (cont, &contlen, file);
      if (!cont)
      {
	/* This cannot happen - we have at least the ungetted char to read */
	nb_error (0, _("internal error while reading a header continuation"));
      }

      space_needed = strlen (header) + strlen (cont) + 1;
      if (space_needed > *len)
      {
	header = nb_realloc (header, space_needed);
	*len = space_needed;
      }
      strcat (header, cont);
      free (cont);
    }
  }
}

int keep_this_header (char *header, struct keep_lines *keep)
{
  struct keep_lines *k;
  int len;

  for (k = keep ; k != NULL ; k = k->next)
  {
    len = strlen (k->header);
    if ((strncasecmp (header, k->header, len) == 0) &&
	(header[len] == ':'))
      return 1;
  }
  return 0;
}
