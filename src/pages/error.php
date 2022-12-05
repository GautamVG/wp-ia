<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/error.css">
    <title>Interval Error | ZSchedule</title>
</head>
<body>
    <div class="container">
        <h1> There was an internal error </h1>
        <p> 
            <?php 
                echo $_GET['msg'];
            ?>
        </p>
    </div>
</body>
</html>