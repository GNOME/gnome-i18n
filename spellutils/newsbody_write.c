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

/* Static functions in this file: */
static void do_header (FILE *newsfile, FILE *newsfile_copy, FILE *bodyfile,
		       struct keep_lines *keep);
static void do_body (FILE *newsfile, FILE *newsfile_copy, FILE *bodyfile,
		     int remove_quote, int remove_sig);

void writebody (FILE *newsfile, FILE *newsfile_copy, FILE *bodyfile,
		int remove_header, int remove_quote, int remove_sig,
		struct keep_lines *keep)
{
  if (remove_header)
    do_header (newsfile, newsfile_copy, bodyfile, keep);

  do_body (newsfile, newsfile_copy, bodyfile, remove_quote, remove_sig);

  if (newsfile != stdin)
    nb_close (newsfile);
  if (newsfile_copy)
    nb_close (newsfile_copy);
  nb_close (bodyfile);
}

static void do_header (FILE *newsfile, FILE *newsfile_copy, FILE *bodyfile,
		       struct keep_lines *keep)
{
  char *header = NULL;
  size_t len = 0;
  int header_lines_kept = 0;

  while (1)
  {
    header = read_header (header, &len, newsfile);

    if (!header)
      /* We have reached EOF on newsfile, i.e. it has no body.
       * Maybe its better to abort with an error message? */
      return;

    if (newsfile_copy)
      write_line (header, newsfile_copy);

    if (*header == '\n')
      break;

    if (keep_this_header (header, keep))
    {
      write_line (header, bodyfile);
      header_lines_kept = 1;
    }
  }

  if (header_lines_kept)
    write_line (header, bodyfile);
  free (header);
}

static void do_body (FILE *newsfile, FILE *newsfile_copy, FILE *bodyfile,
		     int remove_quote, int remove_sig)
{
  char *line = NULL;
  size_t len = 0;

  while (1)
  {
    line = read_line (line, &len, newsfile);
    if (!line)
      return;

    if (remove_sig && strcmp (line, "-- \n") == 0)
      break;

    if (newsfile_copy)
      write_line (line, newsfile_copy);

    if (remove_quote && *line == '>')
    {
      line[1] = '\n';
      line[2] = '\0';
    }

    write_line (line, bodyfile);
  }

  /* This point is only reached when a signature delimeter is found */
  if (newsfile_copy)
  {
    /* Write the signature to newsfile_copy */
    while (line)
    {
      write_line (line, newsfile_copy);
      line = read_line (line, &len, newsfile);
    }
  }
}
