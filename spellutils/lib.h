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

#include "config.h"

#ifdef ENABLE_NLS
#include <libintl.h>
#define _(String) gettext (String)
#else
#define _(String) (String)
#endif

#ifndef NAME_DECLARED
extern const char *Name;
#endif

/* Functions in lib.c */
void *nb_realloc (void *ptr, size_t size);
void *nb_malloc (size_t size);
char *read_line (char *line, size_t *len, FILE *file);
void write_line (char *line, FILE *file);
FILE *open_read (const char *filename);
FILE *open_write (const char *filename);
void nb_close (FILE *file);
char *nb_tmpnam (void);
void nb_unlink (const char *filename);
void nb_rename (const char *oldpath, const char *newpath);
void nb_error (int flags, char *format, ...);

/* Functions in exec.c */
void exec_program (char *argv[]);
void exec_filter (char *argv[], FILE **out, FILE **in);
int nb_wait (void);

/* Flags to nb_error */
#define E_ERRNO (1)    /* Use errno */
#define E_CHILD (1<<1) /* Call from a child process */
