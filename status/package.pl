#!/usr/bin/perl

@langs = qw ( af ar as az bg bn bs ca cs cy da de el en_GB eo es et eu fa fi fr ga 
              gl gu he hi hr hu id is it ja ko kn ko ks lt lv mi ml mk mr nl 
              no nn or pa pl ps pt pt_BR ro ru sd si sk sl sp sr sv ta te th 
              ur wa vi zh_CN zh_TW );

$cvsroot = "/home/kmaraas/cvs/gnome/";
$htmldir = "/home/kmaraas/cvs/gnome/web-devel-2/content/projects/gtp/status/pango";
$posdir = "/home/kmaraas/cvs/gnome/web-devel-2/content/projects/gtp/status/pos";
$tardir = "/home/kmaraas/cvs/gnome/po-dist";

chdir("$posdir");

foreach $lang (@langs) 
{
  unlink("$tardir/gnome-i18n-$lang.tar.gz");
  system("tar zcvf $cvsroot/po-dist/gnome-i18-$lang.tar.gz *-$lang.po");
}

system("tar zcvf $cvsroot/po-dist/gnome-i18n-pot.file.tar.gz *.pot");
