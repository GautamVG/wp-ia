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
These are defaults for a local MySQL server installation. \
You may or may not need to change these.

```
<?php
	define("DB_HOST", "localhost");
	define("DB_PORT", "3306");
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_NAME", "zschedule_dev");

	// This should be the exact server url where this app is hosted
	define("SERVER_ROOT", "http://localhost/path/to/project/root/src");
?>
```

## Run the server

Steps may be different for the server application that you use. \

### For use with XAMPP using Apache

Either change the server config to serve the project's root folder (not recommended) or \
Create a symlink in the htdocs folder that points to the project's root folder.

-   On Windows (Use correct and absolute paths)

```
mklink /D C:\xampp\htdocs\zschedule C:\absolute\path\to\your\project\root
```
