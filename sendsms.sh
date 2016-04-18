#!/bin/bash
text=$(cat /tmp/message)
for number in $(cat /tmp/phones) ; do
echo $text | gammu --sendsms TEXT $number -unicode -16bit -len 400
done
