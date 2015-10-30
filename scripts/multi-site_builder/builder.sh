#!/bin/bash

for (( i = 0; i < `php -f decode.php sites_count`; i++ ));
do
    echo "setting up environment ..."

    PWD=`pwd`
    BASE_PATH=$PWD/../..
    #Retrieve all site information
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
    #Loop through domains array and add domains to sites.php
    for domain in $domains;
    do
        echo "$site['$domain'] = '$machine_name';">>$BASE_PATH/web/sites/sites.php
    done
    unset IFS
    #Make the multi-site directory
    mkdir $BASE_PATH/web/sites/$machine_name
    cp $BASE_PATH/web/sites/default/default.settings.php $BASE_PATH/web/sites/$machine_name/settings.php
    mkdir $BASE_PATH/web/sites/$machine_name/files

    mkdir $BASE_PATH/web/sites/$machine_name/cnf
    cp $BASE_PATH/web/sites_config.json $BASE_PATH/web/sites/$machine_name/cnf/config.json

    cd $BASE_PATH/web/sites/$machine_name/files/ && chmod -R 777 .
    cd $BASE_PATH/web/sites/$machine_name/

    dbconnect="$db_username:$db_pass"
    dburl="$db_driver://$dbconnect@$db_host/$db_name"
    #Drush site-install command
    $BASE_PATH/vendor/bin/drush si $profile --db-url=$dburl --site-name=$site_name --account-name=admin --account-pass=adminpass

    cd $BASE_PATH/scripts/multi-site_builder
done
