#/bin/bash
touch /tmp/redial_inwork
for i in $(seq 1 2); 
do 
sleep 180;  
  mysql -uroot -pvicidialnow asterisk  -e "update vicidial_list set called_since_last_reset = 'N' where list_id =$1" ;
done
rm -f /tmp/redial_inwork
