<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(PROJECT_ROOT . "lib/db.php");
    include_once(PROJECT_ROOT . "lib/redirect.php");
?>

<?php 
    session_start();
    if (!(isset($_SESSION) && isset($_SESSION['svvid']))) Redirect\toLoginPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/phosphor-icons"></script>
    <style>
        <?php 
            include_once(PROJECT_ROOT . "styles/base.css");
            include_once(PROJECT_ROOT . "styles/home.css");
        ?>
    </style>
    <title>Home | ZSchedule</title>
</head>
<body>
    <div class="app-bar">
        <div class="container">
            <i class="ph-user-circle"></i>
            <h3 class="app-bar-title">
                <?php 
                    echo $_SESSION['svvid']
                ?>
            </h3>
            <a href="./logout.php" class="logout">
                <i class="ph-sign-out"></i>
            </a>
        </div>
    </div>
    <main class="container">
        <h1>Today's schedule</h1>
    </main>
</body>
</html>