#! /usr/bin/python

# written by Jim Meier, fatjim@home.com
# for kanikus, kenneth@gnome.dk
# modified a little by kenneth@gnome.dk
# on 20 apr 2000
#
# docbook-stripper is a script, which makes a po compatible file out of 
# a docbook file, which contains <po> and </po> tags marking the strings
#
# freely redistributable and modfieable provided this header remains and
# distribution is free of charge.

import sys, re, string


#Define some variables
#   uncomment the two sys.argv lines to read the filenames from the commandline
#  infile = sys.argv[1]
infile = "test.sgml.in"

#  outfile = sys.argv[2]
outfile = "test.sgml"

#  headfile = sys.argv[3]
headfile = "header"

# read in the header file
header = open(headfile).read()


#Define the regexp and get the input text
po_catcher_exp = "<po>(.*?)</po>"
po_catcher = re.compile(po_catcher_exp, re.DOTALL)
filetext =  open(infile).read()


#get the line number of a position in the string
def findlineno(where):
    # lame and inneficient
    n = 0
    for i in range(0, where):
        if filetext[i] == "\n":
            n=n+1
    return n


res = {}
done = 0
start = 0
#Loop over the input text until there are no more matches
while not done:    
    my_match = po_catcher.search(filetext, start)
    if my_match == None:
        done =1
    else:
        start = my_match.end()
        lineno = findlineno(start)
        try:
            res[my_match.group(1)].append (str(lineno))
        except:
            res[my_match.group(1)] = [str(lineno)]


# if the results of the search need to be munged in anyway, this is the place to
# do it. This generated the po compatible file


#Write out the file.
out = open(outfile, "w")
out.write(header+"\n")
for key,val in res.items():    
    line_nos = string.join(val, ", ")
    out.write("\n#: "+infile+":"+line_nos+"\n")
    if "\n" in key:
        out.write('msgid ""\n')
        lines = string.split(key, "\n")
        for i in lines:
            out.write('"'+string.strip(i)+'"\n')
    else:
        out.write('msgid "' + key + '"\n')
    out.write('msgstr ""\n')
              
              

out.close()

