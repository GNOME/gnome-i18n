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
            else:
                # FIXME: hackish, works for www.gnome.org/tour
                match = re.match("include\s+(.+)", fullline.strip())
                if match:
                    import os.path
                    incfile = os.path.join(os.path.dirname(file), os.path.basename(match.group(1)))
                    if incfile.find("gnome-doc-utils.make")==-1:
                        print >>sys.stderr, "Reading file %s..." % (incfile)
                        var = read_makefile_variable(incfile, variable)
                        if var != "":
                            return var
                    
            fullline = ""
    return ""

def pofile_statistics(command):
    (error, output) = commands.getstatusoutput(command)
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
    return (translated, fuzzy, untranslated)

# type is one of "modules", "languages", "module" (data[module] is set then), "language" (data[language] is set)
def stats_as_html(stats, type = 'modules', otherstats = None, data = {}):
    import time
    html = """<html><head><title>Gnome Documentation Translation Statistics</title>
<style language="css">
body {
  background: white; /*#F8F8F1;*/
  margin: 2em;
  /*margin-right: 180px;*/
}
table {
  background: white;
  /*border: 1px solid #808080;*/
}

tr.odd {
  background: #E0E0E0;
}
tr.even {
  background: #F8F8F1;
}

td {
  text-align: center;
  padding-left: 8px;
  padding-right: 8px;
}

td.lang {
  text-align: left;
}

th {
  background: #446688;
  color: white;
}

div.graph {
  height: 10pt;
  width: 180px;
  border: 1px solid black; 
  position: relative;
}

div.translated {
  position: absolute; 
  height:100%%; 
  left: 0%%; 
  background: #448844;
}

div.goodchange {
  position: absolute; 
  height:100%%; 
  background: #55DD55;
}

div.fuzzy {
  position: absolute; 
  height:100%%; 
  background: #4444AA;
}

div.untranslated {
  position: absolute; 
  height:100%%; 
  background: #FF4444;
}

div.legend {
  width: 100%%;
  color: black;
  text-align: center;
  padding-top: 3px;
  padding-bottom: 3px;
  font-weight: bold;
  border: 1px solid black;
  border-bottom: 0px;
}
</style>    </head>\n<body>
    <h1>Gnome Docs L10N stats</h1>\n<h2>Regenerated on %s</h2>\n""" % (time.ctime())
    toc = ""
    content = ""
    cats = stats.keys()
    cats.sort()

    for cat in cats:
        if type == 'modules':
            cattitle = 'Module %s' % (cat)
            titleline = """<tr><th>Language</th><th>CVS dir/branch</th><th>Translated</th><th>Fuzzy</th><th>Untranslated</th><th>Graph</th></tr>"""
        else:
            cattitle = 'Language %s' % (cat)
            titleline = """<tr><th>Module</th><th>CVS dir/branch</th><th>Translated</th><th>Fuzzy</th><th>Untranslated</th><th>Graph</th></tr>"""
        
        toc += """\t<li><a href="#%s">%s</a></li>\n""" % (cat, cat)
        content += """\t<tr><td colspan="6" style="text-align:left; padding-top:2em;"><h3><a name="%s">%s</a></h3></td></tr>\n%s\n\t\n""" % (cat, cattitle, titleline)
        subcats = stats[cat].keys()
        subcats.sort()
        full_subcats = otherstats.keys()
        full_subcats.sort()
        row = 1
        for id in full_subcats:
            if not id in subcats:
                try:
                    field = otherstats[id]['POT']
                except:
                    field = stats[cat]['POT']
            else:
                field = stats[cat][id]

            row = (row + 1) % 2
            if row % 2 == 0: css_class = "even"
            else: css_class = "odd"
            total = int(field['translated']) + int(field['fuzzy']) + int(field['untranslated'])
            if total <= 0: total = 1
            graphwidth = 180;
            graphtr = int(round(int(field['translated'])*graphwidth/total))
            graphfz = int(round(int(field['fuzzy'])*graphwidth/total))
            graphun = graphwidth - graphtr - graphfz
            graphhtml = """<div class="graph">
            <div class="translated" style="width: %dpx;"></div>
            <div class="fuzzy" style="width: %dpx; left: %dpx;"></div>
            <div class="untranslated" style="width: %dpx; left: %dpx;"></div>
            </div>""" % (graphtr, graphfz, graphtr, graphun, graphtr+graphfz)

            
            content += """\t\t<tr class="%s"><td><a href="/doc-l10n/PO/%s">%s</a></td>
                          <td>%s</td>
                          <td>%.1f%% (%s)</td>
                          <td>%.1f%% (%s)</td>
                          <td>%.1f%% (%s)</td>
                          <td>%s</td></tr>\n""" % (css_class,
                                                   field['poname'],
                                                   id,
                                                   """<a href="http://cvs.gnome.org/viewcvs/%s?only_with_tag=%s">%s (%s)</a>""" % (field['cvsdir'],
                                                                                                                            field['branch'],
                                                                                                                            field['cvsdir'],
                                                                                                                            field['branch']),
                                                   int(field['translated'])*100/total, field['translated'],
                                                   int(field['fuzzy'])*100/total, field['fuzzy'],
                                                   int(field['untranslated'])*100/total, field['untranslated'],
                                                   graphhtml)
        content += "\n\n"
    if content: content = "<center><table>\n\n" + content + "</table></center>\n"
    if type == 'modules': toc_title = 'Modules'
    else: toc_title = 'Languages'
    if toc: toc = "<h3>%s</h3><ul>\n" % (toc_title) + toc + "</ul><hr>\n"
    return html + toc + content + "</body></html>"
    
MyModules("modules.xml")

alllangs = []


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
            
        try:
            os.stat(os.path.join(module["localpath"], "C", modulename + ".xml"))
        except:
            print >>sys.stderr, "Warning: DOC_MODULE doesn't resolve to a real file, trying '%s'." % (moduleid)
            modulename = moduleid
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

        if not modstats.has_key(moduleid): modstats[moduleid] = { }
        if not langstats.has_key('POT'): langstats['POT'] = { }
        (translated, fuzzy, untranslated) = pofile_statistics("LC_ALL=C LANG=C LANGUAGE=C msgfmt --statistics -o /dev/null %s" % (fullpot))
        modstats[moduleid]['POT'] = {
            "poname" : potname,
            "cvsdir" : module["cvsdir"],
            "branch" : module["cvsbranch"],
            "translated" : '0',
            "untranslated" : untranslated,
            "fuzzy" : '0',
            }
        langstats['POT'][moduleid] = {
            "poname" : potname,
            "cvsdir" : module["cvsdir"],
            "branch" : module["cvsbranch"],
            "translated" : '0',
            "untranslated" : untranslated,
            "fuzzy" : '0',
            }

        
        for lang in languages.split(" "):
            print >>sys.stderr, "Processing %s..." % (lang)
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
                    (translated, fuzzy, untranslated) = pofile_statistics("LC_ALL=C LANG=C LANGUAGE=C msgfmt --statistics -o /dev/null %s" % (fullpo))

                    if not modstats.has_key(moduleid): modstats[moduleid] = { }
                    if not langstats.has_key(lang): langstats[lang] = { }
                    modstats[moduleid][lang] = {
                        "poname" : poname,
                        "cvsdir" : module["cvsdir"],
                        "branch" : module["cvsbranch"],
                        "translated" : translated,
                        "untranslated" : untranslated,
                        "fuzzy" : fuzzy,
                        }
                    langstats[lang][moduleid] = {
                        "poname" : poname,
                        "cvsdir" : module["cvsdir"],
                        "branch" : module["cvsbranch"],
                        "translated" : translated,
                        "untranslated" : untranslated,
                        "fuzzy" : fuzzy,
                        }

html_mods = stats_as_html(modstats, type = 'modules', otherstats = langstats)
html_lang = stats_as_html(langstats, type = 'languages', otherstats = modstats)


f = open(os.path.join(webdir, "by-modules.html"), "w")
f.write(html_mods)
f.close()

f = open(os.path.join(webdir, "by-languages.html"), "w")
f.write(html_lang)
f.close()
