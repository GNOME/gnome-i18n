Readme for SUN Style Guide
===========================


Menu titles
============

It would be advisable to include a comprehensive list of menu titles for the Solaris interface  in the section. Could SUN please provide these.

Unadvisable, as the Menu titles change with the locale/product.
 For instance, in Mailer, we have File, Edit, Attachments, Format and Help
 while in Terminal we have Window, Edit, Options and Help. Help is about 
 the only title we could claim remains consistent across all titles/locales.


Hot keys
===========

With reference to the above, could you please also provide the hot keys for the said menu titles.

With reference to the above, the hotkey is chosen depending on the actual
 word. <H>elp becomes <A>ider, etc...

 Also attached please find several error message shots. If you need more,
 let me know.


Accelerator keys
==================

In this section reference is made to the Solaris Operating System Standard. Some vendors have asked where they can obtain this. Could you please provide the path for the relevant file and/or provide the relevant text.



Error messages
===============

Please provide a wide range of examples of different types of error messages.

Netra J 
---------
For each GUI error message in Netra has two parts.
You get the following general message at the top of the page :

"You have made an error. Correct the information and choose OK. (Please 
consult documentation or on-line help for further information)"

Then you get the more specific message, here are 5 examples :

	Mount point must begin with "/"

	Please provide the absolute path name

	Default router host address required

	Host or Network Address required

	Invalid Path
	
	Invalid or duplicate workspace name
	
	File not opened: Unsupported format
	
	Try Send after specifying recipient(s) of the message in the To:, Cc:., or Bcc: fields.
	
	
	



Indices and sorted lists
=========================

Please ask an engineer to provide information on how the sorting works in SGML files.



Cross References
================

Please ask an engineer to provide information on how cross references are done in SGML files.



Extended characters
======================

Please ask an engineer to provide information on whether extended characters should be written using Latin1 (e.g. ä) or using entities, e.g.. &auml in HTML and SGML files.



Trademarks 
=============

"In SGML please use the entity "Trademark" to insert the Trademark"
In the above sentene please check that the entity is in fact given correctly.



Copyright page
==============
Ulf's comment: In previous releases SUN had to include a French and English copyright page in every book. Has this rule been changed.   - Could you comment on this?



Quotes
==========
One of the vendors commented that using double quotes or localized quotes crashes the build. Could you confirm that? Should we put a section in the Style Guide that only single straight quotes are to be used?



Software file types
===================
Some information was supplied for .msg and .tmsg files (taken from Mendez guidelines). Should additional information be supplied for .po and .java files? Could you provide the details for these?



Translating the .book file
==========================

Please ask an engineer to go through this section. This was taken from the Mendez guidelines, and we are really not sure about the exact meaning. In particular we feel that the Note! should be taken out.




Provisos and additional tasks necessary for completing this Style Guide:

Please ensure that all the technical parts are checked and verified by a SUN engineer, and complemented or corrected as necessary.

An native English speaker should review the entire document for correct use of language.

The entire documented should go through a desktop publishing process to adhere with SUN formatting standards before being passed on to the different language people for translation.
