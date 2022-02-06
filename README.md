# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](http://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [the announcement](http://forum.codeigniter.com/thread-62615.html) on the forums.

The user guide corresponding to this version of the framework can be found
[here](https://codeigniter4.github.io/userguide/).

## Installation & updates

`git clone https://github.com/andrei-iovu/emagia-test.git`

`cd emagia-test`

`composer update`

`chmod -R 777 writable/cache/`

`vendor/bin/phpunit --verbose tests/_support/Business/` - for running written unit tests

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Sample of an nginx vhost, on a local dev enviroment

```
server {
    listen 80;
    listen [::]:80;

    server_name test-emagia.example.ro;

    root /shared/projects/emagia-test/public;
    index index.php index.html index.htm;

    location = /robots.txt {access_log off; log_not_found off;}
    location = /favicon.ico { access_log off; log_not_found off; }
    location ~ /\. {deny all; access_log off; log_not_found off;}

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ [^/]\.php(/|$) {
        fastcgi_split_path_info ^(.+?\.php)(/.*)$;
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    error_page 404 /index.php;

    # deny access to hidden files such as .htaccess
    location ~ /\. {
        deny all;
    }
}
```
The app is fully functional by its root, either from an nginx vhost (from the sample above it'll be: http://test-emagia.example.ro) or from a php local webserver.

## Server Requirements

PHP version 7.4 or higher is required, with the following extensions installed:

- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)
