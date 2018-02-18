# vhost
Tool for easy creating a virtual hosts.

### Create a symlink for vhost.php to use it globally.

```
ln -s /var/www/vhost/vhost.php /usr/local/bin/vhost
```
### Change paths in vhost.php

<b>ROOT_PATH</b> is where vhost command is located;


```
define('ROOT_PATH', '/Users/pwalus/Commands/vhost/');
```

<b>PROJECT_PATH</b> is where you locate your projects;


```
define('PROJECT_PATH', '/Users/pwalus/Sites/');
```

### Run

```
vhost create
```
