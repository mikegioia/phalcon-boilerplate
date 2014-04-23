#!/bin/bash
#
# installation script
# creates local configuration files and sets up the environment

# read in the env
#
usage="Sets up the Phalcon Boilerplate application
usage:
    $(basename "$0") [-h] <profile>
options:
    -h  show this help text"

while getopts ':h' option; do
  case "$option" in
    h) echo "$usage"
       exit
       ;;
   \?) printf "Illegal option: -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit 1
       ;;
  esac
done
shift $((OPTIND - 1))

# copy the config environment file
#
env=${1:-"local"}

if [ ! -f "../app/etc/env/${env}.php" ] ; then
    printf "Invalid environment specified: %s\n" "$env" >&2
    exit 1
fi

if [ ! -f "../app/etc/config.local.php" ] ; then
    echo "Copying default '${env}' config file"
    cp ../app/etc/env/${env}.php ../app/etc/config.local.php
else
    echo "'${env}' config file exists, skipping!"
fi

echo ''
echo 'Done! Make sure you run the SQL statements.'
echo 'For help, run ./update_sql_db.sh -h'
