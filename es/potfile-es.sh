> > Kjartan, hay alguna manera de generar los archivos .pot diariamente
> > desde los módulos del CVS y publicarlos en el sitio ftp?
>
> Personalmente uso esta solución:
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
> Visita  http://www.datagroup.ro/~dand/gnome-pot si no me crees
> :)
>
> -dan
>

