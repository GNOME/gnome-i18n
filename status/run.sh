#!/bin/sh
cd /home/kmaraas/cvs/gnome
cvs -z3 up -dP
cd /home/kmaraas/cvs/gnome2
cvs -z3 up -dP
cd /home/kmaraas/cvs/gnome/gnome-i18n/status
LC_ALL=C perl status.pl 2>head-warn
LC_ALL=C perl status-release.pl 2>release-warn
LC_ALL=C perl status-apps.pl 2>apps-warn
perl ./genhtml.pl
perl ./genhtml-release.pl
perl ./genhtml-pango.pl
perl ./genhtml-apps.pl
mv *.po pot
cd pot
tar cvzf gnome-i18n-files.tar.gz *.po
cd /home/kmaraas/cvs/gnome/web-devel-2/content/projects/gtp/status
cvs -z3 commit -m "Update status table"
