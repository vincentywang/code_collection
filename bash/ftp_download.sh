#!/bin/bash

HOST=aftp.example.com
USER=my-user-name
PASS=my-pass-word
FILES=find ./* cmp.xml.gz.lmp

#login
count=1
ftp -inv $HOST << SCRIPT
user $USER $PASS
binary

#need a loop to loop through folders 

# for i in $(find ./*.cmp.xml.gz.lmp -type f); do
for i in $(find . -type d)
do	

for i in $FILES; do
  #get $i /Users/username/ftp_download/$count.cmp.xml
  #count= $count + 1 
  echo $i
done

SCRIPT

