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

struct keep_lines {
  char *header;
  struct keep_lines *next;
};

#include "lib.h"

/* Functions in newsbody_write.c */
void writebody (FILE *newsfile, FILE *newsfile_copy, FILE *bodyfile,
		int remove_header, int remove_quote, int remove_sig,
		struct keep_lines *keep);

/* Functions in newsbody_read.c */
void readbody (FILE *newsfile, FILE *bodyfile, FILE *mergefile,
	       int remove_header, int remove_quote, int remove_sig,
	       struct keep_lines *keep);

/* Functions in header.c */
char *read_header (char *header, size_t *len, FILE *file);
int keep_this_header (char *header, struct keep_lines *keep);
