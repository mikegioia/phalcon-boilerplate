#!/bin/bash
#
# SQL update script
# runs all SQL statements in ./sql directory using command line
# arguments

# read in the env
#
sqlargs=$@
usage="Runs all SQL statements for the application. Any options that mysql
accepts can be used in this script.
usage:
    $(basename "$0") [-h] [mysql arguments]
options:
    -h  show this help text
examples:
    $(basename "$0") -u root -ppassword -h localhost
    $(basename "$0") -u root -p -h localhost
    $(basename "$0") -u root -h localhost
    $(basename "$0") -u root -ppassword -h 123.45.67.89
notes:
    The database 'phalcon' is used in all SQL scripts. Please update this
    in the ./sql/*.sql files if you wish to use a different database name."

while getopts ':h' option; do
  case "$option" in
    h) echo "$usage"
       exit
       ;;
  esac
done
shift $((OPTIND - 1))

# run sql scripts
#
echo "Running database scripts"
FILES="sql/*.sql"
for f in $FILES
do
    echo "  ...running $f"
    mysql ${sqlargs} < $f
done

echo ''
echo 'Done!'
