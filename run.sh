#!/bin/bash
# sudo apt install nodejs

# apt install npm

# Start Apache
/usr/sbin/apache2ctl -D FOREGROUND

# Start Supervisor
/usr/bin/supervisord -n

npm run build

