#include <ctype.h>
#include "strncasecmp.h"

int strncasecmp (const char *s1, const char *s2, size_t n)
{
  int diff;

  while (n--)
  {
    diff = tolower (*s1) - tolower (*s2);
    if (diff)
      return diff;
    if (*s1 == 0)
      break;
    s1++;
    s2++;
  }
  return 0;
}
