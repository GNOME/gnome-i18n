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

/* Static functions in this file */
static void do_header (FILE *newsfile, FILE *bodyfile, FILE *mergefile,
		       struct keep_lines *keep);
static void do_body (FILE *newsfile, FILE *bodyfile, FILE *mergefile,
		     int remove_quote, int remove_sig);

void readbody (FILE *newsfile, FILE *bodyfile, FILE *mergefile,
	       int remove_header, int remove_quote, int remove_sig,
	       struct keep_lines *keep)
{
  if (remove_header)
    do_header (newsfile, bodyfile, mergefile, keep);

  do_body (newsfile, bodyfile, mergefile, remove_quote, remove_sig);

  nb_close (newsfile);
  nb_close (bodyfile);
  nb_close (mergefile);
}

static void do_header (FILE *newsfile, FILE *bodyfile, FILE *mergefile,
		       struct keep_lines *keep)
{
  char *header = NULL;
  size_t len = 0;
  int kept_headers = 0;
  int bodyfile_eoh = 0;

  while (1)
  {
    header = read_header (header, &len, newsfile);
    if ((!header) || (*header == '\n'))
      break;

    if (keep_this_header (header, keep))
    {
      kept_headers = 1;
      if (!bodyfile_eoh)
      {
	header = read_header (header, &len, bodyfile);
	if ((!header) ||
	    *header == '\n')
	  bodyfile_eoh = 1;
	else
	  write_line (header, mergefile);
      }
    }
    else 
      write_line (header, mergefile);
  }

  if (kept_headers)
  {
    if (!bodyfile_eoh)
    {
      while ((header = read_header (header, &len, bodyfile)) != NULL &&
	     *header != '\n')
      {
	write_line (header, mergefile);
      }
    }
  }

  write_line ("\n", mergefile);

  if (header)
    free (header);
}

static void do_body (FILE *newsfile, FILE *bodyfile, FILE *mergefile,
		     int remove_quote, int remove_sig)
{
  char *line = NULL;
  size_t len = 0;

  while ((line = read_line (line, &len, bodyfile)) != NULL)
  {
    if (remove_quote && *line == '>')
    {
      do
      {
	line = read_line (line, &len, newsfile);
	if (!line)
	  nb_error 
	  (0, _("found marker for a removed line in the body file,\n"
		"but cannot find a corresponding line in the message file"));
      }
      while (*line != '>');
    }
    write_line (line, mergefile);
  }

  if (remove_sig)
  {
    do
      line = read_line (line, &len, newsfile);
    while (line && strcmp (line, "-- \n"));

    while (line)
    {
      write_line (line, mergefile);
      line = read_line (line, &len, newsfile);
    }
  }
}
