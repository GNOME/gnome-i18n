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
#include <string.h>
#include <sys/wait.h>

const char *Name = "newsbody";
#define NAME_DECLARED
#include "newsbody.h"

#ifdef HAVE_GETOPT
/*
 * Systems which have the getopt function should declare it in
 * unistd.h, but the declaration is reported to be in unistd.h
 * in an AIX system.
 * It should be safe to include both to be sure...
 */
#include <unistd.h>
#include <stdlib.h>
#else
#include "getopt.h"
#endif

/* Static functions in this file */
static void parse_commandline (int argc, char *argv[]);
static void usage (void);
static void expand_argv (char *argv[], char *filename);
static void check_child (void);

/* These variables are set by parse_commandline() and used by main() */
static char *newsfile_name = NULL;
static char *program = NULL;
static struct keep_lines *keep = NULL;
static int remove_header = 1;
static int remove_quote = 0;
static int remove_sig = 0;
static int filter = 0;
static int printversion = 0;
static char **new_argv;

int main (int argc, char *argv[])
{
  int use_stdin = 0;
  FILE *newsfile;
  FILE *newsfile_copy;
  char *bodyfile_name = NULL;
  FILE *bodyfile_r;
  FILE *bodyfile_w;
  char *mergefile_name = NULL;
  FILE *mergefile;

#if defined(MAINLOOP)
  volatile int w = 1;
  while (w);
#endif

  parse_commandline (argc, argv);

  if (printversion)
  {
    printf ("%s (" PACKAGE ") " VERSION "\n", Name);
    return 0;
  }

  if (!strcmp (newsfile_name, "-"))
  {
    use_stdin = 1;
    newsfile_name = nb_tmpnam ();
    newsfile_copy = open_write (newsfile_name);
    newsfile = stdin;

    mergefile = stdout;
  }
  else
  {
    newsfile_copy = NULL;
    newsfile = open_read (newsfile_name);

    mergefile_name = nb_tmpnam ();
    mergefile = open_write (mergefile_name);
  }

  if (filter)
  {
    expand_argv (new_argv, "-");
    exec_filter (new_argv, &bodyfile_w, &bodyfile_r);
    writebody (newsfile, newsfile_copy, bodyfile_w,
	       remove_header, remove_quote, remove_sig, keep);

    newsfile = open_read (newsfile_name);
    readbody (newsfile, bodyfile_r, mergefile,
	      remove_header, remove_quote, remove_sig, keep);
    check_child ();
  }
  else
  {
    bodyfile_name = nb_tmpnam ();
    bodyfile_w = open_write (bodyfile_name);
    expand_argv (new_argv, bodyfile_name);
    writebody (newsfile, newsfile_copy, bodyfile_w,
	       remove_header, remove_quote, remove_sig, keep);

    exec_program (new_argv);
    check_child ();

    newsfile = open_read (newsfile_name);
    bodyfile_r = open_read (bodyfile_name);
    readbody (newsfile, bodyfile_r, mergefile,
	      remove_header, remove_quote, remove_sig, keep);
  }

  if (use_stdin)
    nb_unlink (newsfile_name);
  else
    nb_rename (mergefile_name, newsfile_name);

  if (!filter)
    nb_unlink (bodyfile_name);

  return 0;
}

static void parse_commandline (int argc, char *argv[])
{
  int option;
  struct keep_lines *k;
  char **ap;

  do
  {
    option = getopt (argc, argv, "qsfhn:p:k:v");
    switch (option)
    {
    case 'q':
      remove_quote = 1;
      break;
    case 's':
      remove_sig = 1;
      break;
    case 'f':
      filter = 1;
      break;
    case 'h':
      remove_header = 0;
      break;
    case 'n':
      newsfile_name = optarg;
      break;
    case 'p':
      program = optarg;
      break;
    case 'k':
      k = nb_malloc (sizeof (struct keep_lines));
      k->header = optarg;
      k->next = keep;
      keep = k;
      break;
    case 'v':
      printversion = 1;
      return; /* no more checking needed */

    case ':': /* option with missing parameter */
    case '?': /* unknown option */
      usage ();
    case EOF: /* no more options */
      break;

    default:  /* all possible cases should have been stated */
      nb_error (0, _("getopt returned impossible value: %d ('%c')"),
		option, option);
    }
  }
  while (option != EOF);

  if (newsfile_name == NULL || program == NULL)
    usage ();

  new_argv = nb_malloc ((argc - optind + 2) * sizeof (char *));
  new_argv[0] = program;
  argv += optind;
  ap = new_argv + 1;
  while ((*(ap ++) = *(argv ++)) != NULL)
    ;
}

static void usage()
{
  fputs
    (_("Usage: newsbody [-fhqsv] [-k headername] -n message -p program\n"
       "       [-- [program-arguments...]]\n"
       "\n"
       "Copies the body of the e-mail or usenet message in `message'\n"
       "to a temporary file (the `bodyfile') and then calls the program\n"
       "`program' (e.g. a spell checker) with all `program-arguments'.\n"
       "Afterwards the possibly changed `bodyfile' is copied back into\n"
       "the `message'.\n"
       "\n"
       "If `message' is specified as `-', newsbody will use its\n"
       "standard input and standard output.\n"
       "\n"
       "`%f' in the `program arguments' will be expanded to the name of\n"
       "the `bodyfile'. Use `%%' for a real `%' character.\n"
       "\n"
       "The flags mean:\n"
       " -f: The called program is a filter so don't make a temporary\n"
       "     file, but pipe `bodyfile' to the standard input of the\n"
       "     `program' and later read it back from its standard output.\n"
       " -h: keep the entire header. This flag should also be used\n"
       "     if 'message' doesn't have a header at all.\n"
       " -k headername: keep these header lines in the `bodyfile'.\n"
       "     (Multiple -k flags are allowed).\n"
       " -q: Also remove quotes from the `bodyfile'.\n"
       "     Lines starting with `>' are considered quotes.\n"
       " -s: Also remove signature from the `bodyfile'.\n"
       "     The signature is all lines from the first signature separator\n"
       "     (i.e. `-- ') to the bottom of the message.\n"
       " -v: Print version and exit.\n"),
     stderr);
  exit (1);
}

static void expand_argv (char *argv[], char *filename)
{
  char *argument;
  char *new_argument;
  char *c;
  int offset;

  while ((argument = *(++ argv)) != NULL)
  {
    offset = 0;
    while ((c = strchr (argument + offset, '%')) != NULL)
    {
      switch (*(c + 1))
      {
      case 'f':
	/* We have a menory leak here when a argument have more than one %f,
	 * but I don't care because this seldom happens and when it does not
	 * much memory will be wasted */
	new_argument = nb_malloc (strlen (argument) + strlen (filename) - 1);
	*c = 0;
	strcpy (new_argument, argument);
	strcat (new_argument, filename);
	offset = strlen (new_argument);
	argument = strcat (new_argument, c + 2);
	break;

      case '%':
	c ++;
	offset = c - argument;
	do 
	  *(c - 1) = *c;
	while (*(c ++));
	break;

      default:
	offset = c + 1 - argument;
      }
    }
    *argv = argument;
  }
}

static void check_child ()
{
  int status = nb_wait ();

  if (WIFSIGNALED (status))
  {
#ifdef HAVE_STRSIGNAL
    nb_error (0, _("%s died with signal \"%s\""), program,
	      strsignal (WTERMSIG (status)));
#else
    nb_error (0, _("%s died with signal no. %d"), program, WTERMSIG (status));
#endif
  }
  else if (WIFEXITED (status))
  {
    status = WEXITSTATUS (status);
    if (status == 128)
      nb_error (0, _("child process couldn't execute %s"), program);
    else if (status)
      nb_error (0, _("%s returned failure exit code %d"), program, status);
  }
  else
    nb_error (0, _("impossible status from child process: %x"), status);
}
