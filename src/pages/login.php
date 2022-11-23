<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(PROJECT_ROOT . "lib/db.php");
    include_once(PROJECT_ROOT . "lib/errors.php");
    include_once(PROJECT_ROOT . "lib/redirect.php");
?>

<?php 
    function isValid($svvid, $pwd) {
        $db = DB\connect();
        try {
            $hashedPwd = md5($pwd);
            $results = $db->query("SELECT * FROM `user` WHERE `svvid` = '$svvid' and `pwd` = '$hashedPwd'");
            if ($results and count($results->fetchAll()) > 0) return true;
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
        return false;
    }

    $errMsg = null;
    if (isset($_POST['login'])) {
        if (isset($_POST['svvid']) && isset($_POST['pwd'])) {
            $svvid = $_POST['svvid'];
            $pwd = $_POST['pwd'];
            if (!isValid($svvid, $pwd)) {
                $errMsg = "Incorrect SVV ID or Password";
            } else {
                session_start();
                $_SESSION['svvid'] = $svvid;
                Redirect\toHomePage();
            }
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
            include_once(PROJECT_ROOT . "styles/login.css");
        ?>
    </style>
    <title>Login | ZSchedule</title>
</head>
<body>
    <div class="container">
        <h1>ZSchedule</h1>
        <h3>Welcome</h3>
        <form method="POST" />
            <label>SVV ID</label>
            <input type="text" name="svvid" required />
            <label>Password</label>
            <input type="password" name="pwd" required />
            <input type="submit" name="login" />
        </form>
        <p>
            Do not have an account? 
            <a href="./signup.php">Sign up</a> 
        </p>
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