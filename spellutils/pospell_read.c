/*
 * Copyright (C) 2000 Byrial Jensen <byrial@image.dk>
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
#include "pospell.h"

/* Static functions in this file */
static void do_body (FILE *pofile, FILE *spellfile, FILE *mergefile,
		     int strict);
static void unsplit (char *src, char *dest);

void readbody (FILE *pofile, FILE *spellfile, FILE *mergefile, int strict)
{
  do_body (pofile, spellfile, mergefile, strict);

  nb_close (pofile);
  nb_close (spellfile);
  nb_close (mergefile);
}

static void do_body (FILE *pofile, FILE *spellfile, FILE *mergefile,
		     int strict)
{
  char *line = NULL;
  char *poline = NULL;
  size_t len = 0;
  size_t poline_len = 0;
  char type;
  char *c;

  while ((line = read_line (line, &len, spellfile)) != NULL)
  {
    if (poline_len < len)
    {
      /* Need room to call unsplit() */
      poline_len = len;
      poline = nb_realloc (poline, poline_len);
    }

    if (*line == '#' || *line == '>' || *line == ')' ||
	*line == ']' || *line == '+')
    {
      type = *line;

      while (1)
      {
	poline = read_line (poline, &poline_len, pofile);
	if (!line)
	  nb_error (0,
		    _("found marker for a removed item in the spell file,\n"
		      "but cannot find a corresponding item in the po file"));
	for (c = poline ; *c == ' ' || *c == '\t' ; c++)
	  ;

	if (type == '#')
	{
	  if (*c == '#')
	    break;
	}
	else if (type == '>')
	{
	  if (strncmp (c, "msgid", 5) == 0)
	    break;
	}
	else if (type == ')' || type == '+')
	{
	  if (strncmp (c, "msgstr", 6) == 0)
	    break;
	}
	else /* type == ']' */
	{
	  if (*c == '"')
	    break;
	}
      }
      if (type == '+')
      {
	char *q = strchr (c, '"');

	if (q)
	  *q = '\0';
	else
	  c[strlen (c) - 1] = '\0';  /* Removes \n from string */
	write_line (poline, mergefile);
	
	unsplit (line, poline);
      }
      write_line (poline, mergefile);
    }
    else if (*line == '"')
    {
      unsplit (line, poline);
      write_line (poline, mergefile);
    }
    else if (*line == '\n')
      write_line (line, mergefile);
    else if (*line == '?' && !strict)
    {
      if (line[1] == '\n')
	write_line (line + 1, mergefile);
      else
	write_line (line + 2, mergefile);
    }
    else
    {
      line[strlen (line) - 1] = '\0';
      nb_error (0, _("found line with unknown content in the spell file:\n"
		     "%s"), line);
    }
  }
}

/*
 * Remove leading text and unsplit splitted source strings
 */
static void unsplit (char *src, char *dest)
{
  while (*src && *src != '"')
    src++;

  while ((*(dest++) = *(src++)))
  {
    if (*src == '\\')
    {
      *(dest++) = *(src++);
      if (*src)
	continue;
      else
	break;
    }
    if (src[0] == '"' && src[1] == ' ' && src[2] == '"')
      src += 3;
  }
}
