#!/bin/bash
text=$(cat /tmp/message-$1)
for number in $(cat /tmp/phones-$1) ; do

echo $text | gammu --sendsms TEXT $number -unicode -16bit -len 400
while [ $? -ne 0 ]; do
sleep 15
echo $text | gammu --sendsms TEXT $number -unicode -16bit -len 400
done

done
rm /tmp/message-$1
rm /tmp/phones-$1
