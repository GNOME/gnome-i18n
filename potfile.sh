> > Kjartan, is there any way we could generate the .pot files on a daily
> > basis from the modules on the CVS and publish those on the ftp site?
>
> I personally use this solution:
>
> #!/bin/bash
>
> export CVSROOT=':pserver:anonymous@anoncvs.gnome.org:/cvs/gnome'
>
> /home/dand/gnome/anoncvslogin.sh
>
> for i in control-center ee ...
>
> do
>     cd /home/dand/gnome/cvs
>
>     if [ -d "$i" ] ; then
>  echo "cvs update module $i from anoncvs.gnome.org..."
>  cvs -z3 update -Pd $i > /dev/null 2>&1
>     else
>  echo "cvs checkout module $i from anoncvs.gnome.org..."
>  cvs -z3 checkout $i > /dev/null 2>&1
>     fi
>
>     cd $i/po
>     echo "updating $i.pot"
>     /usr/bin/xgettext --default-domain=$i --directory=.. \
>                 --add-comments --keyword=_ --keyword=N_  \
>                 --files-from=./POTFILES.in \
>   && mv -f $i.po /home/dand/public_html/gnome-pot/$i.pot
> done
>
> cvs logout
>
> echo `date`
>
>
> ---
>
> Check out  http://www.datagroup.ro/~dand/gnome-pot if you don't believe me
> :)
>
> -dan
>

