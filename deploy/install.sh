#!/bin/bash
#
# installation script
# creates local configuration files and sets up the environment

# read in the env
usage="Sets up the Phalcon Boilerplate application
usage:
    $(basename "$0") [-h] <profile>
options:
    -h  show this help text"

# whether to force config update
force=0

while getopts ':hf' option; do
  case "$option" in
    h) echo "$usage"
       exit
       ;;
    f) force=1
       ;;
   \?) printf "Illegal option: -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit 1
       ;;
  esac
done
shift $((OPTIND - 1))

# get the paths
rootpath="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )";
apppath="$rootpath/../app";
# copy the config environment file
env=${1:-"local"}

if [ ! -f "${apppath}/etc/env/${env}.php" ] ; then
    printf "Invalid environment specified: %s\n" "$env" >&2
    exit 1
fi

if [[ ! -f "${apppath}/etc/config.local.php" || $force == 1 ]] ; then
    echo "Copying default '${env}' config file"
    cp ${apppath}/etc/env/${env}.php ${apppath}/etc/config.local.php
else
    echo "'${env}' config file exists, skipping! (try -f to force)"
fi

# prompt to update the SQL password
read -s -p "Enter SQL Password: " sqlReplace
echo ''
sqlFind="##SQLPASSWORD##"
sed -i "s/${sqlFind}/${sqlReplace}/g" ${apppath}/etc/config.local.php

echo ''
echo 'Done! Make sure you run the SQL statements.'
echo 'For help, run ./update_sql_db.sh -h'
