# ZSchedule

App used to book playing centres ahead of time in colleges/universities. Meant for use by students, college staff and authorities.

This project has been built as part of a college course

# To build and run

Ensure you have

-   A MySQL client and server
-   A server capable of running PHP

## Create a local instance of the database

Inside the mysql shell

```
\source db/init.sql
\source db/populate.sql
```

Create a file named `config.php` in the root folder. \
A `config.example.php` is already present for convenience. \
It contains the defaults for a local MySQL server installation. \
You may or may not need to change its values.

## Run the server

Steps may be different for the server application that you use. \

### For use with XAMPP using Apache

Change the server config to serve the project's root folder using a new virtual host.
Add the following lines to `httpd.conf`

```
<VirtualHost localhost:80>
    ServerName zschedule.com
    DocumentRoot "C:/xampp/htdocs/path/to/zschedule_php/src"

    <Directory "C:/xampp/htdocs/path/to/zschedule_php/src">
        Options -Indexes +FollowSymLinks
    </Directory>

    RewriteEngine on
    RewriteRule "^/$" "/pages/login.php"
</VirtualHost>
```

If you want a custom local domain like `http://zschedule.com`, then:

-   Change the `ServerName` in the above config to `zschedule.com`
-   Then add `zschedule.com` as a hostname that points to your localhost in your system's `hosts` file.
    -   On windows, this file is located at `C:/Windows32/drivers/etc/hosts`
    -   On linux, this file is located at `etc/hosts/`

```
127.0.0.1 zschedule.com
```

-   Flush your dns with `ipconfig /flushdns` and restart the XAMPP server
-   This will serve the app at `http://zschedule.com`.
