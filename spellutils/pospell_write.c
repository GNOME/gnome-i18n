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
#include <ctype.h>
#include "pospell.h"

/* Static functions in this file: */
static void do_body (FILE *pofile, FILE *pofile_copy, FILE *spellfile,
		     int strict, char *lang);
static void split (char *src, char *dest);

void writebody (FILE *pofile, FILE *pofile_copy, FILE *spellfile,
		int strict, char *lang)
{
  do_body (pofile, pofile_copy, spellfile, strict, lang);

  if (pofile != stdin)
    nb_close (pofile);
  if (pofile_copy)
    nb_close (pofile_copy);
  nb_close (spellfile);
}

static void do_body (FILE *pofile, FILE *pofile_copy, FILE *spellfile,
		     int strict, char *lang)
{
  char *line = NULL;
  char *split_line = NULL;
  size_t len = 0;
  size_t split_line_len = 0;
  int in_msgid = 0;
  int msgid_have_content = 0;
  int right_language = 0;
  int in_msgstr = 0;
  char *c;
  char marker[3] = "\0\n\0";

  while (1)
  {
    line = read_line (line, &len, pofile);
    if (!line)
    {
      if (split_line)
	free (split_line);
      return;
    }

    if (pofile_copy)
      write_line (line, pofile_copy);

    if (len * 2 > split_line_len)
    {
      /* make room to call split() */
      split_line_len = len * 2;
      split_line = nb_realloc (split_line, split_line_len);
    }

    for (c = line ; *c == ' ' || *c == '\t' ; c++)
      ;

    if (*c == '#')
    {
      marker[0] = '#';
    }
    else if (!strncmp (c, "msgid", 5))
    {
      char *q = strchr (c, '"');

      in_msgid = 1;
      msgid_have_content = (q && *(q + 1) != '"') ? 1 : 0;
      in_msgstr = 0;

      marker[0] = '>';
    }
    else if (in_msgid && *c == '"')
    {
      msgid_have_content = 1;
      marker[0] = ']';
    }
    else if (!strncmp (c, "msgstr", 6))
    {
      in_msgstr = 1;
      in_msgid = 0;
      right_language = 1;
      if (lang)
      {
	char *q = strstr (c + 6, lang);
	if (!q || q > strchr (c + 6, '"'))
	  right_language = 0;
      }
      if (msgid_have_content && right_language)
      {
	split (c, split_line);
	write_line ("+ ", spellfile);
	write_line (split_line, spellfile);
      }
      else
      {
	marker[0] = ')';
      }
    }
    else if (in_msgstr && *c == '"')
    {
      if (msgid_have_content && right_language)
      {
	split (c, split_line);
	write_line (split_line, spellfile);
      }
      else
      {
	marker[0] = ']';
      }
    }
    else if (*c == '\n')
    {
      write_line (c, spellfile);
    }
    else
    {
      if (strict)
      {
	c[strlen (c) - 1] = '\0';
	nb_error (0, _("I don't understand this line in the .po file:\n"
		       "%s"), line);
      }
      write_line ("? ", spellfile);
      write_line (line, spellfile);
    }

    if (marker[0])
    {
      write_line (marker, spellfile);
      marker[0] = 0;
    }
  }
}

/*
 * Remove leading text and split the source string after C escape sequences
 * followed by a letter.
 */
static void split (char *src, char *dest)
{
  char c;

  while (*src && *src != '"')
    src++;

  while ((c = *(dest++) = *(src++)))
  {
    if (c == '\\')
    {
      c = *(dest++) = *(src++);
      if (!c)
	break;
      if (c >= 'a' && c <= 'v' && isalpha (*src))
      {
	*(dest++) = '"';
	*(dest++) = ' ';
	*(dest++) = '"';
      }
    }
  }
}
