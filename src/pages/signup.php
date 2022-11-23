<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(PROJECT_ROOT . "lib/db.php");
    include_once(PROJECT_ROOT . "lib/errors.php");
    include_once(PROJECT_ROOT . "lib/redirect.php");
?>

<?php 
    function makeAccount($name, $svvid, $pwd) {
        $db = DB\connect();
        try {
            $hashedPwd = md5($pwd);
            $results = $db->exec("INSERT INTO `user` (`name`, `svvid`, `pwd`, `type`) VALUES ('$name', '$svvid', '$hashedPwd', 3)");
            Redirect\toLoginPage();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
        return false;
    }

    $errMsg = null;
    if (isset($_POST['signup'])) {
        if (isset($_POST['svvid']) && isset($_POST['pwd']) && isset($_POST['name'])) {
            $name = $_POST['name'];
            $svvid = $_POST['svvid'];
            $pwd = $_POST['pwd'];
            makeAccount($name, $svvid, $pwd);
        } else {
            $errMsg = "Please fill all details";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php 
            include_once(PROJECT_ROOT . "styles/base.css");
            include_once(PROJECT_ROOT . "styles/signup.css");
        ?>
    </style>
    <title>Login | ZSchedule</title>
</head>
<body>
    <div class="container">
        <h1>ZSchedule</h1>
        <h3>Create your account</h3>
        <form method="POST" />
            <label>Name</label>
            <input type="text" name="name" required />
            <label>SVV ID</label>
            <input type="text" name="svvid" required />
            <label>Password</label>
            <input type="password" name="pwd" required />
            <input type="submit" name="signup" />
        </form>
        <?php 
            if (isset($errMsg)) {
                ?>
                    <p class="err-msg">
                        <?php echo $errMsg ?>
                    </p>
                <?php
            }
        ?>
    </div>
</body>
</html>