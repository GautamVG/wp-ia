<?php namespace Redirect ?>

<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    function toErrorPage($msg) {
        header("Location: " . SERVER_ROOT . "/error.php?msg=$msg");
        die();
    }

    function toHomePage() {
        header("Location: ". SERVER_ROOT . "/home.php");
        die();
    }

    function toLoginPage() {
        header("Location: ". SERVER_ROOT . "/login.php");
        die();
    }
?>