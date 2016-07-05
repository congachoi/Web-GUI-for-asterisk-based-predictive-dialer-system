#/bin/bash
for i in $(seq 1 2); 
do 
sleep 120;
  mysql -uroot -pvicidialnow asterisk  -e "update vicidial_list set called_since_last_reset = 'N' where list_id =$1" ;
done
