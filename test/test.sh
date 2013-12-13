#/bin/bash
# .bash_profile

# Get the aliases and functions


# User specific environment and startup programs

PATH=$PATH:$HOME/bin

export PATH
unset USERNAME

ORACLE_BASE=/opt/oracle
export ORACLE_BASE
ORACLE_HOME=$ORACLE_BASE/product/10.2.0/client_1
export ORACLE_HOME
PATH=$ORACLE_HOME/bin:$PATH:.
export PATH   

/usr/local/php-5.2-fcgi/bin/php /home/product/table_watcher/test/table_watcher.php