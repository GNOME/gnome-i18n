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
#include <errno.h>
#include <stdarg.h>
#include <limits.h>
#include <unistd.h>
#include <sys/stat.h>
#include "lib.h"

void *nb_realloc (void *ptr, size_t size)
{
  if (!ptr)
    return nb_malloc (size);

  ptr = realloc (ptr, size);

  if (!ptr && size )
    nb_error (0, _("could not allocate memory"));

  return ptr;
}

void *nb_malloc (size_t size)
{
  void *p = malloc (size);

  if (!p)
    nb_error (0, _("could not allocate memory"));

  return p;
}

char *read_line (char *line, size_t *len, FILE *file)
{
  char *buf;
  size_t offset;

  if (*len < 256 || line == NULL)
  {
    line = nb_realloc (line, 1024);
    *len = 1024;
  }

  buf = line;
  offset = 0;
  while (1)
  {
    if (fgets (buf, *len - offset, file) == NULL)
    {
      if (ferror (file))
	nb_error (E_ERRNO, _("reading file"));

      free (line);
      *len = 0;
      return NULL;
    }

    buf += strlen (buf);
    if (*(buf- 1) == '\n')
      return line; /* We got a full line */

    offset = buf - line;
    if (*len < offset + 256)
    {
      /* grow the buffer */
      *len += 1024;
      line = nb_realloc (line, *len);
      buf = line + offset;
    }
  }
}

void write_line (char *line, FILE *file)
{
  if (fputs (line, file) == EOF)
    nb_error (E_ERRNO, _("writing file"));
}

FILE *open_read (const char *filename)
{
  FILE *fp = fopen (filename, "r");

  if (!fp)
    nb_error (E_ERRNO, _("open %s for reading"), filename);
  return fp;
}

FILE *open_write (const char *filename)
{
  FILE *fp = fopen (filename, "w");

  if (!fp)
    nb_error (E_ERRNO, _("open %s for writing"), filename);
  return fp;
}

void nb_close (FILE *file)
{   
  if (fclose (file))
    nb_error (E_ERRNO, _("closing file"));
}

char *nb_tmpnam ()
{
  char *name = tmpnam (nb_malloc (_POSIX_PATH_MAX));

  if (!name)
    nb_error (E_ERRNO, _("making temporary filename"));
  return name;
}

void nb_unlink (const char *filename)
{
  if (unlink (filename) == -1)
    nb_error (E_ERRNO, _("unlink %s"), filename);
}

void nb_rename (const char *oldpath, const char *newpath)
{
  if (rename (oldpath, newpath) == 0)
    return;
  if (errno != EXDEV)
    nb_error (E_ERRNO, _("rename %s %s"), oldpath, newpath);

  /* The files are not on the same filesystem so we need to copy it */
  {
    FILE *in = open_read (oldpath);
    FILE *out = open_write (newpath);
    struct stat stat;
    char *buf;
    size_t blocksize;
    size_t size;
    size_t chunk;

    if (fstat (fileno (in), &stat))
      nb_error (E_ERRNO, _("stat %s"), newpath);
    size = stat.st_size; 
    blocksize = stat.st_blksize;
    buf = nb_malloc (blocksize);

    while (size > 0)
    {
      chunk = (size > blocksize) ? blocksize : size;
      if (fread (buf, 1, chunk, in) != chunk)
	nb_error (E_ERRNO, _("read from %s"), oldpath);
      if (fwrite (buf, 1, chunk, out) != chunk)
	nb_error (E_ERRNO, _("write to %s"), newpath);
      size -= chunk;
    }
    free (buf);
    nb_unlink (oldpath);
  }
}

static void dump_stack ()
{
#if 0
  char s[160];

  sprintf (s,
	   "/bin/echo \"attach %d\\nwhere\\ndetach\\nquit\\n\" |"
	   "gdb -silent %s",
	  getpid(), Name);
  system (s);
  putchar ('\n');
#endif
}

void nb_error (int flags, char *format, ...)
{
  va_list ap;
  int saved_errno = errno;

  fprintf (stderr, "%s: %s: ", Name, (flags & E_CHILD)
	   ? _("error in child process") : _("error"));

  va_start (ap, format);
  vfprintf (stderr, format, ap);
  va_end (ap);

  if (flags & E_ERRNO)
  {
#ifdef HAVE_STRERROR
    fprintf (stderr, ": %s", strerror (saved_errno));
#else
    fprintf (stderr, _(", errno = %d"), saved_errno);
#endif
  }

  fputs (".\n", stderr);

  dump_stack ();

  if (flags & E_CHILD)
    _exit (128);
  else
    exit (1);
}
