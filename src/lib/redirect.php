<?php namespace Redirect ?>

<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    function toErrorPage($msg) {
        header("Location: " . APP_ROOT_URL . "/pages/error.php?msg=$msg");
        die();
    }

    function toHomePage() {
        header("Location: ". APP_ROOT_URL . "/pages/home.php");
        die();
    }

    function toLoginPage() {
        header("Location: ". APP_ROOT_URL . "/pages/login.php");
        die();
    }
?>