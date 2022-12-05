<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    session_start();
    session_unset();
    session_destroy();

    Redirect\toLoginPage();
?>