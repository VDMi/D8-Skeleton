#!/bin/bash

for (( i = 0; i < `php -f decode.php sites_count`; i++ ));
do
    echo "setting up environment ..."

    #statements
    machine_name=`php -f decode.php machine_name $i`
    db_name=`php -f decode.php $i database`
    db_username=`php -f decode.php $i username`
    db_pass=`php -f decode.php $i password`
    db_host=`php -f decode.php $i host`
    db_driver=`php -f decode.php $i driver`
    site_name=`php -f decode.php $i site_name`
    profile=`php -f decode.php $i profile`
    domains=`php -f decode.php $i domains`

    site='$sites'
    IFS=','
    for domain in $domains;
    do
    echo $domain
    echo "$site['$domain'] = '$machine_name';">>../../web/sites/sites.php
    done
    unset IFS

    mkdir ../../web/sites/$machine_name
    cp ../../web/sites/default/default.settings.php ../../web/sites/$machine_name/settings.php
    mkdir ../../web/sites/$machine_name/files

    mkdir ../../web/sites/$machine_name/cnf
    cp sites_config.json ../../web/sites/$machine_name/cnf/config.json

    cd ../../web/sites/$machine_name/files/ && chmod -R 777 .
    cd ..

    dbconnect="$db_username:$db_pass"
    dburl="$db_driver://$dbconnect@$db_host/$db_name"

    ../../../vendor/bin/drush si $profile --db-url=$dburl --site-name=$site_name --account-name=admin --account-pass=adminpass

    cd ../../../scripts/multi-site_builder/
done
