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

#include <unistd.h>
#include <stdio.h>
#include <stdarg.h>
#include <stdlib.h>
#include <string.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <fcntl.h>
#include "lib.h"

/* Static functions in this file */
/* none ... */

void exec_program (char *argv[])
{
  pid_t pid;

  pid = fork ();
  if (pid == -1)
    nb_error (E_ERRNO, _("fork"));

  if (pid == 0)
  {
    /* The child */
    char *cterm = ctermid (NULL);
    int fd;

#if defined(CHILDLOOP)
    volatile int w = 1;
    while (w);
#endif

    if (!isatty (STDIN_FILENO) && cterm && *cterm)
    {
      if ((fd = open (cterm, O_RDONLY)) == -1)
	nb_error (E_CHILD | E_ERRNO, _("open terminal read"));
      if (dup2 (fd, STDIN_FILENO) == -1)
	nb_error (E_CHILD | E_ERRNO, _("dup2 stdin"));
      if (close (fd))
	nb_error (E_CHILD | E_ERRNO, _("close extra terminal read fd"));
    }
    if (!isatty (STDOUT_FILENO) && cterm && *cterm)
    {
      if ((fd = open (cterm, O_WRONLY)) == -1)
	nb_error (E_CHILD | E_ERRNO, _("open terminal write"));
      if (dup2 (fd, STDOUT_FILENO) == -1)
	nb_error (E_CHILD | E_ERRNO, _("dup2 stdout"));
      if (close (fd))
	nb_error (E_CHILD | E_ERRNO, _("close extra terminal write fd"));
    }
    execvp (argv[0], argv);
    nb_error (E_CHILD | E_ERRNO, _("exec %s"), argv[0]);
  }

  /* The parent */
  /* Do nothing */
}

void exec_filter (char *argv[], FILE **out, FILE **in)
{
  int outpipe[2]; /* Where the PARENT writes */
  int inpipe[2];  /* Where the PARENT reads */
  pid_t pid;

#define READ_END 0
#define WRITE_END 1

  if (pipe (outpipe) == -1)
    nb_error (E_ERRNO, _("pipe for output"));
  if (pipe (inpipe) == -1)
    nb_error (E_ERRNO, _("pipe for input"));

  pid = fork ();
  if (pid == -1)
    nb_error (E_ERRNO, _("fork"));

  if (pid == 0)
  {
    /* The child */
#if defined(CHILDLOOP)
    volatile int w = 1;
    while (w);
#endif

    if (close (outpipe[WRITE_END]))
      nb_error (E_CHILD | E_ERRNO, _("close write end of output pipe"));
    if (dup2 (outpipe[READ_END], STDIN_FILENO) == -1)
      nb_error (E_CHILD | E_ERRNO, _("dup2 stdin"));
    if (close (outpipe[READ_END]))
      nb_error (E_CHILD | E_ERRNO, _("close read end of output pipe"));

    if (close (inpipe[READ_END]))
      nb_error (E_CHILD | E_ERRNO, _("close read end of input pipe"));
    if (dup2 (inpipe[WRITE_END], STDOUT_FILENO) == -1)
      nb_error (E_CHILD | E_ERRNO, _("dup2 stdout"));
    if (close (inpipe[WRITE_END]))
      nb_error (E_CHILD | E_ERRNO, _("close write end of input pipe"));

    execvp (argv[0], argv);
    nb_error (E_CHILD | E_ERRNO, _("exec %s"), argv[0]);
  }

  /* The parent */
  if (close (outpipe[READ_END]))
    nb_error (E_ERRNO, _("close read end of output pipe"));
  if ((*out = fdopen (outpipe[WRITE_END], "w")) == NULL)
    nb_error (E_ERRNO, _("fdopen write end of output pipe"));

  if (close (inpipe[WRITE_END]))
    nb_error (E_ERRNO, _("close write end of input pipe"));
  if ((*in = fdopen (inpipe[READ_END], "r")) == NULL)
    nb_error (E_ERRNO, _("fdopen read end of input pipe"));
}

int nb_wait ()
{
  int status;

  if (wait (&status) == -1)
    nb_error (E_ERRNO, _("waiting for child"));
  return status;
}
