CREATE TABLE memory ( file varchar, id varchar, str varchar);
copy memory from 'kimenet.txt' using delimiters 'ß';
create index idindex on memory (id);
create index strindex on memory (str);
