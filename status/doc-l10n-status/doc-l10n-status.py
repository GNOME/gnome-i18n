#!/usr/bin/env python
# -*- coding: utf-8 -*-
#
# Copyright (c) 2005 Danilo Segan <danilo@gnome.org>.
#
# This file is part of doc-l10n-status.
#
# doc-l10n-status is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# doc-l10n-status is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with doc-l10n-status; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#

from l10n_config import *


# Configuration done, no need to change anything below

modules = { }
modstats = { }
langstats = { }

import xml.dom.minidom
import os, os.path, sys

class MyModules:
    def getElementText(self, node, element, default = 0):
        all = node.getElementsByTagName(element)
        if not all or len(all)<1:
            return default

        nodelist = all[0].childNodes
        rc = ""
        for el in nodelist:
            if el.nodeType == el.TEXT_NODE:
                rc = rc + el.data
        return rc


    def __init__(self, modfile = "modules.xml"):
        dom = xml.dom.minidom.parse(modfile)

        mods = dom.getElementsByTagName("module")
        for module in mods:
            modid = module.getAttribute("id")

            mycvsroot = self.getElementText(module, "cvs-root", default = cvsroot)
            cvsdir = self.getElementText(module, "cvs-directory", default = modid)
            cvsbranch = self.getElementText(module, "cvs-branch", default = 'HEAD' )
            potname = self.getElementText(module, "potname", default = modid)

            modules[modid] = {
                "potname" : potname,
                "cvsroot" : mycvsroot,
                "cvsdir"  : cvsdir,
                "cvsbranch" : cvsbranch }
            

def checkout_file(cvsroot, module, localroot, moduledir, file):
    command = "cd %(localroot)s && cvs -d%(cvsroot)s -z4 co -d%(dir)s -r%(branch)s %(file)s" % {
        "localroot" : localroot,
        "cvsroot" : cvsroot,
        "dir" : moduledir,
        "branch" : module["cvsbranch"],
        "file" : "/".join([module["cvsdir"], file]),
        }

    import commands

    print >>sys.stderr, command
    (error, output) = commands.getstatusoutput(command)
    if not error:
        print >> sys.stderr, output
        return 1
    else:
        print >> sys.stderr, output
        return 0


def checkout_dir(cvsroot, module, localroot, moduledir, dir):
    try:
        os.makedirs(os.path.join(moduledir, dir))
    except:
        pass
    
    return checkout_file(cvsroot, module, localroot, "/".join([moduledir, dir]), dir)

def checkout_module(moduleid):
    module = modules[moduleid]
    localdir = os.path.join(scratchdir, "cvs")
    moduledir = os.path.join(moduleid + "." + module["cvsbranch"])
    try:
        os.makedirs(localdir)
        os.chdir(localdir)
    except:
        pass

    modules[moduleid]["localpath"] = os.path.join(localdir, moduledir)

    co = checkout_file(cvsroot, module, localdir, moduledir, "") #and checkout_dir(cvsroot, module, localdir, moduledir, "C") 

    if not co:
        print >> sys.stderr, "Problem with checking out module %s" % (moduleid)
        return 0
    return 1

def read_makefile_variable(file, variable):
    import re
    fin = open(file, "r")
    
    fullline = ""
    for line in fin:
        fullline += " " + line.strip()
        if len(line)>2 and line[-2] == "\\":
            fullline = fullline[:-2]
        else:
            match = re.match(variable + r"\s*=\s*([^=]*)", fullline.strip())
            if match:
                return match.group(1)
            fullline = ""
    return ""

def stats_as_html(stats):
    import time
    html = """<html><head><title>Gnome Documentation Translation Statistics</title></head>\n<body>
    <h1>Gnome Docs L10N stats</h1>\n<h2>Regenerated on %s</h2>\n""" % (time.ctime())
    toc = ""
    content = ""
    for cat in stats.keys():
        toc += """\t<li><a href="#%s">%s</a></li><hr>\n""" % (cat, cat)
        content += """\t<h3><a name="%s">%s:</a></h3>\n\n\t<ul>\n""" % (cat, cat)
        for id in stats[cat].keys():
            field = stats[cat][id]
            content += """\t\t<li><a href="/doc-l10n/PO/%s">%s</a> (%s/%s/%s)</li>\n""" % (field['poname'],
                                                                                         id,
                                                                                         field['translated'],
                                                                                         field['fuzzy'],
                                                                                         field['untranslated'])
        content += "\t</ul>\n\n"
    if toc: toc = "<ul>\n" + toc + "</ul>\n"
    return html + toc + content + "</body></html>"
    
MyModules("modules.xml")

for moduleid in modules.keys():
    status = checkout_module(moduleid)
    module = modules[moduleid]
    if status:
        # now read interesting variables from the Makefile.am
        makefileam = os.path.join(module["localpath"], "Makefile.am")
        modulename = read_makefile_variable(makefileam, "DOC_MODULE")
        includes = read_makefile_variable(makefileam, "DOC_INCLUDES")
        entitites = read_makefile_variable(makefileam, "DOC_ENTITIES")
        languages = read_makefile_variable(makefileam, "DOC_LINGUAS")

        potname = module["potname"] + "." + module["cvsbranch"] + ".pot"
        potdir = webpodir
        try: os.makedirs(potdir)
        except: pass

        fullpot = os.path.join(potdir,potname)

        if not modulename:
            print >>sys.stderr, "Module %s doesn't look like gnome-doc-utils module." % (moduleid)
            continue
            
        files = [ modulename + ".xml" ]
        for file in includes.split(" "):
            if file.strip() != "":
                files.append(file.strip())

        allfiles = ""
        for file in files:
            allfiles += " " + os.path.join(module["localpath"], "C", file)
        command = "%s -o %s -e %s" % (XML2PO, fullpot, allfiles)

        import commands

        print >>sys.stderr, command
        (error, output) = commands.getstatusoutput(command)
        if not error:
            print >> sys.stderr, output
        else:
            print >> sys.stderr, "Error regenerating POT file for module %s" % ( moduleid)
            print >> sys.stderr, output
        
        for lang in languages.split(" "):
            if lang.strip() != "":
                lang = lang.strip()
                poname = module["potname"] + "." + module["cvsbranch"] + "." + lang + ".po"
                fullpo = os.path.join(potdir, poname)
                pofile = os.path.join(module["localpath"], lang, lang + ".po")
                command = "msgmerge -o %s %s %s" % (fullpo, pofile, fullpot)
                command = "%s %s %s > %s" % (POMERGE, pofile, fullpot, fullpo)
                (error, output) = commands.getstatusoutput(command)
                print >> sys.stderr, output
                if not error:
                    (error, output) = commands.getstatusoutput("LC_ALL=C LANG=C LANGUAGE=C msgfmt -cv -o /dev/null %s" % (fullpo))
                    import re
                    r_tr = re.search(r"([0-9]+) translated", output)
                    r_un = re.search(r"([0-9]+) untranslated", output)
                    r_fz = re.search(r"([0-9]+) fuzzy", output)

                    if r_tr: translated = r_tr.group(1)
                    else: translated = 0
                    if r_un: untranslated = r_un.group(1)
                    else: untranslated = 0
                    if r_fz: fuzzy = r_fz.group(1)
                    else: fuzzy = 0

                    if not modstats.has_key(moduleid): modstats[moduleid] = { }
                    if not langstats.has_key(lang): langstats[lang] = { }
                    modstats[moduleid][lang] = {
                        "poname" : poname,
                        "translated" : translated,
                        "untranslated" : untranslated,
                        "fuzzy" : fuzzy,
                        }
                    langstats[lang][moduleid] = {
                        "poname" : poname,
                        "translated" : translated,
                        "untranslated" : untranslated,
                        "fuzzy" : fuzzy,
                        }

html_mods = stats_as_html(modstats)
html_lang = stats_as_html(langstats)

f = open(os.path.join(webdir, "by-modules.html"), "w")
f.write(html_mods)
f.close()

f = open(os.path.join(webdir, "by-languages.html"), "w")
f.write(html_lang)
f.close()
