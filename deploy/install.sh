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

# read in the secret file (if there is one). we want to
# iterate line by line looking for VAR=val lines. Then,
# we'll find ##VAR## in the local config file and replace
# it with val.
if [[ -f "${apppath}/etc/secret.ini" ]] ; then
    while read -r line || [[ -n $line ]]
    do
        IFS='=' read -ra ARR <<< "$line"
        find=${ARR[0]}
        replace=${ARR[1]}
        sed -i "s/##${ARR[0]}##/${ARR[1]}/g" ${apppath}/etc/config.local.php
    done < "${apppath}/etc/secret.ini"
else
    touch "${apppath}/etc/secret.ini"
fi

echo ''
echo 'Done! Make sure you run the SQL statements.'
echo 'For help, run ./update_sql_db.sh -h'
