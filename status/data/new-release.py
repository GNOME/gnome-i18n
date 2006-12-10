#!/usr/bin/env python
import sys
import libxml2

fn = 'translation-status.xml'
# remove this branch
rt = 'gnome-2.14'  # or sys.argv[1]
# to generate new branch, copy data from this branch
cf = 'gnome-2.16' # or sys.argv[2]
# and name new branch the following
ct = 'gnome-2.18' # or sys.argv[3]

def main(filename, removetag, copyfrom, copyto):
    try:
        ctxt = libxml2.createFileParserCtxt(filename)
        ctxt.lineNumbers(1)
        #ctxt.replaceEntities(1)
        ctxt.parseDocument()
        doc = ctxt.doc()
        if doc.name != filename:
            print >> sys.stderr, "Error: I tried to open '%s' but got '%s' -- how did that happen?" % (filename, doc.name)
            sys.exit(4)
    except:
        print >> sys.stderr, "Error: cannot open file '%s'." % (filename)
        sys.exit(1)

    root = doc.getRootElement() # <translation-status>

    # go through all releases and modules
    node = root.children
    while node:
        next = node.next
        if node.name == 'release':
            tag = node.prop("name")
            if tag == removetag:
                node.unlinkNode()
                node.freeNode()
            elif tag == copyfrom:
                newnode = node.copyNodeList()
                newnode.setProp("name", copyto)
                node.addNextSibling(newnode)
        elif node.name == 'module':
            child = node.children
            while child:
                vnext = child.next
                if child.name=="version":
                    rel = child.prop("release")
                    if rel == removetag:
                        child.unlinkNode()
                        child.freeNode()
                    elif rel == copyfrom:
                        newnode = child.copyNodeList()
                        newnode.setProp("release", copyto)
                        find = newnode.children
                        while find:
                            if find.name == "component":
                                findbranch = find.children
                                while findbranch:
                                    if findbranch.name == "branch":
                                        findbranch.setProp("name", "HEAD")
                                    findbranch = findbranch.next
                            find = find.next
                        child.addNextSibling(newnode)

                child = vnext

        node = next

    print doc.serialize('utf-8')

main(fn, rt, cf, ct)
