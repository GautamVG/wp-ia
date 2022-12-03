<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php 
            include_once(APP_ROOT . "styles/base.css");
            include_once(APP_ROOT . "styles/error.css");
        ?>
    </style>
    <title>Error | ZSchedule</title>
</head>
<body>
    <h1> There was an error </h1>
    <p> 
        <?php 
            echo $_GET['msg'];
        ?>
    </p>
</body>
</html>