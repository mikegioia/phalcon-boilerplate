# Phalcon Boilerplate Quickstart Guide

This short document will show you how to get the code working.
For a more detailed explanation and documentation visit
[the article series at ParticleBits][1].

## Step 1: Update your local config file(s)

Navigate to `app/config/env` and update the config files. To get
started you only need to update the local.ini file. Feel free to
add/remove/rename any file in this directory. The config files
in here are environment specific and extend the ini settings in
`app/config/config.ini`. You'll probably need to update the paths
and MySQL config at a minimum. Make sure the paths.hostname is
**without** the http://.

## Step 2: Run the install script

Open a terminal, navigate to the `deploy` directory, and run:

    ./install.sh <profile>

where `<profile>` is the name of the config file for the current
environment (local, development, production, etc.). This will
copy the environment config file to `app/config/config.local.ini`.

## Step 3: Run the SQL update script

In the same terminal and directory, run:

    ./update_sql_db.sh <mysql arguments>

to run the SQL statements. For example:

    ./update_sql_db.sh -u root -ppassword -h localhost

You can also copy and execute the SQL commands in `deploy/sql/`
manually. Run `./update_sql_db.sh -h` for help info about the
script.

## Test it out!

Point your browser to the base path you specified in the config.
You should see the welcome message and info page.

[1]: https://particlebits.com/phalcon-boilerplate
