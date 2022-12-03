<?php namespace DB ?>

<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/errors.php");
?>

<?php 
    function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=". DB_NAME;
            $db = new \PDO($dsn, DB_USER, DB_PASS, [
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
            return $db;
        } catch (\PDOException $e) {
            throw new Error\Server("Could not connect to database, PDO said: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new Error\Server("Uncategorised error, message: " . $e->getMessage());
        }
    }
?>